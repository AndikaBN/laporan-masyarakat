<x-dashboard
    :pageTitle="'All Users'"
    :pageRole="'Super Admin'"
    :gradientStart="'#667eea'"
    :gradientEnd="'#764ba2'"
>
    @slot('sidebarContent')
        <li class="sidebar-title">Main</li>
        <li><a href="{{ route('super.dashboard') }}" class="sidebar-link">📊 Dashboard</a></li>

        <li class="sidebar-title">User Management</li>
        <li><a href="{{ route('users.index') }}" class="sidebar-link active">👥 All Users</a></li>
        <li><a href="{{ route('users.create') }}" class="sidebar-link">➕ Create User</a></li>

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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #666;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #f5f5f5;
            border-bottom: 2px solid #e0e0e0;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
        }

        tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        td {
            padding: 15px;
            color: #333;
        }

        .role-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            display: inline-block;
        }

        .role-badge.super-admin {
            background: #667eea;
        }

        .role-badge.agency-admin {
            background: #f093fb;
        }

        .role-badge.user {
            background: #28a745;
        }

        .text-muted {
            color: #999;
            font-size: 13px;
        }

        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .btn-link:hover {
            color: #764ba2;
        }

        .btn-danger {
            color: #dc3545;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .btn-danger:hover {
            color: #c82333;
        }

        .empty-state {
            padding: 40px;
            text-align: center;
            color: #999;
        }

        .pagination-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        .search-filter-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .search-filter-form {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-group {
            display: flex;
            gap: 10px;
            flex: 1;
            min-width: 250px;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-btn {
            padding: 12px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .filter-group {
            flex: 0 1 200px;
        }

        .filter-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background: white;
            transition: border-color 0.3s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .reset-btn {
            padding: 12px 20px;
            background: #e0e0e0;
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }

        .reset-btn:hover {
            background: #d0d0d0;
        }

        @media (max-width: 768px) {
            .search-filter-form {
                flex-direction: column;
            }

            .search-group {
                width: 100%;
            }

            .filter-group {
                width: 100%;
            }
        }

        /* Pagination Styling */
        .pagination-container {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 5px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .pagination-item {
            padding: 5px 10px !important;
            font-size: 12px !important;
            border: 1px solid #ddd;
            border-radius: 3px;
            text-decoration: none;
            color: #667eea;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
            min-height: 24px;
            line-height: 14px;
        }

        .pagination-item:hover:not(.disabled):not(.active) {
            background: #f5f5f5;
            border-color: #667eea;
        }

        .pagination-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border-color: #667eea;
        }

        .pagination-item.disabled {
            color: #ccc;
            cursor: not-allowed;
            border-color: #e0e0e0;
            background: #fafafa;
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">User Management</h1>
            <p class="page-subtitle">Manage all system users</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary">
            ➕ Create New User
        </a>
    </div>

    <!-- Search & Filter Section -->
    <div class="search-filter-container">
        <form method="GET" action="{{ route('users.index') }}" class="search-filter-form">
            <div class="search-group">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search by name..." 
                    value="{{ $searchQuery }}"
                    class="search-input"
                />
                <button type="submit" class="search-btn">🔍 Search</button>
            </div>

            <div class="filter-group">
                <select name="role" class="filter-select" onchange="this.form.submit()">
                    <option value="all" {{ $selectedRole === 'all' ? 'selected' : '' }}>All Roles</option>
                    <option value="super_admin" {{ $selectedRole === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="agency_admin" {{ $selectedRole === 'agency_admin' ? 'selected' : '' }}>Agency Admin</option>
                    <option value="user" {{ $selectedRole === 'user' ? 'selected' : '' }}>Regular User</option>
                </select>
            </div>

            @if ($searchQuery || $selectedRole !== 'all')
                <a href="{{ route('users.index') }}" class="reset-btn">✕ Clear Filters</a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge {{ str_replace('_', '-', $user->role) }}">
                                {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                        <td style="text-align: center;">
                            <div class="actions" style="justify-content: center;">
                                <a href="{{ route('users.edit', $user) }}" class="btn-link">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            No users found. <a href="{{ route('users.create') }}" class="btn-link">Create one now</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($users->hasPages())
        <div class="pagination-wrapper">
            {{ $users->links('vendor.pagination.custom') }}
        </div>
    @endif
</x-dashboard>
