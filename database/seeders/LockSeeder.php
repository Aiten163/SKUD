<?php

namespace Database\Seeders;

use App\Models\Lock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        $n = Lock::count()+1;
        for($i=$n; $i<$n+10; $i++)
        {
            Lock::create(['door_id'=>$i, 'id'=>$i]);
        }
    }
}
