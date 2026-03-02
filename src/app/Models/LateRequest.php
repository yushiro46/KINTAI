<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'attendance_id',
        'reason',
        'status',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
