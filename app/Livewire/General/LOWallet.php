<?php

namespace App\Livewire\General;

use App\Models\CourseCompletion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LOWallet extends Component
{
    public $completions = [];
    public $userData = [];

    public function mount()
    {
        // Load user with general relationship
        $user = Auth::user()->load('general');

        // Prepare user-specific data
        $this->userData = [
            'name' => $user->name,
            'email' => $user->email,
            'tel_number' => $user->tel_number ?? '-',
            'position' => $user->general ? $user->general->position : '-', // ดึง position จากตาราง general
            'agency' => $user->general ? $user->general->agency : '-', // ดึง agency จากตาราง general
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
        return view('livewire.general.l-o-wallet');
    }
}
