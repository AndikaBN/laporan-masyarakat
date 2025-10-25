# Report API Documentation

## Environment Configuration

Sebelum menggunakan Report API, pastikan php.ini Anda dikonfigurasi untuk menangani upload file besar:

```ini
upload_max_filesize = 50M
post_max_size = 60M
max_execution_time = 300
```

Juga pastikan symbolic link ke storage sudah dibuat:
```bash
php artisan storage:link
```

---

## Endpoints

### 1. Create Report with Multiple Images and Optional Video

**Endpoint:** `POST /api/reports`

**Authentication:** Required (Bearer Token)

**Content-Type:** multipart/form-data

**Request Body:**
```
POST /api/reports HTTP/1.1
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW

Authorization: Bearer {token}

------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="title"

Jalan Rusak di Dekat Sekolah
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="description"

Ada lubang besar di jalan utama yang mengganggu lalu lintas
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="category_id"

1
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="location"

Jl. Merdeka No. 10, Jakarta
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="images[]"; filename="photo1.jpg"
Content-Type: image/jpeg

[binary image data]
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="images[]"; filename="photo2.jpg"
Content-Type: image/jpeg

[binary image data]
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="video"; filename="video.mp4"
Content-Type: video/mp4

[binary video data]
------WebKitFormBoundary7MA4YWxkTrZu0gW--
```

**cURL Example:**
```bash
curl -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "title=Jalan Rusak di Dekat Sekolah" \
  -F "description=Ada lubang besar di jalan utama" \
  -F "category_id=1" \
  -F "location=Jl. Merdeka No. 10" \
  -F "images[]=@/path/to/photo1.jpg" \
  -F "images[]=@/path/to/photo2.jpg" \
  -F "video=@/path/to/video.mp4"
```

**Success Response (201):**
```json
{
  "status": "success",
  "message": "Laporan berhasil dibuat",
  "data": {
    "report": {
      "id": 1,
      "user_id": 5,
      "category_id": 1,
      "title": "Jalan Rusak di Dekat Sekolah",
      "description": "Ada lubang besar di jalan utama",
      "location": "Jl. Merdeka No. 10",
      "status": "submitted",
      "created_at": "2025-10-25T10:30:00Z",
      "updated_at": "2025-10-25T10:30:00Z",
      "user": {
        "id": 5,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "category": {
        "id": 1,
        "name": "Infrastruktur Jalan"
      },
      "media": [
        {
          "id": 1,
          "type": "image",
          "file_path": "reports/1/photos/photo1.jpg",
          "mime_type": "image/jpeg",
          "size": 102400
        },
        {
          "id": 2,
          "type": "image",
          "file_path": "reports/1/photos/photo2.jpg",
          "mime_type": "image/jpeg",
          "size": 98304
        },
        {
          "id": 3,
          "type": "video",
          "file_path": "reports/1/video/video.mp4",
          "mime_type": "video/mp4",
          "size": 10485760
        }
      ]
    }
  }
}
```

**Validation Error (422):**
```json
{
  "status": "error",
  "message": "Validasi gagal",
  "errors": {
    "title": ["The title field is required."],
    "images.0": ["The images.0 must be an image."]
  }
}
```

---

### 2. Get List of Reports with Filters

**Endpoint:** `GET /api/reports`

**Authentication:** Required (Bearer Token)

**Query Parameters:**
- `status` (optional): `submitted|under_review|approved|rejected|completed`
- `category_id` (optional): integer
- `q` (optional): search by title or description
- `date_from` (optional): YYYY-MM-DD
- `date_to` (optional): YYYY-MM-DD

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/reports?status=submitted&category_id=1&q=rusak" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Laporan berhasil diambil",
  "data": {
    "reports": [
      {
        "id": 1,
        "user_id": 5,
        "category_id": 1,
        "title": "Jalan Rusak di Dekat Sekolah",
        "description": "Ada lubang besar di jalan utama",
        "location": "Jl. Merdeka No. 10",
        "status": "submitted",
        "created_at": "2025-10-25T10:30:00Z",
        "user": { "id": 5, "name": "John Doe" },
        "category": { "id": 1, "name": "Infrastruktur" },
        "media": [ { "id": 1, "type": "image", "file_path": "..." } ]
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

---

### 3. Get Single Report

**Endpoint:** `GET /api/reports/{id}`

**Authentication:** Required (Bearer Token)

**cURL Example:**
```bash
curl -X GET http://localhost:8000/api/reports/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Laporan berhasil diambil",
  "data": {
    "report": {
      "id": 1,
      "user_id": 5,
      "category_id": 1,
      "title": "Jalan Rusak di Dekat Sekolah",
      "description": "Ada lubang besar di jalan utama",
      "location": "Jl. Merdeka No. 10",
      "status": "submitted",
      "created_at": "2025-10-25T10:30:00Z",
      "updated_at": "2025-10-25T10:30:00Z",
      "user": {
        "id": 5,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "category": {
        "id": 1,
        "name": "Infrastruktur Jalan"
      },
      "media": [
        {
          "id": 1,
          "type": "image",
          "file_path": "reports/1/photos/photo1.jpg",
          "mime_type": "image/jpeg",
          "size": 102400
        }
      ]
    }
  }
}
```

---

### 4. Update Report Status (Admin/Agency Only)

**Endpoint:** `POST /api/reports/{id}/status`

**Authentication:** Required (Bearer Token)

**Content-Type:** application/json

**Request Body:**
```json
{
  "status": "under_review",
  "note": "Sedang diproses oleh tim"
}
```

**cURL Example:**
```bash
curl -X POST http://localhost:8000/api/reports/1/status \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "approved",
    "note": "Laporan telah diverifikasi"
  }'
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Status laporan berhasil diperbarui",
  "data": {
    "report": {
      "id": 1,
      "status": "approved",
      "updated_at": "2025-10-25T11:45:00Z"
    },
    "old_status": "submitted",
    "new_status": "approved"
  }
}
```

**Authorization Error (403):**
```json
{
  "status": "error",
  "message": "This action is unauthorized."
}
```

---

## File Size Limits

- **Single Image:** Max 4MB (jpg, jpeg, png)
- **Single Video:** Max 40MB (mp4, mov, mkv)
- **Images per report:** Max 5 files
- **Total upload:** Max 60MB (post_max_size)

---

## Response Format

Semua API responses mengikuti format standar:

**Success:**
```json
{
  "status": "success",
  "message": "Pesan dalam Bahasa Indonesia",
  "data": { /* data */ }
}
```

**Error:**
```json
{
  "status": "error",
  "message": "Pesan kesalahan",
  "errors": { /* validation errors */ }
}
```

---

## Status Values

- `submitted`: Laporan baru yang telah diajukan
- `under_review`: Sedang dalam proses review
- `approved`: Laporan disetujui
- `rejected`: Laporan ditolak
- `completed`: Laporan sudah ditangani

---

## Access Control

- **Super Admin:** Dapat melihat dan mengupdate semua laporan
- **Agency Admin:** Dapat melihat dan mengupdate laporan dari kategori agensi mereka
- **Regular User:** Dapat membuat laporan dan hanya melihat laporan mereka sendiri

---

## Testing

Jalankan tests dengan:
```bash
php artisan test tests/Feature/ReportApiTest.php
```

---

## Notes

- 📁 File tersimpan di `storage/app/public/reports/{report_id}/`
- 🔗 Akses file via `http://localhost:8000/storage/reports/{report_id}/...`
- 🔐 Hanya user terautentikasi yang dapat membuat laporan
- 📲 FCM integration placeholder siap untuk push notifications
