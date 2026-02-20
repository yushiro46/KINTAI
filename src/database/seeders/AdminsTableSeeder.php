<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '佐藤洸平',
            'email' => 'kouhei@coachtech.com',
            'password' => Hash::make('password123'),
        ];
        DB::table('admins')->insert($param);
        $param = [
            'name' => '近藤勇',
            'email' => 'isami@coachtech.com',
            'password' => Hash::make('password456'),
        ];
        DB::table('admins')->insert($param);
        $param = [
            'name' => '伊藤香織',
            'email' => 'kaori@coachtech.com',
            'password' => Hash::make('password789'),
        ];
        DB::table('admins')->insert($param);

    }
}
