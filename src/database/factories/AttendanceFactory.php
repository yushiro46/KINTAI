<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use App\Models\Staff;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 出勤時間（08:45〜08:59 のランダム）
        $workDate = $this->faker->randomElement(['2026-02-19', '2026-02-20']);
        $clockIn = Carbon::parse($workDate . ' 08:' . $this->faker->numberBetween(45, 59));

        $breakStart = (clone $clockIn)->addHours(4);
        $breakEnd = (clone $breakStart)->addHour();
        $clockOut = (clone $clockIn)->addHours(9);
        return [
            'clock_in'       => $clockIn,
            'break_start'    => $breakStart,
            'break_end'      => $breakEnd,
            'clock_out'      => $clockOut,
            'status'         => 'finished',
            'late_reason'    => null,
            'worked_minutes' => 480, // 8時間
        ];
    }
}
