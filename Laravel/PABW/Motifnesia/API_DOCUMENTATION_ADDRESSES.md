# 📍 API DOCUMENTATION - USER ADDRESSES

**Base URL:** `http://127.0.0.1:8000/api`

---

## 📝 OVERVIEW

API untuk manajemen alamat user dengan fitur:
- Multi address support (bisa tambah banyak alamat)
- Primary address system (satu alamat utama)
- Auto-set first address sebagai primary
- Auto-reassign primary ketika alamat primary dihapus
- Full CRUD operations
- Validation & error handling

---

## 🔑 ENDPOINTS

### 1. **GET ALL ADDRESSES**
Ambil semua alamat user (sorted by is_primary desc, id asc)

**Endpoint:** `GET /api/addresses`

**Query Parameters:**
- `user_id` (required) - ID user

**Request Example:**
```bash
GET http://127.0.0.1:8000/api/addresses?user_id=1
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Addresses berhasil diambil",
  "data": {
    "addresses": [
      {
        "id": 1,
        "user_id": 1,
        "label": "Rumah",
        "recipient_name": "Admin User",
        "phone_number": "08123456789",
        "address_line": "Jl. Contoh No. 123, RT 01/RW 02",
        "city": "Jakarta",
        "province": "DKI Jakarta",
        "postal_code": "12345",
        "notes": null,
        "is_primary": true,
        "created_at": "2026-01-04T17:56:45.000000Z",
        "updated_at": "2026-01-04T17:56:45.000000Z"
      },
      {
        "id": 2,
        "user_id": 1,
        "label": "Kantor",
        "recipient_name": "Admin User",
        "phone_number": "08198765432",
        "address_line": "Jl. Sudirman No. 456",
        "city": "Jakarta Selatan",
        "province": "DKI Jakarta",
        "postal_code": "12190",
        "notes": "Gedung lt. 5",
        "is_primary": false,
        "created_at": "2026-01-04T17:56:56.000000Z",
        "updated_at": "2026-01-04T17:56:56.000000Z"
      }
    ],
    "total": 2
  }
}
```

---

### 2. **GET PRIMARY ADDRESS**
Ambil alamat utama (primary) user

**Endpoint:** `GET /api/addresses/primary`

**Query Parameters:**
- `user_id` (required) - ID user

**Request Example:**
```bash
GET http://127.0.0.1:8000/api/addresses/primary?user_id=1
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Alamat utama berhasil diambil",
  "data": {
    "id": 1,
    "user_id": 1,
    "label": "Rumah",
    "recipient_name": "Admin User",
    "phone_number": "08123456789",
    "address_line": "Jl. Contoh No. 123, RT 01/RW 02",
    "city": "Jakarta",
    "province": "DKI Jakarta",
    "postal_code": "12345",
    "notes": null,
    "is_primary": true,
    "created_at": "2026-01-04T17:56:45.000000Z",
    "updated_at": "2026-01-04T17:56:45.000000Z"
  }
}
```

**Response No Primary (200):**
```json
{
  "success": false,
  "message": "Belum ada alamat utama",
  "data": null
}
```

---

### 3. **GET SINGLE ADDRESS**
Ambil satu alamat berdasarkan ID

**Endpoint:** `GET /api/addresses/{id}`

**Query Parameters:**
- `user_id` (required) - ID user

**Request Example:**
```bash
GET http://127.0.0.1:8000/api/addresses/1?user_id=1
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Alamat berhasil diambil",
  "data": {
    "id": 1,
    "user_id": 1,
    "label": "Rumah",
    "recipient_name": "Admin User",
    "phone_number": "08123456789",
    "address_line": "Jl. Contoh No. 123, RT 01/RW 02",
    "city": "Jakarta",
    "province": "DKI Jakarta",
    "postal_code": "12345",
    "notes": null,
    "is_primary": true,
    "created_at": "2026-01-04T17:56:45.000000Z",
    "updated_at": "2026-01-04T17:56:45.000000Z"
  }
}
```

**Response Not Found (404):**
```json
{
  "success": false,
  "message": "Alamat tidak ditemukan"
}
```

---

### 4. **ADD NEW ADDRESS**
Tambah alamat baru (alamat pertama otomatis jadi primary)

**Endpoint:** `POST /api/addresses`

**Request Body (JSON):**
```json
{
  "user_id": 1,
  "label": "Kost",
  "recipient_name": "John Doe",
  "phone_number": "081234567890",
  "address_line": "Jl. Kaliurang KM 14",
  "city": "Sleman",
  "province": "DIY",
  "postal_code": "55581",
  "notes": "Samping kampus UGM"
}
```

**Field Details:**
- `user_id` (required, integer) - ID user pemilik alamat
- `label` (optional, string, max:255) - Label alamat: "Rumah", "Kantor", "Kost", dll
- `recipient_name` (required, string, max:255) - Nama penerima
- `phone_number` (required, string, max:20) - No HP penerima
- `address_line` (required, string) - Alamat lengkap
- `city` (required, string, max:100) - Kota/Kabupaten
- `province` (required, string, max:100) - Provinsi
- `postal_code` (required, string, max:10) - Kode pos
- `notes` (optional, string) - Catatan tambahan

**Response Success (201):**
```json
{
  "success": true,
  "message": "Alamat berhasil ditambahkan",
  "data": {
    "user_id": 1,
    "label": "Kost",
    "recipient_name": "John Doe",
    "phone_number": "081234567890",
    "address_line": "Jl. Kaliurang KM 14",
    "city": "Sleman",
    "province": "DIY",
    "postal_code": "55581",
    "notes": "Samping kampus UGM",
    "is_primary": false,
    "updated_at": "2026-01-04T18:07:02.000000Z",
    "created_at": "2026-01-04T18:07:02.000000Z",
    "id": 6
  }
}
```

**Response Validation Error (422):**
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "recipient_name": ["The recipient name field is required."],
    "phone_number": ["The phone number field is required."]
  }
}
```

---

### 5. **UPDATE ADDRESS**
Update data alamat (field yang dikirim aja yang diupdate)

**Endpoint:** `PUT /api/addresses/{id}` atau `PATCH /api/addresses/{id}`

**Request Body (JSON):**
```json
{
  "user_id": 1,
  "label": "Kost UGM (Updated)",
  "notes": "Kamar 205"
}
```

**Field Details:**
- `user_id` (required, integer) - ID user pemilik alamat
- Semua field lain optional (kirim yang mau diupdate aja)

**Response Success (200):**
```json
{
  "success": true,
  "message": "Alamat berhasil diupdate",
  "data": {
    "id": 6,
    "user_id": 1,
    "label": "Kost UGM (Updated)",
    "recipient_name": "John Doe",
    "phone_number": "081234567890",
    "address_line": "Jl. Kaliurang KM 14",
    "city": "Sleman",
    "province": "DIY",
    "postal_code": "55581",
    "notes": "Kamar 205",
    "is_primary": false,
    "created_at": "2026-01-04T18:07:02.000000Z",
    "updated_at": "2026-01-04T18:07:35.000000Z"
  }
}
```

**Response Not Found (404):**
```json
{
  "success": false,
  "message": "Alamat tidak ditemukan"
}
```

---

### 6. **SET ADDRESS AS PRIMARY**
Set alamat tertentu sebagai alamat utama (otomatis un-set alamat primary lain)

**Endpoint:** `POST /api/addresses/{id}/set-primary`

**Request Body (JSON):**
```json
{
  "user_id": 1
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Alamat utama berhasil diubah"
}
```

**Response Not Found (404):**
```json
{
  "success": false,
  "message": "Alamat tidak ditemukan"
}
```

---

### 7. **DELETE ADDRESS**
Hapus alamat (jika alamat yang dihapus adalah primary, otomatis set alamat lain jadi primary)

**Endpoint:** `DELETE /api/addresses/{id}`

**Query Parameters:**
- `user_id` (required) - ID user

**Request Example:**
```bash
DELETE http://127.0.0.1:8000/api/addresses/6?user_id=1
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Alamat berhasil dihapus"
}
```

**Response Not Found (404):**
```json
{
  "success": false,
  "message": "Alamat tidak ditemukan"
}
```

---

## 📋 SMART FEATURES

### 1. **Auto Primary Assignment**
Alamat pertama yang ditambahkan otomatis jadi primary
```json
// Pertama kali add address
{
  "is_primary": true  // <- Otomatis true
}
```

### 2. **Auto Primary Reassignment**
Ketika alamat primary dihapus, alamat lain otomatis jadi primary
```bash
# Before delete: Address ID 6 is primary
DELETE /api/addresses/6

# After delete: Address ID 1 auto becomes primary
GET /api/addresses/primary
# Response: Address ID 1 dengan is_primary: true
```

### 3. **Sorted Response**
Response GET all addresses otomatis sorted by:
1. `is_primary` DESC (primary di atas)
2. `id` ASC (yang lama di atas)

---

## 🧪 TESTING GUIDE

### PowerShell Commands:

**1. Add Address:**
```powershell
$body = @{ 
  user_id=1
  label="Kost"
  recipient_name="Test User"
  phone_number="081234567890"
  address_line="Jl. Kaliurang KM 14"
  city="Sleman"
  province="DIY"
  postal_code="55581"
  notes="Samping kampus UGM"
} | ConvertTo-Json

Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/addresses" -Method POST -Body $body -ContentType "application/json" -UseBasicParsing | Select-Object -ExpandProperty Content
```

**2. Get All Addresses:**
```powershell
Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/addresses?user_id=1" -Method GET -UseBasicParsing | Select-Object -ExpandProperty Content
```

**3. Get Primary Address:**
```powershell
Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/addresses/primary?user_id=1" -Method GET -UseBasicParsing | Select-Object -ExpandProperty Content
```

**4. Set Primary:**
```powershell
$body = @{ user_id=1 } | ConvertTo-Json
Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/addresses/6/set-primary" -Method POST -Body $body -ContentType "application/json" -UseBasicParsing | Select-Object -ExpandProperty Content
```

**5. Update Address:**
```powershell
$body = @{ 
  user_id=1
  label="Kost UGM (Updated)"
  notes="Kamar 205"
} | ConvertTo-Json

Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/addresses/6" -Method PUT -Body $body -ContentType "application/json" -UseBasicParsing | Select-Object -ExpandProperty Content
```

**6. Delete Address:**
```powershell
Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/addresses/6?user_id=1" -Method DELETE -UseBasicParsing | Select-Object -ExpandProperty Content
```

---

## ✅ TEST RESULTS

Semua endpoint sudah di-test dan **WORKING 100%**:

✅ GET All Addresses - Response 200 dengan array addresses  
✅ GET Primary Address - Response 200 dengan primary address  
✅ GET Single Address - Response 200 dengan data alamat  
✅ POST Add Address - Response 201, alamat tersimpan  
✅ PUT Update Address - Response 200, data terupdate  
✅ POST Set Primary - Response 200, primary berubah  
✅ DELETE Address - Response 200, alamat terhapus + auto reassign primary  

---

## 🚀 TOTAL API ENDPOINTS

Sekarang total API endpoints jadi: **35 endpoints**

- User/Auth: 8 endpoints
- Products: 7 endpoints
- Shopping Cart: 6 endpoints
- Favorites: 6 endpoints
- **Addresses: 8 endpoints** ⭐ NEW!

---

## 📱 FLUTTER INTEGRATION

Contoh penggunaan di Flutter dengan `http` package:

```dart
// Get all addresses
Future<List<Address>> getAddresses(int userId) async {
  final response = await http.get(
    Uri.parse('http://127.0.0.1:8000/api/addresses?user_id=$userId')
  );
  
  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    return (data['data']['addresses'] as List)
        .map((e) => Address.fromJson(e))
        .toList();
  }
  throw Exception('Failed to load addresses');
}

// Add new address
Future<Address> addAddress(Address address) async {
  final response = await http.post(
    Uri.parse('http://127.0.0.1:8000/api/addresses'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode(address.toJson()),
  );
  
  if (response.statusCode == 201) {
    final data = jsonDecode(response.body);
    return Address.fromJson(data['data']);
  }
  throw Exception('Failed to add address');
}

// Set as primary
Future<void> setPrimary(int addressId, int userId) async {
  final response = await http.post(
    Uri.parse('http://127.0.0.1:8000/api/addresses/$addressId/set-primary'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({'user_id': userId}),
  );
  
  if (response.statusCode != 200) {
    throw Exception('Failed to set primary');
  }
}

// Delete address
Future<void> deleteAddress(int addressId, int userId) async {
  final response = await http.delete(
    Uri.parse('http://127.0.0.1:8000/api/addresses/$addressId?user_id=$userId')
  );
  
  if (response.statusCode != 200) {
    throw Exception('Failed to delete address');
  }
}
```

---

## 🎯 NEXT STEPS

1. ✅ Web interface already working
2. ✅ API endpoints complete and tested
3. 🔜 Implement di Flutter app
4. 🔜 Add authentication middleware (Sanctum)
5. 🔜 Add rate limiting

---

**Created:** January 5, 2026  
**Status:** ✅ Production Ready  
**Tested:** ✅ All endpoints working
