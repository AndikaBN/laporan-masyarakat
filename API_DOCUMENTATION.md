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
