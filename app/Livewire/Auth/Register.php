<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\Branch;
use App\Models\Major;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\General;
use App\Models\Insider;
use App\Models\Role; // เพิ่มการ import Model Role
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = '';
    public string $tel_number = '';
    public $faculties = [];
    public $faculty = '';
    public $programs = [];
    public $program = '';
    public $branches = [];
    public $branch = '';
    public $majors = [];
    public $major = '';
    public $roles = []; // เพิ่มตัวแปรสำหรับเก็บข้อมูล roles
    public string $insider_role = '';
    public string $position = '';
    public string $agency = '';
    public string $academic_position = '';

    public bool $showModal = false;
    public bool $instructorModal = false;
    public bool $insiderModal = false;
    public bool $generalModal = false;

    public bool $emailHasError = false; // เพิ่มตัวแปรสำหรับเก็บสถานะ error
    public bool $emailIsValid = false; // เพิ่มตัวแปรสำหรับกรณีอีเมลใช้งานได้

    public bool $telNumberHasError = false; // ตัวแปรสำหรับเก็บสถานะ error ของ tel_number
    public bool $telNumberIsValid = false; // ตัวแปรสำหรับกรณี tel_number ใช้งานได้

    // Define allowed email domains
    protected $allowedDomains = [
        'hotmail.com',
        'outlook.com',
        'gmail.com',
        'yahoo.com',
        'pkru.ac.th',
    ];

    public function mount()
    {
        $this->faculties = Faculty::pluck('name', 'slug')->toArray();
        $this->roles = Role::pluck('display_name', 'name')->toArray(); // ดึง roles สำหรับ dropdown
    }

    // Real-time validation for name
    public function updatedName($value)
    {
        if ($value && User::where('name', $value)->exists()) {
            $this->addError('name', '* ชื่อนี้ถูกใช้งานแล้ว');
        } else {
            $this->resetErrorBag('name');
        }
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

    // Real-time validation for tel_number
    public function checkTelNumber()
    {
        $this->resetErrorBag('tel_number');
        $this->telNumberHasError = false;
        $this->telNumberIsValid = false;

        // ตรวจสอบว่ามีการกรอกหมายเลข
        if (empty($this->tel_number)) {
            $this->addError('tel_number', '* กรุณากรอกเบอร์โทรศัพท์');
            $this->telNumberHasError = true;
            return;
        }

        // ตรวจสอบรูปแบบหมายเลข (10 หลักและเป็นตัวเลขเท่านั้น)
        if (!preg_match('/^[0-9]{10}$/', $this->tel_number)) {
            $this->addError('tel_number', '* เบอร์โทรศัพท์ต้องมี 10 หลักและเป็นตัวเลขเท่านั้น');
            $this->telNumberHasError = true;
            return;
        }

        // ตรวจสอบความซ้ำในฐานข้อมูล
        if (User::where('tel_number', $this->tel_number)->exists()) {
            $this->addError('tel_number', '* หมายเลขโทรศัพท์นี้ถูกใช้งานแล้ว');
            $this->telNumberHasError = true;
        } else {
            $this->telNumberIsValid = true; // หมายเลขใช้งานได้
        }
    }

    public function updatedRole($value)
    {
        $this->faculty = '';
        $this->program = '';
        $this->branch = '';
        $this->major = '';
        $this->insider_role = '';
        $this->academic_position = '';
        $this->position = '';
        $this->agency = '';
        $this->programs = [];
        $this->branches = [];
        $this->majors = [];
        $this->showModal = $value === 'student';
        $this->instructorModal = $value === 'instructor';
        $this->generalModal = $value === 'general';
        $this->insiderModal = $value === 'insider';
    }

    public function closeStudentModal()
    {
        $this->showModal = false;
        $this->role = '';
        $this->faculty = '';
        $this->program = '';
        $this->branch = '';
        $this->major = '';
    }

    public function closeinstructorModal()
    {
        $this->instructorModal = false;
        $this->role = '';
        $this->academic_position = '';
    }

    public function closegeneralModal()
    {
        $this->generalModal = false;
        $this->role = '';
        $this->position = '';
        $this->agency = '';
    }

    public function closeinsiderModal()
    {
        $this->insiderModal = false;
        $this->role = '';
        $this->insider_role = '';
    }

    public function updatedFaculty($value)
    {
        $this->program = '';
        $this->branch = '';
        $this->major = '';
        $this->branches = [];
        $this->majors = [];

        if ($value) {
            $this->programs = Program::where('faculties_id', Faculty::where('slug', $value)->first()->id)
                ->pluck('name', 'slug')
                ->toArray();
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
            $faculty = Faculty::where('slug', $this->faculty)->first();
            $program = Program::where('slug', $value)->where('faculties_id', $faculty->id)->first();
            if ($program) {
                $this->branches = Branch::where('faculties_id', $faculty->id)->where('programs_id', $program->id)->pluck('name', 'slug')->toArray();
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
            $faculty = Faculty::where('slug', $this->faculty)->first();
            $program = Program::where('slug', $this->program)->where('faculties_id', $faculty->id)->first();
            $branch = Branch::where('slug', $value)->where('faculties_id', $faculty->id)->where('programs_id', $program->id)->first();
            if ($branch) {
                $this->majors = Major::where('faculties_id', $faculty->id)->where('programs_id', $program->id)->where('branches_id', $branch->id)->pluck('name', 'slug')->toArray();
            }
        } else {
            $this->majors = [];
        }
    }

    public function saveModal($role)
    {
        // Validate เฉพาะข้อมูลใน modal ตาม role
        if ($role === 'student') {
            $this->validate([
                'faculty' => ['required', 'exists:faculties,slug'],
                'program' => ['required', 'exists:programs,slug'],
                'branch' => ['required', 'exists:branches,slug'],
                'major' => !empty($this->majors) ? ['required', 'exists:majors,slug'] : ['nullable'],
            ]);
            $this->showModal = false;
        } elseif ($role === 'instructor') {
            $this->validate([
                'academic_position' => ['required', 'string', 'max:255'],
            ]);
            $this->instructorModal = false;
        } elseif ($role === 'insider') {
            $this->validate([
                'insider_role' => ['required', 'in:academic,teaching'],
            ]);
            $this->insiderModal = false;
        } elseif ($role === 'general') {
            $this->validate([
                'position' => ['required', 'string', 'max:255'],
                'agency' => ['required', 'string', 'max:255'],
            ]);
            $this->generalModal = false;
        }

        // เก็บ role
        $this->role = $role;

        // Map role เป็นชื่อภาษาไทย
        $roleNames = [
            'student' => 'นักศึกษา',
            'instructor' => 'วิทยากร',
            'general' => 'บุคคลทั่วไป',
            'insider' => 'บุคคลภายใน',
        ];

        // ใช้ชื่อภาษาไทยใน Flash message
        $roleName = $roleNames[$role] ?? $role; // ถ้า role ไม่มีใน mapping ใช้ค่าเดิม
        session()->flash('modal_message', "บันทึกข้อมูลสำหรับ {$roleName} เรียบร้อย");
    }

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                function ($attribute, $value, $fail) {
                    $domain = strtolower(Str::after($value, '@'));
                    if (!in_array($domain, $this->allowedDomains)) {
                        $fail('* อีเมลต้องใช้โดเมน ' . implode(', ', $this->allowedDomains) . ' เท่านั้น');
                    }
                },
            ],
            'tel_number' => ['required', 'string', 'regex:/^[0-9]{10}$/', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::min(8)],
            'role' => ['required', 'in:student,instructor,general,insider'],
            'faculty' => $this->role === 'student' ? ['required', 'exists:faculties,slug'] : ['nullable'],
            'program' => $this->role === 'student' ? ['required', 'exists:programs,slug'] : ['nullable'],
            'branch' => $this->role === 'student' ? ['required', 'exists:branches,slug'] : ['nullable'],
            'major' => $this->role === 'student' && !empty($this->majors) ? ['required', 'exists:majors,slug'] : ['nullable'],
            'insider_role' => $this->role === 'insider' ? ['required', 'in:academic,teaching'] : ['nullable'],
            'position' => $this->role === 'general' ? ['required', 'string', 'max:255'] : ['nullable'],
            'agency' => $this->role === 'general' ? ['required', 'string', 'max:255'] : ['nullable'],
            'academic_position' => $this->role === 'instructor' ? ['required', 'string', 'max:255'] : ['nullable'],
        ]);

        // ดึง slug จากตาราง roles ตาม role ที่เลือก
        $roleData = Role::where('name', $validated['role'])->first();

        // สร้างผู้ใช้ใหม่ในตาราง users
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tel_number' => $validated['tel_number'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'slug' => $roleData ? $roleData->slug : null,
        ];

        $user = User::create($userData);

        // จัดการข้อมูลเพิ่มเติมตาม role
        if ($this->role === 'student') {
            $faculty = Faculty::where('slug', $validated['faculty'])->first();
            $program = Program::where('slug', $validated['program'])->where('faculties_id', $faculty->id)->first();
            $branch = Branch::where('slug', $validated['branch'])->where('faculties_id', $faculty->id)->where('programs_id', $program->id)->first();
            $major_id = null;
            if (!empty($validated['major'])) {
                $major = Major::where('slug', $validated['major'])->where('faculties_id', $faculty->id)->where('programs_id', $program->id)->where('branches_id', $branch->id)->first();
                $major_id = $major->id;
            }

            Student::create([
                'user_id' => $user->id,
                'faculties_id' => $faculty->id,
                'programs_id' => $program->id,
                'branches_id' => $branch->id,
                'majors_id' => $major_id,
            ]);
        } elseif ($this->role === 'insider') {
            Insider::create([
                'user_id' => $user->id,
                'insider_role' => $validated['insider_role'],
            ]);
        } elseif ($this->role === 'instructor') {
            Instructor::create([
                'user_id' => $user->id,
                'academic_position' => $validated['academic_position'],
            ]);
        } elseif ($this->role === 'general') {
            General::create([
                'user_id' => $user->id,
                'position' => $validated['position'],
                'agency' => $validated['agency'],
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        // ตั้งค่า session flash message สำหรับสมัครสมาชิกสำเร็จ
        session()->flash('register_success', ['name' => $user->name]);
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
