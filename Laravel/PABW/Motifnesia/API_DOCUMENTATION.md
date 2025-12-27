# 📱 API DOCUMENTATION - MOTIFNESIA (FLUTTER)

**Base URL:** `http://127.0.0.1:8000/api`  
**Response Format:** JSON  
**Tanggal:** 19 Desember 2025

---

## 🎯 TINGKAT KESULITAN KODE

### **Overall Complexity: 6/10 (MEDIUM)**

**Yang Bikin Tricky:**
1. ✅ **Validation** - Harus manual pakai `Validator::make()` (ga bisa pakai FormRequest kayak web)
2. ✅ **Response Structure** - Harus consistent di semua endpoint
3. ✅ **Error Handling** - Try-catch di setiap method
4. ✅ **HTTP Status Code** - Harus bener (200, 201, 404, 422, 500)
5. ✅ **File Upload** - Handling multipart/form-data
6. ✅ **Pagination** - Manual structure pagination metadata

**Yang Mudah:**
- ✅ Query Eloquent sama aja kayak web
- ✅ Logic business sama aja, cuma return nya beda
- ✅ Sintaks readable & ada banyak comment

---

## 📚 ENDPOINT LIST

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/products` | Get semua produk (pagination + filter) |
| GET | `/api/products/{id}` | Get detail produk by ID |
| POST | `/api/products` | Create produk baru |
| PUT/PATCH | `/api/products/{id}` | Update produk |
| DELETE | `/api/products/{id}` | Delete produk |
| GET | `/api/products-categories` | Get list kategori |
| POST | `/api/products/search` | Search produk (advanced) |
| GET | `/api/test` | Test API nyala |

---

## 📖 DETAIL API ENDPOINTS

### 1. **GET ALL PRODUCTS** (dengan pagination & filter)

**Endpoint:** `GET /api/products`

**Query Parameters:**
```
per_page    (optional) - Jumlah data per halaman (default: 10)
category    (optional) - Filter by kategori (contoh: "batik")
search      (optional) - Search by nama produk (contoh: "jogja")
```

**Example Request:**
```
GET http://127.0.0.1:8000/api/products?per_page=5&category=batik&search=jogja
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
      "deskripsi": "Batik tulis asli Jogja...",
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
      "created_at": "2025-12-19T10:00:00.000000Z",
      "updated_at": "2025-12-19T10:00:00.000000Z"
    }
    // ... more products
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 5,
    "total": 50
  }
}
```

**Error Response (500):**
```json
{
  "success": false,
  "message": "Gagal mengambil data produk",
  "error": "Error message..."
}
```

---

### 2. **GET SINGLE PRODUCT**

**Endpoint:** `GET /api/products/{id}`

**Example Request:**
```
GET http://127.0.0.1:8000/api/products/1
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Detail produk berhasil diambil",
  "data": {
    "id": 1,
    "nama_produk": "Batik Jogja Premium",
    "deskripsi": "Batik tulis asli Jogja dengan motif parang...",
    "harga": 250000,
    "harga_diskon": 200000,
    "diskon_persen": 20,
    "stok": 10,
    // ... semua field produk
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

### 3. **CREATE NEW PRODUCT**

**Endpoint:** `POST /api/products`

**Content-Type:** `multipart/form-data` atau `application/json`

**Request Body (form-data):**
```
name            (required, string)       - Nama produk
description     (required, string)       - Deskripsi produk
price           (required, numeric)      - Harga produk
stock           (required, integer)      - Stok tersedia
category        (required, string)       - Kategori produk
material        (optional, string)       - Material produk
process         (optional, string)       - Proses pembuatan
sku             (optional, string)       - SKU unik
tags            (optional, string)       - Tags (comma separated)
ukuran          (optional, string)       - Ukuran (S/M/L/XL)
jenis_lengan    (optional, string)       - Jenis lengan
diskon_persen   (optional, 0-100)        - Persentase diskon
image           (optional, file)         - Gambar produk (max 2MB)
```

**Example Request (JSON):**
```json
{
  "name": "Batik Solo Klasik",
  "description": "Batik cap dari Solo dengan motif tradisional",
  "price": 180000,
  "stock": 15,
  "category": "batik",
  "material": "Katun",
  "process": "Cap",
  "sku": "BTK-002",
  "tags": "batik,solo,klasik",
  "ukuran": "M",
  "jenis_lengan": "Pendek",
  "diskon_persen": 10
}
```

**Example Request (form-data dengan Postman):**
```
Key: name              Value: Batik Solo Klasik
Key: description       Value: Batik cap dari Solo...
Key: price             Value: 180000
Key: stock             Value: 15
Key: category          Value: batik
Key: image             Type: File (select image)
... dst
```

**Success Response (201 Created):**
```json
{
  "success": true,
  "message": "Produk berhasil ditambahkan",
  "data": {
    "id": 2,
    "nama_produk": "Batik Solo Klasik",
    "harga": 180000,
    "harga_diskon": 162000,
    // ... full product object
  }
}
```

**Validation Error Response (422):**
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "name": ["The name field is required."],
    "price": ["The price must be a number."]
  }
}
```

---

### 4. **UPDATE PRODUCT**

**Endpoint:** `PUT /api/products/{id}` atau `PATCH /api/products/{id}`

**Content-Type:** `multipart/form-data` atau `application/json`

**Request Body:** (semua field optional, kirim yang mau diupdate aja)
```
name, description, price, stock, category, dll (sama seperti create)
```

**Example Request:**
```json
{
  "price": 190000,
  "stock": 20,
  "diskon_persen": 15
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Produk berhasil diupdate",
  "data": {
    // updated product object
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

### 5. **DELETE PRODUCT**

**Endpoint:** `DELETE /api/products/{id}`

**Example Request:**
```
DELETE http://127.0.0.1:8000/api/products/1
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Produk berhasil dihapus"
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

### 6. **GET CATEGORIES**

**Endpoint:** `GET /api/products-categories`

**Example Request:**
```
GET http://127.0.0.1:8000/api/products-categories
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Kategori berhasil diambil",
  "data": [
    "batik",
    "tenun",
    "songket",
    "lurik"
  ]
}
```

---

### 7. **SEARCH PRODUCTS (Advanced)**

**Endpoint:** `POST /api/products/search`

**Request Body:**
```json
{
  "keyword": "batik",           // required
  "min_price": 50000,          // optional
  "max_price": 200000,         // optional
  "category": "batik"          // optional
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Pencarian berhasil",
  "data": [
    // array of matching products
  ],
  "total_results": 5
}
```

---

### 8. **TEST API**

**Endpoint:** `GET /api/test`

**Example Request:**
```
GET http://127.0.0.1:8000/api/test
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "API Motifnesia is running!",
  "timestamp": "2025-12-19T10:00:00.000000Z"
}
```

---

## 🔥 HTTP STATUS CODES YANG DIPAKAI

| Code | Meaning | Kapan Dipakai |
|------|---------|---------------|
| **200** | OK | Request berhasil (GET, PUT, DELETE) |
| **201** | Created | Resource berhasil dibuat (POST) |
| **404** | Not Found | Resource tidak ditemukan |
| **422** | Unprocessable Entity | Validation error |
| **500** | Internal Server Error | Server error / exception |

---

## 🧪 CARA TESTING API

### **1. Pakai Postman (RECOMMENDED)**

**Install Postman:** https://www.postman.com/downloads/

**Testing GET Request:**
1. Open Postman
2. Create New Request
3. Method: GET
4. URL: `http://127.0.0.1:8000/api/products`
5. Click "Send"
6. Lihat response di bawah

**Testing POST Request (Create Product):**
1. Method: POST
2. URL: `http://127.0.0.1:8000/api/products`
3. Tab "Body" → pilih "form-data"
4. Add key-value:
   - name: Batik Test
   - description: Testing product
   - price: 100000
   - stock: 10
   - category: batik
   - image: (pilih file gambar)
5. Click "Send"

**Testing dengan JSON Body:**
1. Tab "Body" → pilih "raw"
2. Dropdown pilih "JSON"
3. Paste JSON:
```json
{
  "name": "Test Product",
  "description": "Testing",
  "price": 100000,
  "stock": 10,
  "category": "batik"
}
```
4. Click "Send"

---

### **2. Pakai Browser (untuk GET request aja)**

```
http://127.0.0.1:8000/api/test
http://127.0.0.1:8000/api/products
http://127.0.0.1:8000/api/products/1
http://127.0.0.1:8000/api/products-categories
```

---

### **3. Pakai Flutter (HTTP Package)**

**Install package:**
```yaml
# pubspec.yaml
dependencies:
  http: ^1.1.0
```

**Example Code:**

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

// GET all products
Future<void> getProducts() async {
  final response = await http.get(
    Uri.parse('http://127.0.0.1:8000/api/products'),
  );

  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    print(data['data']); // List products
  } else {
    print('Error: ${response.statusCode}');
  }
}

// GET single product
Future<void> getProduct(int id) async {
  final response = await http.get(
    Uri.parse('http://127.0.0.1:8000/api/products/$id'),
  );

  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    print(data['data']); // Product object
  }
}

// CREATE product (JSON body)
Future<void> createProduct() async {
  final response = await http.post(
    Uri.parse('http://127.0.0.1:8000/api/products'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'name': 'Batik Flutter',
      'description': 'Created from Flutter',
      'price': 150000,
      'stock': 5,
      'category': 'batik',
    }),
  );

  if (response.statusCode == 201) {
    final data = jsonDecode(response.body);
    print('Created: ${data['data']}');
  }
}

// UPDATE product
Future<void> updateProduct(int id) async {
  final response = await http.put(
    Uri.parse('http://127.0.0.1:8000/api/products/$id'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'price': 200000,
      'stock': 10,
    }),
  );

  if (response.statusCode == 200) {
    print('Updated successfully');
  }
}

// DELETE product
Future<void> deleteProduct(int id) async {
  final response = await http.delete(
    Uri.parse('http://127.0.0.1:8000/api/products/$id'),
  );

  if (response.statusCode == 200) {
    print('Deleted successfully');
  }
}

// SEARCH products
Future<void> searchProducts(String keyword) async {
  final response = await http.post(
    Uri.parse('http://127.0.0.1:8000/api/products/search'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'keyword': keyword,
      'min_price': 50000,
      'max_price': 200000,
    }),
  );

  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    print('Found: ${data['total_results']} products');
  }
}
```

---

## 🎓 CARA PAKAI DI FLUTTER (Model Class)

**Buat Model Product:**

```dart
// lib/models/product.dart
class Product {
  final int id;
  final String namaProduk;
  final String deskripsi;
  final double harga;
  final double hargaDiskon;
  final int diskonPersen;
  final int stok;
  final String kategori;
  final String? material;
  final String? proses;
  final String? ukuran;
  final String? jenisLengan;
  final String? gambar;
  final String? sku;
  final String? tags;

  Product({
    required this.id,
    required this.namaProduk,
    required this.deskripsi,
    required this.harga,
    required this.hargaDiskon,
    required this.diskonPersen,
    required this.stok,
    required this.kategori,
    this.material,
    this.proses,
    this.ukuran,
    this.jenisLengan,
    this.gambar,
    this.sku,
    this.tags,
  });

  // Dari JSON ke Object
  factory Product.fromJson(Map<String, dynamic> json) {
    return Product(
      id: json['id'],
      namaProduk: json['nama_produk'],
      deskripsi: json['deskripsi'],
      harga: double.parse(json['harga'].toString()),
      hargaDiskon: double.parse(json['harga_diskon'].toString()),
      diskonPersen: json['diskon_persen'] ?? 0,
      stok: json['stok'],
      kategori: json['kategori'],
      material: json['material'],
      proses: json['proses'],
      ukuran: json['ukuran'],
      jenisLengan: json['jenis_lengan'],
      gambar: json['gambar'],
      sku: json['sku'],
      tags: json['tags'],
    );
  }

  // Dari Object ke JSON (untuk POST/PUT)
  Map<String, dynamic> toJson() {
    return {
      'name': namaProduk,
      'description': deskripsi,
      'price': harga,
      'stock': stok,
      'category': kategori,
      'material': material,
      'process': proses,
      'ukuran': ukuran,
      'jenis_lengan': jenisLengan,
      'sku': sku,
      'tags': tags,
      'diskon_persen': diskonPersen,
    };
  }

  // URL gambar lengkap
  String get imageUrl {
    if (gambar != null && gambar!.isNotEmpty) {
      return 'http://127.0.0.1:8000/$gambar';
    }
    return 'https://via.placeholder.com/300'; // Default image
  }
}
```

---

## ⚠️ YANG HARUS LU PERHATIIN

### 1. **CORS (akan error di Flutter mobile)**
Nanti harus setup CORS di Laravel. Gw belum tambahin, tapi nanti gw jelasin.

### 2. **Authentication (belum ada)**
API ini belum pakai authentication. Siapa aja bisa CRUD produk.
Nanti harus implement Laravel Sanctum untuk token-based auth.

### 3. **Image URL**
Gambar disimpan di `public/images/products/`.
Di Flutter, URL lengkapnya: `http://127.0.0.1:8000/images/products/1234_gambar.jpg`

### 4. **Error Handling di Flutter**
Selalu cek `response.statusCode` dan handle semua kemungkinan error.

### 5. **Testing di Real Device**
Jangan pakai `127.0.0.1` di real device, pakai IP komputer lu (misal `192.168.1.100:8000`)

---

## 📝 KESIMPULAN

**Kode API ini level MEDIUM karena:**
- ✅ Sintaks clear & banyak comment
- ✅ Response structure consistent
- ✅ Error handling lengkap
- ✅ Validation di setiap input
- ❌ Tapi tetep butuh pemahaman HTTP, JSON, validation, file upload

**Yang Lu Dapetin:**
- 7 endpoint CRUD lengkap
- Pagination support
- Search & filter
- File upload handling
- Proper error responses
- Ready to use di Flutter!

**Next Step:**
1. Test semua endpoint di Postman
2. Implement di Flutter satu-satu
3. Nanti gw ajarin setup Laravel Sanctum (authentication)
4. Setup CORS untuk mobile

---

**Dokumentasi dibuat:** 19 Desember 2025  
**Total Endpoints:** 8 endpoints  
**Complexity Level:** 6/10 (Medium)
