<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\GoogleController;

/**
 * google routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::get('google/fetch-data', [GoogleController::class, 'fetchData'])->name('admin.google.fetch-data');

    Route::controller(GoogleController::class)->prefix('google/{slug?}/{slug2?}')->group(function () {

        Route::get('', 'index')->name('admin.google.index');
    });
});
