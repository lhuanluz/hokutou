<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show']);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('carts', CartController::class)->except(['show']);
    Route::get('carts/add-product', [CartController::class, 'addProductForm'])->name('carts.addProductForm');
    Route::post('carts/add-product', [CartController::class, 'addProduct'])->name('carts.addProduct');
});

Route::middleware(['auth'])->group(function () {
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
});
require __DIR__.'/auth.php';
