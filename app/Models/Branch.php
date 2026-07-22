<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';
    protected $fillable = ['faculties_id', 'programs_id', 'name', 'slug'];

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

    // ความสัมพันธ์ One-to-Many กับ majors
    public function majors()
    {
        return $this->hasMany(Major::class, 'branchs_id');
    }
}
