<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';
    protected $fillable = ['faculties_id', 'name', 'slug'];

    // ความสัมพันธ์ BelongsTo กับ faculties
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculties_id');
    }

    // ความสัมพันธ์ One-to-Many กับ branchs
    public function branchs()
    {
        return $this->hasMany(Branch::class, 'programs_id');
    }

    // ความสัมพันธ์ One-to-Many กับ majors (ผ่าน branchs)
    public function majors()
    {
        return $this->hasMany(Major::class, 'programs_id');
    }
}
