<?php
// app/Http/Controllers/DokumentasiController.php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DokumentasiController extends Controller
{
    /**
     * Display a listing of the dokumentasi.
     */
    public function index(Request $request)
    {
        $query = Dokumentasi::with(['lokasi', 'hasilSurvei']);
        
        // Filter berdasarkan lokasi
        if ($request->has('lokasi_id') && $request->lokasi_id != '') {
            $query->where('lokasi_id', $request->lokasi_id);
        }
        
        // Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('keterangan', 'LIKE', "%{$search}%")
                  ->orWhereHas('lokasi', function($q2) use ($search) {
                      $q2->where('nama_jalan', 'LIKE', "%{$search}%")
                         ->orWhere('distrik', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $dokumentasi = $query->latest()->paginate(10);
        $lokasi = Lokasi::all();
        
        if ($request->has('search') || $request->has('lokasi_id')) {
            $dokumentasi->appends($request->all());
        }
        
        return view('backend.dokumentasi.index', compact('dokumentasi', 'lokasi'));
    }

    /**
     * Show the form for creating a new dokumentasi.
     */
    public function create()
    {
        $lokasi = Lokasi::all();
        return view('backend.dokumentasi.create', compact('lokasi'));
    }

    /**
     * Store a newly created dokumentasi in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lokasi_id' => 'required|exists:lokasi,id',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // Max 5MB
            'keterangan' => 'required|string|max:500',
            'foto_survei' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ], [
            'lokasi_id.required' => 'Lokasi harus dipilih',
            'gambar.required' => 'Gambar wajib diupload',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 5MB',
            'keterangan.required' => 'Keterangan wajib diisi',
            'foto_survei.image' => 'File harus berupa gambar',
            'foto_survei.max' => 'Ukuran foto survei maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Upload gambar utama
            $gambarPath = $request->file('gambar')->store('dokumentasi/gambar', 'public');
            
            // Upload foto survei jika ada
            $fotoSurveiPath = null;
            if ($request->hasFile('foto_survei')) {
                $fotoSurveiPath = $request->file('foto_survei')->store('dokumentasi/foto_survei', 'public');
            }

            $dokumentasi = Dokumentasi::create([
                'lokasi_id' => $request->lokasi_id,
                'gambar' => $gambarPath,
                'keterangan' => $request->keterangan,
                'foto_survei' => $fotoSurveiPath,
            ]);

            return redirect()->route('dokumentasi.index')
                ->with('success', 'Dokumentasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified dokumentasi.
     */
    public function show(Dokumentasi $dokumentasi)
    {
        $dokumentasi->load(['lokasi', 'hasilSurvei']);
        return view('backend.dokumentasi.show', compact('dokumentasi'));
    }

    /**
     * Show the form for editing the specified dokumentasi.
     */
    public function edit(Dokumentasi $dokumentasi)
    {
        $lokasi = Lokasi::all();
        return view('backend.dokumentasi.edit', compact('dokumentasi', 'lokasi'));
    }

    /**
     * Update the specified dokumentasi in storage.
     */
    public function update(Request $request, Dokumentasi $dokumentasi)
    {
        $validator = Validator::make($request->all(), [
            'lokasi_id' => 'required|exists:lokasi,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'keterangan' => 'required|string|max:500',
            'foto_survei' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ], [
            'lokasi_id.required' => 'Lokasi harus dipilih',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 5MB',
            'keterangan.required' => 'Keterangan wajib diisi',
            'foto_survei.image' => 'File harus berupa gambar',
            'foto_survei.max' => 'Ukuran foto survei maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'lokasi_id' => $request->lokasi_id,
                'keterangan' => $request->keterangan,
            ];

            // Update gambar utama jika ada
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                if ($dokumentasi->gambar && Storage::disk('public')->exists($dokumentasi->gambar)) {
                    Storage::disk('public')->delete($dokumentasi->gambar);
                }
                $data['gambar'] = $request->file('gambar')->store('dokumentasi/gambar', 'public');
            }

            // Update foto survei jika ada
            if ($request->hasFile('foto_survei')) {
                // Hapus foto lama
                if ($dokumentasi->foto_survei && Storage::disk('public')->exists($dokumentasi->foto_survei)) {
                    Storage::disk('public')->delete($dokumentasi->foto_survei);
                }
                $data['foto_survei'] = $request->file('foto_survei')->store('dokumentasi/foto_survei', 'public');
            }

            $dokumentasi->update($data);

            return redirect()->route('dokumentasi.index')
                ->with('success', 'Dokumentasi berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified dokumentasi from storage.
     */
    public function destroy(Dokumentasi $dokumentasi)
    {
        try {
            // Hapus file gambar
            if ($dokumentasi->gambar && Storage::disk('public')->exists($dokumentasi->gambar)) {
                Storage::disk('public')->delete($dokumentasi->gambar);
            }
            if ($dokumentasi->foto_survei && Storage::disk('public')->exists($dokumentasi->foto_survei)) {
                Storage::disk('public')->delete($dokumentasi->foto_survei);
            }

            $dokumentasi->delete();

            return redirect()->route('dokumentasi.index')
                ->with('success', 'Dokumentasi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete dokumentasi
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:dokumentasi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            $dokumentasi = Dokumentasi::whereIn('id', $request->ids)->get();
            
            // Hapus semua file
            foreach ($dokumentasi as $item) {
                if ($item->gambar && Storage::disk('public')->exists($item->gambar)) {
                    Storage::disk('public')->delete($item->gambar);
                }
                if ($item->foto_survei && Storage::disk('public')->exists($item->foto_survei)) {
                    Storage::disk('public')->delete($item->foto_survei);
                }
            }
            
            Dokumentasi::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' dokumentasi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}