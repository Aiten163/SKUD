<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        if (User::first()=== null) {
            Artisan::call('orchid:admin admin admin@admin.com admin');
        }
    }
}
