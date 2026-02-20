<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'work_date',
        'clock_in',
        'break_start',
        'break_end',
        'clock_out',
        'status',
        'late_reason',
        'worked_minutes',
    ];

    /**
     * スタッフとのリレーション
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * 休憩時間（分）のアクセサ
     */
    public function getBreakMinutesAttribute()
    {
        if (!$this->break_start || !$this->break_end) {
            return null;
        }

        return Carbon::parse($this->break_start)
            ->diffInMinutes(Carbon::parse($this->break_end));
    }

    /**
     * 実働時間のアクセサ（hours + minutes）
     */
    public function getWorkedHoursAttribute()
    {
        if (!$this->worked_minutes) return null;
        return floor($this->worked_minutes / 60);
    }

    public function getWorkedRemainMinutesAttribute()
    {
        if (!$this->worked_minutes) return null;
        return $this->worked_minutes % 60;
    }
}
