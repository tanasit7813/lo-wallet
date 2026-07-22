<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $fillable = [
        'title',
        'description',
        'duration',
        'start_date',
        'end_date',
        'training_date',
        'end_training_date',
        'certificate',
        'max_students',
        'image',
    ];

    protected $casts = [
        'certificate' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'training_date' => 'datetime',
        'end_training_date' => 'datetime',
    ];

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'course_instructor')
            ->withPivot('is_owner')
            ->withTimestamps();
    }

    public function coInstructors()
    {
        return $this->hasMany(CoInstructor::class, 'course_id');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function canIssueCertificate()
    {
        return $this->certificate == 1;
    }
}
