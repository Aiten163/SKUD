<?php

namespace Database\Seeders;

use App\Models\Door;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Door::factory()->count(10)->create();
    }
}
