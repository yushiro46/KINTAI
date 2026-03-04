<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;

class AdminAuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /**
     * ログイン画面の表示
     */
    public function test_admin_login_page_is_accessible()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200)
            ->assertSee('ログイン'); // ログインページの文字があるか確認
    }

    /**
     * 正しい情報でログイン成功
     */
    public function test_admin_can_login_with_correct_credentials()
    {
        $admin = Admin::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password123'
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect('/admin/attendance/list');
    }

    /**
     * 間違った情報ではログインできない
     */
    public function test_admin_cannot_login_with_wrong_credentials()
    {
        $admin = Admin::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'wrongpass'
        ]);

        // 未認証のまま
        $this->assertGuest('admin');

        // ログイン画面に戻る
        $response->assertRedirect('/admin/login');
    }

    /**
     * 未ログインでは管理者画面を見れない
     */
    public function test_guest_cannot_access_admin_pages()
    {
        $response = $this->get('/admin/attendance/list');

        $response->assertRedirect('/admin/login');
    }

    /**
     * 管理者ログアウト成功
     */
    public function test_admin_can_logout()
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin');

        $response = $this->post('/admin/logout');

        $this->assertGuest('admin');
        $response->assertRedirect('/admin/login');
    }
}
