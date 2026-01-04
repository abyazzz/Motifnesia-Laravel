<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiShoppingCardController;
use App\Http\Controllers\Api\ApiProductFavoriteController;
use App\Http\Controllers\Api\ApiUserAddressController;

/*
|--------------------------------------------------------------------------
| API Routes untuk Flutter
|--------------------------------------------------------------------------
|
| Base URL: http://127.0.0.1:8000/api/
|
| IMPORTANT: 
| - Semua response format JSON
| - Semua endpoint TIDAK PAKAI middleware dulu (untuk testing)
| - Nanti tambahin middleware 'auth:sanctum' kalau udah implement token
|
*/

// ========== USER/AUTH API ROUTES ==========

// Register user baru
// URL: POST /api/register
// Body: { "name": "john", "email": "john@mail.com", "password": "12345678", "password_confirmation": "12345678" }
Route::post('/register', [ApiUserController::class, 'register']);

// Login user
// URL: POST /api/login
// Body: { "email": "john@mail.com", "password": "12345678" }
Route::post('/login', [ApiUserController::class, 'login']);

// Logout user
// URL: POST /api/logout
Route::post('/logout', [ApiUserController::class, 'logout']);

// Get profile user yang sedang login
// URL: GET /api/profile
Route::get('/profile', [ApiUserController::class, 'profile']);

// Update profile user yang sedang login
// URL: PUT/PATCH /api/profile
// Body: { "full_name": "John Doe", "phone_number": "08123456789", ... }
Route::put('/profile', [ApiUserController::class, 'updateProfile']);
Route::patch('/profile', [ApiUserController::class, 'updateProfile']);

// GET all users (untuk admin)
// URL: GET /api/users?per_page=10&role=customer
Route::get('/users', [ApiUserController::class, 'index']);

// GET user by ID (untuk admin)
// URL: GET /api/users/1
Route::get('/users/{id}', [ApiUserController::class, 'show']);

// DELETE user (untuk admin)
// URL: DELETE /api/users/1
Route::delete('/users/{id}', [ApiUserController::class, 'destroy']);

// ========== PRODUCT API ROUTES ==========

// GET all products (dengan pagination & filter)
// URL: GET /api/products?per_page=10&category=batik&search=jogja
Route::get('/products', [ApiProductController::class, 'index']);

// GET single product by ID
// URL: GET /api/products/1
Route::get('/products/{id}', [ApiProductController::class, 'show']);

// CREATE new product
// URL: POST /api/products
// Body: form-data atau JSON (lihat docs di controller)
Route::post('/products', [ApiProductController::class, 'store']);

// UPDATE product
// URL: PUT/PATCH /api/products/1
// Body: form-data atau JSON (field yang mau diupdate aja)
Route::put('/products/{id}', [ApiProductController::class, 'update']);
Route::patch('/products/{id}', [ApiProductController::class, 'update']);

// DELETE product
// URL: DELETE /api/products/1
Route::delete('/products/{id}', [ApiProductController::class, 'destroy']);

// GET categories (untuk dropdown)
// URL: GET /api/products/categories
Route::get('/products-categories', [ApiProductController::class, 'categories']);

// SEARCH products (advanced search)
// URL: POST /api/products/search
// Body: { "keyword": "batik", "min_price": 50000, "max_price": 200000, "category": "batik" }
Route::post('/products/search', [ApiProductController::class, 'search']);

// ========== SHOPPING CART API ROUTES ==========

// GET cart items (untuk user yang login)
// URL: GET /api/cart?user_id=1
Route::get('/cart', [ApiShoppingCardController::class, 'index']);

// GET cart count (jumlah item)
// URL: GET /api/cart/count?user_id=1
Route::get('/cart/count', [ApiShoppingCardController::class, 'count']);

// ADD to cart
// URL: POST /api/cart
// Body: { "user_id": 1, "product_id": 5, "ukuran": "L", "qty": 2 }
Route::post('/cart', [ApiShoppingCardController::class, 'store']);

// UPDATE cart item qty
// URL: PUT/PATCH /api/cart/1
// Body: { "user_id": 1, "qty": 3 }
Route::put('/cart/{id}', [ApiShoppingCardController::class, 'update']);
Route::patch('/cart/{id}', [ApiShoppingCardController::class, 'update']);

// DELETE cart item
// URL: DELETE /api/cart/1?user_id=1
Route::delete('/cart/{id}', [ApiShoppingCardController::class, 'destroy']);

// CLEAR all cart
// URL: DELETE /api/cart/clear
// Body: { "user_id": 1 }
Route::delete('/cart/clear', [ApiShoppingCardController::class, 'clear']);

// ========== FAVORITES API ROUTES ==========

// GET all favorites
// URL: GET /api/favorites?user_id=1
Route::get('/favorites', [ApiProductFavoriteController::class, 'index']);

// GET favorites count
// URL: GET /api/favorites/count?user_id=1
Route::get('/favorites/count', [ApiProductFavoriteController::class, 'count']);

// CHECK if product is favorited
// URL: GET /api/favorites/check?user_id=1&produk_id=5
Route::get('/favorites/check', [ApiProductFavoriteController::class, 'check']);

// ADD to favorites
// URL: POST /api/favorites
// Body: { "user_id": 1, "produk_id": 5 }
Route::post('/favorites', [ApiProductFavoriteController::class, 'store']);

// DELETE from favorites
// URL: DELETE /api/favorites/1?user_id=1
Route::delete('/favorites/{id}', [ApiProductFavoriteController::class, 'destroy']);

// ADD favorite to cart
// URL: POST /api/favorites/1/add-to-cart
// Body: { "user_id": 1, "ukuran": "L", "qty": 1 }
Route::post('/favorites/{id}/add-to-cart', [ApiProductFavoriteController::class, 'addToCart']);

// ========== USER ADDRESSES API ROUTES ==========

// GET all addresses (untuk user)
// URL: GET /api/addresses?user_id=1
Route::get('/addresses', [ApiUserAddressController::class, 'index']);

// GET primary address
// URL: GET /api/addresses/primary?user_id=1
Route::get('/addresses/primary', [ApiUserAddressController::class, 'getPrimary']);

// GET single address by ID
// URL: GET /api/addresses/1?user_id=1
Route::get('/addresses/{id}', [ApiUserAddressController::class, 'show']);

// ADD new address
// URL: POST /api/addresses
// Body: { "user_id": 1, "label": "Rumah", "recipient_name": "John", "phone_number": "08123456789", "address_line": "Jl. Mawar No. 5", "city": "Yogyakarta", "province": "DIY", "postal_code": "55281", "notes": "Dekat indomaret" }
Route::post('/addresses', [ApiUserAddressController::class, 'store']);

// UPDATE address
// URL: PUT/PATCH /api/addresses/1
// Body: { "user_id": 1, "label": "Kantor", ... }
Route::put('/addresses/{id}', [ApiUserAddressController::class, 'update']);
Route::patch('/addresses/{id}', [ApiUserAddressController::class, 'update']);

// SET address as primary
// URL: POST /api/addresses/1/set-primary
// Body: { "user_id": 1 }
Route::post('/addresses/{id}/set-primary', [ApiUserAddressController::class, 'setPrimary']);

// DELETE address
// URL: DELETE /api/addresses/1?user_id=1
Route::delete('/addresses/{id}', [ApiUserAddressController::class, 'destroy']);

// ========== TEST ENDPOINT (untuk cek API nyala) ==========
Route::get('/test', function() {
    return response()->json([
        'success' => true,
        'message' => 'API Motifnesia is running!',
        'timestamp' => now()
    ]);
});
