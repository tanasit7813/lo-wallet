<?php

namespace App\Livewire\Insider;

use App\Models\CourseCompletion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LOWallet extends Component
{
    public $completions = [];
    public $userData = [];

    public function mount()
    {
        // Restrict to role
        if (Auth::user()->role !== 'insider') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // Load user with insider relationship
        $user = Auth::user()->load('insider');

        // Prepare user-specific data
        $this->userData = [
            'name' => $user->name,
            'email' => $user->email,
            'tel_number' => $user->tel_number ?? '-',
            'insider_role' => $user->insider ? $user->insider->insider_role : '-', // ดึง insider_role จากตาราง insider
        ];


        // Load completed courses with lessons and learning outcomes
        $this->completions = CourseCompletion::with(['course.lessons.learningOutcomes'])
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($completion) {
                // Aggregate learning outcomes from all lessons
                $learningOutcomes = $completion->course->lessons
                    ->flatMap(function ($lesson) {
                        return $lesson->learningOutcomes->pluck('description');
                    })
                    ->unique()
                    ->toArray();

                return [
                    'course_id' => $completion->course->id,
                    'course_title' => $completion->course->title,
                    'learning_outcomes' => $learningOutcomes,
                    'completed_at' => $completion->completed_at?->format('d/m/Y H:i'), // เพิ่ม completed_at เหมือน role student
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.insider.l-o-wallet');
    }
}
