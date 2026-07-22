<?php

namespace App\Livewire\Officer;

use App\Models\Certificate;
use App\Models\CourseCompletion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CertificateRequests extends Component
{
    public $certificateRequests = [];
    public $selectedCertificateId = null;
    public $denialReason = '';
    public $showDenyModal = false;

    public function mount()
    {
        if (Auth::user()->role !== 'officer') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $this->loadCertificateRequests();
    }

    public function loadCertificateRequests()
    {
        $certificates = Certificate::with([
            'user',
            'user.student.faculty',
            'user.student.branch',
            'user.general',
            'user.insider',
            'course'
        ])->get();

        $this->certificateRequests = $certificates->map(function ($certificate) {
            $user = $certificate->user;
            $course = $certificate->course;

            $courseCompletion = CourseCompletion::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();
            $isCompleted = $courseCompletion && !is_null($courseCompletion->completed_at);

            $insiderRoleMap = [
                'academic' => 'บุคลากรสายวิชาการ',
                'teaching' => 'บุคลากรสายการสอน',
            ];

            $insiderRole = '-';
            if ($user->role === 'insider' && $user->insider) {
                $rawInsiderRole = $user->insider->insider_role ?? 'ไม่ระบุ';
                $insiderRole = $insiderRoleMap[$rawInsiderRole] ?? 'ไม่ระบุ';
            }

            $roleData = [
                'role' => $user->role,
                'faculty' => $user->role === 'student' && $user->student && $user->student->faculty ? $user->student->faculty->name : '-',
                'branch' => $user->role === 'student' && $user->student && $user->student->branch ? $user->student->branch->name : '-',
                'position' => $user->role === 'general' && $user->general ? $user->general->position ?? '-' : '-',
                'agency' => $user->role === 'general' && $user->general ? $user->general->agency ?? '-' : '-',
                'insider_role' => $insiderRole,
            ];

            return [
                'id' => $certificate->id,
                'user' => $user,
                'course' => $course,
                'status' => $certificate->status,
                'requested_at' => $certificate->requested_at,
                'denial_reason' => $certificate->denial_reason,
                'course_completion_status' => $isCompleted ? 'completed' : 'in_progress',
                'role_data' => $roleData,
            ];
        })->toArray();
    }

    public function approve($certificateId)
    {
        $certificate = Certificate::findOrFail($certificateId);
        $certificate->update([
            'status' => 'approved',
            'denial_reason' => null,
        ]);

        session()->flash('success', 'อนุมัติคำขอใบ Certificate เรียบร้อยแล้ว');
        $this->loadCertificateRequests();
    }

    public function openDenyModal($certificateId)
    {
        $this->selectedCertificateId = $certificateId;
        $this->denialReason = '';
        $this->showDenyModal = true;
    }

    public function deny()
    {
        $this->validate([
            'denialReason' => 'required|string|min:5',
        ], [
            'denialReason.required' => 'กรุณาระบุเหตุผลในการปฏิเสธ',
            'denialReason.min' => 'เหตุผลต้องมีความยาวอย่างน้อย 5 ตัวอักษร',
        ]);

        $certificate = Certificate::findOrFail($this->selectedCertificateId);
        $certificate->update([
            'status' => 'rejected',
            'denial_reason' => $this->denialReason,
        ]);

        $this->showDenyModal = false;
        $this->selectedCertificateId = null;
        $this->denialReason = '';
        session()->flash('success', 'ปฏิเสธคำขอใบ Certificate เรียบร้อยแล้ว');
        $this->loadCertificateRequests();
    }

    public function render()
    {
        return view('livewire.officer.certificate-requests', [
            'title' => 'จัดการคำขอใบ Certificate',
        ]);
    }
}
