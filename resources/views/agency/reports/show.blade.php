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

<div class="container-fluid" style="padding: 30px;">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(245, 87, 108, 0.15);">
        <div>
            <h1 style="margin: 0; color: white; font-size: 32px; font-weight: 700;">📋 {{ $report->title }}</h1>
            <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0 0; font-size: 14px;">Laporan dari <strong>{{ $report->user->name }}</strong></p>
        </div>
        <a href="{{ route('agency.reports.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s; cursor: pointer;">
            ← Kembali
        </a>
    </div>

    <!-- Status Badge -->
    <div style="margin-bottom: 30px; display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
        @php
            $statusConfig = [
                'submitted' => ['bg' => '#cce5ff', 'text' => '#0047ab', 'icon' => '📝', 'label' => 'Baru Masuk'],
                'under_review' => ['bg' => '#fff3cd', 'text' => '#856404', 'icon' => '🔍', 'label' => 'Sedang Ditinjau'],
                'approved' => ['bg' => '#d4edda', 'text' => '#155724', 'icon' => '✅', 'label' => 'Disetujui'],
                'rejected' => ['bg' => '#f8d7da', 'text' => '#721c24', 'icon' => '❌', 'label' => 'Ditolak'],
                'completed' => ['bg' => '#d1ecf1', 'text' => '#0c5460', 'icon' => '🎉', 'label' => 'Selesai'],
            ];
            $status = $statusConfig[$report->status] ?? ['bg' => '#f0f0f0', 'text' => '#333', 'icon' => '📌', 'label' => ucfirst(str_replace('_', ' ', $report->status))];
        @endphp
        <span style="background: {{ $status['bg'] }}; color: {{ $status['text'] }}; padding: 12px 20px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            {{ $status['icon'] }} {{ $status['label'] }}
        </span>
        <div style="color: #999; font-size: 13px;">
            Diperbarui: <strong>{{ $report->updated_at->format('d M Y H:i') }}</strong>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
        <!-- Main Content -->
        <div>
            <!-- Info Cards Row -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 25px;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1); color: white;">
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">👤 Pelapor</div>
                    <div style="color: white; font-weight: 700; margin-bottom: 3px; font-size: 15px;">{{ $report->user->name }}</div>
                    <div style="color: rgba(255,255,255,0.9); font-size: 12px;">{{ $report->user->email }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.1); color: white;">
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">📂 Kategori</div>
                    <div style="color: white; font-weight: 700; font-size: 15px;">{{ $report->category->name }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.1); color: white;">
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">📍 Lokasi</div>
                    <div style="color: white; font-weight: 700; font-size: 15px;">{{ $report->location ?? 'Tidak ada' }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(250, 112, 154, 0.1); color: white;">
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">🗓️ Tanggal</div>
                    <div style="color: white; font-weight: 700; font-size: 15px;">{{ $report->created_at->format('d M Y') }}</div>
                </div>
            </div>

            <!-- Description Card -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); margin-bottom: 25px; border-top: 4px solid #f093fb;">
                <h3 style="margin: 0 0 15px 0; color: #333; font-weight: 700; font-size: 18px;">📝 Deskripsi Laporan</h3>
                <p style="color: #555; line-height: 1.8; margin: 0; font-size: 15px;">{{ $report->description }}</p>
            </div>

            <!-- Koordinat Card -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); margin-bottom: 25px; border-top: 4px solid #4facfe;">
                <h3 style="margin: 0 0 15px 0; color: #333; font-weight: 700; font-size: 18px;">🧭 Koordinat GPS</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <div style="color: #999; font-size: 12px; font-weight: 700; margin-bottom: 8px; text-transform: uppercase;">Latitude</div>
                        <div style="color: #333; font-weight: 600; font-family: 'Courier New', monospace; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 12px; border-radius: 8px; font-size: 14px;">{{ $report->latitude }}</div>
                    </div>
                    <div>
                        <div style="color: #999; font-size: 12px; font-weight: 700; margin-bottom: 8px; text-transform: uppercase;">Longitude</div>
                        <div style="color: #333; font-weight: 600; font-family: 'Courier New', monospace; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 12px; border-radius: 8px; font-size: 14px;">{{ $report->longitude }}</div>
                    </div>
                </div>
            </div>

            <!-- Media Gallery -->
            @if($report->media->count() > 0)
                <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-top: 4px solid #fa709a;">
                    <h3 style="margin: 0 0 20px 0; color: #333; font-weight: 700; font-size: 18px;">📸 Media Laporan ({{ $report->media->count() }} file)</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px;">
                        @foreach($report->media as $media)
                            @if($media->type === 'image')
                                <div style="position: relative; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.12); transition: transform 0.3s;">
                                    <img src="{{ asset('storage/' . $media->file_path) }}" style="width: 100%; height: 180px; object-fit: cover; cursor: pointer; transition: transform 0.3s;" alt="Report image" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                </div>
                            @elseif($media->type === 'video')
                                <div style="border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.12); background: #000;">
                                    <video style="width: 100%; height: 180px; object-fit: cover;" controls>
                                        <source src="{{ asset('storage/' . $media->file_path) }}" type="{{ $media->mime_type }}">
                                        Browser Anda tidak mendukung video tag.
                                    </video>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Sidebar -->
        <div>
            <!-- Map Card -->
            <div style="background: white; padding: 0; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 25px; border-top: 4px solid #4facfe;">
                <div style="padding: 20px; border-bottom: 1px solid #f0f0f0; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h3 style="margin: 0; color: white; font-weight: 700; font-size: 18px;">🗺️ Lokasi pada Peta</h3>
                </div>
                <div id="map" style="width: 100%; height: 350px;"></div>
            </div>

            <!-- Update Status Card -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 4px solid #f5576c;">
                <h3 style="margin: 0 0 20px 0; color: #333; font-weight: 700; font-size: 18px;">⚙️ Perbarui Status</h3>
                <form action="{{ route('agency.reports.updateStatus', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 18px;">
                        <label for="status" style="display: block; color: #333; font-weight: 700; margin-bottom: 10px; font-size: 14px;">Status Baru</label>
                        <select name="status" id="status" required style="width: 100%; padding: 12px 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: border-color 0.3s, box-shadow 0.3s; font-weight: 600;">
                            <option value="submitted" {{ $report->status === 'submitted' ? 'selected' : '' }}>📝 Baru Masuk</option>
                            <option value="under_review" {{ $report->status === 'under_review' ? 'selected' : '' }}>🔍 Sedang Ditinjau</option>
                            <option value="approved" {{ $report->status === 'approved' ? 'selected' : '' }}>✅ Disetujui</option>
                            <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                            <option value="completed" {{ $report->status === 'completed' ? 'selected' : '' }}>🎉 Selesai</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="note" style="display: block; color: #333; font-weight: 700; margin-bottom: 10px; font-size: 14px;">Catatan (Opsional)</label>
                        <textarea name="note" id="note" rows="3" placeholder="Tambahkan catatan atau komentar..." style="width: 100%; padding: 12px 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; resize: vertical; font-family: inherit; transition: border-color 0.3s, box-shadow 0.3s;"></textarea>
                    </div>

                    <button type="submit" style="width: 100%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 14px 20px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 15px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);">
                        💾 Perbarui Status
                    </button>
                </form>
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
