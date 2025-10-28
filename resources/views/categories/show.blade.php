@extends('layouts.app')

@section('content')
<div style="padding: 40px;">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 40px; border-radius: 15px; margin-bottom: 40px; color: white; box-shadow: 0 8px 30px rgba(245, 87, 108, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 style="margin: 0; font-size: 36px; font-weight: 800;">📋 {{ $category->name }}</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 12px 0 0 0; font-size: 15px;">{{ $category->description ?? 'Tidak ada deskripsi' }}</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('categories.edit', $category->id) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: 14px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s;">
                    ✏️ Edit
                </a>
                <a href="{{ route('categories.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: 14px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s;">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(102,126,234,0.3); color: white;">
            <div style="color: rgba(255,255,255,0.9); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">🏢 Agensi</div>
            <div style="color: white; font-size: 18px; font-weight: 700; margin-top: 12px;">{{ $category->agency->name }}</div>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(79,172,254,0.3); color: white;">
            <div style="color: rgba(255,255,255,0.9); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">🔗 Slug</div>
            <div style="color: white; font-size: 16px; font-weight: 700; margin-top: 12px; word-break: break-all; font-family: monospace;">{{ $category->slug }}</div>
        </div>
        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(250,112,154,0.3); color: white;">
            <div style="color: rgba(255,255,255,0.9); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">📊 Laporan Aktif</div>
            <div style="color: white; font-size: 32px; font-weight: 800; margin-top: 12px;">{{ $category->reports()->count() }}</div>
        </div>
    </div>

    <!-- Agency Details -->
    <div style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); margin-bottom: 40px; border-top: 4px solid #667eea;">
        <h3 style="margin: 0 0 25px 0; color: #333; font-size: 18px; font-weight: 800;">🏢 Detail Agensi Pemilik</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%); padding: 18px; border-radius: 10px; border-left: 4px solid #667eea;">
                <div style="color: #667eea; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Nama Agensi</div>
                <div style="color: #333; font-size: 15px; font-weight: 700;">{{ $category->agency->name }}</div>
            </div>
            <div style="background: linear-gradient(135deg, #fff0f7 0%, #ffe6f0 100%); padding: 18px; border-radius: 10px; border-left: 4px solid #f093fb;">
                <div style="color: #f5576c; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">📧 Email Kontak</div>
                <div style="color: #333; font-size: 15px; font-weight: 700;">{{ $category->agency->contact }}</div>
            </div>
        </div>

        @if ($category->agency->description)
            <div style="background: linear-gradient(135deg, #f5f9ff 0%, #eef4ff 100%); padding: 18px; border-radius: 10px; border-left: 4px solid #4facfe;">
                <div style="color: #4facfe; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Deskripsi Agensi</div>
                <div style="color: #666; font-size: 14px; line-height: 1.6;">{{ $category->agency->description }}</div>
            </div>
        @endif

        <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #f0f0f0;">
            <a href="{{ route('agencies.show', $category->agency->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 22px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(102,126,234,0.3);">
                👁️ Lihat Detail Agensi
            </a>
        </div>
    </div>

    <!-- Recent Reports -->
    <div style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); margin-bottom: 40px; border-top: 4px solid #fa709a;">
        <h3 style="margin: 0 0 25px 0; color: #333; font-size: 18px; font-weight: 800;">📊 Laporan dengan Kategori Ini</h3>

        @if ($category->reports()->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%); border-bottom: 2px solid #dee2e6;">
                            <th style="padding: 16px; text-align: left; color: #667eea; font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Judul Laporan</th>
                            <th style="padding: 16px; text-align: left; color: #667eea; font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Pelapor</th>
                            <th style="padding: 16px; text-align: center; color: #667eea; font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 16px; text-align: center; color: #667eea; font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category->reports()->latest()->limit(10)->get() as $report)
                            <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f8f9ff'; this.style.boxShadow='inset 0 0 10px rgba(102,126,234,0.05)'" onmouseout="this.style.backgroundColor='white'; this.style.boxShadow='none'">
                                <td style="padding: 16px; color: #333; font-weight: 600;">
                                    <a href="{{ route('reports.show', $report->id) }}" style="text-decoration: none; color: #f5576c; font-weight: 700;">{{ Str::limit($report->title, 40) }}</a>
                                </td>
                                <td style="padding: 16px; color: #666; font-size: 13px; font-weight: 500;">{{ $report->user->name }}</td>
                                <td style="padding: 16px; text-align: center;">
                                    @php
                                        $statusColors = [
                                            'submitted' => ['bg' => 'linear-gradient(135deg, #cce5ff 0%, #99ccff 100%)', 'text' => '#0047ab', 'icon' => '📝'],
                                            'under_review' => ['bg' => 'linear-gradient(135deg, #fff3cd 0%, #ffe5a0 100%)', 'text' => '#856404', 'icon' => '🔍'],
                                            'approved' => ['bg' => 'linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%)', 'text' => '#155724', 'icon' => '✅'],
                                            'rejected' => ['bg' => 'linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%)', 'text' => '#721c24', 'icon' => '❌'],
                                            'completed' => ['bg' => 'linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%)', 'text' => '#0c5460', 'icon' => '🎉'],
                                        ];
                                        $status = $statusColors[$report->status] ?? ['bg' => '#f0f0f0', 'text' => '#333', 'icon' => '📌'];
                                    @endphp
                                    <span style="background: {{ $status['bg'] }}; color: {{ $status['text'] }}; padding: 6px 12px; border-radius: 15px; font-size: 12px; font-weight: 700; display: inline-block;">
                                        {{ $status['icon'] }} {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </td>
                                <td style="padding: 16px; text-align: center; color: #999; font-size: 12px; font-weight: 500;">{{ $report->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($category->reports()->count() > 10)
                <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
                    <em style="color: #999; font-size: 13px;">ℹ️ ... dan {{ $category->reports()->count() - 10 }} laporan lainnya</em>
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 50px 20px; background: linear-gradient(135deg, #fff0f7 0%, #ffe6f0 100%); border-radius: 10px;">
                <div style="font-size: 56px; margin-bottom: 15px;">📭</div>
                <p style="color: #999; margin: 0; font-size: 14px; font-weight: 500;">Belum ada laporan dengan kategori ini</p>
            </div>
        @endif
    </div>

    <!-- Delete Section -->
    <div style="background: linear-gradient(135deg, #fff3cd 0%, #ffe5a0 100%); border-left: 4px solid #ffc107; padding: 28px; border-radius: 12px; box-shadow: 0 8px 20px rgba(255,193,7,0.2);">
        <h4 style="margin: 0 0 14px 0; color: #856404; font-weight: 800; display: flex; align-items: center; gap: 10px; font-size: 16px;">⚠️ Zona Berbahaya</h4>
        <p style="color: #856404; margin: 0 0 16px 0; font-size: 14px; line-height: 1.6;">
            Menghapus kategori akan menghapus <strong>semua laporan</strong> yang menggunakan kategori ini.
            @if ($category->reports()->count() > 0)
                <br><strong style="color: #dc3545; font-size: 15px;">⚠️ Saat ini ada {{ $category->reports()->count() }} laporan yang akan terhapus!</strong>
            @endif
        </p>
        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?\n\nTindakan ini TIDAK DAPAT DIBATALKAN!\n{{ $category->reports()->count() }} laporan akan dihapus.');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; box-shadow: 0 4px 12px rgba(220,53,69,0.3); transition: all 0.2s;">
                🗑️ Hapus Kategori Selamanya
            </button>
        </form>
    </div>
</div>
@endsection
