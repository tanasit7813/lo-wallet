<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseCompletion; // <-- เพิ่ม Model นี้
use App\Models\Course;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $tagCounts = [];
        $tags = collect(); // ใช้ collection ว่างเป็นค่าเริ่มต้น

        // --- ตัวแปรสำหรับ Instructor และ Officer ---
        $instructorCourses = [];
        $availableCourses = [];
        $fullCourses = [];

        // --- ตัวแปรสำหรับ Chart.js ---
        $chartLabels = [];
        $chartData = [];
        $totalTags = 0; // เพิ่มตัวแปรผลรวม
        $legendItems = [];

        // --- 🎯 1. เพิ่มตัวแปรใหม่สำหรับเก็บคอร์ส ---
        $tagCourses = [];

        // Logic สำหรับ Student, General, Insider
        if (in_array($user->role, ['student', 'general', 'insider'])) {
            $completedCourses = CourseCompletion::with(['course.lessons.learningOutcomes.tag'])
                ->where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->get();

            // --- 🎯 2. เตรียม Array ตั้งต้นสำหรับ $tagCourses ---
            $tagCourses = [
                'Remembering' => [],
                'Understanding' => [],
                'Applying' => [],
                'Analyzing' => [],
                'Evaluating' => [],
                'Creating' => [],
            ];

            $tagsWithCourses = $completedCourses->flatMap(function ($completion) {
                $courseTitle = $completion->course->title;
                return $completion->course->lessons->flatMap(function ($lesson) use ($courseTitle) {
                    return $lesson->learningOutcomes->map(function ($outcome) use ($courseTitle) {
                        if ($outcome->tag) {
                            return ['tag_name' => $outcome->tag->name, 'course_title' => $courseTitle];
                        }
                        return null;
                    });
                });
            })->filter();

            $tagCounts = [
                'Remembering' => 0,
                'Understanding' => 0,
                'Applying' => 0,
                'Analyzing' => 0,
                'Evaluating' => 0,
                'Creating' => 0,
            ];

            // --- 🎯 3. แก้ไข Loop เพื่อเก็บข้อมูลทั้งจำนวนและชื่อคอร์ส ---
            foreach ($tagsWithCourses as $item) {
                $tagName = $item['tag_name'];
                $courseTitle = $item['course_title'];

                if (array_key_exists($tagName, $tagCounts)) {
                    $tagCounts[$tagName]++;
                    // เพิ่มชื่อคอร์สเข้าไปใน Array โดยไม่ให้ซ้ำ
                    if (!in_array($courseTitle, $tagCourses[$tagName])) {
                        $tagCourses[$tagName][] = $courseTitle;
                    }
                }
            }

            // --- ส่วนที่เพิ่มเข้ามา: แปลงข้อมูลสำหรับกราฟ ---
            $thaiLabels = [
                'Remembering' => 'การจดจำ',
                'Understanding' => 'การทำความเข้าใจ',
                'Applying' => 'การประยุกต์',
                'Analyzing' => 'การวิเคราะห์',
                'Evaluating' => 'การประเมิน',
                'Creating' => 'การสร้างสรรค์',
            ];

            // --- ส่วนที่เพิ่มเข้ามา: สร้างข้อมูลสำหรับ Legend HTML ---
            $legendItems = [
                ['label' => 'การจดจำ',        'color' => '#38bdf8'],
                ['label' => 'การทำความเข้าใจ',  'color' => '#10b981'],
                ['label' => 'การประยุกต์',      'color' => '#f59e0b'],
                ['label' => 'การวิเคราะห์',     'color' => '#e11d48'],
                ['label' => 'การประเมิน',      'color' => '#6366f1'],
                ['label' => 'การสร้างสรรค์',    'color' => '#a855f7'],
            ];

            foreach ($tagCounts as $name => $count) {
                if ($count > 0) { // เพิ่มเงื่อนไขให้แสดงเฉพาะทักษะที่มีค่ามากกว่า 0
                    $chartLabels[] = $thaiLabels[$name];
                    $chartData[] = $count;
                }
            }
            $totalTags = array_sum($chartData); // นับผลรวมของทักษะทั้งหมด
        }

        // --- ส่วนที่แก้ไข: เพิ่ม Logic สำหรับ Officer ---
        if (in_array($user->role, ['instructor', 'officer'])) {
            $courses = collect(); // สร้าง Collection ว่างๆ ไว้ก่อน

            if ($user->role === 'instructor' && $user->instructor) {
                // ถ้าเป็น Instructor, ดึงเฉพาะคอร์สของตัวเอง
                $courses = $user->instructor->courses()->get();
            } elseif ($user->role === 'officer') {
                // ถ้าเป็น Officer, ดึงทุกคอร์สในระบบ
                $courses = Course::all();
            }

            // Loop ประมวลผลข้อมูลคอร์ส (ใช้ร่วมกันทั้ง Instructor และ Officer)
            foreach ($courses as $course) {
                $enrollmentCount = $course->enrollments()
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->count();

                $courseInfo = [
                    'course' => $course,
                    'enrollment_count' => $enrollmentCount,
                ];

                // แยกการเก็บข้อมูลตาม Role
                if ($user->role === 'instructor') {
                    $instructorCourses[] = $courseInfo;
                } elseif ($user->role === 'officer') {
                    // สำหรับ Officer, ให้จัดกลุ่มคอร์ส
                    if ($enrollmentCount >= $course->max_students) {
                        $fullCourses[] = $courseInfo; // กลุ่มคอร์สที่เต็มแล้ว
                    } else {
                        $availableCourses[] = $courseInfo; // กลุ่มคอร์สที่ยังว่าง
                    }
                }
            }
        }
        // --- จบส่วนที่แก้ไข ---

        return view('livewire.dashboard', [
            'tags' => $tags,
            'tagCounts' => $tagCounts,
            'instructorCourses' => $instructorCourses,
            'availableCourses' => $availableCourses, // เพิ่มตัวแปรนี้เข้าไปด้วย
            'fullCourses' => $fullCourses,         // เพิ่มตัวแปรนี้เข้าไปด้วย
            'chartLabels' => $chartLabels,       // ส่งข้อมูลใหม่
            'chartData' => $chartData,         // ส่งข้อมูลใหม่
            'totalTags' => $totalTags,
            'legendItems' => $legendItems,
            'tagCourses' => $tagCourses, // <-- เพิ่มบรรทัดนี้
        ]);
    }
}
