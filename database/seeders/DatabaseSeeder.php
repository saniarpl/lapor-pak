<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Aman dijalankan berulang kali: semua seeder memakai firstOrCreate/updateOrCreate.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            UrgencySeeder::class,
            WorkerSeeder::class,
            ReportSeeder::class,
        ]);
    }
}