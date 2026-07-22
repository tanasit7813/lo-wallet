<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;
    protected $table = 'certificates';
    protected $fillable = ['user_id', 'course_id', 'status', 'denial_reason', 'requested_at'];

    // กำหนดให้ requested_at เป็น Carbon instance
    protected $casts = [
        'requested_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
