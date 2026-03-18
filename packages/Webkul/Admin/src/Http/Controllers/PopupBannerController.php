<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Core\Models\PopupBanner;
use Webkul\Core\Repositories\PopupBannerRepository;

class PopupBannerController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected PopupBannerRepository $popupBannerRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(\Webkul\Admin\DataGrids\PopupBannerDataGrid::class)->process();
        }

        return view('admin::popup-banners.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::popup-banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $this->validate(request(), [
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:bmp,jpeg,jpg,png,webp',
            'link' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = request()->only([
            'title',
            'link',
            'is_active',
            'sort_order',
        ]);

        if (request()->hasFile('image')) {
            $data['image_path'] = request()->file('image')->store('popup-banners', 'public');
        }

        $this->popupBannerRepository->create($data);

        return redirect()->route('admin.popup-banners.index')->with('success', trans('admin::app.popup-banners.create-success'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $banner = $this->popupBannerRepository->findOrFail($id);

        return view('admin::popup-banners.edit', [
            'banner' => $banner,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:bmp,jpeg,jpg,png,webp',
            'link' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $banner = $this->popupBannerRepository->findOrFail($id);

        $data = request()->only([
            'title',
            'link',
            'is_active',
            'sort_order',
        ]);

        if (request()->hasFile('image')) {
            // Delete old image if exists
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $data['image_path'] = request()->file('image')->store('popup-banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.popup-banners.index')->with('success', trans('admin::app.popup-banners.update-success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $banner = $this->popupBannerRepository->findOrFail($id);

        // Delete image if exists
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return response()->json([
            'message' => trans('admin::app.popup-banners.delete-success'),
        ]);
    }
}
