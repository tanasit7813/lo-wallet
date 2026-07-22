<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Certificate; // Import Certificate model
use Illuminate\Support\Facades\DB;

class CourseDetail extends Component
{
    public $course;
    public $isEnrolled = false;
    public $isFull = false;
    public $instructorName;
    public $academicPosition;
    public $enrollmentStatus;
    public $hasCertificate = false; // For pending certificate
    public $hasApprovedCertificate = false; // For approved certificate
    public $coInstructors = [];

    public function mount($id)
    {
        $this->course = Course::with([
            'instructors.user',
            'coInstructors',
            'topics.lessons.learningOutcomes.tag' // เพิ่ม .tag เข้าไป
        ])->findOrFail($id);

        $firstInstructor = $this->course->instructors->first();
        $this->instructorName = $firstInstructor ? $firstInstructor->user->name : 'ไม่ระบุ';
        $this->academicPosition = $firstInstructor ? $firstInstructor->academic_position : 'ไม่ระบุ';

        // Set co-instructors details
        $this->coInstructors = $this->course->coInstructors->map(function ($coInstructor) {
            return [
                'name' => $coInstructor->name ?: 'ไม่ระบุ',
                'position' => $coInstructor->position ?: 'ไม่มีตำแหน่ง',
            ];
        })->toArray();

        $this->checkEnrollmentStatus();
    }

    protected function checkEnrollmentStatus()
    {
        if (Auth::check()) {
            // Allow student, general, and insider roles
            $allowedRoles = ['student', 'general', 'insider'];
            if (!in_array(Auth::user()->role, $allowedRoles)) {
                session()->flash('error', 'คุณไม่มีสิทธิ์ลงทะเบียนในคอร์สนี้');
                return;
            }

            $enrollment = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $this->course->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            $this->isEnrolled = !is_null($enrollment);
            $this->enrollmentStatus = $enrollment ? $enrollment->status : null;

            // Check if a pending certificate exists for the user (for allowed roles)
            if ($this->isEnrolled && in_array(Auth::user()->role, $allowedRoles) && $this->enrollmentStatus === 'confirmed') {
                $this->hasCertificate = Certificate::where('user_id', Auth::id())
                    ->where('course_id', $this->course->id)
                    ->where('status', 'pending')
                    ->exists();

                // Check if an approved certificate exists for the user
                $this->hasApprovedCertificate = Certificate::where('user_id', Auth::id())
                    ->where('course_id', $this->course->id)
                    ->where('status', 'approved')
                    ->exists();
            }

            // Check if the course is full
            $currentEnrollments = $this->course->enrollments()->whereIn('status', ['pending', 'confirmed'])->count();
            $this->isFull = $currentEnrollments >= $this->course->max_students;
        }
    }

    public function enroll()
    {
        // Restrict enrollment to allowed roles
        $allowedRoles = ['student', 'general', 'insider'];
        if (!Auth::check() || !in_array(Auth::user()->role, $allowedRoles)) {
            session()->flash('error', 'คุณไม่มีสิทธิ์ลงทะเบียนในคอร์สนี้');
            return;
        }

        if ($this->isFull) {
            session()->flash('error', 'ขออภัย คอร์สเต็มแล้ว!');
            return;
        }

        if ($this->isEnrolled) {
            session()->flash('info', 'คุณได้ลงทะเบียนในคอร์สนี้แล้ว!');
            return;
        }

        DB::beginTransaction();
        try {
            $currentEnrollments = $this->course->enrollments()->whereIn('status', ['pending', 'confirmed'])->count();
            if ($currentEnrollments >= $this->course->max_students) {
                DB::rollBack();
                $this->isFull = true;
                session()->flash('error', 'ขออภัย คอร์สเต็มแล้ว!');
                return;
            }

            Enrollment::create([
                'user_id' => Auth::id(),
                'course_id' => $this->course->id,
                'status' => 'pending',
            ]);

            DB::commit();
            $this->isEnrolled = true;
            $this->enrollmentStatus = 'pending';
            session()->flash('success', "คุณได้สมัครคอร์สเรียน {$this->course->title} เรียบร้อยแล้ว รอการยืนยันจากวิทยากร");
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.course-detail', [
            'course' => $this->course,
            'instructorName' => $this->instructorName,
            'academicPosition' => $this->academicPosition,
            'enrollmentStatus' => $this->enrollmentStatus,
            'coInstructors' => $this->coInstructors,
            'hasCertificate' => $this->hasCertificate,
            'hasApprovedCertificate' => $this->hasApprovedCertificate, // Pass new variable
        ]);
    }
}
