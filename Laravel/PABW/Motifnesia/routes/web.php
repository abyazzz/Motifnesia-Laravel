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

    // ========== HOME & PRODUCTS ==========
    Route::get('/homePage', [CustomerProductController::class, 'index'])->name('home');
    Route::get('/products/{id}', [CustomerProductController::class, 'show'])->name('product.detail');

    // ========== SHOPPING CART (Keranjang Belanja) ==========
    Route::get('/cart', [ShoppingCardController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [ShoppingCardController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [ShoppingCardController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [ShoppingCardController::class, 'delete'])->name('cart.delete');
    Route::post('/cart/checkout', [ShoppingCardController::class, 'checkout'])->name('cart.checkout');

    // ========== CHECKOUT ==========
    Route::get('/checkout', [CheckOutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/store', [CheckOutController::class, 'store'])->name('checkout.store');

    // ========== PAYMENT/TRANSACTION ==========
    Route::get('/payment', [\App\Http\Controllers\Customer\PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment/store', [\App\Http\Controllers\Customer\PaymentController::class, 'store'])->name('payment.store');
    Route::get('/transaction/success/{orderId}', [\App\Http\Controllers\Customer\PaymentController::class, 'success'])->name('transaction.success');

    // ========== FAVORITES & NOTIFICATIONS ==========
    Route::get('/favorites', [\App\Http\Controllers\Customer\ProductFavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/store', [\App\Http\Controllers\Customer\ProductFavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{id}', [\App\Http\Controllers\Customer\ProductFavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/favorites/{id}/add-to-cart', [\App\Http\Controllers\Customer\ProductFavoriteController::class, 'addToCart'])->name('favorites.addToCart');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // ========== REVIEWS (Old - Product Detail Reviews) ==========
    Route::get('/products/{id}/reviews', [ReviewController::class, 'index'])->name('products.reviews.index');
    Route::post('/product-reviews', [ReviewController::class, 'store'])->name('product.reviews.store');
    Route::put('/product-reviews/{id}', [ReviewController::class, 'update'])->name('product.reviews.update');
    Route::delete('/product-reviews/{id}', [ReviewController::class, 'destroy'])->name('product.reviews.destroy');

    // ========== ORDER REVIEWS (Purchase History Reviews) ==========
    Route::post('/order-reviews', [\App\Http\Controllers\Customer\OrderReviewController::class, 'store'])->name('order.reviews.store');
    Route::get('/order-reviews/{orderItemId}', [\App\Http\Controllers\Customer\OrderReviewController::class, 'show'])->name('order.reviews.show');

    // ========== USER PROFILE ==========
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [UserProfileController::class, 'update'])->name('profile.update');
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
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    // Product Reviews (admin view)
    Route::get('/product-reviews', [App\Http\Controllers\Admin\ProductReviewController::class, 'index'])->name('reviews.index');
    // Live Chat Admin
    Route::get('/live-chat', [ChatController::class, 'index'])->name('chat.index');
    // Returns
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    // Order Status
    Route::get('/order-status', [OrderStatusController::class, 'index'])->name('orders.status');
    Route::post('/order-status/{id}/update', [OrderStatusController::class, 'updateStatus'])->name('orders.status.update');
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

