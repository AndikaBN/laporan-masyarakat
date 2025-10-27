<x-dashboard
    :pageTitle="'Detail Laporan'"
    :pageRole="'Agency Admin'"
    :gradientStart="'#f093fb'"
    :gradientEnd="'#f5576c'"
>
    @slot('sidebarContent')
        <li class="sidebar-title">Utama</li>
        <li><a href="{{ route('agency.dashboard') }}" class="sidebar-link">📊 Dasbor</a></li>
        
        <li class="sidebar-title">Agensi</li>
        <li><a href="{{ route('agency.reports.index') }}" class="sidebar-link {{ request()->routeIs('agency.reports.*') ? 'active' : '' }}">📋 Laporan Agensi</a></li>
    @endslot

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('agency.reports.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Report Details -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $report->title }}</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="fw-bold">Pelapor</label>
                        <p>{{ $report->user->name }} ({{ $report->user->email }})</p>
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">Kategori</label>
                        <p>{{ $report->category->name }}</p>
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">Lokasi</label>
                        <p>{{ $report->location ?? 'Tidak ada' }}</p>
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">Koordinat</label>
                        <p>Latitude: {{ $report->latitude }}, Longitude: {{ $report->longitude }}</p>
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">Status</label>
                        <p>
                            <span class="badge bg-{{ $report->status === 'submitted' ? 'primary' : ($report->status === 'under_review' ? 'warning' : ($report->status === 'approved' ? 'success' : 'danger')) }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">Deskripsi</label>
                        <p>{{ $report->description }}</p>
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">Tanggal Laporan</label>
                        <p>{{ $report->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Media Gallery -->
            @if($report->media->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Media Laporan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($report->media as $media)
                                @if($media->type === 'image')
                                    <div class="col-md-6 mb-3">
                                        <img src="{{ asset('storage/' . $media->file_path) }}" class="img-fluid rounded" alt="Report image">
                                    </div>
                                @elseif($media->type === 'video')
                                    <div class="col-md-12 mb-3">
                                        <video width="100%" height="auto" controls class="rounded">
                                            <source src="{{ asset('storage/' . $media->file_path) }}" type="{{ $media->mime_type }}">
                                            Browser Anda tidak mendukung video tag.
                                        </video>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Map -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lokasi Laporan (Peta)</h5>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="width: 100%; height: 400px; border-radius: 0 0 0.25rem 0.25rem;"></div>
                </div>
            </div>

            <!-- Update Status Form -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Perbarui Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.reports.updateStatus', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="status" class="form-label fw-bold">Status Baru</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="submitted" {{ $report->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="under_review" {{ $report->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="approved" {{ $report->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ $report->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="note" class="form-label fw-bold">Catatan (Opsional)</label>
                            <textarea name="note" id="note" class="form-control" rows="3" placeholder="Tambahkan catatan..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Perbarui Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Add marker
    const marker = L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {
        title: '{{ $report->title }}'
    }).addTo(map);

    // Add popup
    marker.bindPopup(`
        <strong>{{ $report->title }}</strong><br/>
        Lokasi: {{ $report->location ?? 'Tidak ada' }}<br/>
        Pelapor: {{ $report->user->name }}<br/>
        Kategori: {{ $report->category->name }}
    `).openPopup();

    // Marker styling
    marker.setIcon(L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    }));
});
</script>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }

    .card-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .fw-bold {
        font-weight: 600;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }

    img {
        max-width: 100%;
        height: auto;
    }

    video {
        max-width: 100%;
        height: auto;
    }

    #map {
        border-radius: 0 0 0.25rem 0.25rem;
    }
</style>
</div>
</x-dashboard>
