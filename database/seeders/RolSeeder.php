<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'user'
        ]);

        DB::table('roles')->insert([
            'name' => 'admin'
        ]);

        DB::table('roles')->insert([
            'name' => 'super_admin'
        ]);
    }
}