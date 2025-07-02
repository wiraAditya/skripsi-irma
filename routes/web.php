<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\HomeMenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CashierSessionController;
use App\Http\Controllers\RefundController;



Route::get('/', [HomeMenuController::class, 'index'])->name('home');
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::get('/cart/get', [App\Http\Controllers\CartController::class, 'getItem'])->name('cart.get');
Route::get('/cart/remove/{id}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');

Route::get('/payment', [App\Http\Controllers\PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment/paid', [App\Http\Controllers\PaymentController::class, 'paid'])->name('payment.paid');
Route::get('/token', [App\Http\Controllers\PaymentController::class, 'token'])->name('payment.token');



Route::get('/receipt/print/{transactionCode}', [App\Http\Controllers\PaymentController::class, 'print'])
    ->name('receipt.print');

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::redirect('settings', 'settings/profile');

        Route::get('settings/profile', Profile::class)->name('settings.profile');
        Route::get('settings/password', Password::class)->name('settings.password');
        Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
        Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/print', [App\Http\Controllers\ReportController::class, 'print'])->name('reports.print');
        Route::get('/reports/daily', [App\Http\Controllers\ReportController::class, 'index_daily'])->name('reports.index.daily');
        Route::get('/reports/daily/print', [App\Http\Controllers\ReportController::class, 'print_daily'])->name('reports.print.daily');

        Route::resource('users', UserController::class);
        Route::resource('meja', MejaController::class)->except(['show']);
        Route::get('meja/{meja}/qrcode', [MejaController::class, 'showQrcode'])->name('meja.qrcode');
        Route::resource('kategori', KategoriController::class);
        Route::resource('menu', MenuController::class);
        Route::get('/order', [App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
        Route::get('/order/{order}', [OrderController::class, 'detail'])->name('orders.detail');
        Route::get('/order/confirm/{order}', [OrderController::class, 'confirm'])->name('order.confirm');
        Route::get('/order/process/{order}', [OrderController::class, 'process'])->name('order.process');
        Route::get('/order/done/{order}', [OrderController::class, 'done'])->name('order.done');
        Route::get('/order/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/order/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('/order/{order}/details/{detail}', [OrderController::class, 'destroyDetail'])->name('orders.details.destroy');

        Route::get('/admin/sessions/open', [CashierSessionController::class, 'open'])->name('admin.sessions.open');
        Route::post('/admin/sessions/open', [CashierSessionController::class, 'storeOpen'])->name('admin.sessions.storeOpen');
        Route::get('/admin/sessions/close/{session}', [CashierSessionController::class, 'close'])->name('admin.sessions.close');
        Route::post('/admin/sessions/close/{session}', [CashierSessionController::class, 'storeClose'])->name('admin.sessions.storeClose');

        Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
        Route::get('/refunds/create', [RefundController::class, 'create'])->name('refunds.create');
        Route::post('/refunds', [RefundController::class, 'store'])->name('refunds.store');
        Route::get('/refunds/{refund}', [RefundController::class, 'show'])->name('refunds.show');
        
    });
});



require __DIR__ . '/auth.php';
