<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics'; // ชื่อตาราง
    protected $fillable = ['course_id', 'title'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function isCompletedBy($user)
    {
        if (!$user) {
            return false;
        }
        $totalLessons = $this->lessons()->count();
        $completedLessons = $this->lessons()
            ->whereHas('completions', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereNotNull('completed_at'); // เพิ่มเพื่อให้แน่ใจว่า lesson สำเร็จ
            })
            ->count();

        return $totalLessons > 0 && $totalLessons === $completedLessons;
    }
}
