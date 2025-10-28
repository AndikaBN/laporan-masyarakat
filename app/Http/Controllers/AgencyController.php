<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    /**
     * Show list of agencies (for super admin).
     */
    public function index()
    {
        $this->authorize('viewAny', Agency::class);

        $agencies = Agency::with('users', 'categories')->paginate(10);
        return view('agencies.index', compact('agencies'));
    }

    /**
     * Show create agency form.
     */
    public function create()
    {
        $this->authorize('create', Agency::class);
        return view('agencies.create');
    }

    /**
     * Store new agency.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Agency::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies',
            'contact' => 'required|email|unique:agencies',
            'description' => 'nullable|string|max:1000',
        ]);

        $agency = Agency::create($validated);

        return redirect()->route('agencies.show', $agency->id)
            ->with('success', "Agency '{$validated['name']}' berhasil dibuat!");
    }

    /**
     * Show agency details.
     */
    public function show(Agency $agency)
    {
        $this->authorize('view', $agency);
        
        $agency->load('users', 'categories');
        return view('agencies.show', compact('agency'));
    }

    /**
     * Show edit agency form.
     */
    public function edit(Agency $agency)
    {
        $this->authorize('update', $agency);
        return view('agencies.edit', compact('agency'));
    }

    /**
     * Update agency.
     */
    public function update(Request $request, Agency $agency)
    {
        $this->authorize('update', $agency);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies,name,' . $agency->id,
            'contact' => 'required|email|unique:agencies,contact,' . $agency->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $agency->update($validated);

        return redirect()->route('agencies.show', $agency->id)
            ->with('success', "Agency '{$validated['name']}' berhasil diperbarui!");
    }

    /**
     * Delete agency.
     */
    public function destroy(Agency $agency)
    {
        $this->authorize('delete', $agency);

        $name = $agency->name;
        $agency->delete();

        return redirect()->route('agencies.index')
            ->with('success', "Agency '{$name}' berhasil dihapus!");
    }
}
