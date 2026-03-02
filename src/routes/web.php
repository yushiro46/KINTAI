<?php

use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Staff\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\StaffRegisterController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| スタッフ（staff）用ルーティング
|--------------------------------------------------------------------------
*/

// スタッフログイン画面（GET）
Route::get('/login', function () {
    return view('auth.staff-login');
})->middleware('guest:staff')->name('staff.login');

// スタッフログイン処理（POST）
Route::post('/login', [StaffLoginController::class, 'store']);

// スタッフログアウト
Route::post('/staff/logout', function () {
    Auth::guard('staff')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth:staff');


// 新規登録フォーム表示
Route::get('/register', [StaffRegisterController::class, 'showForm'])
    ->middleware('guest:staff')
    ->name('staff.register');

// 新規登録処理
Route::post('/register', [StaffRegisterController::class, 'store'])
    ->middleware('guest:staff')
    ->name('staff.register.store');

/*
|--------------------------------------------------------------------------
| スタッフログイン後（勤怠機能）
|--------------------------------------------------------------------------
*/

Route::middleware('auth:staff')->group(function () {

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('staff.attendance');

    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn']);
    Route::post('/attendance/break-in', [AttendanceController::class, 'breakIn']);
    Route::post('/attendance/break-out', [AttendanceController::class, 'breakOut']);
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut']);

    //勤怠一覧
    Route::get(
        '/attendance/list/{year?}/{month?}',
        [AttendanceController::class, 'monthly']
    )->name('staff.attendance.list');

    //勤怠詳細
    Route::get(
        '/attendance/detail/{id}',
        [AttendanceController::class, 'detail']
    )->name('staff.attendance.detail');

    //修正
    Route::post('/late-request/store', [AttendanceController::class, 'store'])->name('late.request.store');
});

// 申請一覧（スタッフ or 管理者のどちらでもアクセス可能）
Route::get('/stamp_correction_request/list', [RequestController::class, 'index'])
    ->middleware('staff_or_admin')
    ->name('request.list');




/*
|--------------------------------------------------------------------------
| 管理者（admin）用ルーティング
|--------------------------------------------------------------------------
*/

// 管理者ログイン画面（GET）
Route::get('/admin/login', function () {
    return view('auth.admin-login');
})->middleware('guest:admin')->name('admin.login');

// 管理者ログイン処理（POST）
Route::post('/admin/login', [AdminLoginController::class, 'store']);

// 管理者ログアウト
Route::post('/admin/logout', function () {
    Auth::guard('admin')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/admin/login');
})->middleware('auth:admin');


/*
|--------------------------------------------------------------------------
| 管理者ログイン後の画面
|--------------------------------------------------------------------------
*/

Route::middleware('auth:admin')->group(function () {

    //勤怠一覧
    Route::get('/admin/attendance/list/{date?}', [AdminAttendanceController::class, 'daily'])->name('admin.attendance.list');
    // 詳細ページ
    Route::get('/admin/attendance/{id}', [AdminAttendanceController::class, 'detail'])
        ->name('admin.attendance.detail');

    // 備考修正
    Route::put('/admin/attendance/{id}', [AdminAttendanceController::class, 'update'])
        ->name('admin.attendance.update');

    // スタッフ一覧
    Route::get('/admin/staff/list', [AdminAttendanceController::class, 'index'])
        ->name('admin.staff.list');

    //スタッフ別勤怠一覧
    Route::get(
        '/admin/attendance/staff/{staff_id}',
        [AdminAttendanceController::class, 'monthly']
    )->name('admin.attendance.monthly');

    //csv出力
    Route::get(
        '/admin/attendance/staff/{staff_id}/csv',
        [AdminAttendanceController::class, 'csvExport']
    )->name('admin.attendance.monthly.csv');

    //申請の詳細画面
    Route::get(
        '/stamp_correction_request/approve/{id}',
        [AdminAttendanceController::class, 'requestDetail']
    )->name('admin.request.detail');

    //承認
    Route::post(
        '/stamp_correction_request/approve/{id}',
        [AdminAttendanceController::class, 'approveRequest']
    )->name('admin.request.approve');
});
