<?php
// app/Http/Controllers/HasilSurveiController.php
namespace App\Http\Controllers;

use App\Models\HasilSurvei;
use App\Models\Lokasi;
use App\Models\Dokumentasi;
use App\Models\Kriteria;
use App\Models\RankingSaw;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HasilSurveiController extends Controller
{
    public function index(Request $request)
    {
        $query = HasilSurvei::with(['lokasi', 'user']);

        if (Auth::user()->isPetugasSurvei()) {
            $query->where('user_id', Auth::id());
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('lokasi', function ($q) use ($search) {
                $q->where('nama_jalan', 'LIKE', "%{$search}%")
                ->orWhere('distrik', 'LIKE', "%{$search}%");
            });
        }

        // clone query SEBELUM paginate, supaya filter search/role ikut terhitung
        $totalDenganRekomendasi = $query->clone()->whereHas('rekomendasi')->count();

        $hasilSurvei = $query->latest()->paginate(10);

        if ($request->has('search')) {
            $hasilSurvei->appends(['search' => $request->search]);
        }

        return view('backend.survei.index', compact('hasilSurvei', 'totalDenganRekomendasi'));
    }

    public function create()
    {
        $lokasi = Lokasi::all();
        $dokumentasi = Dokumentasi::with('lokasi')->get();
        $kriteria = Kriteria::orderBy('kode_kriteria')->get(); // Ambil semua kriteria
        
        return view('backend.hasil-survei.create', compact('lokasi', 'dokumentasi', 'kriteria'));
    }

   public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lokasi_id' => 'required|exists:lokasi,id',
            'dokumentasi_id' => 'required|exists:dokumentasi,id',
            'tanggal_survei' => 'required|date',
            'panjang_jalan' => 'required|numeric|min:0',
            'lebar_jalan' => 'required|numeric|min:0',
            'tinggi_tiang' => 'required|numeric|min:0',
            // Kriteria C1-C5 (skala 1-5)
            'kepadatan_penduduk' => 'required|integer|min:1|max:5',
            'volume_lalu_lintas' => 'required|integer|min:1|max:5',
            'aktivitas_malam' => 'required|integer|min:1|max:5',
            'penerangan_saat_ini' => 'required|integer|min:1|max:5',
            'kerawanan_kecelakaan' => 'required|integer|min:1|max:5',
        ], [
            'lokasi_id.required' => 'Lokasi wajib dipilih',
            'lokasi_id.exists' => 'Lokasi tidak ditemukan',
            'dokumentasi_id.required' => 'Dokumentasi wajib dipilih',
            'dokumentasi_id.exists' => 'Dokumentasi tidak ditemukan',
            'tanggal_survei.required' => 'Tanggal survei wajib diisi',
            'tanggal_survei.date' => 'Format tanggal tidak valid',
            'panjang_jalan.required' => 'Panjang jalan wajib diisi',
            'panjang_jalan.numeric' => 'Panjang jalan harus berupa angka',
            'panjang_jalan.min' => 'Panjang jalan minimal 0',
            'lebar_jalan.required' => 'Lebar jalan wajib diisi',
            'lebar_jalan.numeric' => 'Lebar jalan harus berupa angka',
            'lebar_jalan.min' => 'Lebar jalan minimal 0',
            'tinggi_tiang.required' => 'Tinggi tiang wajib diisi',
            'tinggi_tiang.numeric' => 'Tinggi tiang harus berupa angka',
            'tinggi_tiang.min' => 'Tinggi tiang minimal 0',
            'kepadatan_penduduk.required' => 'Kepadatan penduduk wajib diisi',
            'kepadatan_penduduk.integer' => 'Kepadatan penduduk harus berupa angka',
            'kepadatan_penduduk.min' => 'Kepadatan penduduk minimal 1',
            'kepadatan_penduduk.max' => 'Kepadatan penduduk maksimal 5',
            'volume_lalu_lintas.required' => 'Volume lalu lintas wajib diisi',
            'volume_lalu_lintas.integer' => 'Volume lalu lintas harus berupa angka',
            'volume_lalu_lintas.min' => 'Volume lalu lintas minimal 1',
            'volume_lalu_lintas.max' => 'Volume lalu lintas maksimal 5',
            'aktivitas_malam.required' => 'Aktivitas malam wajib diisi',
            'aktivitas_malam.integer' => 'Aktivitas malam harus berupa angka',
            'aktivitas_malam.min' => 'Aktivitas malam minimal 1',
            'aktivitas_malam.max' => 'Aktivitas malam maksimal 5',
            'penerangan_saat_ini.required' => 'Penerangan saat ini wajib diisi',
            'penerangan_saat_ini.integer' => 'Penerangan saat ini harus berupa angka',
            'penerangan_saat_ini.min' => 'Penerangan saat ini minimal 1',
            'penerangan_saat_ini.max' => 'Penerangan saat ini maksimal 5',
            'kerawanan_kecelakaan.required' => 'Kerawanan kecelakaan wajib diisi',
            'kerawanan_kecelakaan.integer' => 'Kerawanan kecelakaan harus berupa angka',
            'kerawanan_kecelakaan.min' => 'Kerawanan kecelakaan minimal 1',
            'kerawanan_kecelakaan.max' => 'Kerawanan kecelakaan maksimal 5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $hasilSurvei = HasilSurvei::create([
                'lokasi_id' => $request->lokasi_id,
                'dokumentasi_id' => $request->dokumentasi_id,
                'user_id' => Auth::id(),
                'tanggal_survei' => $request->tanggal_survei,
                'panjang_jalan' => $request->panjang_jalan,
                'lebar_jalan' => $request->lebar_jalan,
                'tinggi_tiang' => $request->tinggi_tiang,
                'kepadatan_penduduk' => $request->kepadatan_penduduk,
                'volume_lalu_lintas' => $request->volume_lalu_lintas,
                'aktivitas_malam' => $request->aktivitas_malam,
                'penerangan_saat_ini' => $request->penerangan_saat_ini,
                'kerawanan_kecelakaan' => $request->kerawanan_kecelakaan,
                'nilai_preferensi' => null, // Akan dihitung nanti
            ]);

            return redirect()->route('hasil-survei.index')
                ->with('success', 'Data survei berhasil disimpan! Silakan lakukan perhitungan SAW.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(HasilSurvei $hasilSurvei)
    {
        $hasilSurvei->load(['lokasi', 'dokumentasi', 'user', 'rankingSaw.kriteria', 'rekomendasi']);
        return view('backend.survei.show', compact('hasilSurvei'));
    }

    public function edit(HasilSurvei $hasilSurvei)
    {
        $lokasi = Lokasi::all();
        $dokumentasi = Dokumentasi::all();
        return view('backend.survei.edit', compact('hasilSurvei', 'lokasi', 'dokumentasi'));
    }

    public function update(Request $request, HasilSurvei $hasilSurvei)
    {
        $validator = Validator::make($request->all(), [
            'lokasi_id' => 'required|exists:lokasi,id',
            'tanggal_survei' => 'required|date',
            'lebar_jalan' => 'required|numeric|min:0',
            'panjang_jalan' => 'required|numeric|min:0',
            'tinggi_tiang' => 'required|numeric|min:0',
            'kepadatan_penduduk' => 'required|integer|min:1|max:5',
            'volume_lalu_lintas' => 'required|integer|min:1|max:5',
            'aktivitas_malam' => 'required|integer|min:1|max:5',
            'penerangan_saat_ini' => 'required|integer|min:1|max:5',
            'kerawanan_kecelakaan' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $hasilSurvei->update([
                'lokasi_id' => $request->lokasi_id,
                'tanggal_survei' => $request->tanggal_survei,
                'lebar_jalan' => $request->lebar_jalan,
                'panjang_jalan' => $request->panjang_jalan,
                'tinggi_tiang' => $request->tinggi_tiang,
                'kepadatan_penduduk' => $request->kepadatan_penduduk,
                'volume_lalu_lintas' => $request->volume_lalu_lintas,
                'aktivitas_malam' => $request->aktivitas_malam,
                'penerangan_saat_ini' => $request->penerangan_saat_ini,
                'kerawanan_kecelakaan' => $request->kerawanan_kecelakaan,
            ]);

            // Reset nilai preferensi karena data berubah
            $hasilSurvei->update([
                'nilai_preferensi' => null,
            ]);

            // Hapus ranking SAW yang lama
            $hasilSurvei->rankingSaw()->delete();

            return redirect()->route('hasil-survei.index')
                ->with('success', 'Data survei berhasil diupdate! Silakan hitung SAW ulang.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(HasilSurvei $hasilSurvei)
    {
        try {
            $hasilSurvei->delete();
            return redirect()->route('hasil-survei.index')
                ->with('success', 'Data survei berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

   public function hitungSAW(HasilSurvei $hasilSurvei)
    {
        try {
            // Ambil semua kriteria dari database
            $kriteria = Kriteria::all();
            
            if ($kriteria->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Kriteria belum ditentukan!');
            }

            // Hapus data ranking SAW sebelumnya
            RankingSaw::where('hasil_survei_id', $hasilSurvei->id)->delete();

            // 1. Normalisasi matriks
            // Ambil semua nilai dari setiap kriteria
            $nilaiKriteria = [];
            foreach ($kriteria as $k) {
                $field = $k->nama_kriteria; // kepadatan_penduduk, volume_lalu_lintas, dll
                $nilaiKriteria[$k->id] = HasilSurvei::pluck($field)->toArray();
            }

            // 2. Hitung normalisasi untuk setiap kriteria
            foreach ($kriteria as $k) {
                $field = $k->nama_kriteria;
                $nilai = $hasilSurvei->{$field};
                $max = max($nilaiKriteria[$k->id] ?? [1]);
                $min = min($nilaiKriteria[$k->id] ?? [0]);
                
                // Normalisasi berdasarkan atribut
                if ($k->atribut == 'benefit') {
                    // Benefit: nilai / max
                    $normalisasi = $max > 0 ? $nilai / $max : 0;
                } else {
                    // Cost: min / nilai
                    $normalisasi = $nilai > 0 ? $min / $nilai : 0;
                }
                
                $nilaiTerbobot = $normalisasi * ($k->bobot / 100);
                
                // Simpan ke ranking_saw
                RankingSaw::create([
                    'hasil_survei_id' => $hasilSurvei->id,
                    'kriteria_id' => $k->id,
                    'nilai_normalisasi' => $normalisasi,
                    'nilai_terbobot' => $nilaiTerbobot,
                ]);
            }

            // 3. Hitung total nilai preferensi
            $totalPreferensi = RankingSaw::where('hasil_survei_id', $hasilSurvei->id)->sum('nilai_terbobot');
            
            // 4. Update nilai preferensi di hasil survei
            $hasilSurvei->update([
                'nilai_preferensi' => $totalPreferensi,
            ]);

            return redirect()->route('survei.show', $hasilSurvei->id)
                ->with('success', 'Perhitungan SAW berhasil dilakukan! Nilai: ' . number_format($totalPreferensi, 4));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat perhitungan SAW: ' . $e->getMessage());
        }
    }

   public function generateRekomendasi(HasilSurvei $hasilSurvei)
    {
        try {
            // Ambil nilai preferensi
            $nilaiPreferensi = $hasilSurvei->nilai_preferensi;
            
            if ($nilaiPreferensi === null) {
                return redirect()->back()
                    ->with('error', 'Silakan hitung SAW terlebih dahulu!');
            }

            // Data untuk perhitungan LPJU
            $panjangJalan = $hasilSurvei->panjang_jalan;
            $tinggiTiang = $hasilSurvei->tinggi_tiang;
            $lebarJalan = $hasilSurvei->lebar_jalan;
            
            // Rumus: Jarak antar lampu = 4 × Tinggi Tiang
            $jarakAntarLampu = 4 * $tinggiTiang;
            
            // Hitung kebutuhan LPJU
            $jumlahLampu = 0;
            if ($jarakAntarLampu > 0) {
                $jumlahLampu = ceil($panjangJalan / $jarakAntarLampu);
            }
            $jumlahLampu = max(1, $jumlahLampu);
            
            // Perkiraan biaya (Rp 5.000.000 per lampu)
            $biayaPerLampu = 5000000;
            $totalBiaya = $jumlahLampu * $biayaPerLampu;

            // Simpan rekomendasi
            $rekomendasi = Rekomendasi::updateOrCreate(
                ['hasil_survei_id' => $hasilSurvei->id],
                [
                    'jumlah_lampu' => $jumlahLampu,
                    'jarak_antar_lampu' => $jarakAntarLampu,
                    'total_biaya' => $totalBiaya,
                    'status' => 'draft',
                ]
            );

            return redirect()->route('survei.show', $hasilSurvei->id)
                ->with('success', 'Rekomendasi berhasil digenerate! 
                    Jarak Lampu: ' . number_format($jarakAntarLampu, 2) . ' meter, 
                    Jumlah Lampu: ' . $jumlahLampu . ' unit');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}