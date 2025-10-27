<?php

use Illuminate\Support\Facades\Route;
use Webkul\Payment\Http\Controllers\PayzyController;

Route::group(['middleware' => ['web']], function () {
    Route::prefix('payzy')->group(function () {
        Route::get('/process', [PayzyController::class, 'process'])->name('payzy.process');

        Route::get('/success', [PayzyController::class, 'success'])->name('payzy.success');

        Route::get('/cancel', [PayzyController::class, 'cancel'])->name('payzy.cancel');
    });
});
