@extends('layouts.app')

@section('content')
<div style="padding: 30px; max-width: 700px; margin: 0 auto;">
    <div style="margin-bottom: 30px;">
        <h1 style="color: #333; margin: 0; font-size: 28px; font-weight: 700;">✏️ Edit Kategori</h1>
        <p style="color: #666; margin: 8px 0 0 0; font-size: 14px;">{{ $category->name }}</p>
    </div>

    @if ($errors->any())
        <div style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); color: #721c24; padding: 16px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc3545; box-shadow: 0 2px 8px rgba(220,53,69,0.1);">
            <strong style="display: flex; align-items: center; gap: 8px;">⚠️ Terjadi Kesalahan</strong>
            <ul style="margin: 12px 0 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li style="margin-bottom: 4px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.update', $category->id) }}" method="POST" style="background: white; padding: 28px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
        @csrf
        @method('PUT')

        <!-- Nama Kategori -->
        <div style="margin-bottom: 24px;">
            <label for="name" style="display: block; color: #333; font-weight: 700; margin-bottom: 8px; font-size: 14px;">
                📝 Nama Kategori <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ $category->name }}" required
                   style="width: 100%; padding: 12px 14px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; box-sizing: border-box; transition: border-color 0.3s, box-shadow 0.3s;"
                   onmouseover="this.style.borderColor='#bbb'"
                   onmouseout="this.style.borderColor='#e0e0e0'"
                   onfocus="this.style.borderColor='#007bff'; this.style.boxShadow='0 0 0 3px rgba(0,123,255,0.1)'"
                   onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
            @error('name')
                <span style="color: #dc3545; font-size: 12px; margin-top: 6px; display: block; font-weight: 600;">❌ {{ $message }}</span>
            @enderror
        </div>

        <!-- Sesuaikan ke Agensi -->
        <div style="margin-bottom: 24px; padding: 16px; background: linear-gradient(135deg, #fff3cd 0%, #ffe5a0 100%); border-left: 4px solid #ffc107; border-radius: 8px; box-shadow: 0 2px 8px rgba(255,193,7,0.1);">
            <label for="agency_id" style="display: block; color: #856404; font-weight: 700; margin-bottom: 8px; font-size: 14px;">
                🏢 Sesuaikan ke Agensi <span style="color: #dc3545;">*</span>
            </label>
            <select id="agency_id" name="agency_id" required
                    style="width: 100%; padding: 12px 14px; border: 2px solid #ffc107; border-radius: 6px; font-size: 14px; box-sizing: border-box; background: white; transition: border-color 0.3s, box-shadow 0.3s;"
                    onmouseover="this.style.borderColor='#ff9800'"
                    onmouseout="this.style.borderColor='#ffc107'"
                    onfocus="this.style.borderColor='#ff9800'; this.style.boxShadow='0 0 0 3px rgba(255,152,0,0.1)'"
                    onblur="this.style.borderColor='#ffc107'; this.style.boxShadow='none'">
                @foreach ($agencies as $agency)
                    <option value="{{ $agency->id }}" {{ $category->agency_id == $agency->id ? 'selected' : '' }}>
                        {{ $agency->name }}
                    </option>
                @endforeach
            </select>
            <small style="color: #856404; display: block; margin-top: 8px; font-weight: 600;">⚠️ Ubah ke agensi lain jika ingin memindahkan kategori ini ke agensi berbeda</small>
            @error('agency_id')
                <span style="color: #dc3545; font-size: 12px; margin-top: 6px; display: block; font-weight: 600;">❌ {{ $message }}</span>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div style="margin-bottom: 24px;">
            <label for="description" style="display: block; color: #333; font-weight: 700; margin-bottom: 8px; font-size: 14px;">
                📋 Deskripsi Kategori <span style="color: #999;">(Opsional)</span>
            </label>
            <textarea id="description" name="description" rows="4"
                      style="width: 100%; padding: 12px 14px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; box-sizing: border-box; resize: vertical; transition: border-color 0.3s, box-shadow 0.3s; font-family: inherit;"
                      onmouseover="this.style.borderColor='#bbb'"
                      onmouseout="this.style.borderColor='#e0e0e0'"
                      onfocus="this.style.borderColor='#007bff'; this.style.boxShadow='0 0 0 3px rgba(0,123,255,0.1)'"
                      onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">{{ $category->description }}</textarea>
            @error('description')
                <span style="color: #dc3545; font-size: 12px; margin-top: 6px; display: block; font-weight: 600;">❌ {{ $message }}</span>
            @enderror
        </div>

        <!-- Info Box -->
        <div style="background: linear-gradient(135deg, #e7f3ff 0%, #d5e9f7 100%); border-left: 4px solid #2196F3; padding: 16px; border-radius: 8px; margin-bottom: 28px; box-shadow: 0 2px 8px rgba(33,150,243,0.1);">
            <div style="color: #1565c0; font-weight: 700; margin-bottom: 10px; font-size: 13px;">ℹ️ INFORMASI KATEGORI</div>
            <ul style="margin: 0; padding-left: 20px; color: #1565c0; font-size: 13px; line-height: 1.8;">
                <li><strong>📊 Laporan Aktif:</strong> {{ $category->reports()->count() }} laporan menggunakan kategori ini</li>
                <li><strong>🔗 Slug:</strong> <code style="background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 3px; font-family: monospace; font-size: 12px;">{{ $category->slug }}</code></li>
                <li>Jika agensi diubah, laporan lama tetap terhubung dengan kategori ini</li>
                <li>Slug otomatis di-generate ulang jika nama kategori berubah</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 30px;">
            <button type="submit" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: white; padding: 13px 24px; border: none; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 12px rgba(40,167,69,0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(40,167,69,0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(40,167,69,0.3)'">
                💾 Simpan Perubahan
            </button>
            <a href="{{ route('categories.index') }}" style="background: #6c757d; color: white; padding: 13px 24px; border: none; border-radius: 6px; font-weight: 700; font-size: 14px; text-decoration: none; text-align: center; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 12px rgba(108,117,125,0.3); display: inline-flex; align-items: center; justify-content: center;"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(108,117,125,0.4)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(108,117,125,0.3)'">
                ❌ Batal
            </a>
        </div>
    </form>
</div>
@endsection
