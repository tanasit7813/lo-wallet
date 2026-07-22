<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\General;
use App\Models\Insider;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\Branch;
use App\Models\Major;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class CourseDetail extends Component
{
    public $course;
    public $instructorName;
    public $academicPosition;
    public $enrollmentCount;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $tel_number;
    public $role;
    public $faculty;
    public $program;
    public $branch;
    public $major;
    public $academic_position;
    public $position;
    public $agency;
    public $insider_role;
    public $emailHasError = false;
    public $emailIsValid = false;
    public $telNumberHasError = false;
    public $telNumberIsValid = false;

    public $faculties = [];
    public $programs = [];
    public $branches = [];
    public $majors = [];

    public $memberType = 'new'; // ค่าเริ่มต้นเป็น new member
    public $searchName = '';
    public $searchResults = null;

    public $enrolledStudents;

    // Define allowed email domains
    protected $allowedDomains = [
        'hotmail.com',
        'outlook.com',
        'gmail.com',
        'yahoo.com',
        'pkru.ac.th',
    ];

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

        $this->instructorName = Auth::user()->name;
        $this->academicPosition = Auth::user()->instructor->academic_position ?? 'ไม่ระบุ';

        // Load faculties with slug as key
        $this->faculties = Faculty::pluck('name', 'slug')->toArray();

        $this->loadEnrollments();
    }

    public function searchExistingMember()
    {
        $this->resetErrorBag('searchName');

        if (empty(trim($this->searchName))) {
            $this->addError('searchName', 'กรุณากรอกชื่อที่ต้องการค้นหา');
            return;
        }

        // ค้นหาจาก table users โดยใช้ชื่อ
        $this->searchResults = User::where('name', 'LIKE', '%' . trim($this->searchName) . '%')
            ->with(['student.faculty', 'student.branch', 'general', 'insider', 'roleData'])
            ->limit(10)
            ->get();
    }

    public function enrollExistingMember($userId)
    {
        // ตรวจสอบสิทธิ์
        if (!in_array(Auth::user()->role, ['instructor', 'officer'])) {
            session()->flash('error', 'คุณไม่มีสิทธิ์ดำเนินการนี้');
            return;
        }

        DB::beginTransaction();
        try {
            $user = User::findOrFail($userId);

            if ($user->role === 'instructor') {
                session()->flash('error', "ผู้ใช้ {$user->name} เป็น instructor ไม่สามารถลงทะเบียนคอร์สนี้ได้");
                return;
            }

            // ตรวจสอบว่า user นี้ลงทะเบียนคอร์สนี้แล้วหรือไม่
            $existingEnrollment = Enrollment::where('user_id', $userId)
                ->where('course_id', $this->course->id)
                ->first();

            if ($existingEnrollment) {
                session()->flash('error', "ผู้ใช้ {$user->name} ได้ลงทะเบียนคอร์สนี้แล้ว");
                return;
            }

            // สร้าง enrollment ใหม่
            Enrollment::create([
                'user_id' => $userId,
                'course_id' => $this->course->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            $this->loadEnrollments();
            session()->flash('success', "ลงทะเบียนคอร์สสำหรับ {$user->name} เรียบร้อยแล้ว");

            // รีเซ็ตข้อมูลการค้นหา
            $this->resetSearchForm();

            $this->dispatch('refresh-page');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in enrollExistingMember', ['error' => $e->getMessage()]);
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function resetSearchForm()
    {
        $this->searchName = '';
        $this->searchResults = null;
        $this->memberType = 'new';
        $this->resetErrorBag();
    }

    public function loadEnrollments()
    {
        // Load enrollments with role-specific relationships
        $enrollments = $this->course->enrollments()
            ->with([
                'user',
                'user.student.faculty',
                'user.student.branch',
                'user.general',
                'user.insider'
            ])
            ->whereIn('status', ['pending', 'confirmed', 'denied'])
            ->get();

        // Map enrollments to include role-specific data
        $this->enrolledStudents = $enrollments->map(function ($enrollment) {
            $user = $enrollment->user;

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
                'id' => $enrollment->id,
                'user' => $user,
                'status' => $enrollment->status,
                'remark' => $enrollment->remark,
                'role_data' => $roleData,
            ];
        })->toArray();

        $this->enrollmentCount = $enrollments
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
    }

    public function checkEmail()
    {
        $this->resetErrorBag('email');
        $this->emailHasError = false;
        $this->emailIsValid = false;

        // Check if email is provided
        if (empty($this->email)) {
            $this->addError('email', '* กรุณากรอกอีเมล');
            $this->emailHasError = true;
            return;
        }

        // Validate email format
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', '* รูปแบบอีเมลไม่ถูกต้อง (เช่น email@pkru.ac.th หรือ email@gmail.com)');
            $this->emailHasError = true;
            return;
        }

        // Extract domain from email
        $domain = strtolower(Str::after($this->email, '@'));

        // Check if domain is allowed
        if (!in_array($domain, $this->allowedDomains)) {
            $this->addError('email', '* อีเมลต้องใช้โดเมน ' . implode(', ', $this->allowedDomains) . ' เท่านั้น');
            $this->emailHasError = true;
            return;
        }

        // Check for duplicate email
        if (User::where('email', $this->email)->exists()) {
            $this->addError('email', '* อีเมลนี้ถูกใช้งานแล้ว');
            $this->emailHasError = true;
        } else {
            $this->emailIsValid = true;
        }
    }

    public function checkTelNumber()
    {
        $this->resetErrorBag('tel_number');
        $this->telNumberHasError = false;
        $this->telNumberIsValid = false;

        // Check if telephone number is provided
        if (empty($this->tel_number)) {
            $this->addError('tel_number', '* กรุณากรอกเบอร์โทรศัพท์');
            $this->telNumberHasError = true;
            return;
        }

        // Validate telephone number format (10 digits, numbers only)
        if (!preg_match('/^[0-9]{10}$/', $this->tel_number)) {
            $this->addError('tel_number', '* เบอร์โทรศัพท์ต้องมี 10 หลักและเป็นตัวเลขเท่านั้น');
            $this->telNumberHasError = true;
            return;
        }

        // Check for duplicate telephone number
        if (User::where('tel_number', $this->tel_number)->exists()) {
            $this->addError('tel_number', '* หมายเลขโทรศัพท์นี้ถูกใช้งานแล้ว');
            $this->telNumberHasError = true;
        } else {
            $this->telNumberIsValid = true;
        }
    }

    public function updatedFaculty($value)
    {
        $this->program = '';
        $this->branch = '';
        $this->major = '';
        $this->branches = [];
        $this->majors = [];

        if ($value) {
            // ค้นหา faculty จาก slug เพื่อดึง id
            $faculty = Faculty::where('slug', $value)->first();
            if ($faculty) {
                $this->programs = Program::where('faculties_id', $faculty->id)
                    ->pluck('name', 'slug')
                    ->toArray();
            }
        } else {
            $this->programs = [];
        }
    }

    public function updatedProgram($value)
    {
        $this->branch = '';
        $this->major = '';
        $this->majors = [];

        if ($value && $this->faculty) {
            // ค้นหา faculty และ program จาก slug
            $faculty = Faculty::where('slug', $this->faculty)->first();
            $program = Program::where('slug', $value)->first();

            if ($faculty && $program) {
                $this->branches = Branch::where('faculties_id', $faculty->id)
                    ->where('programs_id', $program->id)
                    ->pluck('name', 'slug')
                    ->toArray();
            }
        } else {
            $this->branches = [];
        }
    }

    public function updatedBranch($value)
    {
        $this->major = '';
        $this->majors = [];

        if ($value && $this->faculty && $this->program) {
            // ค้นหา faculty, program, และ branch จาก slug
            $faculty = Faculty::where('slug', $this->faculty)->first();
            $program = Program::where('slug', $this->program)->first();
            $branch = Branch::where('slug', $value)->first();

            if ($faculty && $program && $branch) {
                $this->majors = Major::where('faculties_id', $faculty->id)
                    ->where('programs_id', $program->id)
                    ->where('branches_id', $branch->id)
                    ->pluck('name', 'slug')
                    ->toArray();
            }
        } else {
            $this->majors = [];
        }
    }

    public function closeStudentModal()
    {
        $this->faculty = '';
        $this->program = '';
        $this->branch = '';
        $this->major = '';
        $this->role = '';
        $this->programs = [];
        $this->branches = [];
        $this->majors = [];
    }

    public function closegeneralModal()
    {
        $this->role = '';
        $this->position = '';
        $this->agency = '';
    }

    public function closeinsiderModal()
    {
        $this->role = '';
        $this->insider_role = '';
    }

    public function saveModal($role)
    {
        $rules = [];
        if ($role === 'student') {
            $rules = [
                'faculty' => ['required', 'exists:faculties,slug'],
                'program' => ['required', 'exists:programs,slug'],
                'branch' => ['required', 'exists:branches,slug'],
                'major' => !empty($this->majors) ? ['required', 'exists:majors,slug'] : ['nullable'],
            ];
        } elseif ($role === 'general') {
            $rules['position'] = ['required', 'string', 'max:255'];
            $rules['agency'] = ['required', 'string', 'max:255'];
        } elseif ($role === 'insider') {
            $rules['insider_role'] = ['required', 'in:academic,teaching'];
        }

        $this->validate($rules);
        $this->role = $role;

        // Map role to Thai for flash message
        $roleNames = [
            'student' => 'นักศึกษา',
            'instructor' => 'วิทยากร',
            'general' => 'บุคคลทั่วไป',
            'insider' => 'บุคคลภายใน',
        ];

        $roleName = $roleNames[$role] ?? $role;
        session()->flash('modal_message', "บันทึกข้อมูลสำหรับ {$roleName} เรียบร้อย");
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
            'tel_number',
            'role',
            'faculty',
            'program',
            'branch',
            'major',
            'academic_position',
            'position',
            'agency',
            'insider_role',
            'emailHasError',
            'emailIsValid',
            'telNumberHasError',
            'telNumberIsValid',
            'programs',
            'branches',
            'majors',
            'memberType',
            'searchName',
            'searchResults',
        ]);
        $this->resetErrorBag(); // ล้าง error messages ทั้งหมด
        $this->resetValidation(); // รีเซ็ตสถานะการ validate
    }

    public function addUser()
    {
        // Restrict to instructors and officers
        if (!in_array(Auth::user()->role, ['instructor', 'officer'])) {
            session()->flash('error', 'คุณไม่มีสิทธิ์ดำเนินการนี้');
            return;
        }

        DB::beginTransaction();
        try {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    'unique:users,email',
                    function ($attribute, $value, $fail) {
                        $domain = strtolower(Str::after($value, '@'));
                        if (!in_array($domain, $this->allowedDomains)) {
                            $fail('* อีเมลต้องใช้โดเมน ' . implode(', ', $this->allowedDomains) . ' เท่านั้น');
                        }
                    },
                ],
                'tel_number' => ['required', 'string', 'regex:/^[0-9]{10}$/', 'unique:users,tel_number'],
                'password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)],
                'role' => ['required', 'in:student,general,insider'],
                'faculty' => $this->role === 'student' ? ['required', 'exists:faculties,slug'] : ['nullable'],
                'program' => $this->role === 'student' ? ['required', 'exists:programs,slug'] : ['nullable'],
                'branch' => $this->role === 'student' ? ['required', 'exists:branches,slug'] : ['nullable'],
                'major' => $this->role === 'student' && !empty($this->majors) ? ['required', 'exists:majors,slug'] : ['nullable'],
                'insider_role' => $this->role === 'insider' ? ['required', 'in:academic,teaching'] : ['nullable'],
                'position' => $this->role === 'general' ? ['required', 'string', 'max:255'] : ['nullable'],
                'agency' => $this->role === 'general' ? ['required', 'string', 'max:255'] : ['nullable'],
            ]);

            // ดึง slug จากตาราง roles ตาม role ที่เลือก
            $roleData = Role::where('name', $validated['role'])->first();

            // Create new user with slug
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'tel_number' => $this->tel_number,
                'role' => $this->role,
                'slug' => $roleData ? $roleData->slug : null,
            ]);

            // Create role-specific data with corrected field names and slug lookup
            if ($this->role === 'student') {
                $faculty = Faculty::where('slug', $this->faculty)->first();
                $program = Program::where('slug', $this->program)->where('faculties_id', $faculty->id)->first();
                $branch = Branch::where('slug', $this->branch)->where('faculties_id', $faculty->id)->where('programs_id', $program->id)->first();
                $major_id = null;
                if (!empty($this->major)) {
                    $major = Major::where('slug', $this->major)->where('faculties_id', $faculty->id)->where('programs_id', $program->id)->where('branches_id', $branch->id)->first();
                    $major_id = $major->id;
                }

                Student::create([
                    'user_id' => $user->id,
                    'faculties_id' => $faculty->id,
                    'programs_id' => $program->id,
                    'branches_id' => $branch->id,
                    'majors_id' => $major_id,
                ]);
            } elseif ($this->role === 'general') {
                General::create([
                    'user_id' => $user->id,
                    'position' => $this->position,
                    'agency' => $this->agency,
                ]);
            } elseif ($this->role === 'insider') {
                Insider::create([
                    'user_id' => $user->id,
                    'insider_role' => $this->insider_role,
                ]);
            }

            // Create enrollment
            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $this->course->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            $this->loadEnrollments();
            session()->flash('success', "เพิ่มผู้ใช้ {$this->name} เรียบร้อยแล้ว");
            $this->resetForm();
            $this->dispatch('refresh-page');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in addUser', ['error' => $e->getMessage()]);
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function acceptEnrollment($enrollmentId)
    {
        // Restrict to instructors and officers
        if (!in_array(Auth::user()->role, ['instructor', 'officer'])) {
            session()->flash('error', 'คุณไม่มีสิทธิ์ดำเนินการนี้');
            return;
        }

        DB::beginTransaction();
        try {
            $enrollment = Enrollment::findOrFail($enrollmentId);

            if ($enrollment->course_id !== $this->course->id) {
                session()->flash('error', 'คุณไม่มีสิทธิ์ยืนยันการลงทะเบียนนี้');
                return;
            }

            // Update enrollment status to confirmed
            $enrollment->update(['status' => 'confirmed']);

            // Create a certificate record with pending status
            \App\Models\Certificate::create([
                'user_id' => $enrollment->user_id,
                'course_id' => $this->course->id,
                'status' => 'pending',
                'requested_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create a course completion record
            \App\Models\CourseCompletion::create([
                'user_id' => $enrollment->user_id,
                'course_id' => $this->course->id,
                'enrollment_id' => $enrollment->id,
                'completed_at' => now(),
            ]);

            DB::commit();
            $this->loadEnrollments();
            session()->flash('success', "ยืนยันการลงทะเบียนของ {$enrollment->user->name} เรียบร้อยแล้ว");

            // Dispatch event for notification
            $this->dispatch('enrollment-confirmed', [
                'user_id' => $enrollment->user_id,
                'course_name' => $this->course->name,
            ]);

            $this->dispatch('refresh-page');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in acceptEnrollment', ['error' => $e->getMessage()]);
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function denyEnrollment($enrollmentId, $remark, $customRemark)
    {
        // Restrict to instructors and officers
        if (!in_array(Auth::user()->role, ['instructor', 'officer'])) {
            session()->flash('error', 'คุณไม่มีสิทธิ์ดำเนินการนี้');
            return;
        }

        DB::beginTransaction();
        try {
            $enrollment = Enrollment::findOrFail($enrollmentId);

            if ($enrollment->course_id !== $this->course->id) {
                session()->flash('error', 'คุณไม่มีสิทธิ์ปฏิเสธการลงทะเบียนนี้');
                return;
            }

            $finalRemark = $remark === 'อื่นๆ' ? trim($customRemark) : trim($remark);

            // Server-side validation
            if (empty($finalRemark)) {
                session()->flash('error', 'กรุณาระบุหมายเหตุ');
                return;
            }

            $enrollment->update([
                'status' => 'denied',
                'remark' => $finalRemark,
            ]);

            DB::commit();
            $this->loadEnrollments();
            session()->flash('success', "ปฏิเสธการลงทะเบียนของ {$enrollment->user->name} เรียบร้อยแล้ว (หมายเหตุ: {$finalRemark})");
            $this->dispatch('refresh-page');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.instructor.course-detail', [
            'course' => $this->course,
            'instructorName' => $this->instructorName,
            'academicPosition' => $this->academicPosition,
            'enrollmentCount' => $this->enrollmentCount,
            'enrolledStudents' => $this->enrolledStudents,
        ]);
    }
}
