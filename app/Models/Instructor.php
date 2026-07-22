<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'instructors';
    protected $fillable = ['user_id', 'academic_position'];

    // ความสัมพันธ์ BelongsTo กับ users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_instructor')
            ->withPivot('is_owner')
            ->withTimestamps();
    }
}
