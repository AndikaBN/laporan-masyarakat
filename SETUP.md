# 🚀 Quick Start Guide

## Setup Steps

### 1. Update Database - Run Migrations
```bash
php artisan migrate
```

**Akan membuat/update:**
- `categories` table (baru)
- `reports` table (update dengan latitude & longitude)
- `report_media` table (baru)
- `users` table (add agency_id column)

### 2. Create Storage Link (untuk akses file public)
```bash
php artisan storage:link
```

### 3. Update php.ini (untuk upload file besar)
```ini
; Di php.ini atau config via .env

upload_max_filesize = 50M
post_max_size = 60M
max_execution_time = 300
```

### 4. Run Tests (opsional tapi recommended)
```bash
php artisan test tests/Feature/ReportApiTest.php
```

### 5. Seed Sample Data (opsional)
```bash
php artisan db:seed
```

## Access Points

### Web Admin
- **Super Admin Dashboard:** http://localhost:8000/super/dashboard
- **Agency Admin Dashboard:** http://localhost:8000/agency/dashboard
- **Agency Reports List:** http://localhost:8000/agency/reports
- **Report Detail & Map:** http://localhost:8000/agency/reports/{id}

### API (Mobile/Flutter)
- **Register:** POST /api/auth/register
- **Login:** POST /api/auth/login
- **Create Report:** POST /api/reports (with latitude, longitude, images, video)
- **Get Reports:** GET /api/reports?status=submitted&category_id=1
- **View Report:** GET /api/reports/{id}
- **Update Status:** POST /api/reports/{id}/status

## Test Credentials

**Super Admin:**
- Email: super@admin.test
- Password: password

**Agency Admin (Agency ID = 1):**
- Email: agency@admin.test
- Password: password

**Regular User:**
- Email: user@test.test
- Password: password

## API Testing with cURL

### 1. Login (Get token)
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@test.test",
    "password": "password"
  }'
```

### 2. Create Report (dengan latitude & longitude)
```bash
curl -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer {token}" \
  -F "title=Jalan Rusak di Sudirman" \
  -F "description=Ada lubang besar di KM 2" \
  -F "category_id=1" \
  -F "location=Jl. Sudirman KM 2" \
  -F "latitude=-6.2088" \
  -F "longitude=106.8456" \
  -F "images=@/path/to/image1.jpg" \
  -F "images=@/path/to/image2.jpg" \
  -F "video=@/path/to/video.mp4"
```

### 3. List Reports
```bash
curl -X GET "http://localhost:8000/api/reports?status=submitted&category_id=1" \
  -H "Authorization: Bearer {token}"
```

### 4. View Report Detail
```bash
curl -X GET http://localhost:8000/api/reports/1 \
  -H "Authorization: Bearer {token}"
```

## Browser Testing

### 1. Login sebagai Agency Admin
- Go to: http://localhost:8000/login
- Email: agency@admin.test
- Password: password

### 2. View Agency Reports
- Navigate to: http://localhost:8000/agency/reports
- Click "Lihat" untuk detail dengan maps

### 3. Update Report Status
- Di halaman detail report
- Ubah status di form "Perbarui Status"
- Klik "Perbarui Status"

## Expected Results

✅ **Migrations:**
- Categories table created
- Reports table updated with latitude/longitude
- ReportMedia table created
- Users table has agency_id column

✅ **Web:**
- Agency reports list appears at /agency/reports
- Report detail page shows maps dengan marker merah di lokasi
- Status update form berfungsi
- Redirect to list setelah update status

✅ **API:**
- Create report with latitude/longitude berhasil
- Images & video tersimpan di storage/public/reports/{id}
- Get reports menampilkan semua fields termasuk latitude/longitude
- Authorization: Agency admin hanya lihat reports dari agency mereka

✅ **Maps (Leaflet):**
- Maps centered di report latitude/longitude
- Marker red muncul di lokasi
- Popup dengan judul, lokasi, pelapor, kategori
- Zoom level 15 (street level detail)

## Troubleshooting

### Error: "Class 'ReportController' not found"
- Make sure routes/web.php sudah mengimport ReportController
- Run: php artisan route:clear

### Error: "SQLSTATE ... Column 'latitude' doesn't exist"
- Belum run migrations
- Run: php artisan migrate

### Error: "File not found" saat akses image/video
- Belum create storage link
- Run: php artisan storage:link

### Maps tidak tampil
- Check browser console untuk JS errors
- Make sure Leaflet CDN accessible
- Check latitude/longitude values valid

### Upload file gagal
- Check php.ini upload_max_filesize & post_max_size
- Restart web server setelah update php.ini

## File Struktur yang Berubah

```
✅ Updated:
  - database/migrations/2025_10_25_000001_create_reports_table.php (+ latitude, longitude)
  - app/Models/Report.php (+ latitude, longitude fillable & cast)
  - app/Models/User.php (+ agency_id fillable)
  - app/Http/Controllers/Api/ReportController.php (validate latitude/longitude)
  - tests/Feature/ReportApiTest.php (include latitude/longitude)
  - database/Factories/ReportFactory.php (+ latitude, longitude)
  - routes/web.php (+ agency reports routes)

✅ Created:
  - app/Http/Controllers/ReportController.php (Web version)
  - resources/views/agency/reports/index.blade.php
  - resources/views/agency/reports/show.blade.php
  - AGENCY_REPORTS.md (dokumentasi lengkap)
```

---

**Ready to Deploy!** 🎉  
Run `php artisan migrate` untuk memulai.
