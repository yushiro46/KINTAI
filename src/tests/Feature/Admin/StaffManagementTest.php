<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Staff;

class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 管理者ログイン状態を作る
     */
    private function actingAsAdmin(): Admin
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        return $admin;
    }

    /** @test */
    public function 管理者はスタッフ一覧を表示できる()
    {
        $this->actingAsAdmin();

        Staff::factory()->count(3)->create();

        $response = $this->get('/admin/staff/list');

        $response->assertStatus(200);
        $response->assertViewIs('admin.staff-list');
        $response->assertSee('スタッフ一覧');
    }

    /** @test */
    public function 管理者はスタッフの月次勤怠一覧を表示できる()
    {
        $this->actingAsAdmin();

        $staff = Staff::factory()->create();

        Attendance::factory()->create([
            'staff_id' => $staff->id,
            'work_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/attendance/staff/' . $staff->id);

        $response->assertStatus(200);
        $response->assertViewIs('admin.attendance-monthly');
        $response->assertSee($staff->name);
    }

    /** @test */
    public function 管理者は勤怠詳細画面を表示できる()
    {
        $this->actingAsAdmin();

        $attendance = Attendance::factory()->create();

        $response = $this->get('/admin/attendance/' . $attendance->id);

        $response->assertStatus(200);
        $response->assertViewIs('admin.attendance-detail');
    }

    /** @test */
    public function 管理者は勤怠備考を更新できる()
    {
        $this->actingAsAdmin();

        $attendance = Attendance::factory()->create([
            'note' => '元のメモ'
        ]);

        $response = $this->put('/admin/attendance/' . $attendance->id, [
            'note' => '新しいメモ'
        ]);

        $response->assertStatus(302); // リダイレクト

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'note' => '新しいメモ',
        ]);
    }
}
