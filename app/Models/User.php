<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'tel_number', 'role', 'slug'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ความสัมพันธ์ HasOne กับ students
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // ความสัมพันธ์ HasOne กับ instructors
    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    // ความสัมพันธ์ HasOne กับ generals
    public function general()
    {
        return $this->hasOne(General::class);
    }

    // ความสัมพันธ์ HasOne กับ insiders
    public function insider()
    {
        return $this->hasOne(Insider::class);
    }

    public function roleData()
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    public function getRoleDisplayNameAttribute()
    {
        return $this->roleData->display_name ?? $this->role;
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id')->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
