<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CourseIndex extends Component
{
    public function render()
    {
        // สร้าง Query Builder เริ่มต้น พร้อมนับจำนวน enrollments
        $query = Course::withCount(['enrollments' => function ($q) {
            $q->whereIn('status', ['pending', 'confirmed']);
        }]);

        // ตรวจสอบ Role เพื่อกรองข้อมูลคอร์ส
        if (Auth::user()->role === 'instructor') {
            $courses = $query->whereHas('instructors', function ($q) {
                $q->where('instructors.id', Auth::user()->instructor->id);
            })->get();
        } else {
            // สำหรับ Role อื่นๆ ให้ดึงข้อมูลทั้งหมด (ที่ผ่านการนับแล้ว)
            $courses = $query->get();
        }

        return view('livewire.course-index', [
            'courses' => $courses,
        ]);
    }
}
