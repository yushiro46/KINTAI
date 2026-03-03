<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Staff;
use App\Models\Attendance;
use Carbon\Carbon;

class StaffAttendanceListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /**
     * スタッフのテスト用ログイン
     */
    private function actingAsStaff()
    {
        $staff = Staff::factory()->create();
        $this->actingAs($staff, 'staff');
        return $staff;
    }

    /**
     * ログインしていない場合はログイン画面へリダイレクト
     */
    public function test_guest_cannot_access_attendance_list()
    {
        $response = $this->get('/attendance/list');

        $response->assertRedirect('/login');
    }

    /**
     * スタッフが自分の勤怠一覧を取得できる
     */
    public function test_staff_can_view_their_attendance_list()
    {
        $staff = $this->actingAsStaff();

        // 今日の日付を固定
        $today = Carbon::today()->toDateString();

        // ログインしたスタッフの勤怠データ作成
        Attendance::factory()->create([
            'staff_id'   => $staff->id,
            'work_date'  => $today,
            'clock_in'   => '09:00:00',
            'clock_out'  => '18:00:00',
        ]);

        $response = $this->get('/attendance/list');

        $response->assertStatus(200)
            ->assertViewIs('staff.attendance-list')
            ->assertSee($today)
            ->assertSee('09:00:00')
            ->assertSee('18:00:00');
    }

    /**
     * 他のスタッフの勤怠は表示されない
     */
    public function test_staff_cannot_see_other_staff_attendance()
    {
        $staff = $this->actingAsStaff();

        $today = Carbon::today()->toDateString();

        // ログインしたスタッフのデータ
        Attendance::factory()->create([
            'staff_id'  => $staff->id,
            'work_date' => $today
        ]);

        // 別のスタッフの勤怠
        $other = Staff::factory()->create();

        Attendance::factory()->create([
            'staff_id'  => $other->id,
            'work_date' => $today,
            'clock_in'  => '10:00:00', // 違いがわかるように
        ]);

        $response = $this->get('/attendance/list');

        // 自分のデータは見える
        $response->assertSee($today);

        // 他人のデータは見えない
        $response->assertDontSee('10:00:00');
    }
}
