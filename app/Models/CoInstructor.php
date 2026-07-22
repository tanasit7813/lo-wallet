<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoInstructor extends Model
{
    protected $table = 'co_instructors';
    protected $fillable = ['course_id', 'instructor_id', 'name', 'position'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
}
