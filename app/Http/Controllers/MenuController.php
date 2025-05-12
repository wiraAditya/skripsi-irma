<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->latest()->paginate(10);
        return view('menu.index', compact('menus'));
    }

    public function create()
    {
        $kategoris = Kategori::where('is_active', true)->get();
        return view('menu.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_category_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : 0;

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('menu_images', 'public');
        }

        Menu::create($validated);

        return redirect()->route('menu.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $kategoris = Kategori::where('is_active', true)->get();
        return view('menu.edit', compact('menu', 'kategoris'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'menu_category_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        // Check if 'is_active' is set and handle accordingly
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : 0;

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('menu_images', 'public');
        }

        $menu->update($validated);

        return redirect()->route('menu.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }
        
        $menu->delete();
        
        return redirect()->route('menu.index')->with('success', 'Menu deleted successfully.');
    }
}