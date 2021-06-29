<?php

use App\Models\Wallet;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WalletController;
use App\Http\Controllers\Backend\AdminUserController;

Route::prefix('/admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('dashboard');

    Route::resource('admin-user', AdminUserController::class)->except([
        'show'
    ]);
    Route::get('admin-user/datatable/ssd', [AdminUserController::class, 'serverSideData']);

    Route::resource('user', UserController::class)->except([
        'show'
    ]);
    Route::get('user/datatable/ssd', [UserController::class, 'serverSideData']);

    Route::get('wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('wallet/datatable/ssd', [WalletController::class, 'serverSideData']);
});
