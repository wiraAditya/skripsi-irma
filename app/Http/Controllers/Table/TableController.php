<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $baseUrl = env('APP_URL') . '?table=';

        if ($request->ajax()) {
            $data = Table::query();

            return DataTables::of($data)
                ->addIndexColumn() // <-- tambahkan ini
                ->editColumn('is_active', function ($row) {
                    return $row->is_active ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('table.qrcode', $row->id) . '" target="_blank" class="btn btn-secondary btn-sm">Show QR</a> ';
                    $btn .= '<button class="btn btn-sm btn-warning" onclick="openEditModal( ' . $row->id . ' )">Edit</button> ';
                    $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action', 'qr_link'])
                ->make(true);
        }

        $totalData = Table::all()->count();

        return view('table.index', compact('totalData', 'baseUrl'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('table.create');
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
                'qr_link' => 'required|string|max:255',
                'status' => 'boolean'
            ]);

            $validated['status'] = $request->has('status');

            Table::create($validated);

            $notification = array(
                'message'    => 'Table added successfully!',
                'alert-type' => 'success'
            );

            DB::commit();

            return redirect()->route('table.index')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            $notification = array(
                'message'    => $e->validator->errors()->first(),
                'alert-type' => 'error'
            );

            return redirect()->route('table.index')->with($notification);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Table::find($id);

        return view('table.detail', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        return response()->json([
            'id' => $table->id,
            'name' => $table->name,
            'qr_link' => $table->qr_link,
            'is_active' => $table->is_active
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'qr_link' => 'required|string|max:255',
                'status' => 'boolean'
            ]);

            $validated['status'] = (bool)$request->status;

            $table->update($validated);

            $notification = array(
                'message'    => 'Table updated successfully!',
                'alert-type' => 'success'
            );

            DB::commit();

            return redirect()->route('table.index')->with($notification);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            $notification = array(
                'message'    => $e->validator->errors()->first(),
                'alert-type' => 'error'
            );

            return redirect()->route('table.index')->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $table = Table::find($id);

        if (!$table) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Table not found.'], 404);
            }
            return redirect()->route('table.index')->with('error', 'Table not found.');
        }

        $table->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Table deleted successfully.']);
        }

        return redirect()->route('table.index')->with('success', 'Table deleted successfully.');
    }

    public function data(Request $request)
    {
        $baseUrl = env('APP_URL') . '?table=';
    }
    public function showQrcode($id)
    {
        $data = Table::findOrFail($id);
        return QrCode::size(300)->generate($data->qr_link);
    }
}
