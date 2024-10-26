<?php

namespace Database\Seeders;

use App\Models\Add_lock;
use App\Models\Card;
use App\Models\Door;
use App\Models\Lock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Card::factory()->count(10)->create();
        Door::factory()->count(10)->create();
        Lock::factory()->count(10)->create();
        Add_lock::class->create();
    }
}
