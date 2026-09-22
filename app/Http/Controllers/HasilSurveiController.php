<?php
// app/Http/Controllers/HasilSurveiController.php
namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\HasilSurvei;
use App\Models\HasilSurveiDetail;
use App\Models\Kriteria;
use App\Models\Lokasi;
use App\Models\RankingSaw;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $kriteria = Kriteria::orderBy('nama_kriteria')->get(); // Ambil semua kriteria
        
        return view('backend.survei.create', compact('lokasi', 'dokumentasi', 'kriteria'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lokasi_id'       => 'required|exists:lokasi,id',
            'dokumentasi_id'  => 'required|exists:dokumentasi,id',
            'tanggal_survei'  => 'required|date',
            'panjang_jalan'   => 'required|numeric|min:0',
            'lebar_jalan'     => 'required|numeric|min:0',
            'tinggi_tiang'    => 'required|numeric|min:0',
            'kriteria'        => 'required|array',
        ]);

        $kriteriaList = \App\Models\Kriteria::all();
        foreach ($kriteriaList as $k) {
            $validator->addRules([
                'kriteria.' . $k->id => 'required|integer|min:1|max:5',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $hasilSurvei = HasilSurvei::create([
                'lokasi_id'        => $request->lokasi_id,
                'dokumentasi_id'   => $request->dokumentasi_id,
                'user_id'          => Auth::id(),
                'tanggal_survei'   => $request->tanggal_survei,
                'panjang_jalan'    => $request->panjang_jalan,
                'lebar_jalan'      => $request->lebar_jalan,
                'tinggi_tiang'     => $request->tinggi_tiang,
                'nilai_preferensi' => null,
            ]);

            foreach ($request->kriteria as $kriteriaId => $nilai) {
                HasilSurveiDetail::create([
                    'hasil_survei_id' => $hasilSurvei->id,
                    'kriteria_id'     => $kriteriaId,
                    'nilai'           => (int) $nilai,
                ]);
            }

            DB::commit();

            return redirect()->route('hasil-survei.index')
                ->with('success', 'Data survei berhasil disimpan! Silakan lakukan perhitungan SAW.');

        } catch (\Exception $e) {
            DB::rollBack();
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
            $kriteria = Kriteria::all();

            if ($kriteria->isEmpty()) {
                return redirect()->back()->with('error', 'Kriteria belum ditentukan!');
            }

            // Pastikan semua nilai kriteria untuk survei ini sudah ada
            $hasilSurvei->load('details');

            if ($hasilSurvei->details->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Data nilai kriteria belum diinput untuk survei ini.');
            }

            // Hapus ranking SAW lama
            RankingSaw::where('hasil_survei_id', $hasilSurvei->id)->delete();

            // 1. Ambil semua nilai dari tabel detail per kriteria (untuk cari max & min)
            $nilaiKriteria = [];
            foreach ($kriteria as $k) {
                $nilaiKriteria[$k->id] = HasilSurveiDetail::where('kriteria_id', $k->id)
                    ->pluck('nilai')
                    ->filter()
                    ->toArray();
            }

            // 2. Hitung normalisasi untuk setiap kriteria
            foreach ($kriteria as $k) {
                $nilai = $hasilSurvei->getNilaiKriteria($k->id);

                if ($nilai === null) {
                    return redirect()->back()
                        ->with('error', "Nilai kriteria '{$k->nama_kriteria}' belum diisi.");
                }

                $arrNilai = $nilaiKriteria[$k->id] ?? [];
                $max = !empty($arrNilai) ? max($arrNilai) : 0;
                $min = !empty($arrNilai) ? min($arrNilai) : 0;

                if ($k->atribut === 'benefit') {
                    $normalisasi = $max > 0 ? $nilai / $max : 0;
                } else { // cost
                    $normalisasi = $nilai > 0 ? $min / $nilai : 0;
                }

                $nilaiTerbobot = $normalisasi * ($k->bobot / 100);

                RankingSaw::create([
                    'hasil_survei_id'   => $hasilSurvei->id,
                    'kriteria_id'       => $k->id,
                    'nilai_normalisasi' => $normalisasi,
                    'nilai_terbobot'    => $nilaiTerbobot,
                ]);
            }

            // 3. Total preferensi
            $totalPreferensi = RankingSaw::where('hasil_survei_id', $hasilSurvei->id)
                ->sum('nilai_terbobot');

            $hasilSurvei->update(['nilai_preferensi' => $totalPreferensi]);

            return redirect()->route('hasil-survei.show', $hasilSurvei->id)
                ->with('success', 'Perhitungan SAW berhasil! Nilai: ' . number_format($totalPreferensi, 4));

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

            return redirect()->route('rekomendasi.index', $hasilSurvei->id)
                ->with('success', 'Rekomendasi berhasil digenerate! 
                    Jarak Lampu: ' . number_format($jarakAntarLampu, 2) . ' meter, 
                    Jumlah Lampu: ' . $jumlahLampu . ' unit');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}