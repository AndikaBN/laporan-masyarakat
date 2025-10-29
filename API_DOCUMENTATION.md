# API Documentation - Mobile App Integration

Base URL: `http://localhost:8000/api` (atau domain produksi Anda)

## Authentication

API menggunakan **Laravel Sanctum** untuk authentication. Token harus dikirim di header:
```
Authorization: Bearer {token}
```

---

## Endpoints

### 1. Register User
**Endpoint:** `POST /auth/register`

**Description:** Daftar pengguna baru (hanya role 'user')

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Success Response (201):**
```json
{
  "status": "success",
  "message": "Registrasi berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user"
    },
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "token_type": "Bearer"
  }
}
```

**Error Response (422):**
```json
{
  "status": "error",
  "message": "Validasi gagal",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

### 2. Login User
**Endpoint:** `POST /auth/login`

**Description:** Login pengguna (hanya role 'user')

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user"
    },
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "token_type": "Bearer"
  }
}
```

**Error Response (401):**
```json
{
  "status": "error",
  "message": "Kredensial tidak valid"
}
```

**Error Response (403) - Non-user role:**
```json
{
  "status": "error",
  "message": "Akses ditolak. Hanya pengguna biasa yang dapat login melalui API"
}
```

---

### 3. Get Current User Info
**Endpoint:** `GET /auth/me`

**Description:** Ambil informasi pengguna yang sedang login

**Headers Required:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Data pengguna berhasil diambil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user",
      "created_at": "2025-10-25T10:30:00Z",
      "updated_at": "2025-10-25T10:30:00Z"
    }
  }
}
```

---

### 4. Logout User
**Endpoint:** `POST /auth/logout`

**Description:** Logout pengguna (revoke token)

**Headers Required:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Logout berhasil"
}
```

---

### 5. Refresh Token
**Endpoint:** `POST /auth/refresh`

**Description:** Perbarui token (buat token baru dan hapus yang lama)

**Headers Required:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Token berhasil diperbarui",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "token_type": "Bearer"
  }
}
```

---

## Report API Endpoints

### 1. Get Reports List
**Endpoint:** `GET /reports`

**Description:** Ambil daftar laporan dengan filter dan paginasi. Data otomatis difilter berdasarkan role pengguna:
- **user**: Hanya melihat laporan miliknya sendiri
- **agency_admin**: Melihat laporan dari kategori agensi mereka
- **super_admin**: Melihat semua laporan

**Headers Required:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Query Parameters (Optional):**
```
?status=submitted
&category_id=1
&q=keyword
&date_from=2025-01-01
&date_to=2025-12-31
```

**Status Values:** `submitted`, `under_review`, `approved`, `rejected`, `completed`

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Laporan berhasil diambil",
  "data": {
    "reports": [
      {
        "id": 1,
        "user_id": 2,
        "category_id": 1,
        "title": "Jalan Rusak di Jalan Merdeka",
        "description": "Jalan sangat rusak dengan banyak lubang besar",
        "location": "Jalan Merdeka No. 45, Jakarta Pusat",
        "latitude": -6.175392,
        "longitude": 106.827153,
        "status": "submitted",
        "created_at": "2025-10-28T10:30:00Z",
        "updated_at": "2025-10-28T10:30:00Z",
        "user": {
          "id": 2,
          "name": "Budi Santoso",
          "email": "budi@example.com",
          "role": "user"
        },
        "category": {
          "id": 1,
          "name": "Jalan dan Jembatan",
          "slug": "jalan-dan-jembatan",
          "description": "Laporan masalah jalan dan jembatan"
        },
        "media": [
          {
            "id": 1,
            "report_id": 1,
            "type": "image",
            "file_path": "reports/1/photos/image1.jpg",
            "mime_type": "image/jpeg",
            "size": 2048000
          }
        ]
      }
    ],
    "pagination": {
      "total": 45,
      "per_page": 15,
      "current_page": 1,
      "last_page": 3
    }
  }
}
```

**Example Usage with Filters:**
```
GET /reports?status=under_review&category_id=2&q=kebakaran&date_from=2025-10-01&date_to=2025-10-31
```

**Request Example (cURL):**
```bash
curl -X GET "http://localhost:8000/api/reports?status=submitted&category_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Request Example (Postman):**
```
Method: GET
URL: http://localhost:8000/api/reports
Headers:
  Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
  Content-Type: application/json

Query Params:
  status: submitted
  category_id: 1
  q: jalan rusak
  page: 1
```

---

### 2. Create Report with Media
**Endpoint:** `POST /reports`

**Description:** Buat laporan baru dengan gambar dan video (optional). Support multiple images (max 5) dan 1 video.

**Headers Required:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Parameters:**
```
title: string (required, max 255)
description: string (required)
category_id: integer (required, harus ada di database)
location: string (optional, max 255)
latitude: float (required, range -90 to 90)
longitude: float (required, range -180 to 180)
images: array (optional, max 5 files)
  - images[0]: image file (jpg, jpeg, png, max 4MB)
  - images[1]: image file
  - ... up to 5 images
video: file (optional, mp4/quicktime/x-matroska, max 40MB)
```

**Request Body Example (Form-Data with files):**
```
Content-Type: multipart/form-data

Form Fields:
  title: Jalan Rusak di Jalan Merdeka
  description: Jalan sangat rusak dengan banyak lubang besar yang membahayakan pengendara
  category_id: 1
  location: Jalan Merdeka No. 45, Jakarta Pusat
  latitude: -6.175392
  longitude: 106.827153
  images: [file1.jpg, file2.jpg]
  video: video.mp4
```

**Request Example (cURL with files):**
```bash
curl -X POST "http://localhost:8000/api/reports" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "title=Jalan Rusak di Jalan Merdeka" \
  -F "description=Jalan sangat rusak dengan banyak lubang besar yang membahayakan pengendara" \
  -F "category_id=1" \
  -F "location=Jalan Merdeka No. 45, Jakarta Pusat" \
  -F "latitude=-6.175392" \
  -F "longitude=106.827153" \
  -F "images[]=@/path/to/photo1.jpg" \
  -F "images[]=@/path/to/photo2.jpg" \
  -F "video=@/path/to/video.mp4"
```

**Request Example (Postman - form-data):**
```
Method: POST
URL: http://localhost:8000/api/reports

Headers:
  Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
  Content-Type: multipart/form-data

Body (form-data):
  KEY              | TYPE     | VALUE
  -----------      | -------- | --------
  title            | text     | Jalan Rusak di Jalan Merdeka
  description      | text     | Jalan sangat rusak dengan banyak lubang besar
  category_id      | text     | 1
  location         | text     | Jalan Merdeka No. 45, Jakarta Pusat
  latitude         | text     | -6.175392
  longitude        | text     | 106.827153
  images[]         | file     | photo1.jpg
  images[]         | file     | photo2.jpg
  video            | file     | video.mp4
```

**Success Response (201):**
```json
{
  "status": "success",
  "message": "Laporan berhasil dibuat",
  "data": {
    "report": {
      "id": 5,
      "user_id": 2,
      "category_id": 1,
      "title": "Jalan Rusak di Jalan Merdeka",
      "description": "Jalan sangat rusak dengan banyak lubang besar yang membahayakan pengendara",
      "location": "Jalan Merdeka No. 45, Jakarta Pusat",
      "latitude": -6.175392,
      "longitude": 106.827153,
      "status": "submitted",
      "created_at": "2025-10-29T14:22:30Z",
      "updated_at": "2025-10-29T14:22:30Z",
      "user": {
        "id": 2,
        "name": "Budi Santoso",
        "email": "budi@example.com",
        "role": "user"
      },
      "category": {
        "id": 1,
        "name": "Jalan dan Jembatan",
        "slug": "jalan-dan-jembatan",
        "description": "Laporan masalah jalan dan jembatan"
      },
      "media": [
        {
          "id": 10,
          "report_id": 5,
          "type": "image",
          "file_path": "reports/5/photos/1730211750_image1.jpg",
          "mime_type": "image/jpeg",
          "size": 2048000
        },
        {
          "id": 11,
          "report_id": 5,
          "type": "image",
          "file_path": "reports/5/photos/1730211750_image2.jpg",
          "mime_type": "image/jpeg",
          "size": 1850000
        },
        {
          "id": 12,
          "report_id": 5,
          "type": "video",
          "file_path": "reports/5/video/1730211750_video.mp4",
          "mime_type": "video/mp4",
          "size": 15728640
        }
      ]
    }
  }
}
```

**Validation Error Response (422):**
```json
{
  "status": "error",
  "message": "Validasi gagal",
  "errors": {
    "title": ["The title field is required."],
    "category_id": ["The category_id must exist in categories table."],
    "latitude": ["The latitude must be between -90 and 90."],
    "images.0": ["The images.0 must be an image."],
    "video": ["The video may not be greater than 40960 kilobytes."]
  }
}
```

**Example Flutter Code - Get Reports:**
```dart
Future<Map<String, dynamic>> getReports({
  int page = 1,
  String? status,
  int? categoryId,
  String? query,
  String? dateFrom,
  String? dateTo,
}) async {
  final token = await _getToken();
  
  var url = Uri.parse('$baseUrl/reports?page=$page');
  
  if (status != null) url = url.replace(queryParameters: {
    ...url.queryParameters,
    'status': status,
  });
  if (categoryId != null) url = url.replace(queryParameters: {
    ...url.queryParameters,
    'category_id': categoryId.toString(),
  });
  if (query != null) url = url.replace(queryParameters: {
    ...url.queryParameters,
    'q': query,
  });
  if (dateFrom != null) url = url.replace(queryParameters: {
    ...url.queryParameters,
    'date_from': dateFrom,
  });
  if (dateTo != null) url = url.replace(queryParameters: {
    ...url.queryParameters,
    'date_to': dateTo,
  });

  final response = await http.get(
    url,
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Gagal mengambil laporan');
  }
}
```

**Example Flutter Code - Create Report:**
```dart
Future<Map<String, dynamic>> createReport({
  required String title,
  required String description,
  required int categoryId,
  required String location,
  required double latitude,
  required double longitude,
  List<File>? images,
  File? video,
}) async {
  final token = await _getToken();
  final request = http.MultipartRequest(
    'POST',
    Uri.parse('$baseUrl/reports'),
  );

  request.headers['Authorization'] = 'Bearer $token';
  request.fields['title'] = title;
  request.fields['description'] = description;
  request.fields['category_id'] = categoryId.toString();
  request.fields['location'] = location;
  request.fields['latitude'] = latitude.toString();
  request.fields['longitude'] = longitude.toString();

  // Add images
  if (images != null) {
    for (int i = 0; i < images.length; i++) {
      request.files.add(
        await http.MultipartFile.fromPath('images[]', images[i].path),
      );
    }
  }

  // Add video
  if (video != null) {
    request.files.add(
      await http.MultipartFile.fromPath('video', video.path),
    );
  }

  final response = await request.send();
  final responseData = await response.stream.bytesToString();
  
  if (response.statusCode == 201) {
    return jsonDecode(responseData);
  } else {
    throw Exception('Gagal membuat laporan');
  }
}
```

---

### 3. Get Report Detail
**Endpoint:** `GET /reports/{id}`

**Description:** Ambil detail laporan lengkap dengan semua media dan informasi terkait

**Headers Required:**
```
Authorization: Bearer {token}
```

**URL Parameters:**
```
id: integer (required) - Report ID
```

**Request Example (cURL):**
```bash
curl -X GET "http://localhost:8000/api/reports/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Request Example (Postman):**
```
Method: GET
URL: http://localhost:8000/api/reports/1

Headers:
  Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
  Content-Type: application/json
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Laporan berhasil diambil",
  "data": {
    "report": {
      "id": 1,
      "user_id": 2,
      "category_id": 1,
      "title": "Jalan Rusak di Jalan Merdeka",
      "description": "Jalan sangat rusak dengan banyak lubang besar yang membahayakan pengendara. Sudah berlangsung selama berbulan-bulan.",
      "location": "Jalan Merdeka No. 45, Jakarta Pusat",
      "latitude": -6.175392,
      "longitude": 106.827153,
      "status": "under_review",
      "created_at": "2025-10-28T10:30:00Z",
      "updated_at": "2025-10-28T14:15:00Z",
      "user": {
        "id": 2,
        "name": "Budi Santoso",
        "email": "budi@example.com",
        "role": "user",
        "created_at": "2025-10-25T09:00:00Z"
      },
      "category": {
        "id": 1,
        "name": "Jalan dan Jembatan",
        "slug": "jalan-dan-jembatan",
        "description": "Laporan masalah jalan dan jembatan",
        "agency_id": 1
      },
      "media": [
        {
          "id": 1,
          "report_id": 1,
          "type": "image",
          "file_path": "reports/1/photos/image1.jpg",
          "mime_type": "image/jpeg",
          "size": 2048000,
          "url": "http://localhost:8000/storage/reports/1/photos/image1.jpg"
        },
        {
          "id": 2,
          "report_id": 1,
          "type": "image",
          "file_path": "reports/1/photos/image2.jpg",
          "mime_type": "image/jpeg",
          "size": 1900000,
          "url": "http://localhost:8000/storage/reports/1/photos/image2.jpg"
        },
        {
          "id": 3,
          "report_id": 1,
          "type": "video",
          "file_path": "reports/1/video/video1.mp4",
          "mime_type": "video/mp4",
          "size": 20480000,
          "url": "http://localhost:8000/storage/reports/1/video/video1.mp4"
        }
      ]
    }
  }
}
```

**Authorization Error (403):**
```json
{
  "status": "error",
  "message": "Akses ditolak. Anda tidak dapat melihat laporan ini."
}
```

**Not Found Error (404):**
```json
{
  "status": "error",
  "message": "Laporan tidak ditemukan"
}
```

**Example Flutter Code - Get Report Detail:**
```dart
Future<Map<String, dynamic>> getReportDetail(int reportId) async {
  final token = await _getToken();
  final response = await http.get(
    Uri.parse('$baseUrl/reports/$reportId'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else if (response.statusCode == 403) {
    throw Exception('Akses ditolak');
  } else if (response.statusCode == 404) {
    throw Exception('Laporan tidak ditemukan');
  } else {
    throw Exception('Gagal mengambil detail laporan');
  }
}
```

---

### 4. Update Report Status
**Endpoint:** `POST /reports/{id}/status`

**Description:** Update status laporan dan tambahkan catatan (untuk admin/agency_admin only)

**Headers Required:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**URL Parameters:**
```
id: integer (required) - Report ID
```

**Request Body:**
```json
{
  "status": "under_review",
  "note": "Laporan sedang diverifikasi oleh tim kami"
}
```

**Request Example (cURL):**
```bash
curl -X POST "http://localhost:8000/api/reports/1/status" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "under_review",
    "note": "Laporan sedang diverifikasi oleh tim kami"
  }'
```

**Request Example (Postman - JSON):**
```
Method: POST
URL: http://localhost:8000/api/reports/1/status

Headers:
  Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
  Content-Type: application/json

Body (raw - JSON):
{
  "status": "under_review",
  "note": "Laporan sedang diverifikasi oleh tim kami"
}
```

**Request Body dengan status lainnya (example):**

Untuk status `approved`:
```json
{
  "status": "approved",
  "note": "Laporan sudah diverifikasi dan disetujui. Akan segera ditindaklanjuti."
}
```

Untuk status `rejected`:
```json
{
  "status": "rejected",
  "note": "Laporan ini tidak sesuai dengan kategori yang ditentukan. Silakan buat laporan baru dengan kategori yang tepat."
}
```

Untuk status `completed`:
```json
{
  "status": "completed",
  "note": "Penanganan laporan telah selesai. Jalan sudah diperbaiki dan layak digunakan kembali."
}
```

**Status Options:**
- `submitted` - Baru masuk
- `under_review` - Sedang ditinjau
- `approved` - Disetujui
- `rejected` - Ditolak
- `completed` - Selesai

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Status laporan berhasil diperbarui",
  "data": {
    "report": {
      "id": 1,
      "user_id": 2,
      "category_id": 1,
      "title": "Jalan Rusak di Jalan Merdeka",
      "description": "Jalan sangat rusak dengan banyak lubang besar",
      "location": "Jalan Merdeka No. 45, Jakarta Pusat",
      "latitude": -6.175392,
      "longitude": 106.827153,
      "status": "under_review",
      "created_at": "2025-10-28T10:30:00Z",
      "updated_at": "2025-10-29T15:45:22Z",
      "user": {
        "id": 2,
        "name": "Budi Santoso",
        "email": "budi@example.com",
        "role": "user"
      },
      "category": {
        "id": 1,
        "name": "Jalan dan Jembatan",
        "slug": "jalan-dan-jembatan"
      },
      "media": []
    },
    "old_status": "submitted",
    "new_status": "under_review"
  }
}
```

**Validation Error (422):**
```json
{
  "status": "error",
  "message": "Validasi gagal",
  "errors": {
    "status": ["The status must be one of: submitted, under_review, approved, rejected, completed."],
    "note": ["The note may not be greater than 500 characters."]
  }
}
```

**Authorization Error (403):**
```json
{
  "status": "error",
  "message": "Akses ditolak. Anda tidak memiliki izin mengubah status laporan ini."
}
```

**Example Flutter Code:**
```dart
Future<Map<String, dynamic>> updateReportStatus({
  required int reportId,
  required String status,
  String? note,
}) async {
  final token = await _getToken();
  final response = await http.post(
    Uri.parse('$baseUrl/reports/$reportId/status'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
    body: jsonEncode({
      'status': status,
      'note': note,
    }),
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Gagal mengupdate status');
  }
}
```

---

## Error Handling

### Common Error Responses

**400 Bad Request:**
```json
{
  "status": "error",
  "message": "Request tidak valid"
}
```

**401 Unauthorized:**
```json
{
  "status": "error",
  "message": "Kredensial tidak valid"
}
```

**403 Forbidden:**
```json
{
  "status": "error",
  "message": "Akses ditolak"
}
```

**422 Unprocessable Entity:**
```json
{
  "status": "error",
  "message": "Validasi gagal",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

**500 Internal Server Error:**
```json
{
  "status": "error",
  "message": "Terjadi kesalahan pada server"
}
```

---

## Flutter Implementation Example

### Dependencies (pubspec.yaml)
```yaml
dependencies:
  http: ^1.1.0
  shared_preferences: ^2.2.0
```

### Register Service
```dart
Future<Map<String, dynamic>> register({
  required String name,
  required String email,
  required String password,
  required String passwordConfirmation,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/auth/register'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
    }),
  );

  if (response.statusCode == 201) {
    final data = jsonDecode(response.body);
    await _saveToken(data['data']['token']);
    return data;
  } else {
    throw Exception('Registrasi gagal');
  }
}
```

### Login Service
```dart
Future<Map<String, dynamic>> login({
  required String email,
  required String password,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/auth/login'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'email': email,
      'password': password,
    }),
  );

  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    await _saveToken(data['data']['token']);
    return data;
  } else {
    throw Exception('Login gagal');
  }
}
```

### Get Current User
```dart
Future<Map<String, dynamic>> getCurrentUser() async {
  final token = await _getToken();
  final response = await http.get(
    Uri.parse('$baseUrl/auth/me'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Gagal mengambil data pengguna');
  }
}
```

### Logout Service
```dart
Future<void> logout() async {
  final token = await _getToken();
  await http.post(
    Uri.parse('$baseUrl/auth/logout'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
  );
  await _deleteToken();
}
```

---

## Notes

- ⚠️ API hanya menerima pengguna dengan role `'user'`
- ⚠️ Admin (super_admin, agency_admin) tidak dapat login via API
- ✅ Token valid selamanya hingga di-logout atau di-refresh
- ✅ Semua responses dalam Bahasa Indonesia
- ✅ Gunakan HTTPS di production
