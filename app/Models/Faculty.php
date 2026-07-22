<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $table = 'faculties'; // ชื่อตาราง
    protected $fillable = ['name', 'slug'];

    // ความสัมพันธ์ One-to-Many กับ programs
    public function programs()
    {
        return $this->hasMany(Program::class, 'faculties_id');
    }

    // ความสัมพันธ์ One-to-Many กับ branchs (ผ่าน programs)
    public function branchs()
    {
        return $this->hasMany(Branch::class, 'faculties_id');
    }

    // ความสัมพันธ์ One-to-Many กับ majors (ผ่าน branchs)
    public function majors()
    {
        return $this->hasMany(Major::class, 'faculties_id');
    }
}
