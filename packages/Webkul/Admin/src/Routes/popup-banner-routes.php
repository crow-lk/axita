<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\PopupBannerController;

/**
 * Popup Banner routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::controller(PopupBannerController::class)->prefix('popup-banners')->group(function () {
        Route::get('', 'index')->name('admin.popup-banners.index');

        Route::get('create', 'create')->name('admin.popup-banners.create');

        Route::post('create', 'store')->name('admin.popup-banners.store');

        Route::get('edit/{id}', 'edit')->name('admin.popup-banners.edit');

        Route::put('edit/{id}', 'update')->name('admin.popup-banners.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.popup-banners.delete');
    });
});
