# 🗺️ Agency Reports Feature - Dokumentasi Lengkap

## 📋 Ringkasan Fitur

Agency Admin dapat melihat laporan dari user berdasarkan kategori yang di-assign ke agency mereka. Setiap laporan menampilkan lokasi user dengan peta interaktif menggunakan **Leaflet.js** dengan marker lokasi laporan.

## 🏗️ Database Structure

### Migration: create_reports_table

```sql
ALTER TABLE reports ADD COLUMN latitude DECIMAL(10, 8) NULLABLE;
ALTER TABLE reports ADD COLUMN longitude DECIMAL(11, 8) NULLABLE;
CREATE SPATIAL INDEX spatialIndex ON reports(latitude, longitude);
```

**Fields yang ditambahkan:**
- `latitude` - Decimal(10, 8) untuk koordinat latitude (-90 hingga 90)
- `longitude` - Decimal(11, 8) untuk koordinat longitude (-180 hingga 180)
- Spatial index untuk query performa pada maps

## 🔄 User Flow

```
1. User membuat Report (API)
   └─→ Submit dengan latitude, longitude, images, video
   └─→ Report tersimpan dengan agency target (via category.agency_id)

2. Agency Admin Login ke Web
   └─→ Redirect ke /agency/dashboard
   └─→ View menu "Laporan"

3. Agency Admin buka /agency/reports
   └─→ Lihat list laporan dari category agency mereka
   └─→ Paginated 10 per page
   └─→ Tampil: Judul, Pelapor, Kategori, Status, Tanggal

4. Agency Admin klik "Lihat" pada laporan
   └─→ Buka /agency/reports/{id}
   └─→ Tampil detail laporan + Maps
   └─→ Maps menampilkan marker di lokasi user melaporkan
   └─→ Popup marker: Judul, Lokasi, Pelapor, Kategori

5. Agency Admin update status laporan
   └─→ Ubah status: submitted → under_review → approved/rejected
   └─→ Tekan "Perbarui Status"
   └─→ Redirect kembali dengan success message
```

## 🛣️ Routes

### Web Routes (agency.php dalam routes/web.php)

```php
Route::middleware(['auth', 'ensure.admin'])->prefix('/agency')->name('agency.')->group(function () {
    // Dashboard
    GET    /agency/dashboard              → agency.dashboard

    // Reports
    GET    /agency/reports                → ReportController@agencyIndex    (agency.reports.index)
    GET    /agency/reports/{report}       → ReportController@show           (agency.reports.show)
    PUT    /agency/reports/{report}/status → ReportController@updateStatus  (agency.reports.updateStatus)
});
```

## 🎮 Controllers

### ReportController (Web - app/Http/Controllers/ReportController.php)

**Method 1: agencyIndex()**
```php
public function agencyIndex(Request $request)
{
    // Ambil semua reports dengan category agency_id = user->agency_id
    // Include: user, category, media
    // Paginate 10 per page
    // Return view: agency.reports.index
}
```

**Method 2: show()**
```php
public function show(Request $request, Report $report)
{
    // Authorization: Super admin semua bisa, agency admin hanya dari agency mereka
    // Load: user, category, media
    // Return view: agency.reports.show dengan maps
}
```

**Method 3: updateStatus()**
```php
public function updateStatus(Request $request, Report $report)
{
    // Validate status & note
    // Update report->status
    // Redirect dengan success message
}
```

## 🗺️ Maps Implementation (Leaflet.js)

### CDN Links
```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

### JavaScript Code

```javascript
// Initialize map dengan center di report latitude/longitude
const map = L.map('map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);

// Add OpenStreetMap tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

// Add red marker di lokasi user melaporkan
const marker = L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {
    title: '{{ $report->title }}'
}).addTo(map);

// Add popup dengan informasi
marker.bindPopup(`
    <strong>{{ $report->title }}</strong><br/>
    Lokasi: {{ $report->location }}<br/>
    Pelapor: {{ $report->user->name }}<br/>
    Kategori: {{ $report->category->name }}
`).openPopup();
```

## 📊 Views

### 1. agency/reports/index.blade.php
**List semua laporan untuk agency**
- Table dengan kolom: No, Judul, Pelapor, Kategori, Status, Tanggal, Aksi
- Status badge: primary (submitted), warning (under_review), success (approved), danger (rejected/completed)
- Button "Lihat" ke detail page
- Pagination custom (10 per page)
- Empty state: "Belum ada laporan yang masuk"

### 2. agency/reports/show.blade.php
**Detail laporan dengan maps**

**Left Column:**
- Judul
- Pelapor (Name + Email)
- Kategori
- Lokasi (text)
- Koordinat (Latitude, Longitude)
- Status badge
- Deskripsi
- Tanggal laporan
- Media gallery (images & video)

**Right Column:**
- **Peta Leaflet** (height: 400px)
  - Map centered at report coordinates
  - Red marker dengan popup
  - OpenStreetMap tiles
- **Update Status Form**
  - Select dropdown: submitted, under_review, approved, rejected, completed
  - Optional notes textarea
  - Submit button

## 🔐 Authorization

### agencyIndex()
- ✅ Super Admin - Lihat semua laporan
- ✅ Agency Admin - Lihat laporan dari category agency mereka
- ❌ Regular User - Blocked (abort 403)

### show()
- ✅ Super Admin - Lihat laporan apapun
- ✅ Agency Admin - Lihat laporan dari category agency mereka SAJA
- ❌ Agency Admin - Blocked jika category dari agency lain
- ❌ Regular User - Blocked (abort 403)

### updateStatus()
- ✅ Super Admin - Update status laporan apapun
- ✅ Agency Admin - Update status laporan dari agency mereka SAJA
- ❌ Agency Admin - Blocked jika category dari agency lain
- ❌ Regular User - Blocked (abort 403)

## 🔗 Model Relations

### Report Model
```php
class Report extends Model
{
    // Fields: id, user_id, category_id, title, description, location, 
    //         latitude, longitude, status, created_at, updated_at
    
    public function user(): BelongsTo { }           // User yang membuat report
    public function category(): BelongsTo { }       // Category report
    public function media(): HasMany { }            // Images & video
    
    public function scopeForUser(Builder $query, User $user): Builder
    {
        // Super admin: semua
        // Agency admin: by category.agency_id
        // Regular user: user_id = current user
    }
}
```

### Category Model
```php
class Category extends Model
{
    // Fields: id, agency_id, name, description, created_at, updated_at
    
    public function reports(): HasMany { }
    
    public function scopeForAgency($query, $agencyId)
    {
        return $query->where('agency_id', $agencyId);
    }
}
```

### User Model
```php
class User extends Model
{
    // Sudah ada: isSuperAdmin(), isAgencyAdmin(), isAdmin()
    // agency_id fillable untuk assign ke agency
}
```

## 📱 API Integration (Mobile - Flutter)

User saat membuat laporan via API perlu submit:

```json
POST /api/reports
Content-Type: multipart/form-data

{
    "title": "Jalan Rusak di Jln. Sudirman",
    "description": "Jalan rusak parah di kilometer 2",
    "category_id": 1,
    "location": "Jln. Sudirman KM 2",
    "latitude": -6.2088,
    "longitude": 106.8456,
    "images": [file1, file2, ...],
    "video": file
}
```

## ✅ Checklist Implementasi

- [x] Update Report migration - add latitude, longitude, spatial index
- [x] Update Report model - fillable, casts
- [x] Update ReportController API store() - validate latitude/longitude
- [x] Create ReportController web - agencyIndex(), show(), updateStatus()
- [x] Create agency/reports/index.blade.php - list view
- [x] Create agency/reports/show.blade.php - detail + maps
- [x] Update routes/web.php - add agency reports routes
- [x] Implement Leaflet maps dengan marker
- [x] Authorization checks

## 🚀 Next Steps

1. **Run migrations:**
   ```bash
   php artisan migrate
   ```

2. **Test di browser:**
   ```
   http://localhost:8000/agency/reports
   http://localhost:8000/agency/reports/1
   ```

3. **Test API dengan latitude/longitude:**
   ```bash
   curl -X POST http://localhost:8000/api/reports \
     -H "Authorization: Bearer {token}" \
     -H "Content-Type: multipart/form-data" \
     -F "title=Test Report" \
     -F "description=Test" \
     -F "category_id=1" \
     -F "latitude=-6.2088" \
     -F "longitude=106.8456" \
     -F "location=Jakarta" \
     -F "images=@/path/to/image.jpg"
   ```

4. **Configure php.ini (jika belum):**
   ```ini
   upload_max_filesize = 50M
   post_max_size = 60M
   max_execution_time = 300
   ```

## 📝 Notes

- Latitude range: -90 hingga 90
- Longitude range: -180 hingga 180
- Koordinat default untuk testing: Jakarta (-6.2088, 106.8456)
- Maps zoom level: 15 (street level)
- Marker color: Red (standard danger/important)
- Responsive: Grid col-md-6 untuk left/right column

## 🎨 CSS & Styling

- Menggunakan Bootstrap 5 classes
- Custom card styling: box-shadow, rounded corners
- Status badges dengan warna semantic: primary, warning, success, danger
- Responsive grid system
- Leaflet maps fully responsive dengan height 400px

---

**Status:** ✅ Ready for Testing  
**Last Updated:** Oct 27, 2025
