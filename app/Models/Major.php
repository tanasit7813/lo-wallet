<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'majors';
    protected $fillable = ['faculties_id', 'programs_id', 'branches_id', 'name', 'slug'];

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
}
