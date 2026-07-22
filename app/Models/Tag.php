<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    // ความสัมพันธ์ย้อนกลับ: Tag หนึ่งอัน "มี" LearningOutcomes ได้หลายอัน
    public function learningOutcomes()
    {
        return $this->hasMany(LearningOutcome::class);
    }
}
