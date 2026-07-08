<?php
// app/Http/Controllers/LokasiController.php
namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lokasi::query();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_jalan', 'LIKE', "%{$search}%")
                  ->orWhere('distrik', 'LIKE', "%{$search}%");
            });
        }
        
        $lokasi = $query->latest()->paginate(10);
        
        if ($request->has('search')) {
            $lokasi->appends(['search' => $request->search]);
        }
        
        return view('backend.lokasi.index', compact('lokasi'));
    }

    public function create()
    {
        return view('backend.lokasi.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jalan' => 'required|string|max:255',
            'distrik' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Lokasi::create([
                'nama_jalan' => $request->nama_jalan,
                'distrik' => $request->distrik,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return redirect()->route('lokasi.index')
                ->with('success', 'Lokasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Lokasi $lokasi)
    {
        return view('backend.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $validator = Validator::make($request->all(), [
            'nama_jalan' => 'required|string|max:255',
            'distrik' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $lokasi->update([
                'nama_jalan' => $request->nama_jalan,
                'distrik' => $request->distrik,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return redirect()->route('lokasi.index')
                ->with('success', 'Lokasi berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Lokasi $lokasi)
    {
        try {
            $lokasi->delete();
            return redirect()->route('lokasi.index')
                ->with('success', 'Lokasi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:lokasi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            Lokasi::whereIn('id', $request->ids)->delete();
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' lokasi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}