<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Kerusakan Meja' => 'Meja siswa atau meja guru rusak, patah, atau goyang.',
            'Kerusakan Kursi' => 'Kursi patah, kaki kursi lepas, atau sandaran rusak.',
            'Kerusakan Pintu' => 'Pintu, engsel, atau kunci pintu rusak.',
            'Kerusakan Jendela' => 'Kaca pecah, bingkai jendela rusak, atau engsel macet.',
            'Kerusakan Lampu' => 'Lampu mati, berkedip, atau fitting lampu bermasalah.',
            'Kerusakan Toilet' => 'Kloset, keran, saluran air, atau pintu toilet rusak.',
            'Kerusakan Komputer' => 'Komputer atau perangkat di laboratorium tidak berfungsi.',
            'Kerusakan Proyektor' => 'Proyektor atau layar proyektor tidak berfungsi.',
            'Kerusakan Atap' => 'Atap bocor, plafon jebol, atau talang air rusak.',
            'Kerusakan Lantai' => 'Lantai retak, ubin lepas, atau permukaan licin berbahaya.',
        ];

        foreach ($categories as $name => $description) {
            Category::updateOrCreate(['name' => $name], ['description' => $description]);
        }
    }
}