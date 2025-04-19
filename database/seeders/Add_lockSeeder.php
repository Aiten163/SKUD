<?php

namespace Database\Seeders;

use App\Models\Add_lock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Add_lockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        Add_lock::create();
    }
}
