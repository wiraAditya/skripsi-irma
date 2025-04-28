<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = MenuCategory::all();

        if ($request->ajax()) {

            $data = Menu::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('menu_category', function ($row) {
                    return $row->menuCategory->name;
                })
                ->addColumn('image', function ($row) {
                    return '<img src="' . asset('/storage/menu_images/' . $row->image) . '" alt="Image" style="width: 150px; height: 150px;">';
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active ? 'Active' : 'Inactive';
                })
                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('menu.show', $row->id) . '" class="edit btn btn-primary btn-sm">View</a> ';
                    $btn .= '<button class="btn btn-sm btn-warning" onclick="openEditModal( ' . $row->id . ' )">
                                Edit
                            </button> ';
                    $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        $totalData = Menu::all()->count();

        return view('menu.index', compact('categories', 'totalData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'menu_category_id' => 'required|exists:menu_categories,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:65535',
                'price' => 'required|integer|min:1',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_active' => 'boolean'
            ]);

            if ($request->hasFile('image')) {
                $imageName = $request->file('image')->getClientOriginalName();
                $validated['image'] = $imageName;
                $request->image->move(public_path('/storage/menu_images'), $imageName);
            }

            $validated['is_active'] = $request->has('is_active');

            Menu::create($validated);

            $notification = array(
                'message'    => 'Menu added successfully!',
                'alert-type' => 'success'
            );

            DB::commit();

            return redirect()->route('menu.index')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            $notification = array(
                'message'    => $e->validator->errors()->first(),
                'alert-type' => 'error'
            );

            return redirect()->route('menu.index')->with($notification);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $menu = Menu::find($id);
        $categories = MenuCategory::all();

        return view('menu.detail', compact('menu', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        return response()->json([
            'id' => $menu->id,
            'menu_category_id' => $menu->menu_category_id,
            'name' => $menu->name,
            'description' => $menu->description,
            'price' => $menu->price,
            'is_active' => $menu->is_active,
            'image' => $menu->image
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'menu_category_id' => 'required|exists:menu_categories,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:65535',
                'price' => 'required|integer|min:1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_active' => 'nullable|boolean'
            ]);

            if ($request->hasFile('image')) {
                if (File::exists(public_path('/storage/menu_images/' . $menu->image . ''))) {
                    File::delete(public_path('/storage/menu_images/' . $menu->image . ''));
                }

                $imageName = $request->image->getClientOriginalName();
                $request->image->move(public_path('/storage/menu_images'), $imageName);
                $validated['image'] = $imageName;
            }

            $validated['status'] = (bool)$request->status;

            $menu->update($validated);

            $notification = array(
                'message'    => 'Menu updated successfully!',
                'alert-type' => 'success'
            );

            DB::commit();

            return redirect()->route('menu.index')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            $notification = array(
                'message'    => $e->validator->errors()->first(),
                'alert-type' => 'error'
            );

            return redirect()->route('menu.index')->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Menu not found.'], 404);
            }
            return redirect()->route('menu.index')->with('error', 'Menu not found.');
        }

        $menu->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Menu deleted successfully.']);
        }

        return redirect()->route('menu.index')->with('success', 'Menu deleted successfully.');
    }
}
