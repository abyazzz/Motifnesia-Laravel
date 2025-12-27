<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;

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

// ========== TEST ENDPOINT (untuk cek API nyala) ==========
Route::get('/test', function() {
    return response()->json([
        'success' => true,
        'message' => 'API Motifnesia is running!',
        'timestamp' => now()
    ]);
});
