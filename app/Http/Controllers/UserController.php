<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show list of users (for super admin).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();

        // Search by name
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by role
        if ($request->filled('role') && $request->get('role') !== 'all') {
            $role = $request->get('role');
            $query->where('role', $role);
        }

        $users = $query->paginate(10);
        $selectedRole = $request->get('role', 'all');
        $searchQuery = $request->get('search', '');

        return view('users.index', compact('users', 'selectedRole', 'searchQuery'));
    }

    /**
     * Show create user form.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = ['agency_admin', 'user'];
        return view('users.create', compact('roles'));
    }

    /**
     * Store new user.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:agency_admin,user',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', "User '{$validated['name']}' berhasil dibuat!");
    }

    /**
     * Show edit user form.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = ['agency_admin', 'user'];
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:agency_admin,user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', "User '{$validated['name']}' berhasil diperbarui!");
    }

    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User '{$name}' berhasil dihapus!");
    }
}
