<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\InstalmentCar;
use Illuminate\Database\Seeder;

class InstalmentCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InstalmentCar::create([
            'car' => 'Avanza',
            'brand' => 'Toyota',
            'price' => 250000000,
            'description' => 'Toyota Avanza 2024'
        ]);

        InstalmentCar::create([
            'car' => 'Brio',
            'brand' => 'Honda',
            'price' => 210000000,
            'description' => 'Honda Brio 2024'
        ]);

        InstalmentCar::create([
            'car' => 'Xpander',
            'brand' => 'Mitsubishi',
            'price' => 275000000,
            'description' => 'Mitsubishi Xpander 2024'
        ]);
    }
}
