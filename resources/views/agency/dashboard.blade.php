<x-dashboard
    :pageTitle="'Agency Admin Dashboard'"
    :pageRole="'Agency Admin'"
    :gradientStart="'#f093fb'"
    :gradientEnd="'#f5576c'"
>
    @slot('sidebarContent')
        <li class="sidebar-title">Utama</li>
        <li><a href="{{ route('agency.dashboard') }}" class="sidebar-link {{ request()->routeIs('agency.dashboard') ? 'active' : '' }}">📊 Dasbor</a></li>

        <li class="sidebar-title">Agensi</li>
        <li><a href="#" class="sidebar-link">📍 Profil Agensi</a></li>
        <li><a href="#" class="sidebar-link">📋 Laporan Agensi</a></li>

        <li class="sidebar-title">Pengaturan</li>
        <li><a href="#" class="sidebar-link">⚙️ Pengaturan Akun</a></li>
    @endslot

    <style>
        .sidebar-link {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s;
            font-size: 14px;
        }

        .sidebar-link:hover {
            background: #f5f5f5;
            border-left-color: #f093fb;
        }

        .sidebar-link.active {
            background: #f0f0f0;
            color: #f093fb;
            border-left-color: #f093fb;
            font-weight: 600;
        }
    </style>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <!-- Welcome Card -->
        <div style="background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h2 style="color: #333; margin-bottom: 10px;">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p style="color: #666; line-height: 1.6;">Anda login sebagai Admin Agensi. Anda memiliki akses ke fitur khusus agensi dan laporan.</p>
        </div>

        <!-- Stats -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Akses Dasbor</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>

        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Manajemen Agensi</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>

        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Akses Laporan</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>

        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Pengaturan Terbatas</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>
    </div>
</x-dashboard>
