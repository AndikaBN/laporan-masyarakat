<x-dashboard
    :pageTitle="'Daftar Laporan'"
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
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h2 style="color: #333; margin: 0; font-weight: 700;">📋 Daftar Laporan</h2>
                    <p style="color: #666; margin: 5px 0 0 0; font-size: 14px;">Kelola dan pantau laporan dari pengguna</p>
                </div>
                <div>
                    <span style="background: #f093fb; color: white; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 14px;">
                        Total: {{ $reports->total() }} Laporan
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon submitted">📝</div>
                <div class="stat-content">
                    <div class="stat-value">{{ $reports->where('status', 'submitted')->count() }}</div>
                    <div class="stat-label">Baru Masuk</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon review">🔍</div>
                <div class="stat-content">
                    <div class="stat-value">{{ $reports->where('status', 'under_review')->count() }}</div>
                    <div class="stat-label">Sedang Ditinjau</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon approved">✅</div>
                <div class="stat-content">
                    <div class="stat-value">{{ $reports->where('status', 'approved')->count() }}</div>
                    <div class="stat-label">Disetujui</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon completed">🎉</div>
                <div class="stat-content">
                    <div class="stat-value">{{ $reports->where('status', 'completed')->count() }}</div>
                    <div class="stat-label">Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card modern-card">
                <div class="card-body p-0">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover reports-table">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 25%;">Judul Laporan</th>
                                        <th style="width: 20%;">Pelapor</th>
                                        <th style="width: 15%;">Kategori</th>
                                        <th style="width: 15%;">Status</th>
                                        <th style="width: 15%;">Tanggal</th>
                                        <th style="width: 5%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $report)
                                        <tr class="report-row">
                                            <td>
                                                <span style="background: #f0f0f0; padding: 4px 8px; border-radius: 4px; font-weight: 600; color: #666;">
                                                    {{ ($reports->currentPage() - 1) * $reports->perPage() + $key + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div style="font-weight: 600; color: #333;">{{ Str::limit($report->title, 40) }}</div>
                                                <div style="font-size: 12px; color: #999; margin-top: 3px;">{{ Str::limit($report->description, 50) }}...</div>
                                            </td>
                                            <td>
                                                <div style="font-weight: 500; color: #333;">{{ $report->user->name }}</div>
                                                <div style="font-size: 12px; color: #999;">{{ $report->user->email }}</div>
                                            </td>
                                            <td>
                                                <span style="background: #e3f2fd; color: #1976d2; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">
                                                    {{ $report->category->name }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'submitted' => ['bg' => '#cce5ff', 'text' => '#0047ab', 'label' => 'Baru Masuk'],
                                                        'under_review' => ['bg' => '#fff3cd', 'text' => '#856404', 'label' => 'Sedang Ditinjau'],
                                                        'approved' => ['bg' => '#d4edda', 'text' => '#155724', 'label' => 'Disetujui'],
                                                        'rejected' => ['bg' => '#f8d7da', 'text' => '#721c24', 'label' => 'Ditolak'],
                                                        'completed' => ['bg' => '#d1ecf1', 'text' => '#0c5460', 'label' => 'Selesai'],
                                                    ];
                                                    $statusInfo = $statusColors[$report->status] ?? ['bg' => '#f0f0f0', 'text' => '#333', 'label' => ucfirst(str_replace('_', ' ', $report->status))];
                                                @endphp
                                                <span style="background: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['text'] }}; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                    {{ $statusInfo['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <div style="color: #666; font-size: 13px;">
                                                    <div>{{ $report->created_at->format('d M Y') }}</div>
                                                    <div style="color: #999; font-size: 12px;">{{ $report->created_at->format('H:i') }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('agency.reports.show', $report->id) }}" class="btn-action" title="Lihat Detail">
                                                    👁️
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div style="padding: 20px; border-top: 1px solid #e0e0e0; display: flex; justify-content: center;">
                            {{ $reports->links('vendor.pagination.custom') }}
                        </div>
                    @else
                        <div style="text-align: center; padding: 60px 20px;">
                            <div style="font-size: 48px; margin-bottom: 20px;">📭</div>
                            <h3 style="color: #666; margin: 0;">Belum Ada Laporan</h3>
                            <p style="color: #999; margin-top: 10px;">Saat ini belum ada laporan yang masuk dari pengguna untuk agensi Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Card Styling */
    .modern-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: box-shadow 0.3s;
    }

    .modern-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    /* Stats Card */
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        font-size: 32px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f5f5f5;
    }

    .stat-icon.submitted {
        background: #cce5ff;
    }

    .stat-icon.review {
        background: #fff3cd;
    }

    .stat-icon.approved {
        background: #d4edda;
    }

    .stat-icon.completed {
        background: #d1ecf1;
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }

    .stat-label {
        font-size: 13px;
        color: #999;
        margin-top: 3px;
    }

    /* Table Styling */
    .reports-table {
        margin: 0;
    }

    .reports-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #e0e0e0;
    }

    .reports-table thead th {
        color: #666;
        font-weight: 600;
        padding: 16px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .reports-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .report-row {
        transition: background-color 0.2s;
    }

    .report-row:hover {
        background-color: #fafafa;
    }

    /* Action Button */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f093fb;
        color: white;
        text-decoration: none;
        font-size: 16px;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
    }

    .btn-action:hover {
        background: #f5576c;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-card {
            flex-direction: column;
            text-align: center;
        }

        .reports-table thead {
            display: none;
        }

        .reports-table tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .reports-table tbody td {
            display: block;
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        .reports-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #666;
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
        }
    }

    .sidebar-link.active {
        background-color: #e3f2fd;
        color: #1976d2;
        border-left: 4px solid #1976d2;
    }

    .card-body {
        background: white;
    }
</style>
</div>
</x-dashboard>
