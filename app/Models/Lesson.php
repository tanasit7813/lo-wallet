<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons'; // ชื่อตาราง
    protected $fillable = ['topic_id', 'course_id', 'title', 'content'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function learningOutcomes()
    {
        return $this->hasMany(LearningOutcome::class, 'lesson_id');
    }
}
