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
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Daftar Laporan</h4>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Pelapor</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $report)
                                        <tr>
                                            <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $key + 1 }}</td>
                                            <td>{{ Str::limit($report->title, 30) }}</td>
                                            <td>{{ $report->user->name }}</td>
                                            <td>{{ $report->category->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $report->status === 'submitted' ? 'primary' : ($report->status === 'under_review' ? 'warning' : ($report->status === 'approved' ? 'success' : 'danger')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('agency.reports.show', $report->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $reports->links('vendor.pagination.custom') }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada laporan yang masuk dari pengguna.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .sidebar-link.active {
        background-color: #e3f2fd;
        color: #1976d2;
        border-left: 4px solid #1976d2;
    }

    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
</div>
</x-dashboard>
