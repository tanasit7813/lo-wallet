<?php

namespace App\Livewire\Student;

use App\Models\CourseCompletion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Director;

class LOWallet extends Component
{
    public $completions = [];
    public $userData = [];
    public $directorName = '';

    public function mount()
    {
        // Restrict to student role only
        if (Auth::user()->role !== 'student') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // Load user with student-specific relationships
        $user = Auth::user()->load([
            'student.faculty',
            'student.branch',
        ]);

        $director = Director::latest()->first(); // ดึงผู้อำนวยการคนล่าสุด
        if ($director) {
            $this->directorName = $director->name; // เก็บชื่อลงใน property
        }

        // Prepare student-specific data
        $this->userData = [
            'name' => $user->name,
            'program' => $user->student && $user->student->branch ? $user->student->branch->name : '-',
            'faculty' => $user->student && $user->student->faculty ? $user->student->faculty->name : '-',
            'branch' => $user->student && $user->student->branch ? $user->student->branch->name : '-',
        ];

        $this->completions = CourseCompletion::with([
            'course.lessons.learningOutcomes.tag' // <-- แก้ไข Eager Loading ที่นี่
        ])
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($completion) {
                $learningOutcomes = $completion->course->lessons->flatMap(function ($lesson) {
                    return $lesson->learningOutcomes->map(function ($outcome) {
                        return [
                            'description' => $outcome->description,
                            'tag_name' => $outcome->tag ? $outcome->tag->name : null
                        ];
                    });
                })->filter(function ($outcome) {
                    // กรองอันที่ไม่มี description ออก
                    return !empty($outcome['description']);
                })->toArray();

                return [
                    'course_id' => $completion->course->id,
                    'course_title' => $completion->course->title,
                    'learning_outcomes' => $learningOutcomes,
                    'completed_at' => $completion->completed_at?->format('d/m/Y H:i'),
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.student.l-o-wallet');
    }
}
