@extends('layouts.app')

@section('content')
<div style="padding: 40px;">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; border-radius: 15px; margin-bottom: 40px; color: white; box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 style="margin: 0; font-size: 36px; font-weight: 800;">🏢 {{ $agency->name }}</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 12px 0 0 0; font-size: 15px;">{{ $agency->description ?? 'Tidak ada deskripsi' }}</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('agencies.edit', $agency->id) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: 14px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s;">
                    ✏️ Edit
                </a>
                <a href="{{ route('agencies.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: 14px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s;">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(79,172,254,0.3); color: white;">
            <div style="color: rgba(255,255,255,0.9); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">📧 Email Kontak</div>
            <div style="color: white; font-size: 16px; font-weight: 700; margin-top: 12px; word-break: break-word;">{{ $agency->contact }}</div>
        </div>
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(102,126,234,0.3); color: white;">
            <div style="color: rgba(255,255,255,0.9); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">👥 Total Pengguna</div>
            <div style="color: white; font-size: 32px; font-weight: 800; margin-top: 12px;">{{ $agency->users->count() }}</div>
        </div>
        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(250,112,154,0.3); color: white;">
            <div style="color: rgba(255,255,255,0.9); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">📋 Total Kategori</div>
            <div style="color: white; font-size: 32px; font-weight: 800; margin-top: 12px;">{{ $agency->categories->count() }}</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30;">
        <!-- Users Section -->
        <div style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); border-top: 4px solid #667eea;">
            <h3 style="margin: 0 0 25px 0; color: #333; font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 8px;">👥 Pengguna <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">{{ $agency->users->count() }}</span></h3>
            @if ($agency->users->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach ($agency->users as $user)
                        <div style="padding: 16px; background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%); border-radius: 10px; border-left: 4px solid #667eea; display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #333; margin-bottom: 4px; font-size: 15px;">{{ $user->name }}</div>
                                <div style="font-size: 12px; color: #999;">{{ $user->email }}</div>
                            </div>
                            <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; margin-left: 10px;">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 50px 20px; background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%); border-radius: 10px;">
                    <div style="font-size: 48px; margin-bottom: 12px;">👥</div>
                    <p style="color: #999; margin: 0; font-size: 14px;">Belum ada pengguna di agensi ini</p>
                </div>
            @endif
        </div>

        <!-- Categories Section -->
        <div style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); border-top: 4px solid #f093fb;">
            <h3 style="margin: 0 0 25px 0; color: #333; font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 8px;">📋 Kategori <span style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">{{ $agency->categories->count() }}</span></h3>
            @if ($agency->categories->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach ($agency->categories as $category)
                        <div style="padding: 16px; background: linear-gradient(135deg, #fff0f7 0%, #ffe6f0 100%); border-radius: 10px; border-left: 4px solid #f093fb; display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <a href="{{ route('categories.show', $category->id) }}" style="font-weight: 700; color: #f5576c; text-decoration: none; margin-bottom: 4px; display: block; font-size: 15px;">{{ $category->name }}</a>
                                <div style="font-size: 12px; color: #999;">{{ $category->description ?? 'Tidak ada deskripsi' }}</div>
                            </div>
                            <span style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; margin-left: 10px;">
                                {{ $category->reports()->count() }} laporan
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 50px 20px; background: linear-gradient(135deg, #fff0f7 0%, #ffe6f0 100%); border-radius: 10px;">
                    <div style="font-size: 48px; margin-bottom: 12px;">📋</div>
                    <p style="color: #999; margin: 0; font-size: 14px;">Belum ada kategori untuk agensi ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Section -->
    <div style="background: linear-gradient(135deg, #fff3cd 0%, #ffe5a0 100%); border-left: 4px solid #ffc107; padding: 28px; border-radius: 12px; margin-top: 40px; box-shadow: 0 8px 20px rgba(255,193,7,0.2);">
        <h4 style="margin: 0 0 14px 0; color: #856404; font-weight: 800; display: flex; align-items: center; gap: 10px; font-size: 16px;">⚠️ Zona Berbahaya</h4>
        <p style="color: #856404; margin: 0 0 16px 0; font-size: 14px; line-height: 1.6;">
            Menghapus agensi akan menghapus <strong>semua pengguna dan kategori</strong> yang terkait dengan agensi ini.
            <br><strong style="color: #dc3545; font-size: 15px;">⚠️ {{ $agency->users->count() }} pengguna dan {{ $agency->categories->count() }} kategori akan dihapus!</strong>
        </p>
        <form action="{{ route('agencies.destroy', $agency->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus agensi ini?\n\nTindakan ini TIDAK DAPAT DIBATALKAN!\n{{ $agency->users->count() }} pengguna dan {{ $agency->categories->count() }} kategori akan dihapus.');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; box-shadow: 0 4px 12px rgba(220,53,69,0.3); transition: all 0.2s;">
                🗑️ Hapus Agensi Selamanya
            </button>
        </form>
    </div>
</div>
@endsection
