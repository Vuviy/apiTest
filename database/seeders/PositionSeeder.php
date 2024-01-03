<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create(['name' => 'Security']);
        Position::create(['name' => 'Designer']);
        Position::create(['name' => 'Content manager']);
        Position::create(['name' => 'Lawyer']);

    }
}
