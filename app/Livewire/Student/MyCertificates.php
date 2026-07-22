<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class MyCertificates extends Component
{
    public $certificates = [];

    public function mount()
    {
        // Ensure the user is a student
        if (Auth::user()->role !== 'student') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // Load the user's certificate requests with related course data
        $this->certificates = Certificate::with('course')
            ->where('user_id', Auth::id())
            ->get();
    }

    public function render()
    {
        return view('livewire.student.my-certificates');
    }
}
