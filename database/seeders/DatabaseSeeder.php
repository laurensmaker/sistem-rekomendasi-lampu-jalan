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
        // $kriteria = [
        //     [
        //         'nama_kriteria' => 'kepadatan_penduduk',
        //         'bobot' => 25, // 0.25
        //         'atribut' => 'benefit',
        //     ],
        //     [
        //         'nama_kriteria' => 'volume_lalu_lintas',
        //         'bobot' => 20, // 0.20
        //         'atribut' => 'benefit',
        //     ],
        //     [
        //         'nama_kriteria' => 'aktivitas_malam',
        //         'bobot' => 20, // 0.20
        //         'atribut' => 'benefit',
        //     ],
        //     [
        //         'nama_kriteria' => 'penerangan_saat_ini',
        //         'bobot' => 15, // 0.15
        //         'atribut' => 'cost',
        //     ],
        //     [
        //         'nama_kriteria' => 'kerawanan_kecelakaan',
        //         'bobot' => 20, // 0.20
        //         'atribut' => 'benefit',
        //     ],
        // ];

        // foreach ($kriteria as $k) {
        //     Kriteria::create($k);
        // }

        // 3. Buat Data Lokasi di Kota Jayapura, Papua
        $lokasiData = [
            [
                'nama_jalan' => 'Jl. Pasar youtefa Lama',
                'distrik' => 'Abepura',
                'latitude' => -2.6107358610756357,
                'longitude' => 140.68157945285068,
            ],
            [
                'nama_jalan' => 'Jl. Woroth',
                'distrik' => 'Abepura',
                'latitude' => -2.6057512715715814,
                'longitude' => 140.66774766634254,
            ],
            [
                'nama_jalan' => 'Kampung Skouw Yambe',
                'distrik' => 'Skow Yambe',
                'latitude' => -2.614075426356023,
                'longitude' => 140.84454020764468,
            ],
            [
                'nama_jalan' => 'Jl. Melati Lapangan Futsal Barnabas',
                'distrik' => 'Abepura',
                'latitude' => 0.52813425222345,
                'longitude' => 101.43736406632459,
            ],
            [
                'nama_jalan' => 'Jl. Abepura I',
                'distrik' => 'Abepura',
                'latitude' => -2.66615284955562,
                'longitude' => 140.80164289517907,
            ],
            [
                'nama_jalan' => 'Belakang Pasar Cigombong Perum Pemda',
                'distrik' => 'Abepura',
                'latitude' => -2.5986137769291835,
                'longitude' => 140.6723647835384,
            ],
            [
                'nama_jalan' => 'Jl. Yoka',
                'distrik' => 'Heram',
                'latitude' => -2.6057367360882,
                'longitude' =>  140.63048236634256,
            ],
            [
                'nama_jalan' => 'Jl. Nimboran Dok 8',
                'distrik' => 'Jayapura Utara',
                'latitude' => -2.521752283878286,
                'longitude' => 140.728060824014,
            ],
            [
                'nama_jalan' => 'Jl. Tanjung Ria',
                'distrik' => 'Jayapura Utara',
                'latitude' => -2.518545209549884,
                'longitude' => 140.73395187798178,
            ],
            [
                'nama_jalan' => 'Pantai Base G',
                'distrik' => 'Jayapura Utara',
                'latitude' => -2.5166125043643173,
                'longitude' => 140.74002073035837,
            ],
        ];

        foreach ($lokasiData as $lokasi) {
            Lokasi::create($lokasi);
        }

        // 4. Buat Dokumentasi untuk setiap lokasi
        // $dokumentasiList = [];
        // $lokasiIds = Lokasi::pluck('id')->toArray();
        
        // foreach ($lokasiIds as $index => $lokasiId) {
        //     $dokumentasi = Dokumentasi::create([
        //         'lokasi_id' => $lokasiId,
        //         'gambar' => 'dokumentasi/gambar/default_' . ($index + 1) . '.jpg',
        //         'keterangan' => 'Dokumentasi lokasi ' . ($index + 1),
        //         'foto_survei' => 'dokumentasi/foto_survei/survei_' . ($index + 1) . '.jpg',
        //     ]);
        //     $dokumentasiList[] = $dokumentasi;
        // }

        // 5. Buat Data Hasil Survei (10 Alternatif sesuai studi kasus)
        // $petugasId = User::where('role', 'petugas_survei')->first()->id;
        
        // Data dari tabel contoh (10 alternatif)
        // $surveiData = [
        //     [
        //         'lokasi_index' => 0, // Jl. Sam Ratulangi
        //         'kepadatan_penduduk' => 5,
        //         'volume_lalu_lintas' => 5,
        //         'aktivitas_malam' => 5,
        //         'penerangan_saat_ini' => 2,
        //         'kerawanan_kecelakaan' => 4,
        //         'panjang_jalan' => 1500,
        //         'tinggi_tiang' => 8,
        //         'lebar_jalan' => 6,
        //     ],
        //     [
        //         'lokasi_index' => 1, // Jl. Ahmad Yani
        //         'kepadatan_penduduk' => 4,
        //         'volume_lalu_lintas' => 4,
        //         'aktivitas_malam' => 3,
        //         'penerangan_saat_ini' => 2,
        //         'kerawanan_kecelakaan' => 4,
        //         'panjang_jalan' => 1200,
        //         'tinggi_tiang' => 8,
        //         'lebar_jalan' => 6,
        //     ],
        //     [
        //         'lokasi_index' => 2, // Jl. Percetakan
        //         'kepadatan_penduduk' => 2,
        //         'volume_lalu_lintas' => 2,
        //         'aktivitas_malam' => 2,
        //         'penerangan_saat_ini' => 5,
        //         'kerawanan_kecelakaan' => 2,
        //         'panjang_jalan' => 800,
        //         'tinggi_tiang' => 7,
        //         'lebar_jalan' => 5,
        //     ],
        //     [
        //         'lokasi_index' => 3, // Jl. Kesehatan
        //         'kepadatan_penduduk' => 4,
        //         'volume_lalu_lintas' => 4,
        //         'aktivitas_malam' => 4,
        //         'penerangan_saat_ini' => 1,
        //         'kerawanan_kecelakaan' => 3,
        //         'panjang_jalan' => 1000,
        //         'tinggi_tiang' => 8,
        //         'lebar_jalan' => 6,
        //     ],
        //     [
        //         'lokasi_index' => 4, // Jl. Gereja
        //         'kepadatan_penduduk' => 3,
        //         'volume_lalu_lintas' => 3,
        //         'aktivitas_malam' => 2,
        //         'penerangan_saat_ini' => 4,
        //         'kerawanan_kecelakaan' => 3,
        //         'panjang_jalan' => 900,
        //         'tinggi_tiang' => 7,
        //         'lebar_jalan' => 5,
        //     ],
        //     [
        //         'lokasi_index' => 5, // Jl. Kemiri
        //         'kepadatan_penduduk' => 4,
        //         'volume_lalu_lintas' => 3,
        //         'aktivitas_malam' => 4,
        //         'penerangan_saat_ini' => 3,
        //         'kerawanan_kecelakaan' => 4,
        //         'panjang_jalan' => 1100,
        //         'tinggi_tiang' => 8,
        //         'lebar_jalan' => 6,
        //     ],
        //     [
        //         'lokasi_index' => 6, // Jl. Raya Abepura
        //         'kepadatan_penduduk' => 5,
        //         'volume_lalu_lintas' => 5,
        //         'aktivitas_malam' => 4,
        //         'penerangan_saat_ini' => 4,
        //         'kerawanan_kecelakaan' => 5,
        //         'panjang_jalan' => 2000,
        //         'tinggi_tiang' => 9,
        //         'lebar_jalan' => 8,
        //     ],
        //     [
        //         'lokasi_index' => 7, // Jl. Tanjung Ria
        //         'kepadatan_penduduk' => 3,
        //         'volume_lalu_lintas' => 4,
        //         'aktivitas_malam' => 3,
        //         'penerangan_saat_ini' => 2,
        //         'kerawanan_kecelakaan' => 3,
        //         'panjang_jalan' => 800,
        //         'tinggi_tiang' => 7,
        //         'lebar_jalan' => 5,
        //     ],
        //     [
        //         'lokasi_index' => 8, // Jl. Pasifik
        //         'kepadatan_penduduk' => 4,
        //         'volume_lalu_lintas' => 4,
        //         'aktivitas_malam' => 4,
        //         'penerangan_saat_ini' => 4,
        //         'kerawanan_kecelakaan' => 5,
        //         'panjang_jalan' => 1300,
        //         'tinggi_tiang' => 8,
        //         'lebar_jalan' => 6,
        //     ],
        //     [
        //         'lokasi_index' => 9, // Jl. Trans Papua
        //         'kepadatan_penduduk' => 3,
        //         'volume_lalu_lintas' => 3,
        //         'aktivitas_malam' => 5,
        //         'penerangan_saat_ini' => 4,
        //         'kerawanan_kecelakaan' => 4,
        //         'panjang_jalan' => 2500,
        //         'tinggi_tiang' => 9,
        //         'lebar_jalan' => 8,
        //     ],
        // ];

        // $hasilSurveiList = [];
        // foreach ($surveiData as $index => $data) {
        //     $lokasiId = $lokasiIds[$data['lokasi_index']];
        //     $dokumentasiId = $dokumentasiList[$data['lokasi_index']]->id;
            
        //     $hasilSurvei = HasilSurvei::create([
        //         'lokasi_id' => $lokasiId,
        //         'dokumentasi_id' => $dokumentasiId,
        //         'user_id' => $petugasId,
        //         'tanggal_survei' => now()->subDays(rand(1, 30)),
        //         'kepadatan_penduduk' => $data['kepadatan_penduduk'],
        //         'volume_lalu_lintas' => $data['volume_lalu_lintas'],
        //         'aktivitas_malam' => $data['aktivitas_malam'],
        //         'penerangan_saat_ini' => $data['penerangan_saat_ini'],
        //         'kerawanan_kecelakaan' => $data['kerawanan_kecelakaan'],
        //         'panjang_jalan' => $data['panjang_jalan'],
        //         'tinggi_tiang' => $data['tinggi_tiang'],
        //         'lebar_jalan' => $data['lebar_jalan'],
        //         'nilai_preferensi' => null, // Akan dihitung oleh sistem
        //     ]);
            
        //     $hasilSurveiList[] = $hasilSurvei;
        // }

        // // 6. Hitung SAW untuk semua data dan buat ranking & rekomendasi
        // $this->hitungSAWUntukSemua($hasilSurveiList);
    }
}