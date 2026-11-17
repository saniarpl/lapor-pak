<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // AKUN PENGUJIAN: ganti password sebelum dipakai sungguhan.
        // firstOrCreate: password tidak ditimpa jika seeder dijalankan ulang.
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Sekolah',
                'password' => 'admin12345',
                'role' => User::ROLE_ADMIN,
                'class_name' => null,
            ]
        );

        $students = [
            ['siswa1', 'Andi Pratama', 'X IPA 1'],
            ['siswa2', 'Bella Safitri', 'X IPS 2'],
            ['siswa3', 'Citra Lestari', 'XI IPA 2'],
            ['siswa4', 'Dimas Saputra', 'XI IPS 1'],
            ['siswa5', 'Eka Wulandari', 'XII IPA 3'],
        ];

        foreach ($students as [$username, $name, $class]) {
            User::firstOrCreate(
                ['username' => $username],
                [
                    'name' => $name,
                    'password' => Hash::make('siswa12345'),
                    'role' => User::ROLE_SISWA,
                    'class_name' => $class,
                ]
            );
        }
    }
}