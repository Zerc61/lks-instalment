<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Regional;
use App\Models\Society;
use Illuminate\Support\Facades\Hash;

class SocietySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regional = Regional::create([
            'province' => 'Jawa Timur',
            'district' => 'Surabaya'
        ]);

        Society::create([
            'regional_id' => $regional->id,
            'name' => 'Anjar',
            'id_card_number' => '1234567890',
            'password' => Hash::make('1234'),
            'born_date' => '2005-11-06',
            'gender' => 'male',
            'address' => 'Simo Gunung, Surabaya',
        ]);

        Society::create([
           'name' => 'Admin',
           'id_card_number' => '0001',
           'password' => bcrypt('password'),
           'born_date' => '2000-01-01',
           'gender' => 'male',
           'address' => 'Office',
           'regional_id' => 1,
           'role' => 'admin'
]);
    }
}
