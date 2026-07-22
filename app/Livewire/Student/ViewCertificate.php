<?php

namespace App\Livewire\Student;

use App\Models\Certificate;
use App\Models\Director;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Browsershot\Browsershot; // เพิ่ม use statement
use Illuminate\Support\Str;

class ViewCertificate extends Component
{
    public $certificate;
    public $course;
    public $student;
    public $logoOption = '';
    public $director;

    public function mount($id)
    {
        // ตรวจสอบบทบาท student, general, หรือ insider
        $allowedRoles = ['student', 'general', 'insider'];
        if (!in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $this->certificate = Certificate::with('course')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($this->certificate->status !== 'approved' || !$this->certificate->course) {
            abort(403, 'ใบ Certificate นี้ยังไม่ได้รับการอนุมัติหรือไม่มีข้อมูลคอร์ส');
        }

        $this->course = $this->certificate->course;
        $this->student = Auth::user()->student;

        $this->director = Director::first();
    }

    public function updatedLogoOption($value)
    {
        $this->logoOption = $value;
    }

    public function downloadCertificate()
    {
        if (empty($this->logoOption)) {
            return;
        }

        $data = [
            'certificate' => $this->certificate,
            'director' => $this->director,
            'logoOption' => $this->logoOption,
            'userName' => Auth::user()->name,
        ];

        // STEP 1: สร้าง HTML ของ Certificate ที่ซับซ้อน
        $certificateHtml = view('pdf.certificate', $data)->render();

        // STEP 2: แปลง HTML นั้นให้เป็นไฟล์รูปภาพชั่วคราว (Flattening)
        $tempImageName = 'cert-' . uniqid() . '.png';
        $tempImagePath = storage_path('app/temp/' . $tempImageName);

        // สร้าง directory ถ้ายังไม่มี
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        Browsershot::html($certificateHtml)
            ->setNodeBinary(config('app.node_binary_path'))
            ->setNpmBinary(config('app.npm_binary_path'))
            ->waitUntilNetworkIdle()
            ->windowSize(1200, 900) // กำหนดขนาดหน้าต่างให้เหมาะสมกับอัตราส่วน 4:3
            ->save($tempImagePath);

        // STEP 3: สร้าง HTML ใหม่ที่แสดงแค่รูปภาพที่เราเพิ่งสร้าง
        $imageWrapperHtml = view('pdf.image-wrapper', [
            'imagePath' => 'data:image/png;base64,' . base64_encode(file_get_contents($tempImagePath))
        ])->render();

        $pdf = Browsershot::html($imageWrapperHtml)
            ->setNodeBinary(config('app.node_binary_path'))
            ->setNpmBinary(config('app.npm_binary_path'))
            ->paperSize(297, 210, 'mm') // กำหนดขนาด A4 Landscape โดยตรง (กว้าง 297mm, สูง 210mm)
            ->pdf();

        // STEP 5: ลบไฟล์รูปภาพชั่วคราวทิ้ง
        unlink($tempImagePath);

        // สร้าง Prefix สำหรับชื่อไฟล์ตาม Option ที่เลือก
        $optionPrefix = match ($this->logoOption) {
            'with_logo'    => 'with-logo',
            'without_logo' => 'pkru-logo',
            default        => 'general', // กำหนดค่า default เผื่อกรณีที่ไม่ตรงเงื่อนไข
        };

        // สร้างชื่อไฟล์โดยรวม Prefix เข้าไปด้วย
        $fileName = 'Certificate-' . $optionPrefix . '-' . Str::slug($this->course->title) . '-' . Auth::user()->name . '.pdf';

        // ส่ง response ให้ browser ดาวน์โหลดไฟล์
        return response()->streamDownload(
            fn() => print($pdf),
            $fileName
        );
    }

    public function render()
    {
        return view('livewire.student.view-certificate', [
            'title' => 'ใบ Certificate - ' . $this->course->title,
            'logoOption' => $this->logoOption,
            'director' => $this->director,
        ]);
    }
}
