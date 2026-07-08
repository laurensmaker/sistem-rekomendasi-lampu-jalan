<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\HasilSurvei;
use App\Models\Kriteria;
use App\Models\Rekomendasi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $data = [
            'total_lokasi' => Lokasi::count(),
            'total_survei' => HasilSurvei::count(),
            'total_rekomendasi' => Rekomendasi::count(),
            'total_kriteria' => Kriteria::count(),
            'rekomendasi_terbaru' => Rekomendasi::with(['hasilSurvei.lokasi'])
                ->latest()
                ->take(5)
                ->get(),
        ];
        return view('backend.dashboard.admin', $data);
    }

    public function petugas()
    {
        $data = [
            'total_survei' => HasilSurvei::count(),
            'total_lokasi' => Lokasi::count(),
            'survei_terbaru' => HasilSurvei::where('user_id', Auth::id())
                ->with('lokasi')
                ->latest()
                ->take(5)
                ->get(),
        ];
        return view('backend.dashboard.petugas', $data);
    }

    public function staf()
    {
        $data = [
            'total_survei' => HasilSurvei::count(),
            'total_rekomendasi' => Rekomendasi::count(),
            'rekomendasi_draft' => Rekomendasi::where('status', 'draft')->count(),
            'perhitungan_terbaru' => HasilSurvei::whereNotNull('nilai_preferensi')
                ->with('lokasi')
                ->latest()
                ->take(5)
                ->get(),
        ];
        return view('backend.dashboard.staf', $data);
    }

     public function kepala()
    {
        // Ambil data rekomendasi dengan status 'diajukan' (perlu validasi)
        $rekomendasi = Rekomendasi::with(['hasilSurvei.lokasi', 'hasilSurvei.user'])
            ->where('status', 'diajukan')
            ->latest();
        
        $data = [
            'totalRekomendasi' => Rekomendasi::count(),
            'perluValidasi' => Rekomendasi::where('status', 'diajukan')->count(),
            'disetujui' => Rekomendasi::where('status', 'disetujui')->count(),
            'ditolak' => Rekomendasi::where('status', 'ditolak')->count(),
            // Hanya tampilkan yang statusnya 'diajukan'
            'rekomendasiTerbaru' => Rekomendasi::with(['hasilSurvei.lokasi', 'hasilSurvei.user'])
                ->where('status', 'diajukan')
                ->latest()
                ->take(10)
                ->get(),
        ];
        
        return view('backend.dashboard.kepala', $data);
    }
}