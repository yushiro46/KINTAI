<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '西玲奈',
            'email' => 'reina@coachtech.com',
            'password' => Hash::make('pas123'),
        ];
        DB::table('staff')->insert($param);
        $param = [
            'name' => '山田太郎',
            'email' => 'taro@coachtech.com',
            'password' => Hash::make('pas456'),
        ];
        DB::table('staff')->insert($param);
        $param = [
            'name' => '増田一世',
            'email' => 'issei@coachtech.com',
            'password' => Hash::make('pas789'),
        ];
        DB::table('staff')->insert($param);
        $param = [
            'name' => '山本敬一',
            'email' => 'keiichi@coachtech.com',
            'password' => Hash::make('pas135'),
        ];
        DB::table('staff')->insert($param);
        $param = [
            'name' => '秋田友美',
            'email' => 'tomomi@coachtech.com',
            'password' => Hash::make('pas246'),
        ];
        DB::table('staff')->insert($param);
        $param = [
            'name' => '中西紀夫',
            'email' => 'norio@coachtech.com',
            'password' => Hash::make('pas987'),
        ];
        DB::table('staff')->insert($param);
    }
}
