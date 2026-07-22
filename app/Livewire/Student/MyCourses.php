<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class MyCourses extends Component
{
    public $enrolledCourses;

    public function mount()
    {
        // Ensure the user is a student
        if (Auth::user()->role !== 'student') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // Fetch enrollments for the authenticated student
        $this->enrolledCourses = Enrollment::with(['course', 'course.instructors.user'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed', 'denied'])
            ->get();
    }

    public function render()
    {
        return view('livewire.student.my-courses');
    }
}
