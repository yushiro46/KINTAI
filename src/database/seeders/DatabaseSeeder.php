<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 管理者・スタッフを先に作成
        $this->call(AdminsTableSeeder::class);
        $this->call(StaffTableSeeder::class);

        // ⭐ ここから勤怠データ作成（1人1日1回に保証できる形）
        $dates = ['2026-02-19', '2026-02-20'];
        $staffIds = [1, 2, 3, 4, 5, 6];

        foreach ($dates as $date) {
            foreach ($staffIds as $staffId) {
                Attendance::factory()->create([
                    'staff_id'  => $staffId,
                    'work_date' => $date,
                ]);
            }
        }
    }
}
