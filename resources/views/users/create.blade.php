<x-dashboard
    :pageTitle="'Create User'"
    :pageRole="'Super Admin'"
    :gradientStart="'#667eea'"
    :gradientEnd="'#764ba2'"
>
    @slot('sidebarContent')
        <li class="sidebar-title">Main</li>
        <li><a href="{{ route('super.dashboard') }}" class="sidebar-link">📊 Dashboard</a></li>

        <li class="sidebar-title">User Management</li>
        <li><a href="{{ route('users.index') }}" class="sidebar-link">👥 All Users</a></li>
        <li><a href="{{ route('users.create') }}" class="sidebar-link active">➕ Create User</a></li>

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

        .form-container {
            max-width: 600px;
        }

        .page-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input.error, select.error {
            border-color: #dc3545;
        }

        .input-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #d0d0d0;
        }
    </style>

    <div class="form-container">
        <h1 class="page-title">Create New User</h1>
        <p class="page-subtitle">Add a new user account (Agency Admin or Regular User)</p>

        <div class="form-card">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <!-- Name Field -->
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        placeholder="Enter full name"
                        class="{{ $errors->has('name') ? 'error' : '' }}"
                    />
                    @if ($errors->has('name'))
                        <div class="input-error">{{ $errors->first('name') }}</div>
                    @endif
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="user@example.com"
                        class="{{ $errors->has('email') ? 'error' : '' }}"
                    />
                    @if ($errors->has('email'))
                        <div class="input-error">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <!-- Role Field -->
                <div class="form-group">
                    <label for="role">Role</label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="{{ $errors->has('role') ? 'error' : '' }}"
                    >
                        <option value="">-- Select Role --</option>
                        <option value="agency_admin" {{ old('role') === 'agency_admin' ? 'selected' : '' }}>Agency Admin</option>
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Regular User</option>
                    </select>
                    @if ($errors->has('role'))
                        <div class="input-error">{{ $errors->first('role') }}</div>
                    @endif
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        placeholder="Minimum 8 characters"
                        class="{{ $errors->has('password') ? 'error' : '' }}"
                    />
                    @if ($errors->has('password'))
                        <div class="input-error">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        placeholder="Confirm password"
                    />
                </div>

                <!-- Buttons -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-dashboard>
