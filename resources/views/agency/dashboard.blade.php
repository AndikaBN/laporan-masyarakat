<x-dashboard
    :pageTitle="'Agency Admin Dashboard'"
    :pageRole="'Agency Admin'"
    :gradientStart="'#f093fb'"
    :gradientEnd="'#f5576c'"
>
    @slot('sidebarContent')
        <li class="sidebar-title">Main</li>
        <li><a href="{{ route('agency.dashboard') }}" class="sidebar-link {{ request()->routeIs('agency.dashboard') ? 'active' : '' }}">📊 Dashboard</a></li>

        <li class="sidebar-title">Agency</li>
        <li><a href="#" class="sidebar-link">📍 Agency Profile</a></li>
        <li><a href="#" class="sidebar-link">📋 Agency Reports</a></li>

        <li class="sidebar-title">Settings</li>
        <li><a href="#" class="sidebar-link">⚙️ Account Settings</a></li>
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
            <h2 style="color: #333; margin-bottom: 10px;">Welcome, {{ auth()->user()->name }}! 👋</h2>
            <p style="color: #666; line-height: 1.6;">You are logged in as an Agency Admin. You have access to agency-specific features and reports.</p>
        </div>

        <!-- Stats -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Dashboard Access</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>

        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Agency Management</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>

        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Reports Access</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>

        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Limited Settings</h3>
            <div style="font-size: 32px; font-weight: bold;">✓</div>
        </div>
    </div>
</x-dashboard>
