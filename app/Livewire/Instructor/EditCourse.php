<?php

namespace App\Livewire\Instructor;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Topic;
use App\Models\Lesson;
use App\Models\LearningOutcome;
use App\Models\CoInstructor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tag;

class EditCourse extends Component
{
    use WithFileUploads;

    public $courseId;
    public $step = 1;
    public $title;
    public $description;
    public $duration;
    public $start_date;
    public $end_date;
    public $start_date_formatted;
    public $end_date_formatted;
    public $training_date;
    public $training_date_formatted;
    public $end_training_date;
    public $end_training_date_formatted;
    public $certificate;
    public $max_students;
    public $image;
    public $imagePath;
    public array $co_instructors = [];
    public array $co_instructor_positions = [];
    public $canAccess = true;
    public $errorMessage;
    public $instructor_name;
    public $academic_position;
    public $topics = [];
    public $collapsedTopics = [];
    public $collapsedLessons = [];
    public $allTags = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|nullable|string',
        'duration' => 'required|nullable|integer|min:1',
        'start_date' => 'required|date',
        'start_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
        'end_date' => 'required|date|after_or_equal:start_date',
        'end_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
        'training_date' => 'required|date|after:end_date',
        'training_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
        'end_training_date' => 'required|date|after_or_equal:training_date',
        'end_training_date_formatted' => 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/',
        'certificate' => 'required|in:0,1',
        'max_students' => 'required|nullable|integer|min:1',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'co_instructors' => 'array',
        'co_instructors.*' => 'nullable|string|max:255',
        'co_instructor_positions.*' => 'nullable|string|max:255',
        'instructor_name' => 'required|string|max:255',
        'academic_position' => 'nullable|string|max:255',
        'topics.*.title' => 'nullable|string|max:255',
        'topics.*.lessons.*.title' => 'nullable|string|max:255',
        'topics.*.lessons.*.content' => 'nullable|string',
        'topics.*.lessons.*.learning_outcomes.*.description' => 'nullable|string|max:255',
        'topics.*.lessons.*.learning_outcomes.*.tag_id' => 'nullable|exists:tags,id',
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
        return $this->certificate == '1';
    }

    public function mount($id)
    {
        $this->courseId = $id;
        $this->allTags = Tag::all();

        if (!Auth::check()) {
            session()->flash('error', 'คุณยังไม่ได้เข้าสู่ระบบ กรุณาเข้าสู่ระบบ');
            return redirect()->route('login');
        } elseif (Auth::user()->role !== 'instructor') {
            session()->flash('error', 'เฉพาะวิทยากรเท่านั้นที่สามารถแก้ไขคอร์สได้');
            return redirect()->route('dashboard');
        }

        $course = Course::with(['topics.lessons.learningOutcomes', 'coInstructors'])->findOrFail($id);
        $currentInstructor = Instructor::where('user_id', Auth::id())->first();

        if (!$course->instructors()->where('instructor_id', $currentInstructor->id)->exists()) {
            session()->flash('error', 'คุณไม่มีสิทธิ์แก้ไขคอร์สนี้');
            return redirect()->route('dashboard');
        }

        $this->title = $course->title;
        $this->description = $course->description;
        $this->duration = $course->duration;
        $this->certificate = $course->certificate ? '1' : '0';
        $this->max_students = $course->max_students;
        $this->imagePath = $course->image;
        $this->instructor_name = $currentInstructor->user->name;
        $this->academic_position = $currentInstructor->academic_position ?? '';
        $this->co_instructors = $course->coInstructors->pluck('name')->toArray();
        $this->co_instructor_positions = $course->coInstructors->pluck('position')->toArray();

        // Initialize date fields
        try {
            $this->start_date = $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('Y-m-d') : null;
            $this->start_date_formatted = $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d/m/Y') : '';
            $this->end_date = $course->end_date ? \Carbon\Carbon::parse($course->end_date)->format('Y-m-d') : null;
            $this->end_date_formatted = $course->end_date ? \Carbon\Carbon::parse($course->end_date)->format('d/m/Y') : '';
            $this->training_date = $course->training_date ? \Carbon\Carbon::parse($course->training_date)->format('Y-m-d') : null;
            $this->training_date_formatted = $course->training_date ? \Carbon\Carbon::parse($course->training_date)->format('d/m/Y') : '';
            $this->end_training_date = $course->end_training_date ? \Carbon\Carbon::parse($course->end_training_date)->format('Y-m-d') : null;
            $this->end_training_date_formatted = $course->end_training_date ? \Carbon\Carbon::parse($course->end_training_date)->format('d/m/Y') : '';
        } catch (\Exception $e) {
            $this->start_date = null;
            $this->start_date_formatted = '';
            $this->end_date = null;
            $this->end_date_formatted = '';
            $this->training_date = null;
            $this->training_date_formatted = '';
            $this->end_training_date = null;
            $this->end_training_date_formatted = '';
            session()->flash('error', 'เกิดข้อผิดพลาดในการโหลดวันที่ของคอร์ส');
        }

        $this->topics = $course->topics->map(function ($topic) {
            return [
                'id' => $topic->id,
                'title' => $topic->title,
                'lessons' => $topic->lessons->map(function ($lesson) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'content' => $lesson->content,
                        // เปลี่ยนจาก pluck('description')
                        // 'learning_outcomes' => $lesson->learningOutcomes->pluck('description')->toArray(),
                        // เป็นการ map เพื่อสร้าง structure ใหม่ให้ถูกต้อง
                        'learning_outcomes' => $lesson->learningOutcomes->map(function ($outcome) {
                            return [
                                'description' => $outcome->description,
                                'tag_id' => $outcome->tag_id // ดึง tag_id มาด้วย
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
            ];
        })->toArray();

        if (empty($this->topics)) {
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
        }

        if (empty($this->co_instructors)) {
            $this->co_instructors = [''];
            $this->co_instructor_positions = [''];
        }

        $this->initializeCollapsedStates();

        // Emit Pikaday update to sync with frontend
        $this->emitPikadayUpdate();

        // Validate the first step on mount to enable the 'Next' button
        // ตรวจสอบความถูกต้องของ step 1 ทันทีที่โหลดหน้า เพื่อให้ปุ่ม 'ถัดไป' สามารถกดได้
        $this->validate();
    }

    private function initializeCollapsedStates($preserveTopicIndices = [])
    {
        $preservedTopicStates = $this->collapsedTopics;
        $preservedLessonStates = $this->collapsedLessons;

        $this->collapsedTopics = [];
        $this->collapsedLessons = [];

        foreach ($this->topics as $topicIndex => $topic) {
            if (in_array($topicIndex, $preserveTopicIndices) && isset($preservedTopicStates[$topicIndex])) {
                $this->collapsedTopics[$topicIndex] = $preservedTopicStates[$topicIndex];
            } else {
                $this->collapsedTopics[$topicIndex] = true;
            }

            foreach ($topic['lessons'] as $lessonIndex => $lesson) {
                $key = "$topicIndex-$lessonIndex";
                if (in_array($topicIndex, $preserveTopicIndices) && isset($preservedLessonStates[$key])) {
                    $this->collapsedLessons[$key] = $preservedLessonStates[$key];
                } else {
                    $this->collapsedLessons[$key] = true;
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

        $preserveTopicIndices = array_keys($this->topics);
        $this->initializeCollapsedStates($preserveTopicIndices);
    }

    public function removeLesson($topicIndex, $lessonIndex)
    {
        $topicTitle = $this->topics[$topicIndex]['title'] ?? 'หัวข้อใหม่';
        $lessonTitle = $this->topics[$topicIndex]['lessons'][$lessonIndex]['title'] ?? 'บทเรียนใหม่';

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
        $learningOutcome = $this->topics[$topicIndex]['lessons'][$lessonIndex]['learning_outcomes'][$outcomeIndex] ?? 'ผลลัพธ์การเรียนรู้ใหม่';
        $topicTitle = $this->topics[$topicIndex]['title'] ?? 'หัวข้อใหม่';

        unset($this->topics[$topicIndex]['lessons'][$lessonIndex]['learning_outcomes'][$outcomeIndex]);
    }

    public function addCoInstructor()
    {
        $this->co_instructors[] = '';
        $this->co_instructor_positions[] = '';
    }

    public function removeCoInstructor($index)
    {
        unset($this->co_instructors[$index]);
        unset($this->co_instructor_positions[$index]);
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
            'end_training_date' => $this->end_training_date,
            'end_training_date_formatted' => $this->end_training_date_formatted,
        ]);
    }

    public function updatedStartDateFormatted($value)
    {
        if ($value) {
            try {
                $parsedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $value);
                $this->start_date = $parsedDate->format('Y-m-d');
                // Only clear dependent dates if they violate the new start_date
                if ($this->end_date && $this->end_date < $this->start_date) {
                    $this->end_date = null;
                    $this->end_date_formatted = '';
                }
                if ($this->training_date && $this->training_date <= $this->end_date) {
                    $this->training_date = null;
                    $this->training_date_formatted = '';
                }
                if ($this->end_training_date && $this->end_training_date < $this->training_date) {
                    $this->end_training_date = null;
                    $this->end_training_date_formatted = '';
                }
            } catch (\Exception $e) {
                $this->start_date = null;
                $this->addError('start_date_formatted', 'รูปแบบวันที่ไม่ถูกต้อง');
            }
        } else {
            $this->start_date = null;
        }
        $this->emitPikadayUpdate();
    }

    public function updatedEndDateFormatted($value)
    {
        if ($value) {
            try {
                $parsedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $value);
                $this->end_date = $parsedDate->format('Y-m-d');
                if ($this->start_date && $this->end_date < $this->start_date) {
                    $this->end_date = null;
                    $this->end_date_formatted = '';
                    $this->addError('end_date_formatted', 'วันที่ปิดรับสมัครต้องอยู่หลังหรือเท่ากับวันที่เปิดสมัคร');
                }
                if ($this->training_date && $this->training_date <= $this->end_date) {
                    $this->training_date = null;
                    $this->training_date_formatted = '';
                }
                if ($this->end_training_date && $this->end_training_date < $this->training_date) {
                    $this->end_training_date = null;
                    $this->end_training_date_formatted = '';
                }
            } catch (\Exception $e) {
                $this->end_date = null;
                $this->addError('end_date_formatted', 'รูปแบบวันที่ไม่ถูกต้อง');
            }
        } else {
            $this->end_date = null;
        }
        $this->emitPikadayUpdate();
    }

    public function updatedTrainingDateFormatted($value)
    {
        if ($value) {
            try {
                $parsedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $value);
                $this->training_date = $parsedDate->format('Y-m-d');
                if ($this->end_date && $this->training_date <= $this->end_date) {
                    $this->training_date = null;
                    $this->training_date_formatted = '';
                    $this->addError('training_date_formatted', 'วันที่อบรมต้องเป็นวันที่หลังวันที่ปิดรับสมัคร');
                }
                if ($this->end_training_date && $this->end_training_date < $this->training_date) {
                    $this->end_training_date = null;
                    $this->end_training_date_formatted = '';
                }
            } catch (\Exception $e) {
                $this->training_date = null;
                $this->addError('training_date_formatted', 'รูปแบบวันที่ไม่ถูกต้อง');
            }
        } else {
            $this->training_date = null;
        }
        $this->emitPikadayUpdate();
    }

    public function updatedEndTrainingDateFormatted($value)
    {
        if ($value) {
            try {
                $parsedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $value);
                $this->end_training_date = $parsedDate->format('Y-m-d');
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
        $this->emitPikadayUpdate();
    }

    public function nextStep()
    {
        try {
            $this->validateStep();
            $this->step++;
            $this->resetValidation();
            $this->emitPikadayUpdate();
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
            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'required|nullable|string',
                'duration' => 'required|nullable|integer|min:1',
                'certificate' => 'required|in:0,1',
                'max_students' => 'required|nullable|integer|min:1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'instructor_name' => 'required|string|max:255',
                'academic_position' => 'nullable|string|max:255',
                'co_instructors.*' => 'nullable|string|max:255',
                'co_instructor_positions.*' => 'nullable|string|max:255',
            ];

            // Conditionally validate dates only if they are provided or modified
            if ($this->start_date_formatted && !empty(trim($this->start_date_formatted))) {
                $rules['start_date'] = 'required|date';
                $rules['start_date_formatted'] = 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/';
            }
            if ($this->end_date_formatted && !empty(trim($this->end_date_formatted))) {
                $rules['end_date'] = 'required|date|after_or_equal:start_date';
                $rules['end_date_formatted'] = 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/';
            }
            if ($this->training_date_formatted && !empty(trim($this->training_date_formatted))) {
                $rules['training_date'] = 'required|date|after:end_date';
                $rules['training_date_formatted'] = 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/';
            }
            if ($this->end_training_date_formatted && !empty(trim($this->end_training_date_formatted))) {
                $rules['end_training_date'] = 'required|date|after_or_equal:training_date';
                $rules['end_training_date_formatted'] = 'required|string|regex:/^\d{2}\/\d{2}\/\d{4}$/';
            }

            $this->validate($rules, [], [
                'start_date' => 'วันที่เปิดสมัคร',
                'start_date_formatted' => 'วันที่เปิดสมัคร',
                'end_date' => 'วันที่ปิดรับสมัคร',
                'end_date_formatted' => 'วันที่ปิดรับสมัคร',
                'training_date' => 'วันที่อบรม',
                'training_date_formatted' => 'วันที่อบรม',
                'end_training_date' => 'วันที่สิ้นสุดการอบรม',
                'end_training_date_formatted' => 'วันที่สิ้นสุดการอบรม',
                'certificate' => 'การออกใบรับรอง',
                'max_students' => 'จำนวนที่เปิดรับสมัคร',
                'instructor_name' => 'ชื่อวิทยากร',
                'academic_position' => 'ตำแหน่ง',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'topics.*.title' => 'nullable|string|max:255',
                'topics.*.lessons.*.title' => 'nullable|string|max:255',
                'topics.*.lessons.*.content' => 'nullable|string',
                'topics.*.lessons.*.learning_outcomes.*.description' => 'nullable|string|max:255',
                'topics.*.lessons.*.learning_outcomes.*.tag_id' => 'nullable|exists:tags,id',
            ]);
        }
    }

    public function update()
    {
        $this->validate();

        $course = Course::findOrFail($this->courseId);
        $course->update([
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'training_date' => $this->training_date,
            'end_training_date' => $this->end_training_date,
            'certificate' => $this->certificate == '1',
            'max_students' => $this->max_students,
            'image' => $this->imagePath,
        ]);

        $currentInstructor = Instructor::where('user_id', Auth::id())->first();
        if (!$course->instructors()->where('instructor_id', $currentInstructor->id)->exists()) {
            $course->instructors()->attach($currentInstructor->id, ['is_owner' => true]);
        }

        $course->coInstructors()->delete();
        if (!empty($this->co_instructors)) {
            foreach (array_filter($this->co_instructors) as $index => $co_instructor_name) {
                if (!empty($co_instructor_name)) {
                    CoInstructor::create([
                        'course_id' => $course->id,
                        'instructor_id' => $currentInstructor->id,
                        'name' => $co_instructor_name,
                        'position' => $this->co_instructor_positions[$index] ?? null,
                    ]);
                }
            }
        }

        $existingTopicIds = $course->topics()->pluck('id')->toArray();
        $newTopicIds = array_filter(array_column($this->topics, 'id'), fn($id) => !empty($id));
        $topicsToDelete = array_diff($existingTopicIds, $newTopicIds);
        Topic::whereIn('id', $topicsToDelete)->delete();

        foreach ($this->topics as $topicData) {
            $topic = isset($topicData['id']) ? Topic::find($topicData['id']) : null;
            if ($topic) {
                $topic->update(['title' => $topicData['title']]);
            } else {
                $topic = $course->topics()->create(['title' => $topicData['title']]);
            }

            $existingLessonIds = $topic->lessons()->pluck('id')->toArray();
            $newLessonIds = array_filter(array_column($topicData['lessons'], 'id'), fn($id) => !empty($id));
            $lessonsToDelete = array_diff($existingLessonIds, $newLessonIds);
            Lesson::whereIn('id', $lessonsToDelete)->delete();

            foreach ($topicData['lessons'] as $lessonData) {
                $lesson = isset($lessonData['id']) ? Lesson::find($lessonData['id']) : null;
                if ($lesson) {
                    $lesson->update([
                        'title' => $lessonData['title'],
                        'content' => $lessonData['content'],
                        'course_id' => $course->id,
                    ]);
                } else {
                    $lesson = $topic->lessons()->create([
                        'title' => $lessonData['title'],
                        'content' => $lessonData['content'],
                        'course_id' => $course->id,
                    ]);
                }

                $lesson->learningOutcomes()->delete();
                if (isset($lessonData['learning_outcomes'])) {
                    foreach ($lessonData['learning_outcomes'] as $outcome) {
                        if (!empty($outcome['description'])) { // <-- แก้ไข: เช็คจาก description
                            $lesson->learningOutcomes()->create([
                                'description' => $outcome['description'], // <-- แก้ไข: ดึงจาก description
                                'tag_id'      => $outcome['tag_id'] ?: null, // <-- เพิ่ม: บันทึก tag_id
                            ]);
                        }
                    }
                }
            }
        }

        session()->flash('updateCourse', 'แก้ไขคอร์สสำเร็จ!');

        $this->dispatch('course-updated');
        return redirect()->route('instructor.course.detail', ['id' => $this->courseId]);
    }

    public function render()
    {
        return view('livewire.instructor.edit-course');
    }
}
