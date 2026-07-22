<?php

namespace App\Livewire\Instructor;

use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CoInstructor;
use App\Models\Tag;

class CreateCourse extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $title;
    public $description;
    public $duration;
    public $start_date;
    public $end_date;
    public $start_date_formatted;
    public $end_date_formatted;
    public $certificate;
    public $max_students;
    public $image;
    public $imagePath;
    public array $co_instructors = []; // Now stores names as strings
    public array $co_instructor_positions = [];
    public $canAccess = true;
    public $errorMessage;
    public $instructor_name;
    public $academic_position;
    public $topics = [];
    public $collapsedTopics = [];
    public $collapsedLessons = [];
    public $training_date;
    public $training_date_formatted;
    public $end_training_date; // Add this
    public $end_training_date_formatted; // Add this
    public $allTags = [];


    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|nullable|string',
        'duration' => 'required|nullable|integer|min:1',
        'start_date' => 'required|date|after_or_equal:today',
        'start_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
        'end_date' => 'required|date|after_or_equal:start_date',
        'end_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
        'training_date' => 'required|date|after:end_date', // อัปเดต: ต้องหลัง end_date
        'training_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
        'end_training_date' => 'required|date|after_or_equal:training_date', // Add validation
        'end_training_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/', // Add validation
        'certificate' => 'required|in:0,1',
        'max_students' => 'required|nullable|integer|min:1',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'co_instructors' => 'array',
        'co_instructors.*' => 'nullable|string|max:255', // Changed to string validation
        'co_instructor_positions.*' => 'nullable|string|max:255',
        'instructor_name' => 'required|string|max:255',
        'academic_position' => 'nullable|string|max:255',
        'topics.*.title' => 'nullable|string|max:255',
        'topics.*.lessons.*.title' => 'nullable|string|max:255',
        'topics.*.lessons.*.content' => 'nullable|string',
        'topics.*.lessons.*.learning_outcomes.*.description' => 'nullable|string|max:255', // เปลี่ยน
        'topics.*.lessons.*.learning_outcomes.*.tag_id' => 'nullable|exists:tags,id', // เพิ่ม Rule สำหรับ tag_id
    ];

    public function updatedImage()
    {
        if ($this->image) {
            if ($this->imagePath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($this->imagePath);
            }
            $this->imagePath = $this->image->store('course', 'public');
        }
    }

    public function getCertificateBooleanProperty()
    {
        return $this->certificate == '1'; // แปลง "1" เป็น true, "0" เป็น false
    }

    public function mount()
    {
        if (!Auth::check()) {
            session()->flash('error', 'คุณยังไม่ได้เข้าสู่ระบบ กรุณาเข้าสู่ระบบ');
            return redirect()->route('login');
        } elseif (Auth::user()->role !== 'instructor') {
            session()->flash('error', 'เฉพาะวิทยากรเท่านั้นที่สามารถสร้างคอร์สได้');
            return redirect()->route('dashboard');
        }

        $currentInstructor = Instructor::where('user_id', Auth::id())->with('user')->first();
        if ($currentInstructor) {
            $this->instructor_name = $currentInstructor->user->name;
            $this->academic_position = $currentInstructor->academic_position ?? '';
        }

        $this->topics = [
            [
                'title' => '',
                'lessons' => [
                    [
                        'title' => '',
                        'content' => '',
                        'learning_outcomes' => [ // เปลี่ยนจาก array_fill
                            ['description' => '', 'tag_id' => ''],
                            ['description' => '', 'tag_id' => ''],
                            ['description' => '', 'tag_id' => ''],
                        ],
                    ],
                ],
            ],
        ];

        $this->co_instructors = ['']; // Initialize with one empty co-instructor
        $this->co_instructor_positions = ['']; // Initialize with one empty position
        $this->allTags = Tag::all();
        $this->initializeCollapsedStates();
    }

    private function initializeCollapsedStates($preserveTopicIndices = [])
    {
        // เก็บสถานะเดิมของทุก Topic และ Lessons
        $preservedTopicStates = $this->collapsedTopics;
        $preservedLessonStates = $this->collapsedLessons;

        // รีเซ็ตสถานะทั้งหมด
        $this->collapsedTopics = [];
        $this->collapsedLessons = [];

        // ตั้งค่าเริ่มต้นให้ทุก Topic และ Lesson เป็น collapsed
        foreach ($this->topics as $topicIndex => $topic) {
            // ถ้า Topic นี้อยู่ในรายการที่ต้องการคงสถานะ ให้ใช้สถานะเดิม
            if (in_array($topicIndex, $preserveTopicIndices) && isset($preservedTopicStates[$topicIndex])) {
                $this->collapsedTopics[$topicIndex] = $preservedTopicStates[$topicIndex];
            } else {
                $this->collapsedTopics[$topicIndex] = true; // Topic อื่นๆ เริ่มต้น collapsed
            }

            foreach ($topic['lessons'] as $lessonIndex => $lesson) {
                $key = "$topicIndex-$lessonIndex";
                // ถ้า Lesson อยู่ใน Topic ที่ต้องการคงสถานะ และมีสถานะเดิม ให้ใช้สถานะเดิม
                if (in_array($topicIndex, $preserveTopicIndices) && isset($preservedLessonStates[$key])) {
                    $this->collapsedLessons[$key] = $preservedLessonStates[$key];
                } else {
                    $this->collapsedLessons[$key] = true; // Lesson อื่นๆ เริ่มต้น collapsed
                }
            }
        }
    }

    public function toggleTopic($topicIndex)
    {
        $this->collapsedTopics[$topicIndex] = !$this->collapsedTopics[$topicIndex];
    }

    public function toggleLesson($topicIndex, $lessonIndex)
    {
        $key = "$topicIndex-$lessonIndex";
        $this->collapsedLessons[$key] = !$this->collapsedLessons[$key];
    }

    public function addTopic()
    {
        $this->topics[] = [
            'title' => '',
            'lessons' => [
                [
                    'title' => '',
                    'content' => '',
                    // เปลี่ยนเป็นโครงสร้างข้อมูลที่ถูกต้อง
                    'learning_outcomes' => [
                        ['description' => '', 'tag_id' => ''],
                        ['description' => '', 'tag_id' => ''],
                        ['description' => '', 'tag_id' => ''],
                    ],
                ],
            ],
        ];
        $newTopicIndex = count($this->topics) - 1;
        $this->collapsedTopics[$newTopicIndex] = false;
        $this->collapsedLessons["$newTopicIndex-0"] = false;
    }

    public function addLesson($topicIndex)
    {
        $this->topics[$topicIndex]['lessons'][] = [
            'title' => '',
            'content' => '',
            // เปลี่ยนเป็นโครงสร้างข้อมูลที่ถูกต้อง
            'learning_outcomes' => [
                ['description' => '', 'tag_id' => ''],
                ['description' => '', 'tag_id' => ''],
                ['description' => '', 'tag_id' => ''],
            ],
        ];
        $newLessonIndex = count($this->topics[$topicIndex]['lessons']) - 1;
        $this->collapsedLessons["$topicIndex-$newLessonIndex"] = false;
    }

    public function removeTopic($index)
    {
        unset($this->topics[$index]);
        unset($this->collapsedTopics[$index]);
        foreach ($this->collapsedLessons as $key => $value) {
            if (str_starts_with($key, "$index-")) {
                unset($this->collapsedLessons[$key]);
            }
        }
        $this->topics = array_values($this->topics);

        // สร้างอาร์เรย์ของ Topic ที่ต้องการคงสถานะ (ทุก Topic ที่เหลือ)
        $preserveTopicIndices = array_keys($this->topics);

        // ส่ง $preserveTopicIndices เพื่อให้ initializeCollapsedStates คงสถานะของ Topic และ Lessons ที่เหลือ
        $this->initializeCollapsedStates($preserveTopicIndices);
    }

    public function removeLesson($topicIndex, $lessonIndex)
    {
        // ดึงชื่อ Topic และ Lesson ก่อนลบ
        $topicTitle = $this->topics[$topicIndex]['title'] ?? 'หัวข้อใหม่';
        $lessonTitle = $this->topics[$topicIndex]['lessons'][$lessonIndex]['title'] ?? 'บทเรียนใหม่';

        // ลบ Lesson และปรับสถานะ
        unset($this->topics[$topicIndex]['lessons'][$lessonIndex]);
        unset($this->collapsedLessons["$topicIndex-$lessonIndex"]);
        $this->topics[$topicIndex]['lessons'] = array_values($this->topics[$topicIndex]['lessons']);

        $preserveTopicIndices = array_keys($this->topics);
        $this->initializeCollapsedStates($preserveTopicIndices);
    }

    public function addLearningOutcome($topicIndex, $lessonIndex)
    {
        $newOutcomeIndex = max(array_keys($this->topics[$topicIndex]['lessons'][$lessonIndex]['learning_outcomes'])) + 1;
        $this->topics[$topicIndex]['lessons'][$lessonIndex]['learning_outcomes'][] = [
            'description' => '',
            'tag_id' => ''
        ];
        $this->dispatch('focusLearningOutcome', [
            'topicIndex' => $topicIndex,
            'lessonIndex' => $lessonIndex,
            'outcomeIndex' => $newOutcomeIndex
        ]);
    }

    public function removeLearningOutcome($topicIndex, $lessonIndex, $outcomeIndex)
    {
        // 1. ดึงข้อมูล learning outcome ที่เป็น array ออกมา
        $learningOutcomeData = $this->topics[$topicIndex]['lessons'][$lessonIndex]['learning_outcomes'][$outcomeIndex] ?? null;

        // 2. ดึงเฉพาะ description มาใช้ในข้อความ ถ้าไม่มีให้ใช้ค่า default
        $description = !empty($learningOutcomeData['description']) ? $learningOutcomeData['description'] : 'ผลลัพธ์การเรียนรู้ใหม่';

        $topicTitle = $this->topics[$topicIndex]['title'] ?? 'หัวข้อใหม่';

        unset($this->topics[$topicIndex]['lessons'][$lessonIndex]['learning_outcomes'][$outcomeIndex]);
    }

    public function addCoInstructor()
    {
        $this->co_instructors[] = '';
        $this->co_instructor_positions[] = ''; // Add empty position

    }

    public function removeCoInstructor($index)
    {
        unset($this->co_instructors[$index]);
        unset($this->co_instructor_positions[$index]); // Remove corresponding position
        $this->co_instructors = array_values($this->co_instructors);
        $this->co_instructor_positions = array_values($this->co_instructor_positions);
    }

    private function emitPikadayUpdate()
    {
        $this->dispatch('updatePikaday', [
            'start_date' => $this->start_date,
            'start_date_formatted' => $this->start_date_formatted,
            'end_date' => $this->end_date,
            'end_date_formatted' => $this->end_date_formatted,
            'training_date' => $this->training_date,
            'training_date_formatted' => $this->training_date_formatted,
            'end_training_date' => $this->end_training_date, // Add this
            'end_training_date_formatted' => $this->end_training_date_formatted, // Add this
        ]);
    }

    public function updatedStartDateFormatted($value)
    {
        if ($value) {
            try {
                $this->start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
                if ($this->end_date && $this->end_date < $this->start_date) {
                    $this->end_date = null;
                    $this->end_date_formatted = '';
                }
            } catch (\Exception $e) {
                $this->start_date = null;
                $this->addError('start_date_formatted', 'รูปแบบวันที่ไม่ถูกต้อง');
            }
        } else {
            $this->start_date = null;
        }
        $this->validateOnly('start_date');
        $this->emitPikadayUpdate();
    }

    public function updatedEndDateFormatted($value)
    {
        if ($value) {
            try {
                $this->end_date = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            } catch (\Exception $e) {
                $this->end_date = null;
                $this->addError('end_date_formatted', 'รูปแบบวันที่ไม่ถูกต้อง');
            }
        } else {
            $this->end_date = null;
        }
        $this->validateOnly('end_date');
        $this->emitPikadayUpdate();
    }

    public function updatedTrainingDateFormatted($value)
    {
        if ($value) {
            try {
                $this->training_date = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
                // ตรวจสอบว่า training_date อยู่หลัง end_date
                if ($this->end_date && $this->training_date <= $this->end_date) {
                    $this->training_date = null;
                    $this->training_date_formatted = '';
                    $this->addError('training_date_formatted', 'วันที่อบรมต้องเป็นวันที่หลังวันที่ปิดรับสมัคร');
                }
            } catch (\Exception $e) {
                $this->training_date = null;
                $this->addError('training_date_formatted', 'รูปแบบวันที่ไม่ถูกต้อง');
            }
        } else {
            $this->training_date = null;
        }
        $this->validateOnly('training_date');
        $this->emitPikadayUpdate();
    }

    public function updatedEndTrainingDateFormatted($value)
    {
        if ($value) {
            try {
                $this->end_training_date = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
                // Ensure end_training_date is after or equal to training_date
                if ($this->training_date && $this->end_training_date < $this->training_date) {
                    $this->end_training_date = null;
                    $this->end_training_date_formatted = '';
                    $this->addError('end_training_date_formatted', 'วันที่สิ้นสุดการอบรมต้องเป็นวันที่หลังหรือเท่ากับวันที่อบรม');
                }
            } catch (\Exception $e) {
                $this->end_training_date = null;
                $this->addError('end_training_date_formatted', 'รูปแบบวันที่ไม่ถูกต้อง');
            }
        } else {
            $this->end_training_date = null;
        }
        $this->validateOnly('end_training_date');
        $this->emitPikadayUpdate();
    }

    public function nextStep()
    {
        try {
            $this->validateStep();
            $this->step++;
            $this->resetValidation();
            $this->dispatch('stepChanged');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->emitPikadayUpdate();
            throw $e;
        }
    }

    public function prevStep()
    {
        $this->step--;
        $this->emitPikadayUpdate();
        $this->resetValidation();
        $this->dispatch('stepChanged');
    }


    private function validateStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|nullable|string',
                'duration' => 'required|nullable|integer|min:1',
                'start_date' => 'required|date|after_or_equal:today',
                'start_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
                'end_date' => 'required|date|after_or_equal:start_date',
                'end_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
                'training_date' => 'required|date|after:end_date', // อัปเดต: ต้องหลัง end_date
                'training_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
                'end_training_date' => 'required|date|after_or_equal:training_date', // Add validation
                'end_training_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/', // Add validation
                'certificate' => 'required|in:0,1',
                'max_students' => 'required|nullable|integer|min:1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'instructor_name' => 'required|string|max:255',
                'academic_position' => 'nullable|string|max:255',
                'co_instructors.*' => 'nullable|string|max:255',
                'co_instructor_positions.*' => 'nullable|string|max:255',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'topics.*.title' => 'nullable|string|max:255',
                'topics.*.lessons.*.title' => 'nullable|string|max:255',
                'topics.*.lessons.*.content' => 'nullable|string',
                'topics.*.lessons.*.learning_outcomes.*.description' => 'nullable|string|max:255', // แก้ไข
                'topics.*.lessons.*.learning_outcomes.*.tag_id' => 'nullable|exists:tags,id',       // เพิ่ม
            ]);
        }
    }

    public function create()
    {
        $this->validate();

        $course = Course::create([
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'training_date' => $this->training_date,
            'end_training_date' => $this->end_training_date, // Add this
            'certificate' => $this->certificate == '1',
            'max_students' => $this->max_students,
            'image' => $this->imagePath,
        ]);

        $currentInstructor = Instructor::where('user_id', Auth::id())->first();
        $course->instructors()->attach($currentInstructor->id, ['is_owner' => true]);

        if (!empty($this->co_instructors)) {
            foreach (array_filter($this->co_instructors) as $index => $co_instructor_name) {
                if (!empty($co_instructor_name)) {
                    CoInstructor::create([
                        'course_id' => $course->id,
                        'instructor_id' => $currentInstructor->id, // Still linked to the current instructor
                        'name' => $co_instructor_name,
                        'position' => $this->co_instructor_positions[$index] ?? null,
                    ]);
                }
            }
        }

        foreach ($this->topics as $topicData) {
            $topic = $course->topics()->create([
                'title' => $topicData['title'],
            ]);

            foreach ($topicData['lessons'] as $lessonData) {
                $lesson = $topic->lessons()->create([
                    'title' => $lessonData['title'],
                    'content' => $lessonData['content'],
                    'course_id' => $course->id,
                ]);

                if (isset($lessonData['learning_outcomes'])) {
                    foreach ($lessonData['learning_outcomes'] as $outcome) {
                        if (!empty($outcome['description'])) { // เช็คว่า description ไม่ว่าง
                            $lesson->learningOutcomes()->create([
                                'description' => $outcome['description'],
                                'tag_id' => $outcome['tag_id'] ?: null, // ถ้า tag_id เป็นค่าว่างให้บันทึกเป็น null
                            ]);
                        }
                    }
                }
            }
        }

        session()->flash('createCourse', 'สร้างคอร์สสำเร็จ!');
        $this->reset([
            'title',
            'description',
            'duration',
            'start_date',
            'end_date',
            'start_date_formatted',
            'end_date_formatted',
            'training_date',
            'training_date_formatted',
            'certificate',
            'max_students',
            'image',
            'co_instructors',
            'co_instructor_positions',
            'topics',
        ]);
        $this->imagePath = null; // รีเซ็ต $imagePath ด้วย
        $this->topics = [
            [
                'title' => '',
                'lessons' => [
                    [
                        'title' => '',
                        'content' => '',
                        'learning_outcomes' => array_fill(0, 3, ''),
                    ],
                ],
            ],
        ];
        $this->co_instructors = ['']; // Re-initialize
        $this->co_instructor_positions = ['']; // Re-initialize
        $this->step = 1;
        $this->initializeCollapsedStates();
        $this->dispatch('course-created');
    }

    public function render()
    {
        return view('livewire.instructor.create-course');
    }
}
