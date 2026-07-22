<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseInstructor extends Pivot
{
    protected $table = 'course_instructor';

    protected $casts = [
        'is_owner' => 'boolean',
    ];
}
