<?php

namespace Database\Seeders;

use App\Models\Urgency;
use Illuminate\Database\Seeder;

class UrgencySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Rendah' => 1, 'Sedang' => 2, 'Tinggi' => 3, 'Darurat' => 4] as $name => $level) {
            Urgency::updateOrCreate(['name' => $name], ['level' => $level]);
        }
    }
}