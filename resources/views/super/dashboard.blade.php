<x-dashboard
    :pageTitle="'Super Admin Dashboard'"
    :pageRole="'Super Admin'"
    :gradientStart="'#667eea'"
    :gradientEnd="'#764ba2'"
>
    @slot('sidebarContent')
        <li class="sidebar-title">Main</li>
        <li><a href="{{ route('super.dashboard') }}" class="sidebar-link {{ request()->routeIs('super.dashboard') ? 'active' : '' }}">📊 Dashboard</a></li>

        <li class="sidebar-title">User Management</li>
        <li><a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">👥 All Users</a></li>
        <li><a href="{{ route('users.create') }}" class="sidebar-link {{ request()->routeIs('users.create') ? 'active' : '' }}">➕ Create User</a></li>

        <li class="sidebar-title">Settings</li>
        <li><a href="#" class="sidebar-link">⚙️ System Settings</a></li>
        <li><a href="#" class="sidebar-link">📋 Reports</a></li>
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
            border-left-color: #667eea;
        }

        .sidebar-link.active {
            background: #f0f0f0;
            color: #667eea;
            border-left-color: #667eea;
            font-weight: 600;
        }
    </style>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <!-- Welcome Card -->
        <div style="background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h2 style="color: #333; margin-bottom: 10px;">Welcome, {{ auth()->user()->name }}! 👋</h2>
            <p style="color: #666; line-height: 1.6;">You are logged in as a Super Admin. You have full access to all system features and settings.</p>
        </div>

        <!-- Stats -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Dashboard Access</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>

        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Full System Control</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>

        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">User Management</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>

        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Reports Access</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>
    </div>
</x-dashboard>
