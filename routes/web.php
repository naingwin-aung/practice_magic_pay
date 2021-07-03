<?php

use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;

//User Auth
Auth::routes();

//Admin User Auth
Route::get('/admin/login', [AdminLoginController::class, 'showAdminLoginForm']);
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

//User Home
Route::middleware(['auth'])->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('home');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    Route::get('/update-password', [PageController::class, 'updatePassword'])->name('update-password');
    Route::post('/update-password', [PageController::class, 'updatePasswordStore'])->name('update-password.store');
    Route::get('/wallet',[PageController::class, 'wallet'])->name('wallet');
    Route::get('/transaction',[PageController::class, 'transaction'])->name('transaction');
    Route::get('/transaction/detail/{trx}',[PageController::class, 'transactionDetail'])->name('transaction.detail');

    Route::get('/transfer',[PageController::class, 'transfer'])->name('transfer');
    Route::get('/transfer/confirm',[PageController::class, 'transferConfirm'])->name('transfer.confirm');
    Route::post('/transfer/complete',[PageController::class, 'transferComplete'])->name('transfer.complete');

    Route::get('/to-account-verfiy', [PageController::class, 'toAccountVerify']);
    Route::get('/password-check', [PageController::class, 'passwordCheck']);
    Route::get('/transfer-hash', [PageController::class , 'transferHash']);

    Route::get('/receive-qr', [PageController::class, 'receiveQR'])->name('receiveQR');
    Route::get('/scan-and-pay', [PageController::class, 'scanAndPay'])->name('scan&pay');
    Route::get('/scan-and-pay-form', [PageController::class, 'scanAndPayForm'])->name('scan&payform');
    Route::get('/scan-and-pay-confirm', [PageController::class, 'scanAndPayConfirm'])->name('scan&pay.confirm');
    Route::post('/scan-and-pay/complete', [PageController::class, 'scanAndPayComplete'])->name('scan&pay.complete');
});