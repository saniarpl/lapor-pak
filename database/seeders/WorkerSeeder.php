<?php

namespace Database\Seeders;

use App\Models\Worker;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    public function run(): void
    {
        // DATA PENGUJIAN: nomor di bawah adalah nomor dummy, BUKAN nomor WhatsApp sungguhan.
        // Ganti lewat menu Petugas sebelum dipakai.
        $workers = [
            'Petugas Uji 1 - Listrik (DATA UJI)' => '628000000001',
            'Petugas Uji 2 - Bangunan (DATA UJI)' => '628000000002',
            'Petugas Uji 3 - Perabot & IT (DATA UJI)' => '628000000003',
        ];

        foreach ($workers as $name => $number) {
            Worker::updateOrCreate(['name' => $name], ['whatsapp_number' => $number]);
        }
    }
}