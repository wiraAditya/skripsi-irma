<?php

namespace App\Http\Controllers\MenuCategory;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = MenuCategory::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('menu-category.show', $row->id) . '" class="edit btn btn-primary btn-sm">View</a> ';
                    $btn .= '<button class="btn btn-sm btn-warning" onclick="openEditModal( ' . $row->id . ' )">
                                Edit
                            </button> ';
                    $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $totalData = MenuCategory::all()->count();

        return view('menu-category.index', compact('totalData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menu-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            MenuCategory::create($validated);

            $notification = array(
                'message'    => 'Menu category added successfully!',
                'alert-type' => 'success'
            );

            DB::commit();

            return redirect()->route('menu-category.index')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            $notification = array(
                'message'    => $e->validator->errors()->first(),
                'alert-type' => 'error'
            );

            return redirect()->route('menu-category.index')->with($notification);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = MenuCategory::find($id);

        return view('menu-category.detail', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuCategory $menuCategory)
    {
        return response()->json([
            'id' => $menuCategory->id,
            'name' => $menuCategory->name,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuCategory $menuCategory)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $menuCategory->update($validated);

            $notification = array(
                'message'    => 'Menu category updated successfully!',
                'alert-type' => 'success'
            );

            DB::commit();

            return redirect()->route('menu-category.index')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            $notification = array(
                'message'    => $e->validator->errors()->first(),
                'alert-type' => 'error'
            );

            return redirect()->route('menu-category.index')->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menuCategory = MenuCategory::find($id);

        if (!$menuCategory) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Menu category not found.'], 404);
            }
            return redirect()->route('menu-category.index')->with('error', 'Menu category not found.');
        }

        $menuCategory->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Menu category deleted successfully.']);
        }

        return redirect()->route('menu-category.index')->with('success', 'Menu category deleted successfully.');
    }
}
