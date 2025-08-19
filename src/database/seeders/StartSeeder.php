<?php

namespace Database\Seeders;

use App\Models\Add_lock;
use App\Models\Card;
use App\Models\Door;
use App\Models\Lock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('orchid:admin admin admin@admin.com admin');
    }
}
