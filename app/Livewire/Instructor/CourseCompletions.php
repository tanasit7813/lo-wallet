<?php

namespace App\Livewire\Instructor;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\CourseCompletion;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CourseCompletions extends Component
{
    public $course;
    public $students = [];

    public function mount($id)
    {
        // Load the course
        $this->course = Course::findOrFail($id);

        // Allow access for instructors assigned to the course or officers
        if (Auth::user()->role === 'officer') {
            // Officers have access without instructor record
        } elseif (Auth::user()->role === 'instructor' && Auth::user()->instructor && $this->course->instructors()->where('instructors.id', Auth::user()->instructor->id)->exists()) {
            // Instructors must be assigned to the course
        } else {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงคอร์สนี้');
        }

        $this->loadStudents();
    }

    public function loadStudents()
    {
        // Get confirmed enrollments for this course with role-specific relationships
        $enrollments = Enrollment::with([
            'user',
            'user.student.faculty',
            'user.student.branch',
            'user.general',
            'user.insider'
        ])
            ->where('course_id', $this->course->id)
            ->where('status', 'confirmed')
            ->get();

        $this->students = $enrollments->map(function ($enrollment) {
            $user = $enrollment->user;

            // Check CourseCompletion for this user and course
            $courseCompletion = CourseCompletion::where('user_id', $user->id)
                ->where('course_id', $this->course->id)
                ->first();

            // Determine completion status
            $isCompleted = $courseCompletion && !is_null($courseCompletion->completed_at);

            // Check if the user has requested a certificate
            $certificateRequest = Certificate::where('user_id', $user->id)
                ->where('course_id', $this->course->id)
                ->first();

            // Map insider_role to Thai equivalents
            $insiderRoleMap = [
                'academic' => 'บุคลากรสายวิชาการ',
                'teaching' => 'บุคลากรสายการสอน',
            ];

            $insiderRole = '-';
            if ($user->role === 'insider' && $user->insider) {
                $rawInsiderRole = $user->insider->insider_role ?? 'ไม่ระบุ';
                $insiderRole = $insiderRoleMap[$rawInsiderRole] ?? 'ไม่ระบุ';
            }

            // Prepare role-specific data
            $roleData = [
                'role' => $user->role,
                'faculty' => $user->role === 'student' && $user->student && $user->student->faculty ? $user->student->faculty->name : '-',
                'branch' => $user->role === 'student' && $user->student && $user->student->branch ? $user->student->branch->name : '-',
                'position' => $user->role === 'general' && $user->general ? $user->general->position ?? '-' : '-',
                'agency' => $user->role === 'general' && $user->general ? $user->general->agency ?? '-' : '-',
                'insider_role' => $insiderRole,
            ];

            return [
                'user' => $user,
                'status' => $isCompleted ? 'completed' : 'in_progress',
                'remark' => $isCompleted ? 'เรียนครบทุกบทเรียนแล้ว' : 'ยังเรียนไม่ครบทุกบทเรียน',
                'certificate_status' => $certificateRequest ? $certificateRequest->status : null,
                'certificate_requested_at' => $certificateRequest ? $certificateRequest->requested_at : null,
                'role_data' => $roleData,
            ];
        })->filter(function ($student) {
            // Only include students with a CourseCompletion record
            return CourseCompletion::where('user_id', $student['user']->id)
                ->where('course_id', $this->course->id)
                ->exists();
        })->toArray();
    }

    public function render()
    {
        return view('livewire.instructor.course-completions', [
            'title' => 'รายชื่อผู้ผ่านการอบรม - ' . $this->course->title,
        ]);
    }
}
