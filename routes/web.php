<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MenuCategory\MenuCategoryController;
use App\Http\Controllers\Menu\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Table\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::post('/add-to-cart', [FrontendController::class, 'addToCart'])->name('add.to.cart');
Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');
Route::post('/checkout/store', [FrontendController::class, 'checkoutStore'])->name('checkout.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(MenuController::class)
    ->middleware('auth')->group(function () {
        Route::resource('menu', MenuController::class);
        Route::resource('menu-category', MenuCategoryController::class);
        Route::get('/{id}/qrcode', [TableController::class, 'showQrCode'])->name('table.qrcode');
        Route::get('/table/data', [TableController::class, 'data'])->name('table.data');
        Route::resource('table', TableController::class);
    });

require __DIR__ . '/auth.php';
