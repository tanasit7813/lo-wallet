<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = ['user_id', 'faculties_id', 'programs_id', 'branches_id', 'majors_id'];

    // ความสัมพันธ์ BelongsTo กับ users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ความสัมพันธ์ BelongsTo กับ faculties
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculties_id');
    }

    // ความสัมพันธ์ BelongsTo กับ programs
    public function program()
    {
        return $this->belongsTo(Program::class, 'programs_id');
    }

    // ความสัมพันธ์ BelongsTo กับ branchs
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branches_id');
    }

    // ความสัมพันธ์ BelongsTo กับ majors
    public function major()
    {
        return $this->belongsTo(Major::class, 'majors_id');
    }
}
