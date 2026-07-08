<?php
// app/Http/Controllers/RankingSawController.php

namespace App\Http\Controllers;

use App\Models\HasilSurvei;
use App\Models\Kriteria;
use App\Models\RankingSaw;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Validator;

class RankingSawController extends Controller
{
    /**
     * Display a listing of the ranking SAW (Top 3 Prioritas).
     */
    public function index(Request $request)
    {
        // Ambil data dengan nilai preferensi tertinggi (3 data teratas)
        $ranking = HasilSurvei::with(['lokasi', 'user', 'rekomendasi'])
            ->whereNotNull('nilai_preferensi')
            ->orderBy('nilai_preferensi', 'desc')
            ->limit(3)
            ->get();
        
        // Tambahkan ranking
        $ranking->each(function($item, $key) {
            $item->rank = $key + 1;
        });
        
        // Statistik
        $statistik = [
            'total' => HasilSurvei::whereNotNull('nilai_preferensi')->count(),
            'rata_rata' => HasilSurvei::whereNotNull('nilai_preferensi')->avg('nilai_preferensi'),
            'tertinggi' => HasilSurvei::whereNotNull('nilai_preferensi')->max('nilai_preferensi'),
            'terendah' => HasilSurvei::whereNotNull('nilai_preferensi')->min('nilai_preferensi'),
            'sudah_rekomendasi' => HasilSurvei::whereNotNull('nilai_preferensi')
                ->whereHas('rekomendasi')
                ->count(),
            'belum_rekomendasi' => HasilSurvei::whereNotNull('nilai_preferensi')
                ->whereDoesntHave('rekomendasi')
                ->count(),
        ];
        
        // Data untuk filter (opsional)
        $lokasi = Lokasi::all();
        $kriteria = Kriteria::all();
        
        return view('backend.ranking-saw.index', compact('ranking', 'lokasi', 'kriteria', 'statistik'));
    }

    /**
     * Display the specified ranking SAW.
     */
    public function show($id)
    {
        $hasilSurvei = HasilSurvei::with([
            'lokasi', 
            'user', 
            'dokumentasi',
            'rekomendasi',
            'rankingSaw.kriteria'
        ])->findOrFail($id);
        
        // Ambil semua ranking untuk perbandingan
        $allRanking = HasilSurvei::whereNotNull('nilai_preferensi')
            ->orderBy('nilai_preferensi', 'desc')
            ->get(['id', 'nilai_preferensi']);
        
        // Cari posisi ranking
        $position = 1;
        foreach ($allRanking as $index => $item) {
            if ($item->id == $hasilSurvei->id) {
                $position = $index + 1;
                break;
            }
        }
        
        $totalData = $allRanking->count();
        
        return view('backend.ranking-saw.show', compact('hasilSurvei', 'position', 'totalData'));
    }

    /**
     * Recalculate SAW for all data.
     */
    public function recalculateAll()
    {
        try {
            // Ambil semua kriteria
            $kriteria = Kriteria::all();
            
            if ($kriteria->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Kriteria belum ditentukan!');
            }

            // Ambil semua data hasil survei yang memiliki nilai preferensi
            $hasilSurveiList = HasilSurvei::whereNotNull('nilai_preferensi')->get();
            
            if ($hasilSurveiList->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Tidak ada data survei yang perlu dihitung ulang!');
            }

            DB::beginTransaction();
            
            foreach ($hasilSurveiList as $hasilSurvei) {
                // Hapus ranking lama
                RankingSaw::where('hasil_survei_id', $hasilSurvei->id)->delete();
                
                // Ambil semua nilai dari setiap kriteria
                $nilaiKriteria = [];
                foreach ($kriteria as $k) {
                    $field = $k->nama_kriteria;
                    $nilaiKriteria[$k->id] = HasilSurvei::pluck($field)->toArray();
                }
                
                $totalPreferensi = 0;
                
                foreach ($kriteria as $k) {
                    $field = $k->nama_kriteria;
                    $nilai = $hasilSurvei->{$field};
                    $max = max($nilaiKriteria[$k->id] ?? [1]);
                    $min = min($nilaiKriteria[$k->id] ?? [0]);
                    
                    // Normalisasi berdasarkan atribut
                    if ($k->atribut == 'benefit') {
                        $normalisasi = $max > 0 ? $nilai / $max : 0;
                    } else {
                        $normalisasi = $nilai > 0 ? $min / $nilai : 0;
                    }
                    
                    $nilaiTerbobot = $normalisasi * ($k->bobot / 100);
                    $totalPreferensi += $nilaiTerbobot;
                    
                    // Simpan ke ranking_saw
                    RankingSaw::create([
                        'hasil_survei_id' => $hasilSurvei->id,
                        'kriteria_id' => $k->id,
                        'nilai_normalisasi' => $normalisasi,
                        'nilai_terbobot' => $nilaiTerbobot,
                    ]);
                }
                
                // Update nilai preferensi
                $hasilSurvei->update([
                    'nilai_preferensi' => $totalPreferensi,
                ]);
            }
            
            DB::commit();
            
            // Redirect ke halaman ranking-saw dengan pesan sukses
            return redirect()->route('ranking-saw.index')
                ->with('success', 'Perhitungan SAW berhasil dilakukan ulang! Menampilkan 3 prioritas teratas.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat perhitungan ulang SAW: ' . $e->getMessage());
        }
    }

    /**
     * Export ranking SAW to Excel.
     */
    public function exportExcel()
    {
        try {
            $data = HasilSurvei::with(['lokasi', 'user', 'rekomendasi'])
                ->whereNotNull('nilai_preferensi')
                ->orderBy('nilai_preferensi', 'desc')
                ->limit(3)
                ->get();
            
            // Buat array untuk export
            $exportData = [];
            $no = 1;
            
            foreach ($data as $item) {
                $exportData[] = [
                    'Ranking' => $no++,
                    'Lokasi' => $item->lokasi->nama_jalan,
                    'Distrik' => $item->lokasi->distrik,
                    'Surveyor' => $item->user->name,
                    'Tanggal Survei' => $item->tanggal_survei->format('d/m/Y'),
                    'Nilai Preferensi' => number_format($item->nilai_preferensi, 4),
                    'Jumlah Lampu' => $item->rekomendasi ? $item->rekomendasi->jumlah_lampu : '-',
                    'Total Biaya' => $item->rekomendasi ? 'Rp ' . number_format($item->rekomendasi->total_biaya, 0, ',', '.') : '-',
                    'Status' => $item->rekomendasi ? ucfirst($item->rekomendasi->status) : 'Belum',
                ];
            }
            
            // Simpan sebagai session untuk di-download
            session()->put('export_ranking_data', $exportData);
            
            return redirect()->back()
                ->with('success', 'Data 3 prioritas teratas siap diexport.');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export ranking SAW to PDF.
     */
    public function exportPdf()
    {
        try {
            $data = HasilSurvei::with(['lokasi', 'user', 'rekomendasi'])
                ->whereNotNull('nilai_preferensi')
                ->orderBy('nilai_preferensi', 'desc')
                ->limit(3)
                ->get();
            
            $statistik = [
                'total' => HasilSurvei::whereNotNull('nilai_preferensi')->count(),
                'rata_rata' => HasilSurvei::whereNotNull('nilai_preferensi')->avg('nilai_preferensi'),
                'tertinggi' => HasilSurvei::whereNotNull('nilai_preferensi')->max('nilai_preferensi'),
                'terendah' => HasilSurvei::whereNotNull('nilai_preferensi')->min('nilai_preferensi'),
            ];
            
            return view('backend.ranking-saw.export-pdf', compact('data', 'statistik'));
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}