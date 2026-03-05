<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InstalmentMonth;

class InstalmentMonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Avanza (id:1)
        InstalmentMonth::create([
            'instalment_car_id' => 1,
            'month' => 12,
            'description' => '1 Tahun'
        ]);

        InstalmentMonth::create([
            'instalment_car_id' => 1,
            'month' => 24,
            'description' => '2 Tahun'
        ]);

        InstalmentMonth::create([
            'instalment_car_id' => 1,
            'month' => 36,
            'description' => '3 Tahun'
        ]);

        // Brio (id:2)
        InstalmentMonth::create([
            'instalment_car_id' => 2,
            'month' => 12,
            'description' => '1 Tahun'
        ]);

        InstalmentMonth::create([
            'instalment_car_id' => 2,
            'month' => 48,
            'description' => '4 Tahun'
        ]);
    }
}
