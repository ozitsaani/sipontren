<?php

namespace Database\Seeders;

use App\Models\KelasMadrasah;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DummySantriWaliSeeder extends Seeder
{
    public function run(): void
    {
        $dataSantri = [
            1 => [
                ['nis' => '202600101', 'nama' => 'Ahmad Zaki Al-Farizi', 'asrama' => 'Darul Wiqor', 'kamar' => '101', 'wali' => 'Bpk. Hasan Basri'],
                ['nis' => '202600102', 'nama' => 'Muhammad Fadli Ramadhan', 'asrama' => 'Darul Wiqor', 'kamar' => '102', 'wali' => 'Bpk. Hendra Wijaya'],
                ['nis' => '202600103', 'nama' => 'Rizky Maulana Yusuf', 'asrama' => 'Darul Muttaqin', 'kamar' => '103', 'wali' => 'Bpk. Agus Salim'],
                ['nis' => '202600104', 'nama' => 'Fauzan Akbar Pratama', 'asrama' => 'Darul Muttaqin', 'kamar' => '104', 'wali' => 'Bpk. Dedi Kurniawan'],
                ['nis' => '202600105', 'nama' => 'Ilham Nur Hidayat', 'asrama' => 'Darul Hikmah', 'kamar' => '105', 'wali' => 'Bpk. Wahyu Saputra'],
            ],
            2 => [
                ['nis' => '202600201', 'nama' => 'Budi Santoso', 'asrama' => 'Darul Hikmah', 'kamar' => '201', 'wali' => 'Bpk. Joko Widodo'],
                ['nis' => '202600202', 'nama' => 'Rafi Al-Ghazali', 'asrama' => 'Darul Qur’an', 'kamar' => '202', 'wali' => 'Bpk. Ahmad Sulaiman'],
                ['nis' => '202600203', 'nama' => 'Dimas Arya Nugraha', 'asrama' => 'Darul Qur’an', 'kamar' => '203', 'wali' => 'Bpk. Bambang Riyanto'],
                ['nis' => '202600204', 'nama' => 'Naufal Rizki Ananda', 'asrama' => 'Darul Amanah', 'kamar' => '204', 'wali' => 'Bpk. Yusuf Hidayat'],
                ['nis' => '202600205', 'nama' => 'Farhan Maulana Hakim', 'asrama' => 'Darul Amanah', 'kamar' => '205', 'wali' => 'Bpk. Taufik Rahman'],
            ],
            3 => [
                ['nis' => '202600301', 'nama' => 'Fakhrur Rozi Tsani', 'asrama' => 'Darul Wiqor', 'kamar' => '301', 'wali' => 'Bpk. Rahmat Fauzi'],
                ['nis' => '202600302', 'nama' => 'Zaid Fikri Anwar', 'asrama' => 'Darul Muttaqin', 'kamar' => '302', 'wali' => 'Bpk. Abdullah Karim'],
                ['nis' => '202600303', 'nama' => 'Raka Putra Mahendra', 'asrama' => 'Darul Hikmah', 'kamar' => '303', 'wali' => 'Bpk. Irfan Maulana'],
                ['nis' => '202600304', 'nama' => 'M. Syafiq Al-Baihaqi', 'asrama' => 'Darul Qur’an', 'kamar' => '304', 'wali' => 'Bpk. Saiful Bahri'],
                ['nis' => '202600305', 'nama' => 'Hilmi Abdillah Putra', 'asrama' => 'Darul Amanah', 'kamar' => '305', 'wali' => 'Bpk. Rudi Hartono'],
            ],
            4 => [
                ['nis' => '202600401', 'nama' => 'Aldi Prasetyo Nugroho', 'asrama' => 'Darul Wiqor', 'kamar' => '401', 'wali' => 'Bpk. Eko Prasetyo'],
                ['nis' => '202600402', 'nama' => 'Riyan Hidayatullah', 'asrama' => 'Darul Muttaqin', 'kamar' => '402', 'wali' => 'Bpk. Nanang Sutrisno'],
                ['nis' => '202600403', 'nama' => 'Bagas Saputra Rahman', 'asrama' => 'Darul Hikmah', 'kamar' => '403', 'wali' => 'Bpk. Umar Faruq'],
                ['nis' => '202600404', 'nama' => 'Nabil Aufa Syahputra', 'asrama' => 'Darul Qur’an', 'kamar' => '404', 'wali' => 'Bpk. Slamet Riyadi'],
                ['nis' => '202600405', 'nama' => 'M. Haikal Ramadhan', 'asrama' => 'Darul Amanah', 'kamar' => '405', 'wali' => 'Bpk. Ridwan Hakim'],
            ],
            5 => [
                ['nis' => '202600501', 'nama' => 'Arkan Maulana Ibrahim', 'asrama' => 'Darul Wiqor', 'kamar' => '501', 'wali' => 'Bpk. Fajar Nugroho'],
                ['nis' => '202600502', 'nama' => 'Reza Fahlevi Putra', 'asrama' => 'Darul Muttaqin', 'kamar' => '502', 'wali' => 'Bpk. Maman Suherman'],
                ['nis' => '202600503', 'nama' => 'Iqbal Firmansyah', 'asrama' => 'Darul Hikmah', 'kamar' => '503', 'wali' => 'Bpk. Heri Setiawan'],
                ['nis' => '202600504', 'nama' => 'M. Irsyad Al-Fatih', 'asrama' => 'Darul Qur’an', 'kamar' => '504', 'wali' => 'Bpk. Asep Saepudin'],
                ['nis' => '202600505', 'nama' => 'Rangga Aditya Saputra', 'asrama' => 'Darul Amanah', 'kamar' => '505', 'wali' => 'Bpk. Sutanto Wijaya'],
            ],
            6 => [
                ['nis' => '202600601', 'nama' => 'Rafiq Akmal Ramadhan', 'asrama' => 'Darul Wiqor', 'kamar' => '601', 'wali' => 'Bpk. Mahmud Yunus'],
                ['nis' => '202600602', 'nama' => 'M. Salman Al-Hakim', 'asrama' => 'Darul Muttaqin', 'kamar' => '602', 'wali' => 'Bpk. Lukman Hakim'],
                ['nis' => '202600603', 'nama' => 'Wildan Nur Fauzi', 'asrama' => 'Darul Hikmah', 'kamar' => '603', 'wali' => 'Bpk. Suryadi Putra'],
                ['nis' => '202600604', 'nama' => 'Aditya Hafizh Pratama', 'asrama' => 'Darul Qur’an', 'kamar' => '604', 'wali' => 'Bpk. Arif Hidayat'],
                ['nis' => '202600605', 'nama' => 'M. Faris Al-Amin', 'asrama' => 'Darul Amanah', 'kamar' => '605', 'wali' => 'Bpk. Dani Firmansyah'],
            ],
        ];

        foreach ($dataSantri as $nomorKelas => $santriList) {
            $kelas = KelasMadrasah::updateOrCreate(
                [
                    'nama_kelas' => 'Kelas ' . $nomorKelas . ' MKHS',
                ],
                [
                    'keterangan' => 'Kelas ' . $nomorKelas . ' Madrasah Kyai Haji Sanusi',
                ]
            );

            foreach ($santriList as $index => $item) {
                $santri = Santri::updateOrCreate(
                    [
                        'nis' => $item['nis'],
                    ],
                    [
                        'nama_santri' => $item['nama'],
                        'kelas_madrasah_id' => $kelas->id,
                        'asrama' => $item['asrama'],
                        'kamar' => $item['kamar'],
                        'status' => 'aktif',
                    ]
                );

                $emailWali = 'wali' . $item['nis'] . '@sipontren.test';

                $userData = [
                    'name' => $item['wali'],
                    'email' => $emailWali,
                    'password' => Hash::make('password'),
                ];

                if (Schema::hasColumn('users', 'no_hp')) {
                    $userData['no_hp'] = '0812' . substr($item['nis'], -8);
                }

                if (Schema::hasColumn('users', 'role')) {
                    $userData['role'] = 'orang_tua';
                }

                $wali = User::updateOrCreate(
                    [
                        'email' => $emailWali,
                    ],
                    $userData
                );

                if (method_exists($wali, 'santris')) {
                    $wali->santris()->syncWithoutDetaching([
                        $santri->id => [
                            'hubungan' => 'ayah',
                        ],
                    ]);
                }
            }
        }
    }
}