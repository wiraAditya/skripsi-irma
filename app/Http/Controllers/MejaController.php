<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MejaController extends Controller
{
    /**
     * Display a listing of meja.
     */
    public function index()
    {
        $mejas = Meja::paginate(10);
        return view('meja.index', compact('mejas'));
    }

    /**
     * Show the form for creating a new meja.
     */
    public function create()
    {
        return view('meja.create');
    }

    /**
     * Store a newly created meja in storage.
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

        do {
            $code = Str::upper(Str::random(8));
        } while (Meja::where('unique_code', $code)->exists());

        $validated['unique_code'] = $code;

        Meja::create($validated);

        return redirect()->route('meja.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified meja.
     */
    public function edit(Meja $meja)
    {
        return view('meja.edit', compact('meja'));
    }

    /**
     * Update the specified meja in storage.
     */
    public function update(Request $request, Meja $meja)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        // Handle checkbox for is_active
        $validated['is_active'] = $request->has('is_active') ? (bool) 1 : 0;

        $meja->update($validated);

        return redirect()->route('meja.index')
            ->with('success', 'Meja berhasil diperbarui.');
    }

    /**
     * Remove the specified meja from storage.
     */
    public function destroy(Meja $meja)
    {
        // todo: add checker to the order table
        $meja->delete();

        return redirect()->route('meja.index')
            ->with('success', 'Meja berhasil dihapus.');
    }

    public function showQrcode($id)
    {
        $meja = Meja::findOrFail($id);

        $url = config('app.url') . '/?mejaId=' . $meja->unique_code;
        $size = request()->integer('size', 300);

        $qrCode = QrCode::size($size)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($url);

        return view('meja.print', [
            'qrCode' => $qrCode,
            'meja' => $meja,
            'autoPrint' => request()->has('autoprint'),
            'printTitle' => "QR Code Meja {$meja->nomor}"
        ]);
    }
}
