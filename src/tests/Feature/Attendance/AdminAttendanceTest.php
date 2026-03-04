<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Staff;

class AdminAttendanceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /**
     * 管理者としてログイン
     */
    
    private function actingAsAdmin()
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        return $admin;
    }

    /** @test */
    public function 管理者は日別勤怠一覧を表示できる()
    {
        $this->actingAsAdmin();

        Attendance::factory()->create([
            'work_date' => now()->format('Y-m-d')
        ]);

        $response = $this->get('/admin/attendance/list');

        $response->assertStatus(200);
        $response->assertViewIs('admin.daily');
        $response->assertSee(now()->format('Y-m-d'));
    }

    /** @test */
    public function 管理者は勤怠詳細を表示できる()
    {
        $this->actingAsAdmin();

        $attendance = Attendance::factory()->create();

        $response = $this->get('/admin/attendance/' . $attendance->id);

        $response->assertStatus(200);
        $response->assertViewIs('admin.attendance-detail');
        $response->assertSee($attendance->staff->name);
    }

    /** @test */
    public function 管理者は勤怠備考を更新できる()
    {
        $this->actingAsAdmin();

        $attendance = Attendance::factory()->create([
            'note' => '元の備考'
        ]);

        $response = $this->put('/admin/attendance/' . $attendance->id, [
            'note' => '更新後の備考'
        ]);

        $response->assertStatus(302); // redirect

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'note' => '更新後の備考',
        ]);
    }

    /** @test */
    public function 管理者はスタッフ別月次勤怠一覧を取得できる()
    {
        $this->actingAsAdmin();

        $staff = Staff::factory()->create();

        Attendance::factory()->count(3)->create([
            'staff_id' => $staff->id,
            'work_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/attendance/staff/' . $staff->id);

        $response->assertStatus(200);
        $response->assertViewIs('admin.attendance-monthly');
        $response->assertSee($staff->name);
    }

    /** @test */
    public function 管理者は勤怠CSVをダウンロードできる()
    {
        $this->actingAsAdmin();

        $staff = Staff::factory()->create();

        Attendance::factory()->create([
            'staff_id' => $staff->id,
            'work_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/attendance/staff/' . $staff->id . '/csv?year=2024&month=1');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertSee($staff->name); // CSV に含まれるはず
    }
}
