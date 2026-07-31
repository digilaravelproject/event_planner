<?php

use App\Modules\DynamicVendors\Http\Controllers\DynamicVendorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:admin'])
    ->prefix('admin/dynamic-vendors')
    ->name('admin.dynamic-vendors.')
    ->group(function (): void {
        Route::post('{dynamic_vendor}/duplicate', [DynamicVendorController::class, 'duplicate'])->name('duplicate');
        Route::patch('{dynamic_vendor}/status', [DynamicVendorController::class, 'status'])->name('status');
        Route::post('{dynamic_vendor}/versions/{version}/rollback', [DynamicVendorController::class, 'rollback'])->name('rollback');
        Route::resource('/', DynamicVendorController::class)
            ->parameters(['' => 'dynamic_vendor'])
            ->names([
                'index' => 'index',
                'create' => 'create',
                'store' => 'store',
                'show' => 'show',
                'edit' => 'edit',
                'update' => 'update',
                'destroy' => 'destroy',
            ]);
    });
