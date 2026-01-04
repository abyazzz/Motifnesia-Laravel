# 📱 API DOCUMENTATION - USER & PRODUCT (FLUTTER)

**Base URL:** `http://127.0.0.1:8000/api`  
**Response Format:** JSON  
**Tanggal:** 4 Januari 2026

---

## 🎯 OVERVIEW

API ini untuk Flutter app dengan 2 fitur utama:
1. **USER MANAGEMENT** - Register, Login, Profile, Edit Profile
2. **PRODUCT MANAGEMENT** - CRUD Produk (Admin) + List/Detail (Customer)

---

## 🔐 AUTHENTICATION

Untuk sekarang: **Belum pakai token** (untuk testing mudah)  
Nanti bisa ditambahin: **Laravel Sanctum** untuk token-based auth

---

## 📚 ENDPOINT LIST

### 👤 USER ENDPOINTS

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| POST | `/api/register` | Registrasi user baru | ❌ |
| POST | `/api/login` | Login user | ❌ |
| POST | `/api/logout` | Logout user | ✅ |
| GET | `/api/profile` | Get profile user login | ✅ |
| PUT/PATCH | `/api/profile` | Update profile user | ✅ |
| GET | `/api/users` | Get all users (admin) | ✅ |
| GET | `/api/users/{id}` | Get user by ID (admin) | ✅ |
| DELETE | `/api/users/{id}` | Delete user (admin) | ✅ |

### 📦 PRODUCT ENDPOINTS

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/products` | Get all products + filter | ❌ |
| GET | `/api/products/{id}` | Get detail product | ❌ |
| POST | `/api/products` | Create product (admin) | ✅ |
| PUT/PATCH | `/api/products/{id}` | Update product (admin) | ✅ |
| DELETE | `/api/products/{id}` | Delete product (admin) | ✅ |
| GET | `/api/products-categories` | Get list categories | ❌ |
| POST | `/api/products/search` | Advanced search | ❌ |

---

## 📖 DETAIL ENDPOINTS

## 👤 USER API

### 1. REGISTER USER BARU

**Endpoint:** `POST /api/register`

**Request Body (JSON):**
```json
{
  "name": "johndoe",
  "full_name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "customer",
  "phone_number": "08123456789"
}
```

**Field Rules:**
- `name` - Required, unique, max 255 char
- `full_name` - Optional, max 255 char (default = name)
- `email` - Required, unique, valid email
- `password` - Required, min 8 char
- `password_confirmation` - Required, must match password
- `role` - Optional, "customer" atau "admin" (default: customer)
- `phone_number` - Optional, max 20 char

**Success Response (201):**
```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "johndoe",
      "full_name": "John Doe",
      "email": "john@example.com",
      "role": "customer",
      "phone_number": "08123456789",
      "profile_pic": null
    }
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password confirmation does not match."]
  }
}
```

---

### 2. LOGIN USER

**Endpoint:** `POST /api/login`

**Request Body (JSON):**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "johndoe",
      "full_name": "John Doe",
      "email": "john@example.com",
      "role": "customer",
      "phone_number": "08123456789",
      "profile_pic": null,
      "address_line": "Jl. Contoh No. 123",
      "city": "Jakarta",
      "province": "DKI Jakarta",
      "postal_code": "12345"
    }
  }
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Email atau password salah"
}
```

---

### 3. GET PROFILE USER

**Endpoint:** `GET /api/profile`

**Headers:** (Kalau udah pakai token)
```
Authorization: Bearer {your_token}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Profile berhasil diambil",
  "data": {
    "id": 1,
    "name": "johndoe",
    "full_name": "John Doe",
    "email": "john@example.com",
    "role": "customer",
    "phone_number": "08123456789",
    "birth_date": "1990-01-01",
    "gender": "male",
    "profile_pic": "images/profiles/123456_photo.jpg",
    "address_line": "Jl. Contoh No. 123",
    "city": "Jakarta",
    "province": "DKI Jakarta",
    "postal_code": "12345"
  }
}
```

---

### 4. UPDATE PROFILE USER

**Endpoint:** `PUT /api/profile` atau `PATCH /api/profile`

**Headers:**
```
Authorization: Bearer {your_token}
Content-Type: multipart/form-data (kalau upload foto)
```

**Request Body (form-data):**
```
full_name: John Doe Updated
phone_number: 08198765432
birth_date: 1990-01-01
gender: male
address_line: Jl. Baru No. 456
city: Bandung
province: Jawa Barat
postal_code: 40123
profile_pic: [file]
```

**Semua field optional** - kirim yang mau diupdate aja

**Success Response (200):**
```json
{
  "success": true,
  "message": "Profile berhasil diupdate",
  "data": {
    "id": 1,
    "name": "johndoe",
    "full_name": "John Doe Updated",
    "email": "john@example.com",
    "role": "customer",
    "phone_number": "08198765432",
    "birth_date": "1990-01-01",
    "gender": "male",
    "profile_pic": "images/profiles/1704326400_photo.jpg",
    "address_line": "Jl. Baru No. 456",
    "city": "Bandung",
    "province": "Jawa Barat",
    "postal_code": "40123"
  }
}
```

---

### 5. LOGOUT USER

**Endpoint:** `POST /api/logout`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

---

### 6. GET ALL USERS (Admin)

**Endpoint:** `GET /api/users`

**Query Parameters:**
```
per_page (optional) - Jumlah data per halaman (default: 10)
role (optional) - Filter by role: "customer" atau "admin"
```

**Example:**
```
GET /api/users?per_page=5&role=customer
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Users berhasil diambil",
  "data": [
    {
      "id": 1,
      "name": "johndoe",
      "full_name": "John Doe",
      "email": "john@example.com",
      "role": "customer",
      "phone_number": "08123456789",
      "profile_pic": null
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 5,
    "total": 15
  }
}
```

---

### 7. GET USER BY ID (Admin)

**Endpoint:** `GET /api/users/{id}`

**Example:**
```
GET /api/users/1
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "User berhasil diambil",
  "data": {
    "id": 1,
    "name": "johndoe",
    "full_name": "John Doe",
    "email": "john@example.com",
    "role": "customer",
    "phone_number": "08123456789",
    "birth_date": "1990-01-01",
    "gender": "male",
    "profile_pic": "images/profiles/123456_photo.jpg",
    "address_line": "Jl. Contoh No. 123",
    "city": "Jakarta",
    "province": "DKI Jakarta",
    "postal_code": "12345"
  }
}
```

---

### 8. DELETE USER (Admin)

**Endpoint:** `DELETE /api/users/{id}`

**Success Response (200):**
```json
{
  "success": true,
  "message": "User berhasil dihapus"
}
```

---

## 📦 PRODUCT API

### 1. GET ALL PRODUCTS

**Endpoint:** `GET /api/products`

**Query Parameters:**
```
per_page (optional) - Default: 10
category (optional) - Filter by kategori
search (optional) - Search by nama produk
```

**Example:**
```
GET /api/products?per_page=10&category=batik&search=jogja
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Produk berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama_produk": "Batik Jogja Premium",
      "deskripsi": "Batik tulis asli dari Jogja",
      "harga": 250000,
      "harga_diskon": 200000,
      "diskon_persen": 20,
      "stok": 10,
      "kategori": "batik",
      "material": "Katun",
      "proses": "Tulis",
      "ukuran": "L",
      "jenis_lengan": "Panjang",
      "gambar": "images/products/1234567890_batik.jpg",
      "sku": "BTK-001",
      "tags": "batik,jogja,premium"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 10,
    "total": 50
  }
}
```

---

### 2. GET PRODUCT DETAIL

**Endpoint:** `GET /api/products/{id}`

**Example:**
```
GET /api/products/1
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Detail produk berhasil diambil",
  "data": {
    "id": 1,
    "nama_produk": "Batik Jogja Premium",
    "deskripsi": "Batik tulis asli dari Jogja dengan kualitas terbaik",
    "harga": 250000,
    "harga_diskon": 200000,
    "diskon_persen": 20,
    "stok": 10,
    "kategori": "batik",
    "material": "Katun",
    "proses": "Tulis",
    "ukuran": "L",
    "jenis_lengan": "Panjang",
    "gambar": "images/products/1234567890_batik.jpg",
    "sku": "BTK-001",
    "tags": "batik,jogja,premium",
    "created_at": "2026-01-01T10:00:00.000000Z",
    "updated_at": "2026-01-01T10:00:00.000000Z"
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Produk tidak ditemukan"
}
```

---

### 3. CREATE PRODUCT (Admin)

**Endpoint:** `POST /api/products`

**Content-Type:** `multipart/form-data`

**Request Body (form-data):**
```
name: Batik Jogja Premium
description: Batik tulis asli dari Jogja
price: 250000
stock: 10
category: batik
material: Katun
process: Tulis
ukuran: L
jenis_lengan: Panjang
diskon_persen: 20
sku: BTK-001
tags: batik,jogja,premium
image: [file gambar]
```

**Required Fields:**
- `name` - String, max 255 char
- `description` - String
- `price` - Numeric, min 0
- `stock` - Integer, min 0
- `category` - String

**Optional Fields:**
- `material`, `process`, `ukuran`, `jenis_lengan`, `sku`, `tags`
- `diskon_persen` - Integer 0-100
- `image` - File (jpeg, png, jpg, gif, max 2MB)

**Success Response (201):**
```json
{
  "success": true,
  "message": "Produk berhasil ditambahkan",
  "data": {
    "id": 1,
    "nama_produk": "Batik Jogja Premium",
    "deskripsi": "Batik tulis asli dari Jogja",
    "harga": 250000,
    "harga_diskon": 200000,
    "diskon_persen": 20,
    "stok": 10,
    "kategori": "batik",
    "gambar": "images/products/1704326400_batik.jpg"
  }
}
```

---

### 4. UPDATE PRODUCT (Admin)

**Endpoint:** `PUT /api/products/{id}` atau `PATCH /api/products/{id}`

**Content-Type:** `multipart/form-data`

**Request Body:** Sama seperti CREATE, tapi semua field **optional**

**Example:**
```
PUT /api/products/1

Body:
name: Batik Jogja Premium Updated
price: 220000
stock: 15
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Produk berhasil diupdate",
  "data": {
    "id": 1,
    "nama_produk": "Batik Jogja Premium Updated",
    "harga": 220000,
    "stok": 15,
    ...
  }
}
```

---

### 5. DELETE PRODUCT (Admin)

**Endpoint:** `DELETE /api/products/{id}`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Produk berhasil dihapus"
}
```

---

### 6. GET CATEGORIES

**Endpoint:** `GET /api/products-categories`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Kategori berhasil diambil",
  "data": [
    "batik",
    "songket",
    "tenun",
    "ikat"
  ]
}
```

---

### 7. SEARCH PRODUCTS

**Endpoint:** `POST /api/products/search`

**Request Body (JSON):**
```json
{
  "keyword": "batik",
  "min_price": 50000,
  "max_price": 300000,
  "category": "batik"
}
```

**Required:** `keyword`  
**Optional:** `min_price`, `max_price`, `category`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Pencarian berhasil",
  "data": [
    {
      "id": 1,
      "nama_produk": "Batik Jogja Premium",
      ...
    }
  ],
  "total_results": 15
}
```

---

## 🎯 CARA PAKAI DI FLUTTER

### Setup HTTP Client

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
  static const String baseUrl = 'http://127.0.0.1:8000/api';
  
  // Untuk emulator Android gunakan: http://10.0.2.2:8000/api
  // Untuk device fisik gunakan: http://YOUR_LOCAL_IP:8000/api
}
```

---

### 1. Register User

```dart
Future<Map<String, dynamic>> register({
  required String name,
  required String email,
  required String password,
  String? phoneNumber,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/register'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': password,
      'phone_number': phoneNumber,
    }),
  );

  return jsonDecode(response.body);
}
```

---

### 2. Login User

```dart
Future<Map<String, dynamic>> login({
  required String email,
  required String password,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/login'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'email': email,
      'password': password,
    }),
  );

  return jsonDecode(response.body);
}
```

---

### 3. Get Profile

```dart
Future<Map<String, dynamic>> getProfile() async {
  final response = await http.get(
    Uri.parse('$baseUrl/profile'),
    // Nanti kalau udah pakai token:
    // headers: {'Authorization': 'Bearer $token'},
  );

  return jsonDecode(response.body);
}
```

---

### 4. Update Profile (dengan gambar)

```dart
import 'package:http/http.dart' as http;
import 'dart:io';

Future<Map<String, dynamic>> updateProfile({
  String? fullName,
  String? phoneNumber,
  File? profilePic,
}) async {
  var request = http.MultipartRequest(
    'PUT',
    Uri.parse('$baseUrl/profile'),
  );

  if (fullName != null) request.fields['full_name'] = fullName;
  if (phoneNumber != null) request.fields['phone_number'] = phoneNumber;

  if (profilePic != null) {
    request.files.add(
      await http.MultipartFile.fromPath('profile_pic', profilePic.path),
    );
  }

  final streamedResponse = await request.send();
  final response = await http.Response.fromStream(streamedResponse);

  return jsonDecode(response.body);
}
```

---

### 5. Get All Products

```dart
Future<Map<String, dynamic>> getProducts({
  int perPage = 10,
  String? category,
  String? search,
}) async {
  var queryParams = {
    'per_page': perPage.toString(),
    if (category != null) 'category': category,
    if (search != null) 'search': search,
  };

  final uri = Uri.parse('$baseUrl/products').replace(
    queryParameters: queryParams,
  );

  final response = await http.get(uri);
  return jsonDecode(response.body);
}
```

---

### 6. Get Product Detail

```dart
Future<Map<String, dynamic>> getProductDetail(int id) async {
  final response = await http.get(
    Uri.parse('$baseUrl/products/$id'),
  );

  return jsonDecode(response.body);
}
```

---

### 7. Create Product (Admin)

```dart
Future<Map<String, dynamic>> createProduct({
  required String name,
  required String description,
  required double price,
  required int stock,
  required String category,
  File? image,
}) async {
  var request = http.MultipartRequest(
    'POST',
    Uri.parse('$baseUrl/products'),
  );

  request.fields['name'] = name;
  request.fields['description'] = description;
  request.fields['price'] = price.toString();
  request.fields['stock'] = stock.toString();
  request.fields['category'] = category;

  if (image != null) {
    request.files.add(
      await http.MultipartFile.fromPath('image', image.path),
    );
  }

  final streamedResponse = await request.send();
  final response = await http.Response.fromStream(streamedResponse);

  return jsonDecode(response.body);
}
```

---

### 8. Delete Product (Admin)

```dart
Future<Map<String, dynamic>> deleteProduct(int id) async {
  final response = await http.delete(
    Uri.parse('$baseUrl/products/$id'),
  );

  return jsonDecode(response.body);
}
```

---

## 🔥 TIPS & NOTES

### 1. **Base URL untuk Testing**
- **Emulator Android:** `http://10.0.2.2:8000/api`
- **iOS Simulator:** `http://127.0.0.1:8000/api`
- **Device Fisik:** `http://YOUR_LOCAL_IP:8000/api` (misal: `http://192.168.1.100:8000/api`)

### 2. **Error Handling**
Semua response punya field `success`:
```dart
final result = await apiService.login(...);

if (result['success'] == true) {
  // Success
  final user = result['data']['user'];
} else {
  // Error
  final errorMessage = result['message'];
  print(errorMessage);
}
```

### 3. **Upload File**
Untuk upload gambar (profile pic, product image), gunakan `MultipartRequest`:
```dart
var request = http.MultipartRequest('POST', uri);
request.files.add(await http.MultipartFile.fromPath('image', file.path));
```

### 4. **Pagination**
Response list (products, users) ada field `pagination`:
```dart
final result = await getProducts(perPage: 10);
final products = result['data'];
final pagination = result['pagination'];

int currentPage = pagination['current_page'];
int totalPages = pagination['last_page'];
```

### 5. **Status Code**
- `200` - Success (GET, PUT, DELETE)
- `201` - Created (POST)
- `401` - Unauthorized (belum login atau token invalid)
- `404` - Not Found (data tidak ditemukan)
- `422` - Validation Error (input tidak valid)
- `500` - Server Error

---

## ✅ TESTING API

### Pakai Postman atau Thunder Client

**Test Register:**
```
POST http://127.0.0.1:8000/api/register
Body (JSON):
{
  "name": "testuser",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Test Login:**
```
POST http://127.0.0.1:8000/api/login
Body (JSON):
{
  "email": "test@example.com",
  "password": "password123"
}
```

**Test Get Products:**
```
GET http://127.0.0.1:8000/api/products?per_page=5
```

---

## 🚀 NEXT STEPS (Optional)

Kalau mau lebih advanced:

1. **Install Laravel Sanctum** untuk token-based auth:
   ```bash
   composer require laravel/sanctum
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```

2. **Update User model** - tambah `use HasApiTokens`:
   ```php
   use Laravel\Sanctum\HasApiTokens;
   
   class User extends Authenticatable {
       use HasApiTokens, HasFactory, Notifiable;
   }
   ```

3. **Generate token di login:**
   ```php
   $token = $user->createToken('auth_token')->plainTextToken;
   ```

4. **Protect routes:**
   ```php
   Route::middleware('auth:sanctum')->group(function () {
       Route::get('/profile', [ApiUserController::class, 'profile']);
   });
   ```

---

**Happy Coding! 🚀**
