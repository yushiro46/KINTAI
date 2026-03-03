<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Staff;

class StaffAuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /**
     * ログイン画面が表示される
     */
    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
            ->assertSee('ログイン'); // ログインページの文字を確認
    }

    /**
     * 正しい情報でスタッフログイン成功
     */
    public function test_staff_can_login_with_correct_credentials()
    {
        $staff = Staff::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $staff->email,
            'password' => 'password123',
        ]);

        // スタッフとして認証されたかを確認
        $this->assertAuthenticatedAs($staff, 'staff');

        // 勤怠画面へリダイレクト（あなたの仕様）
        $response->assertRedirect('/attendance');
    }

    /**
     * 間違った情報ではログインできない
     */
    public function test_staff_cannot_login_with_wrong_credentials()
    {
        $staff = Staff::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $staff->email,
            'password' => 'wrongpass',
        ]);

        // 認証されていないこと
        $this->assertGuest('staff');

        // ログイン画面へ戻る
        $response->assertRedirect('/login');
    }

    /**
     * 未ログインでは勤怠画面は見れない
     */
    public function test_guest_cannot_access_attendance_page()
    {
        $response = $this->get('/attendance');

        $response->assertRedirect('/login');
    }

    /**
     * スタッフログアウトの確認
     */
    public function test_staff_can_logout()
    {
        $staff = Staff::factory()->create();

        $this->actingAs($staff, 'staff');

        $response = $this->post('/staff/logout');

        // ログアウトされているか
        $this->assertGuest('staff');

        // ログイン画面に戻る
        $response->assertRedirect('/login');
    }
}
