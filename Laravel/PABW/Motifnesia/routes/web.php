<?php
use Illuminate\Support\Facades\Route;

// Import Controllers auth
use App\Http\Controllers\AdminController;

// Import Controllers User (Frontend)
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Customer\CheckOutController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Admin\StaticContentController;

// Import Controllers Admin (Backend)
use App\Http\Controllers\Customer\TransactionController;
use App\Http\Controllers\Customer\UserProfileController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\ShoppingCartController;
use App\Http\Controllers\Customer\ShoppingCardController;
use App\Http\Controllers\Customer\ControllerSessionCheckout;
use App\Http\Controllers\Customer\CustomerProductController;
use App\Http\Controllers\Admin\AdminProductController; // ← CRUD Produk Admin
// Redirect root path ke homePage
Route::get('/', function() {
    return redirect()->route('customer.home');
});

// ==================== AUTH GROUP ====================
Route::group(['prefix' => '', 'as' => 'auth.'], function () {
    // Halaman Login (GET)
    Route::get('/login', [UserController::class, 'login'])->name('login');
    // Proses Login (POST)
    Route::post('/login', [UserController::class, 'doLogin'])->name('doLogin');
    // Register (GET)
    Route::get('/register', [UserController::class, 'register'])->name('register');
    Route::post('/register', [UserController::class, 'doRegister'])->name('doRegister');
    // Forgot Password
    Route::get('/forgot', [UserController::class, 'forgot'])->name('forgot');
    // Logout
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
});

// ==================== CUSTOMER GROUP ====================
Route::group(['prefix' => '', 'as' => 'customer.'], function () {
        // Proses simpan session checkout dari shoppingCard
        Route::post('/checkout/session', [ControllerSessionCheckout::class, 'storeCheckoutSession'])->name('checkout.session');
        // Halaman checkout
        Route::get('/checkout', [ControllerSessionCheckout::class, 'index'])->name('checkout.index');
        // Proses simpan session checkout final
        Route::post('/checkout/final', [ControllerSessionCheckout::class, 'storeCheckoutFinal'])->name('checkout.final');
    // Home Page → khusus CustomerProductController
    Route::get('/homePage', [App\Http\Controllers\Customer\CustomerProductController::class, 'index'])->name('home');
    // Product Detail
    Route::get('/products/{id}', [App\Http\Controllers\Customer\CustomerProductController::class, 'show'])->name('product.detail');
    // Shopping Cart (keranjang belanja CRUD)
    Route::get('/cart', [ShoppingCardController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [ShoppingCardController::class, 'add'])->name('cart.add');
    // Checkout
    Route::get('/checkout', [App\Http\Controllers\Customer\CheckOutController::class, 'index'])->name('checkout.index');
    // Payment Confirm
    Route::get('/payment-confirm', [App\Http\Controllers\Customer\TransactionController::class, 'showPaymentConfirmation'])->name('payment.confirm');
    // Favorites & Notifications
    Route::get('/favorites', [App\Http\Controllers\Customer\FavoriteController::class, 'index'])->name('favorites.index');
    Route::get('/notifications', [App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications.index');
    // Reviews (AJAX)
    Route::get('/products/{id}/reviews', [App\Http\Controllers\Customer\ReviewController::class, 'index'])->name('products.reviews.index');
    Route::post('/reviews', [App\Http\Controllers\Customer\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [App\Http\Controllers\Customer\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [App\Http\Controllers\Customer\ReviewController::class, 'destroy'])->name('reviews.destroy');
    // User Profile
    Route::get('/profile', [App\Http\Controllers\Customer\UserProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [App\Http\Controllers\Customer\UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [App\Http\Controllers\Customer\UserProfileController::class, 'update'])->name('profile.update');
});

// ==================== ADMIN GROUP ====================
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Daftar Produk (list/table view)
    Route::get('/daftar-produk', [AdminProductController::class, 'index'])
        ->name('daftar-produk');
    // Product Management Page (grid + modal edit/delete)
    Route::get('/product-management', [AdminProductController::class, 'manage'])
        ->name('product.management.index');
    // CREATE product (Form Add)
    Route::get('/products/create', [AdminProductController::class, 'create'])
        ->name('products.create');
    // STORE product
    Route::post('/products/create', [AdminProductController::class, 'store'])
        ->name('products.store');
    // UPDATE product (modal)
    Route::post('/products/{id}/update', [AdminProductController::class, 'update'])
        ->name('products.update');
    // DELETE product (modal)
    Route::post('/products/{id}/delete', [AdminProductController::class, 'destroy'])
        ->name('products.delete');
    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    // Reviews (admin view)
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    // Live Chat Admin
    Route::get('/live-chat', [ChatController::class, 'index'])->name('chat.index');
    // Returns
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    // Order Status
    Route::get('/order-status', [OrderStatusController::class, 'index'])->name('orders.status');
    // Sales Report
    Route::get('/sales-report', [ReportController::class, 'index'])->name('reports.sales');
    // STATIC CONTENT
    Route::get('/konten', [App\Http\Controllers\Admin\StaticContentController::class, 'index'])
        ->name('konten.index');
    // Slideshow CRUD
    Route::post('/konten/slides/create', [App\Http\Controllers\Admin\StaticContentController::class, 'storeSlide'])
        ->name('konten.slides.create');
    Route::post('/konten/slides/{id}/update', [App\Http\Controllers\Admin\StaticContentController::class, 'updateSlide'])
        ->name('konten.slides.update');
    Route::post('/konten/slides/{id}/delete', [App\Http\Controllers\Admin\StaticContentController::class, 'deleteSlide'])
        ->name('konten.slides.delete');
    // Old endpoint (compatibility only)
    Route::post('/konten/slideshow', [App\Http\Controllers\Admin\StaticContentController::class, 'updateSlideshow']);
});

