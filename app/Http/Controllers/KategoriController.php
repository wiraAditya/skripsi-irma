<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of kategori.
     */
    public function index()
    {
        $kategoris = Kategori::paginate(10);
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new kategori.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created kategori in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        // Set default value for is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }
        $validated['is_active'] = $request->has('is_active') ? (bool) 1 : 0;
        Kategori::create($validated);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified kategori.
     */
    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified kategori in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        // Handle checkbox for is_active
        $validated['is_active'] = $request->has('is_active') ? (bool) 1 : 0;

        $kategori->update($validated);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified kategori from storage.
     */
    public function destroy(Kategori $kategori)
    {
        // Check if this category has any associated menus
        if ($kategori->menus()->count() > 0) {
            // Deactivate instead of delete
            $kategori->update(['is_active' => false]);
            
            return redirect()->route('kategori.index')
                ->with('warning', 'Kategori tidak dapat dihapus karena digunakan pada menu, status diubah menjadi tidak aktif.');
        }
        
        // Safe to delete
        $kategori->delete();

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}