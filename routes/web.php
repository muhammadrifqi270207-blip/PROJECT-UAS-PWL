<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KonserController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\WishlistController; // Controller baru kita

Route::get('/', function () {
    return redirect()->route('login');
});

// ------------------ GUEST / AUTH VIA LOGIN-REGISTER ------------------
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ------------------ MIDDLWARE AUTH GENERAL ------------------
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ------------------ ADMIN PANEL ------------------
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('konser', KonserController::class);
    Route::resource('tiket', TiketController::class);
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::get('/orders/export', [DashboardController::class, 'exportOrders'])->name('orders.export'); 
});

// ------------------ USER PANEL ------------------
Route::middleware(['auth', 'user'])->prefix('home')->name('user.')->group(function () {
    // Beranda & Detail
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/konser/{konser}', [HomeController::class, 'show'])->name('konser.show');
    Route::post('/notifications/read/{konser}', [HomeController::class, 'markRead'])->name('notifications.read');
    Route::post('/chatbot', [HomeController::class, 'chatbot'])->name('chatbot');
    Route::post('/review/{konser}', [HomeController::class, 'storeReview'])->name('review.store');

    // FITUR WISHLIST BARU (Menggunakan WishlistController agar Log Aktivitas Tercatat)
    Route::get('/wishlist', [HomeController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{konser}', [HomeController::class, 'toggleWishlist'])->name('wishlist.toggle');



    // Checkout & Transaksi
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/order/{order}/success', [CheckoutController::class, 'success'])->name('order.success');
    Route::get('/orders/{order}/download', [CheckoutController::class, 'download'])->name('orders.download');
    Route::get('/myorders', [CheckoutController::class, 'myOrders'])->name('orders.my');
    Route::delete('/orders/{order}', [CheckoutController::class, 'destroy'])->name('orders.destroy');

    // Manajemen Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // API Internal
    Route::get('/api/konser-beranda', function() {
        sleep(1); 
        return response()->json(\App\Models\Konser::where('status', 'aktif')->latest()->get());
    })->name('api.konser.beranda');
});