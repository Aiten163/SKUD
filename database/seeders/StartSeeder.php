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
        CardSeeder::run();
        DoorSeeder::run();
        LockSeeder::run();
        Add_lockSeeder::run();
    }
}
