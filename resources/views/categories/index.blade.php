@extends('layouts.app')

@section('content')
<div style="padding: 40px;">
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 40px; border-radius: 15px; margin-bottom: 40px; color: white; box-shadow: 0 8px 30px rgba(245, 87, 108, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 style="margin: 0; font-size: 36px; font-weight: 800;">📋 Daftar Kategori</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 12px 0 0 0; font-size: 16px;">Kelola kategori laporan untuk setiap agensi</p>
            </div>
            <a href="{{ route('categories.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s; cursor: pointer;">
                ➕ Buat Kategori Baru
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 4px solid #f093fb;">
        <form action="{{ route('categories.index') }}" method="GET" style="display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 15px; align-items: flex-end;">
            <div>
                <label style="display: block; color: #333; font-weight: 700; margin-bottom: 10px; font-size: 14px;">🔍 Cari Kategori</label>
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Cari nama atau deskripsi..."
                       style="width: 100%; padding: 12px 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: all 0.3s; font-weight: 500;" onfocus="this.style.borderColor='#f5576c'; this.style.boxShadow='0 0 0 3px rgba(245,87,108,0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
            </div>
            <div>
                <label style="display: block; color: #333; font-weight: 700; margin-bottom: 10px; font-size: 14px;">🏢 Filter Agensi</label>
                <select name="agency_id" style="width: 100%; padding: 12px 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: all 0.3s; font-weight: 500; cursor: pointer;" onfocus="this.style.borderColor='#f5576c'; this.style.boxShadow='0 0 0 3px rgba(245,87,108,0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
                    <option value="">-- Semua Agensi --</option>
                    @foreach ($agencies as $agency)
                        <option value="{{ $agency->id }}" {{ $selectedAgency == $agency->id ? 'selected' : '' }}>
                            {{ $agency->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);">
                🔍 Filter
            </button>
            <a href="{{ route('categories.index') }}" style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; text-decoration: none; text-align: center; transition: all 0.2s; box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);">
                ↻ Reset
            </a>
        </form>
    </div>

    @if ($categories->count() > 0)
        <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.12);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">No</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Nama Kategori</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Agensi</th>
                        <th style="padding: 18px 20px; text-align: center; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">📊 Laporan</th>
                        <th style="padding: 18px 20px; text-align: center; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $key => $category)
                        <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f9f5ff'; this.style.boxShadow='inset 0 0 10px rgba(245,87,108,0.05)'" onmouseout="this.style.backgroundColor='white'; this.style.boxShadow='none'">
                            <td style="padding: 18px 20px; color: #f5576c; font-weight: 700; font-size: 15px;">{{ ($categories->currentPage() - 1) * $categories->perPage() + $key + 1 }}</td>
                            <td style="padding: 18px 20px;">
                                <div style="font-weight: 700; color: #333; font-size: 15px;">{{ $category->name }}</div>
                                <div style="font-size: 13px; color: #999; margin-top: 4px;">{{ Str::limit($category->description ?? '-', 60) }}</div>
                            </td>
                            <td style="padding: 18px 20px;">
                                <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 700;">
                                    {{ $category->agency->name }}
                                </span>
                            </td>
                            <td style="padding: 18px 20px; text-align: center;">
                                <span style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 700;">
                                    {{ $category->reports()->count() }}
                                </span>
                            </td>
                            <td style="padding: 18px 20px; text-align: center;">
                                <a href="{{ route('categories.show', $category->id) }}" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 9px 13px; text-decoration: none; border-radius: 8px; font-size: 12px; display: inline-block; margin: 0 3px; transition: all 0.2s; font-weight: 700;" onmouseover="this.style.transform='scale(1.08); box-shadow: 0 4px 12px rgba(79,172,254,0.4);'" onmouseout="this.style.transform='scale(1); box-shadow: none;'">
                                    👁️
                                </a>
                                <a href="{{ route('categories.edit', $category->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 9px 13px; text-decoration: none; border-radius: 8px; font-size: 12px; display: inline-block; margin: 0 3px; transition: all 0.2s; font-weight: 700;" onmouseover="this.style.transform='scale(1.08); box-shadow: 0 4px 12px rgba(102,126,234,0.4);'" onmouseout="this.style.transform='scale(1); box-shadow: none;'">
                                    ✏️
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
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
        <div style="padding: 25px; display: flex; justify-content: center; margin-top: 25px;">
            {{ $categories->links('vendor.pagination.custom') }}
        </div>
    @else
        <div style="text-align: center; padding: 100px 40px; background: white; border-radius: 15px; box-shadow: 0 8px 30px rgba(0,0,0,0.12);">
            <div style="font-size: 80px; margin-bottom: 25px; opacity: 0.7;">📋</div>
            <h3 style="color: #333; margin: 0; font-size: 22px; font-weight: 700;">Belum Ada Kategori</h3>
            <p style="color: #666; margin: 15px 0 30px 0; font-size: 15px;">Silakan buat kategori baru untuk memulai.</p>
            <a href="{{ route('categories.create') }}" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; display: inline-block; font-weight: 700; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3); transition: all 0.3s;">
                ➕ Buat Kategori Baru
            </a>
        </div>
    @endif
</div>
@endsection
