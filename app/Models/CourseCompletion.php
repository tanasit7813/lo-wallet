<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCompletion extends Model
{
    protected $fillable = ['user_id', 'course_id', 'enrollment_id', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }
}
