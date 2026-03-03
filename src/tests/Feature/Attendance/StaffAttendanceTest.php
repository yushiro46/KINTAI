<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Staff;
use App\Models\Attendance;
use Carbon\Carbon;

class StaffAttendanceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /**
     * スタッフとしてログインするヘルパー
     */
    private function actingAsStaff()
    {
        $staff = Staff::factory()->create();
        $this->actingAs($staff, 'staff');
        return $staff;
    }

    /**
     * 未ログイン時はログインページにリダイレクト
     */
    public function test_guest_cannot_access_attendance_page()
    {
        $response = $this->get('/attendance');
        $response->assertRedirect('/login');
    }

    /**
     * 勤怠画面が表示できる（status=off）
     */
    public function test_staff_can_view_attendance_page()
    {
        $staff = $this->actingAsStaff();

        $response = $this->get('/attendance');

        $response->assertStatus(200)
            ->assertViewIs('staff.attendance')
            ->assertSee('勤務外'); // status=off
    }

    /**
     * 出勤処理の正常動作
     */
    public function test_staff_can_clock_in()
    {
        $staff = $this->actingAsStaff();

        $this->post('/attendance/clock-in');

        $attendance = Attendance::where('staff_id', $staff->id)->first();
        $this->assertNotNull($attendance->clock_in);
        $this->assertEquals('working', $attendance->status);
    }

    /**
     * 休憩開始の正常動作
     */
    public function test_staff_can_start_break()
    {
        $staff = $this->actingAsStaff();

        // 出勤状態を作る
        Attendance::factory()->create([
            'staff_id' => $staff->id,
            'work_date' => Carbon::today()->toDateString(),
            'status' => 'working',
        ]);

        $this->post('/attendance/break-in');

        $attendance = Attendance::where('staff_id', $staff->id)->first();
        $this->assertNotNull($attendance->break_start);
        $this->assertEquals('break', $attendance->status);
    }

    /**
     * 休憩終了の正常動作
     */
    public function test_staff_can_end_break()
    {
        $staff = $this->actingAsStaff();

        Attendance::factory()->create([
            'staff_id' => $staff->id,
            'work_date' => Carbon::today()->toDateString(),
            'status' => 'break',
        ]);

        $this->post('/attendance/break-out');

        $attendance = Attendance::first();
        $this->assertNotNull($attendance->break_end);
        $this->assertEquals('working', $attendance->status);
    }

    /**
     * 退勤処理の正常動作
     */
    public function test_staff_can_clock_out()
    {
        $staff = $this->actingAsStaff();

        Attendance::factory()->create([
            'staff_id' => $staff->id,
            'work_date' => Carbon::today()->toDateString(),
            'status' => 'working',
        ]);

        $this->post('/attendance/clock-out');

        $attendance = Attendance::first();
        $this->assertNotNull($attendance->clock_out);
        $this->assertEquals('finished', $attendance->status);
    }
}
