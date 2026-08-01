<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\Lokasi;
use App\Models\Dokumentasi;
use App\Models\HasilSurvei;
use App\Models\RankingSaw;
use App\Models\Rekomendasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User untuk setiap role
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Budi Petugas Survei',
                'email' => 'petugas@mail.com',
                'password' => Hash::make('password'),
                'role' => 'petugas_survei',
            ],
            [
                'name' => 'Dian Staf Perencana',
                'email' => 'staf@mail.com',
                'password' => Hash::make('password'),
                'role' => 'staf_perencana',
            ],
            [
                'name' => 'Kepala Bidang PUPR',
                'email' => 'kepala@mail.com',
                'password' => Hash::make('password'),
                'role' => 'kepala_bidang',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // 2. Buat Kriteria SAW sesuai studi kasus
        $kriteria = [
            [
                'nama_kriteria' => 'kepadatan_penduduk',
                'bobot' => 25, // 0.25
                'atribut' => 'benefit',
            ],
            [
                'nama_kriteria' => 'volume_lalu_lintas',
                'bobot' => 20, // 0.20
                'atribut' => 'benefit',
            ],
            [
                'nama_kriteria' => 'aktivitas_malam',
                'bobot' => 20, // 0.20
                'atribut' => 'benefit',
            ],
            // [
            //     'nama_kriteria' => 'penerangan_saat_ini',
            //     'bobot' => 15, // 0.15
            //     'atribut' => 'cost',
            // ],
            // [
            //     'nama_kriteria' => 'kerawanan_kecelakaan',
            //     'bobot' => 20, // 0.20
            //     'atribut' => 'benefit',
            // ],
        ];

        foreach ($kriteria as $k) {
            Kriteria::create($k);
        }

        // 3. Buat Data Lokasi di Kota Jayapura, Papua
        $lokasiData = [
            [
                'nama_jalan' => 'Jl. Sam Ratulangi',
                'distrik' => 'Jayapura Selatan',
                'latitude' => -2.5333333,
                'longitude' => 140.7000000,
            ],
            [
                'nama_jalan' => 'Jl. Ahmad Yani',
                'distrik' => 'Jayapura Utara',
                'latitude' => -2.5166667,
                'longitude' => 140.7166667,
            ],
            [
                'nama_jalan' => 'Jl. Percetakan',
                'distrik' => 'Jayapura Selatan',
                'latitude' => -2.5416667,
                'longitude' => 140.6916667,
            ],
            [
                'nama_jalan' => 'Jl. Kesehatan',
                'distrik' => 'Jayapura Utara',
                'latitude' => -2.5083333,
                'longitude' => 140.7250000,
            ],
            [
                'nama_jalan' => 'Jl. Gereja',
                'distrik' => 'Jayapura Selatan',
                'latitude' => -2.5500000,
                'longitude' => 140.6833333,
            ],
            [
                'nama_jalan' => 'Jl. Kemiri',
                'distrik' => 'Abepura',
                'latitude' => -2.5833333,
                'longitude' => 140.6333333,
            ],
            [
                'nama_jalan' => 'Jl. Raya Abepura',
                'distrik' => 'Abepura',
                'latitude' => -2.5666667,
                'longitude' => 140.6500000,
            ],
            [
                'nama_jalan' => 'Jl. Tanjung Ria',
                'distrik' => 'Jayapura Utara',
                'latitude' => -2.5000000,
                'longitude' => 140.7333333,
            ],
            [
                'nama_jalan' => 'Jl. Pasifik',
                'distrik' => 'Jayapura Selatan',
                'latitude' => -2.5583333,
                'longitude' => 140.6750000,
            ],
            [
                'nama_jalan' => 'Jl. Trans Papua',
                'distrik' => 'Abepura',
                'latitude' => -2.6000000,
                'longitude' => 140.6166667,
            ],
        ];

        foreach ($lokasiData as $lokasi) {
            Lokasi::create($lokasi);
        }

        // 4. Buat Dokumentasi untuk setiap lokasi
        $dokumentasiList = [];
        $lokasiIds = Lokasi::pluck('id')->toArray();
        
        foreach ($lokasiIds as $index => $lokasiId) {
            $dokumentasi = Dokumentasi::create([
                'lokasi_id' => $lokasiId,
                'gambar' => 'dokumentasi/gambar/default_' . ($index + 1) . '.jpg',
                'keterangan' => 'Dokumentasi lokasi ' . ($index + 1),
                'foto_survei' => 'dokumentasi/foto_survei/survei_' . ($index + 1) . '.jpg',
            ]);
            $dokumentasiList[] = $dokumentasi;
        }

        // 5. Buat Data Hasil Survei (10 Alternatif sesuai studi kasus)
        $petugasId = User::where('role', 'petugas_survei')->first()->id;
        
        // Data dari tabel contoh (10 alternatif)
        $surveiData = [
            [
                'lokasi_index' => 0, // Jl. Sam Ratulangi
                'kepadatan_penduduk' => 5,
                'volume_lalu_lintas' => 5,
                'aktivitas_malam' => 5,
                'penerangan_saat_ini' => 2,
                'kerawanan_kecelakaan' => 4,
                'panjang_jalan' => 1500,
                'tinggi_tiang' => 8,
                'lebar_jalan' => 6,
            ],
            [
                'lokasi_index' => 1, // Jl. Ahmad Yani
                'kepadatan_penduduk' => 4,
                'volume_lalu_lintas' => 4,
                'aktivitas_malam' => 3,
                'penerangan_saat_ini' => 2,
                'kerawanan_kecelakaan' => 4,
                'panjang_jalan' => 1200,
                'tinggi_tiang' => 8,
                'lebar_jalan' => 6,
            ],
            [
                'lokasi_index' => 2, // Jl. Percetakan
                'kepadatan_penduduk' => 2,
                'volume_lalu_lintas' => 2,
                'aktivitas_malam' => 2,
                'penerangan_saat_ini' => 5,
                'kerawanan_kecelakaan' => 2,
                'panjang_jalan' => 800,
                'tinggi_tiang' => 7,
                'lebar_jalan' => 5,
            ],
            [
                'lokasi_index' => 3, // Jl. Kesehatan
                'kepadatan_penduduk' => 4,
                'volume_lalu_lintas' => 4,
                'aktivitas_malam' => 4,
                'penerangan_saat_ini' => 1,
                'kerawanan_kecelakaan' => 3,
                'panjang_jalan' => 1000,
                'tinggi_tiang' => 8,
                'lebar_jalan' => 6,
            ],
            [
                'lokasi_index' => 4, // Jl. Gereja
                'kepadatan_penduduk' => 3,
                'volume_lalu_lintas' => 3,
                'aktivitas_malam' => 2,
                'penerangan_saat_ini' => 4,
                'kerawanan_kecelakaan' => 3,
                'panjang_jalan' => 900,
                'tinggi_tiang' => 7,
                'lebar_jalan' => 5,
            ],
            [
                'lokasi_index' => 5, // Jl. Kemiri
                'kepadatan_penduduk' => 4,
                'volume_lalu_lintas' => 3,
                'aktivitas_malam' => 4,
                'penerangan_saat_ini' => 3,
                'kerawanan_kecelakaan' => 4,
                'panjang_jalan' => 1100,
                'tinggi_tiang' => 8,
                'lebar_jalan' => 6,
            ],
            [
                'lokasi_index' => 6, // Jl. Raya Abepura
                'kepadatan_penduduk' => 5,
                'volume_lalu_lintas' => 5,
                'aktivitas_malam' => 4,
                'penerangan_saat_ini' => 4,
                'kerawanan_kecelakaan' => 5,
                'panjang_jalan' => 2000,
                'tinggi_tiang' => 9,
                'lebar_jalan' => 8,
            ],
            [
                'lokasi_index' => 7, // Jl. Tanjung Ria
                'kepadatan_penduduk' => 3,
                'volume_lalu_lintas' => 4,
                'aktivitas_malam' => 3,
                'penerangan_saat_ini' => 2,
                'kerawanan_kecelakaan' => 3,
                'panjang_jalan' => 800,
                'tinggi_tiang' => 7,
                'lebar_jalan' => 5,
            ],
            [
                'lokasi_index' => 8, // Jl. Pasifik
                'kepadatan_penduduk' => 4,
                'volume_lalu_lintas' => 4,
                'aktivitas_malam' => 4,
                'penerangan_saat_ini' => 4,
                'kerawanan_kecelakaan' => 5,
                'panjang_jalan' => 1300,
                'tinggi_tiang' => 8,
                'lebar_jalan' => 6,
            ],
            [
                'lokasi_index' => 9, // Jl. Trans Papua
                'kepadatan_penduduk' => 3,
                'volume_lalu_lintas' => 3,
                'aktivitas_malam' => 5,
                'penerangan_saat_ini' => 4,
                'kerawanan_kecelakaan' => 4,
                'panjang_jalan' => 2500,
                'tinggi_tiang' => 9,
                'lebar_jalan' => 8,
            ],
        ];

        $hasilSurveiList = [];
        foreach ($surveiData as $index => $data) {
            $lokasiId = $lokasiIds[$data['lokasi_index']];
            $dokumentasiId = $dokumentasiList[$data['lokasi_index']]->id;
            
            $hasilSurvei = HasilSurvei::create([
                'lokasi_id' => $lokasiId,
                'dokumentasi_id' => $dokumentasiId,
                'user_id' => $petugasId,
                'tanggal_survei' => now()->subDays(rand(1, 30)),
                'kepadatan_penduduk' => $data['kepadatan_penduduk'],
                'volume_lalu_lintas' => $data['volume_lalu_lintas'],
                'aktivitas_malam' => $data['aktivitas_malam'],
                'penerangan_saat_ini' => $data['penerangan_saat_ini'],
                'kerawanan_kecelakaan' => $data['kerawanan_kecelakaan'],
                'panjang_jalan' => $data['panjang_jalan'],
                'tinggi_tiang' => $data['tinggi_tiang'],
                'lebar_jalan' => $data['lebar_jalan'],
                'nilai_preferensi' => null, // Akan dihitung oleh sistem
            ]);
            
            $hasilSurveiList[] = $hasilSurvei;
        }

        // 6. Hitung SAW untuk semua data dan buat ranking & rekomendasi
        $this->hitungSAWUntukSemua($hasilSurveiList);
    }

    /**
     * Fungsi untuk menghitung SAW dan membuat rekomendasi
     */
    private function hitungSAWUntukSemua($hasilSurveiList)
    {
        $kriteria = Kriteria::all();
        
        foreach ($hasilSurveiList as $hasilSurvei) {
            // Ambil semua nilai dari setiap kriteria
            $nilaiKriteria = [];
            foreach ($kriteria as $k) {
                $field = $k->nama_kriteria;
                $nilaiKriteria[$k->id] = HasilSurvei::pluck($field)->toArray();
            }

            // Hitung normalisasi dan nilai terbobot
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

            // Generate rekomendasi
            $panjangJalan = $hasilSurvei->panjang_jalan;
            $tinggiTiang = $hasilSurvei->tinggi_tiang;
            
            // Jarak antar lampu = 4 × Tinggi Tiang
            $jarakAntarLampu = 4 * $tinggiTiang;
            
            // Hitung kebutuhan LPJU
            $jumlahLampu = 0;
            if ($jarakAntarLampu > 0) {
                $jumlahLampu = ceil($panjangJalan / $jarakAntarLampu);
            }
            $jumlahLampu = max(1, $jumlahLampu);
            
            // Biaya per lampu (Rp 5.000.000)
            $biayaPerLampu = 5000000;
            $totalBiaya = $jumlahLampu * $biayaPerLampu;

            // Simpan rekomendasi
            Rekomendasi::create([
                'hasil_survei_id' => $hasilSurvei->id,
                'jumlah_lampu' => $jumlahLampu,
                'jarak_antar_lampu' => $jarakAntarLampu,
                'total_biaya' => $totalBiaya,
                'status' => 'draft',
                'catatan' => null,
                'tanggal_disetujui' => null,
            ]);
        }
    }
}