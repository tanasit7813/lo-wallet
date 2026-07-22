<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningOutcome extends Model
{
    protected $table = 'learning_outcomes';
    protected $fillable = ['description', 'lesson_id', 'tag_id'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
