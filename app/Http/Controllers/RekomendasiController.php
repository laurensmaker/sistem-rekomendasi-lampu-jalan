<?php
// app/Http/Controllers/RekomendasiController.php
namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Models\HasilSurvei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Rekomendasi::with(['hasilSurvei.lokasi', 'hasilSurvei.user']);
        
        if (Auth::user()->isStafPerencana()) {
            $query->where('status', 'draft');
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('hasilSurvei.lokasi', function($q) use ($search) {
                $q->where('nama_jalan', 'LIKE', "%{$search}%")
                  ->orWhere('distrik', 'LIKE', "%{$search}%");
            });
        }
        
        $rekomendasi = $query->latest()->paginate(10);
        
        return view('backend.rekomendasi.index', compact('rekomendasi'));
    }

    public function show(Rekomendasi $rekomendasi)
    {
        $rekomendasi->load(['hasilSurvei.lokasi', 'hasilSurvei.dokumentasi', 'hasilSurvei.user']);
        return view('backend.rekomendasi.show', compact('rekomendasi'));
    }

    public function ajukan(Rekomendasi $rekomendasi)
    {
        try {
            $rekomendasi->update([
                'status' => 'diajukan'
            ]);
            
            return redirect()->back()
                ->with('success', 'Rekomendasi berhasil diajukan untuk validasi!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function validasi(Request $request, Rekomendasi $rekomendasi)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            $rekomendasi->update([
                'status' => $request->status,
                'catatan' => $request->catatan,
                'tanggal_disetujui' => $request->status == 'disetujui' ? now() : null,
            ]);
            
            $message = $request->status == 'disetujui' 
                ? 'Rekomendasi berhasil disetujui!' 
                : 'Rekomendasi ditolak!';
            
            return redirect()->back()
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetak(Rekomendasi $rekomendasi)
    {
        $rekomendasi->load(['hasilSurvei.lokasi', 'hasilSurvei.user']);
        return view('backend.rekomendasi.cetak', compact('rekomendasi'));
    }

    public function exportExcel()
    {
        // Implementasi export Excel
        return redirect()->back()->with('info', 'Fitur export dalam pengembangan');
    }
}