<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;
    protected $table = 'enrollments';

    protected $fillable = ['user_id', 'course_id', 'status', 'remark'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseCompletion()
    {
        return $this->hasOne(CourseCompletion::class, 'enrollment_id');
    }
}
