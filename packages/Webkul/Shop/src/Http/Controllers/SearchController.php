<?php

namespace Webkul\Shop\Http\Controllers;

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
            'query' => ['sometimes', 'required', 'string', 'regex:/^[^\\\\]+$/u'],
            'category' => ['sometimes', 'integer', 'exists:categories,id'],
        ]);

        $searchTerm = $this->searchTermRepository->findOneWhere([
            'term'       => request()->query('query'),
            'channel_id' => core()->getCurrentChannel()->id,
            'locale'     => app()->getLocale(),
        ]);

        if ($searchTerm?->redirect_url) {
            return redirect()->to($searchTerm->redirect_url);
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

        // Build search parameters
        $params = [
            'name' => $query,
            'channel_id' => core()->getCurrentChannel()->id,
            'status' => 1,
            'visible_individually' => 1,
            'limit' => 8, // Limit suggestions to 8 items
        ];

        if ($categoryId) {
            $params['category_id'] = $categoryId;
        }

        // Get search engine configuration
        $searchEngine = 'database';
        if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
            $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
        }

        // Get products
        $products = app(\Webkul\Product\Repositories\ProductRepository::class)
            ->setSearchEngine($searchEngine ?? 'database')
            ->getAll($params);

        // Format response
        $suggestions = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'url' => $product->url_key,
                'image' => $product->base_image_url,
                'price' => $product->getTypeInstance()->getMinimalPrice(),
                'formatted_price' => core()->currency($product->getTypeInstance()->getMinimalPrice()),
            ];
        })->take(8);

        return response()->json($suggestions);
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
