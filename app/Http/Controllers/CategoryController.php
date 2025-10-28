<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Agency;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Show list of categories (for super admin).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        $query = Category::with('agency');

        // Filter by agency
        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->get('agency_id'));
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->paginate(10);
        $agencies = Agency::all();
        $selectedAgency = $request->get('agency_id', '');
        $searchQuery = $request->get('search', '');

        return view('categories.index', compact('categories', 'agencies', 'selectedAgency', 'searchQuery'));
    }

    /**
     * Show create category form.
     */
    public function create()
    {
        $this->authorize('create', Category::class);
        $agencies = Agency::all();
        return view('categories.create', compact('agencies'));
    }

    /**
     * Store new category.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'agency_id' => 'required|exists:agencies,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $category = Category::create($validated);

        return redirect()->route('categories.show', $category->id)
            ->with('success', "Kategori '{$validated['name']}' berhasil dibuat untuk agency '{$category->agency->name}'!");
    }

    /**
     * Show category details.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);
        $category->load('agency');
        return view('categories.show', compact('category'));
    }

    /**
     * Show edit category form.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        $agencies = Agency::all();
        return view('categories.edit', compact('category', 'agencies'));
    }

    /**
     * Update category.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'agency_id' => 'required|exists:agencies,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($validated);

        return redirect()->route('categories.show', $category->id)
            ->with('success', "Kategori '{$validated['name']}' berhasil diperbarui!");
    }

    /**
     * Delete category.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $name = $category->name;
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', "Kategori '{$name}' berhasil dihapus!");
    }
}
