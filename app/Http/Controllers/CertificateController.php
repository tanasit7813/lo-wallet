<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Director;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showPdf($certificateId, $logoOption)
    {
        // ตรวจสอบบทบาท student, general, หรือ insider
        $allowedRoles = ['student', 'general', 'insider'];
        if (!in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // ตรวจสอบว่า logoOption ถูกต้อง
        if (!in_array($logoOption, ['with_logo', 'without_logo'])) {
            abort(404, 'Invalid logo option');
        }

        // ดึง certificate พร้อม course
        $certificate = Certificate::with('course')
            ->where('id', $certificateId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // ตรวจสอบสถานะและ course
        if ($certificate->status !== 'approved' || !$certificate->course) {
            abort(403, 'ใบ Certificate นี้ยังไม่ได้รับการอนุมัติหรือไม่มีข้อมูลคอร์ส');
        }

        // ประมวลผล course title
        $courseTitle = $certificate->course->title ?? 'ไม่ระบุ';
        if (preg_match('/^(.*?)\s*\(([^)]+)\)$/', $courseTitle, $matches)) {
            $mainTitle = trim($matches[1]);
            $subTitle = trim($matches[2]);
        } else {
            $mainTitle = $courseTitle;
            $subTitle = '';
        }

        // ดึงข้อมูล Director
        $director = Director::first(); // ใช้การดึงข้อมูลแบบเดียวกับ ViewCertificate.php
        $directorName = $director ? $director->name : 'ไม่ระบุ';
        $directorPosition = '';

        if ($director) {
            if (preg_match('/^(.*?)\s*\(([^)]+)\)$/', $director->name, $matches)) {
                $directorName = trim($matches[1]);
                $directorPosition = trim($matches[2]);
            } elseif ($director->position) {
                $directorPosition = $director->position;
            }
        }

        $data = [
            'name' => Auth::user()->name ?? 'ไม่ระบุ',
            'mainTitle' => $mainTitle,
            'subTitle' => $subTitle,
            'logoOption' => $logoOption,
            'certificate' => $certificate,
            'director' => $director,
            'directorName' => $directorName,
            'directorPosition' => $directorPosition,
        ];

        return view('pdf.certificate', $data);
    }
}
