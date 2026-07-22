<?php

namespace App\Livewire\General;

use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewCertificate extends Component
{
    public $certificate;
    public $course;
    public $general;

    public function mount($id)
    {
        if (Auth::user()->role !== 'general') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // Load the certificate
        $this->certificate = Certificate::with('course')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Ensure the certificate is approved
        if ($this->certificate->status !== 'approved') {
            abort(403, 'ใบ Certificate นี้ยังไม่ได้รับการอนุมัติ');
        }

        $this->course = $this->certificate->course;
        $this->general = Auth::user()->general;
    }

    public function render()
    {
        return view('livewire.general.view-certificate', [
            'title' => 'ใบ Certificate - ' . ($this->course->title ?? 'ไม่ระบุ'),
            'general' => $this->general,
        ]);
    }
}
