<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Webkul\Marketing\Repositories\SearchTermRepository;
use Webkul\Product\Repositories\SearchRepository;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SearchTermRepository $searchTermRepository,
        protected SearchRepository $searchRepository
    ) {}

    /**
     * Index to handle the view loaded with the search results
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->validate(request(), [
            'query' => ['nullable', 'string', 'regex:/^[^\\\\]+$/u'],
            'category' => ['sometimes', 'integer', 'exists:categories,id'],
        ]);

        // Only check for redirect if query is provided
        if (request()->query('query')) {
            $searchTerm = $this->searchTermRepository->findOneWhere([
                'term'       => request()->query('query'),
                'channel_id' => core()->getCurrentChannel()->id,
                'locale'     => app()->getLocale(),
            ]);

            if ($searchTerm?->redirect_url) {
                return redirect()->to($searchTerm->redirect_url);
            }
        }

        return view('shop::search.index');
    }

    /**
     * Get search suggestions for autocomplete
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestions()
    {
        $this->validate(request(), [
            'query' => ['required', 'string', 'min:1', 'max:255'],
            'category' => ['sometimes', 'integer', 'exists:categories,id'],
        ]);

        $query = request()->query('query');
        $categoryId = request()->query('category');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            // Split query into words for better matching
            $searchTerms = array_filter(explode(' ', strtolower($query)));
            
            // Build query to search product names with ranking
            $productsQuery = \Webkul\Product\Models\Product::query()
                ->select('products.*')
                ->selectRaw('
                    CASE 
                        WHEN LOWER(product_flat.name) LIKE ? THEN 1
                        WHEN LOWER(product_flat.sku) = ? THEN 2
                        WHEN LOWER(product_flat.name) LIKE ? THEN 3
                        ELSE 4
                    END as relevance
                ', [
                    '%' . strtolower($query) . '%',
                    strtolower($query),
                    strtolower($searchTerms[0]) . '%'
                ])
                ->leftJoin('product_flat', function($join) {
                    $join->on('products.id', '=', 'product_flat.product_id')
                        ->where('product_flat.channel', core()->getCurrentChannel()->code)
                        ->where('product_flat.locale', app()->getLocale());
                })
                ->where('product_flat.status', 1)
                ->where('product_flat.visible_individually', 1);

            // Add category filter if provided
            if ($categoryId) {
                $productsQuery->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->where('product_categories.category_id', $categoryId);
            }

            $productsQuery->where(function($q) use ($searchTerms, $query) {
                    // Match full query or any individual word (3+ chars)
                    $q->where('product_flat.name', 'like', '%' . $query . '%')
                      ->orWhere('product_flat.sku', 'like', '%' . $query . '%');
                    
                    // Also match individual words for partial matches
                    foreach ($searchTerms as $term) {
                        if (strlen($term) >= 3) {
                            $q->orWhere('product_flat.name', 'like', '%' . $term . '%');
                        }
                    }
                });

            $products = $productsQuery->with(['images', 'price_indices'])
                ->orderBy('relevance')
                ->limit(8)
                ->get();

            // Format response
            $suggestions = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'url' => $product->url_key,
                    'image' => $product->base_image_url ?? bagisto_asset('images/small-product-placeholder.webp'),
                    'price' => $product->getTypeInstance()->getMinimalPrice(),
                    'formatted_price' => core()->currency($product->getTypeInstance()->getMinimalPrice()),
                ];
            });

            return response()->json($suggestions);
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Search suggestions error: ' . $e->getMessage());
            
            return response()->json([]);
        }
    }

    /**
     * Upload image for product search with machine learning.
     *
     * @return string
     */
    public function upload()
    {
        return $this->searchRepository->uploadSearchImage(request()->all());
    }
}
