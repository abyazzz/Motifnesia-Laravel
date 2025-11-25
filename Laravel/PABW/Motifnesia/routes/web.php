<?php

use Illuminate\Support\Facades\Route;

// Import Controllers auth
use App\Http\Controllers\Auth\UserController;

// Import Controllers User (Frontend)
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ReviewController;

// Import Controllers Admin (Backend)
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\ProductFormController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\ProductManagementController;
// Kita import ProductController Admin dari namespace Admin dan kasih alias


// ---------------------------------- Auth ----------------------------------

// Halaman Login (GET)
Route::get('/login', [UserController::class, 'login'])->name('login'); // <-- Tambahkan route GET login

// Proses Login (POST)
Route::post('/login', [UserController::class, 'doLogin'])->name('doLogin');

// Register (GET)
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'doRegister'])->name('doRegister');

// Forgot Password (GET)
Route::get('/forgot', [UserController::class, 'forgot'])->name('forgot');

// Logout
Route::get('/logout', [UserController::class, 'logout'])->name('logout');


// Halaman Home (Ganti ke '/' untuk URL root, atau tetap '/homePage' sesuai permintaan)
Route::get('/homePage', [ProductController::class, 'index'])->name('home');

// Halaman Detail Produk
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.detail');

// Shopping & Checkout
Route::get('/cart', [ShoppingCartController::class, 'index'])->name('cart.index');

Route::get('/checkout', [CheckOutController::class, 'index'])->name('checkout.index');

Route::get('/payment-confirm', [TransactionController::class, 'showPaymentConfirmation'])->name('payment.confirm');

Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

// Reviews CRUD (AJAX)
Route::get('/products/{id}/reviews', [ReviewController::class, 'index'])->name('products.reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');


// User Profile
Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/edit', [UserProfileController::class, 'update'])->name('profile.update');

// ---------------------------------- Route Khusu Admin ------------------------------------------
Route::get('/admin/daftar-produk', [AdminProductController::class, 'index'])->name('admin.daftar-produk');

Route::get('/admin/customers', [CustomerController::class, 'index'])->name('admin.customers.index');

Route::get('/admin/product-management', [ProductManagementController::class, 'index'])->name('admin.product.management.index');

Route::get('/admin/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');

Route::get('/admin/live-chat', [ChatController::class, 'index'])->name('admin.chat.index');

Route::get('/admin/returns', [ReturnController::class, 'index'])->name('admin.returns.index');

Route::get('/admin/order-status', [OrderStatusController::class, 'index'])->name('admin.orders.status');

Route::get('/admin/products/create', [ProductFormController::class, 'create'])->name('admin.products.create');
Route::post('/admin/products/create', [ProductFormController::class, 'store'])->name('admin.products.store');
Route::post('/admin/products/{id}/update', [ProductFormController::class, 'update'])->name('admin.products.update');
Route::post('/admin/products/{id}/delete', [ProductFormController::class, 'destroy'])->name('admin.products.delete');

Route::get('/admin/sales-report', [ReportController::class, 'index'])->name('admin.reports.sales');



// Admin static content (About Us / Icons / Slideshow)
Route::get('/admin/konten', [App\Http\Controllers\Admin\StaticContentController::class, 'index'])->name('admin.konten.index');
// Slideshow CRUD (AJAX endpoints)
Route::post('/admin/konten/slides/create', [App\Http\Controllers\Admin\StaticContentController::class, 'storeSlide'])->name('admin.konten.slides.create');
Route::post('/admin/konten/slides/{id}/update', [App\Http\Controllers\Admin\StaticContentController::class, 'updateSlide'])->name('admin.konten.slides.update');
Route::post('/admin/konten/slides/{id}/delete', [App\Http\Controllers\Admin\StaticContentController::class, 'deleteSlide'])->name('admin.konten.slides.delete');

// Backward-compatible single-endpoint kept but redirects to index (not used by new UI)
Route::post('/admin/konten/slideshow', [App\Http\Controllers\Admin\StaticContentController::class, 'updateSlideshow']);


