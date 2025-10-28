@extends('layouts.app')

@section('content')
<div style="padding: 30px; max-width: 650px; margin: 0 auto;">
    <div style="margin-bottom: 30px;">
        <h1 style="color: #333; margin: 0; font-size: 28px; font-weight: 700;">✏️ Edit Agensi</h1>
        <p style="color: #666; margin: 8px 0 0 0; font-size: 14px;">{{ $agency->name }}</p>
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

    <form action="{{ route('agencies.update', $agency->id) }}" method="POST" style="background: white; padding: 28px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
        @csrf
        @method('PUT')

        <!-- Nama Agensi -->
        <div style="margin-bottom: 24px;">
            <label for="name" style="display: block; color: #333; font-weight: 700; margin-bottom: 8px; font-size: 14px;">
                🏢 Nama Agensi <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ $agency->name }}" required
                   style="width: 100%; padding: 12px 14px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; box-sizing: border-box; transition: border-color 0.3s, box-shadow 0.3s;"
                   onmouseover="this.style.borderColor='#bbb'"
                   onmouseout="this.style.borderColor='#e0e0e0'"
                   onfocus="this.style.borderColor='#007bff'; this.style.boxShadow='0 0 0 3px rgba(0,123,255,0.1)'"
                   onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
            <small style="color: #999; display: block; margin-top: 6px; font-size: 12px;">Nama lengkap agensi/dinas pemerintah</small>
            @error('name')
                <span style="color: #dc3545; font-size: 12px; margin-top: 6px; display: block; font-weight: 600;">❌ {{ $message }}</span>
            @enderror
        </div>

        <!-- Email Kontak -->
        <div style="margin-bottom: 24px;">
            <label for="contact" style="display: block; color: #333; font-weight: 700; margin-bottom: 8px; font-size: 14px;">
                📧 Email Kontak <span style="color: #dc3545;">*</span>
            </label>
            <input type="email" id="contact" name="contact" value="{{ $agency->contact }}" required
                   style="width: 100%; padding: 12px 14px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; box-sizing: border-box; transition: border-color 0.3s, box-shadow 0.3s;"
                   onmouseover="this.style.borderColor='#bbb'"
                   onmouseout="this.style.borderColor='#e0e0e0'"
                   onfocus="this.style.borderColor='#007bff'; this.style.boxShadow='0 0 0 3px rgba(0,123,255,0.1)'"
                   onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
            <small style="color: #999; display: block; margin-top: 6px; font-size: 12px;">Email pusat kontak agensi untuk komunikasi</small>
            @error('contact')
                <span style="color: #dc3545; font-size: 12px; margin-top: 6px; display: block; font-weight: 600;">❌ {{ $message }}</span>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div style="margin-bottom: 24px;">
            <label for="description" style="display: block; color: #333; font-weight: 700; margin-bottom: 8px; font-size: 14px;">
                📝 Deskripsi <span style="color: #999;">(Opsional)</span>
            </label>
            <textarea id="description" name="description" rows="4"
                      style="width: 100%; padding: 12px 14px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; box-sizing: border-box; resize: vertical; transition: border-color 0.3s, box-shadow 0.3s; font-family: inherit;"
                      onmouseover="this.style.borderColor='#bbb'"
                      onmouseout="this.style.borderColor='#e0e0e0'"
                      onfocus="this.style.borderColor='#007bff'; this.style.boxShadow='0 0 0 3px rgba(0,123,255,0.1)'"
                      onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">{{ $agency->description }}</textarea>
            <small style="color: #999; display: block; margin-top: 6px; font-size: 12px;">Penjelasan singkat tentang agensi dan fungsinya</small>
            @error('description')
                <span style="color: #dc3545; font-size: 12px; margin-top: 6px; display: block; font-weight: 600;">❌ {{ $message }}</span>
            @enderror
        </div>

        <!-- Info Box -->
        <div style="background: linear-gradient(135deg, #e7f3ff 0%, #d5e9f7 100%); border-left: 4px solid #2196F3; padding: 16px; border-radius: 8px; margin-bottom: 28px; box-shadow: 0 2px 8px rgba(33,150,243,0.1);">
            <div style="color: #1565c0; font-weight: 700; margin-bottom: 10px; font-size: 13px;">ℹ️ INFORMASI AGENSI</div>
            <ul style="margin: 0; padding-left: 20px; color: #1565c0; font-size: 13px; line-height: 1.8;">
                <li><strong>👥 Pengguna:</strong> {{ $agency->users->count() }} pengguna terdaftar di agensi ini</li>
                <li><strong>📂 Kategori:</strong> {{ $agency->categories->count() }} kategori laporan</li>
                <li>Perubahan data akan langsung berlaku untuk semua pengguna agensi</li>
                <li>Untuk menghapus agensi, gunakan tombol hapus di halaman daftar agensi</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 30px;">
            <button type="submit" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: white; padding: 13px 24px; border: none; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 12px rgba(40,167,69,0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(40,167,69,0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(40,167,69,0.3)'">
                💾 Simpan Perubahan
            </button>
            <a href="{{ route('agencies.index') }}" style="background: #6c757d; color: white; padding: 13px 24px; border: none; border-radius: 6px; font-weight: 700; font-size: 14px; text-decoration: none; text-align: center; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 12px rgba(108,117,125,0.3); display: inline-flex; align-items: center; justify-content: center;"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(108,117,125,0.4)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(108,117,125,0.3)'">
                ❌ Batal
            </a>
        </div>
    </form>
</div>
@endsection
