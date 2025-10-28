@extends('layouts.app')

@section('content')
<div style="padding: 40px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; border-radius: 15px; margin-bottom: 40px; color: white; box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 style="margin: 0; font-size: 36px; font-weight: 800;">🏢 Daftar Agensi</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 12px 0 0 0; font-size: 16px;">Kelola semua agensi dalam sistem</p>
            </div>
            <a href="{{ route('agencies.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s; cursor: pointer;">
                ➕ Buat Agensi Baru
            </a>
        </div>
    </div>

    @if ($agencies->count() > 0)
        <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.12);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">No</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Nama Agensi</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Kontak</th>
                        <th style="padding: 18px 20px; text-align: center; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">👥 Pengguna</th>
                        <th style="padding: 18px 20px; text-align: center; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">📂 Kategori</th>
                        <th style="padding: 18px 20px; text-align: center; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agencies as $key => $agency)
                        <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f8f9ff'; this.style.boxShadow='inset 0 0 10px rgba(102,126,234,0.05)'" onmouseout="this.style.backgroundColor='white'; this.style.boxShadow='none'">
                            <td style="padding: 18px 20px; color: #667eea; font-weight: 700; font-size: 15px;">{{ ($agencies->currentPage() - 1) * $agencies->perPage() + $key + 1 }}</td>
                            <td style="padding: 18px 20px;">
                                <div style="font-weight: 700; color: #333; font-size: 15px;">{{ $agency->name }}</div>
                                <div style="font-size: 13px; color: #999; margin-top: 4px;">{{ $agency->description ?? '-' }}</div>
                            </td>
                            <td style="padding: 18px 20px; color: #666; font-size: 14px; font-weight: 500;">{{ $agency->contact }}</td>
                            <td style="padding: 18px 20px; text-align: center;">
                                <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 700;">
                                    {{ $agency->users->count() }}
                                </span>
                            </td>
                            <td style="padding: 18px 20px; text-align: center;">
                                <span style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 700;">
                                    {{ $agency->categories->count() }}
                                </span>
                            </td>
                            <td style="padding: 18px 20px; text-align: center;">
                                <a href="{{ route('agencies.show', $agency->id) }}" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 9px 13px; text-decoration: none; border-radius: 8px; font-size: 12px; display: inline-block; margin: 0 3px; transition: all 0.2s; font-weight: 700;" onmouseover="this.style.transform='scale(1.08); box-shadow: 0 4px 12px rgba(79,172,254,0.4);'" onmouseout="this.style.transform='scale(1); box-shadow: none;'">
                                    👁️
                                </a>
                                <a href="{{ route('agencies.edit', $agency->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 9px 13px; text-decoration: none; border-radius: 8px; font-size: 12px; display: inline-block; margin: 0 3px; transition: all 0.2s; font-weight: 700;" onmouseover="this.style.transform='scale(1.08); box-shadow: 0 4px 12px rgba(102,126,234,0.4);'" onmouseout="this.style.transform='scale(1); box-shadow: none;'">
                                    ✏️
                                </a>
                                <form action="{{ route('agencies.destroy', $agency->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus agensi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 9px 13px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; margin: 0 3px; transition: all 0.2s; font-weight: 700;" onmouseover="this.style.transform='scale(1.08); box-shadow: 0 4px 12px rgba(250,112,154,0.4);'" onmouseout="this.style.transform='scale(1); box-shadow: none;'">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px; display: flex; justify-content: center; margin-top: 20px;">
            {{ $agencies->links('vendor.pagination.custom') }}
        </div>
    @else
        <div style="text-align: center; padding: 100px 40px; background: white; border-radius: 15px; box-shadow: 0 8px 30px rgba(0,0,0,0.12);">
            <div style="font-size: 80px; margin-bottom: 25px; opacity: 0.7;">🏢</div>
            <h3 style="color: #333; margin: 0; font-size: 22px; font-weight: 700;">Belum Ada Agensi</h3>
            <p style="color: #666; margin: 15px 0 30px 0; font-size: 15px;">Silakan buat agensi baru untuk memulai.</p>
            <a href="{{ route('agencies.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; display: inline-block; font-weight: 700; box-shadow: 0 4px 15px rgba(102,126,234,0.3); transition: all 0.3s;">
                ➕ Buat Agensi Baru
            </a>
        </div>
    @endif
</div>
@endsection
