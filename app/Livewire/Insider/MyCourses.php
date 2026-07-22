<?php

namespace App\Livewire\Insider;

use Livewire\Component;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class MyCourses extends Component
{
    public $enrolledCourses;

    public function mount()
    {
        if (Auth::user()->role !== 'insider') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $this->enrolledCourses = Enrollment::with(['course', 'course.instructors.user'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed', 'denied'])
            ->get();
    }

    public function render()
    {
        return view('livewire.insider.my-courses');
    }
}
