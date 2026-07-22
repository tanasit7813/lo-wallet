<div class="min-h-screen flex items-center justify-center w-full">
    <div class="flex flex-col gap-6 w-full max-w-full sm:max-w-md p-4 sm:p-6 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-lg rounded-lg"
        x-cloak x-transition>
        <!-- Header -->
        <div class="flex flex-col items-center space-y-2">
            <label class="text-xl sm:text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Create an account</label>
            <label class="text-lg text-[#706f6c] dark:text-[#A1A09A]">Enter your details below to create your
                account</label>
        </div>

        @if (session('modal_message'))
            <x-popup message="{{ session('modal_message') }}" bgColor="bg-green-500" darkBgColor="dark:bg-green-900" />
        @endif

        <form wire:submit.prevent="register" class="flex flex-col gap-1 sm:gap-1">

            <div class="relative min-h-[6rem] sm:min-h-[6.5rem]">
                <div class="flex items-center justify-between">
                    <label for="name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Name
                        <span class="text-red-500">(ไม่ต้องใส่คำนำหน้า)</span>
                    </label>
                </div>
                <div class="relative">
                    <input wire:model.lazy="name" type="text" id="name" placeholder="ชื่อ-นามสกุล"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                </div>
                <div class="text-sm flex-shrink-0 mt-2">
                    @error('name')
                        <label class="text-red-500 starting:opacity-0 transition duration-500">{{ $message }}</label>
                    @enderror
                </div>
            </div>

            <!-- Email Address -->
            <div class="relative min-h-[6rem] sm:min-h-[6.5rem]">
                <div class="flex items-center justify-between">
                    <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                        Email Address
                    </label>
                </div>
                <div class="relative flex items-center space-x-2">
                    <div class="relative flex-1">
                        <input wire:model="email" type="email" id="email" placeholder="email@example.com"
                            x-data="{ email: @entangle('email').live, hasError: @entangle('emailHasError').live, isValid: @entangle('emailIsValid').live }"
                            :class="hasError ? 'border-red-500' : (isValid ? 'border-green-500' :
                                'border-gray-300 dark:border-gray-600')"
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3" x-data="{ email: @entangle('email').live, hasError: @entangle('emailHasError').live, isValid: @entangle('emailIsValid').live }"
                            x-show="hasError || isValid">
                            <i x-show="hasError" class="fas fa-times text-red-500"></i>
                            <i x-show="isValid" class="fas fa-check text-green-500"></i>
                        </span>
                    </div>
                    <button type="button" wire:click="checkEmail"
                        :class="email.length ? 'bg-green-500 hover:bg-green-600' : 'bg-blue-400 cursor-pointer'"
                        class="mt-1 px-6 py-2 group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-blue-500 backdrop-blur-lg sm:px-6 sm:py-1.5 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-blue-600/50 border border-white/20">
                        <span class="text-base sm:text-lg">Check</span>
                    </button>
                </div>
                <div class="text-sm flex-shrink-0 mt-2">
                    <div x-data="{ hasError: @entangle('emailHasError').live }">
                        @error('email')
                            <label class="text-red-500">{{ $message }}</label>
                        @enderror
                    </div>
                    <div x-data="{ isValid: @entangle('emailIsValid').live }" x-show="isValid">
                        <label class="text-green-500">อีเมลนี้สามารถใช้งานได้</label>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="relative min-h-[6rem] sm:min-h-[6.5rem]" x-data="{ showPassword: false, password: @entangle('password').live }">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                        Password
                    </label>
                    <span class="flex items-center cursor-pointer" @click="showPassword = !showPassword">
                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                            class="text-gray-500 dark:text-gray-400 text-base"></i>
                    </span>
                </div>

                <div class="relative">
                    <input wire:model.live="password" :type="showPassword ? 'text' : 'password'" id="password"
                        placeholder="รหัสผ่าน"
                        :class="password.length > 0 ? (password.length >= 8 ? 'border-green-500' : 'border-red-500') :
                            'border-gray-300 dark:border-gray-600'"
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3" x-show="password.length > 0">
                        <i x-show="password.length < 8"
                            class="fas fa-times text-red-500 text-base starting:opacity-0 transition duration-500"></i>
                        <i x-show="password.length >= 8"
                            class="fas fa-check text-green-500 text-base starting:opacity-0 transition duration-500"></i>
                    </span>
                </div>

                <div class="text-sm flex-shrink-0 mt-2">
                    <label x-show="password.length > 0 && password.length < 8"
                        class="text-red-500 starting:opacity-0 transition duration-500">
                        *รหัสผ่านควรมีความยาว 8 ตัวอักษรขึ้นไป
                    </label>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="relative min-h-[5rem] sm:min-h-[5.5rem]" x-data="{ showConfirmPassword: false, password: @entangle('password').live, password_confirmation: @entangle('password_confirmation').live }">
                <div class="flex items-center justify-between">
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                        Confirm Password
                    </label>
                    <span class="flex items-center cursor-pointer" @click="showConfirmPassword = !showConfirmPassword">
                        <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                            class="text-gray-500 dark:text-gray-400 text-base"></i>
                    </span>
                </div>
                <div class="relative">
                    <input wire:model.live="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'"
                        id="password_confirmation" placeholder="ยืนยันรหัสผ่าน"
                        :class="password_confirmation.length > 0 ? (password === password_confirmation ? 'border-green-500' :
                            'border-red-500') : 'border-gray-300 dark:border-gray-600'"
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3"
                        x-show="password_confirmation.length > 0">
                        <i x-show="password !== password_confirmation"
                            class="fas fa-times text-red-500 text-base starting:opacity-0 transition duration-500"></i>
                        <i x-show="password === password_confirmation"
                            class="fas fa-check text-green-500 text-base starting:opacity-0 transition duration-500"></i>
                    </span>
                </div>
            </div>

            <!-- Telephone Number -->
            <div class="relative min-h-[6rem] sm:min-h-[6.5rem]">
                <div class="flex items-center justify-between">
                    <label for="tel_number" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                        Telephone Number
                    </label>
                </div>
                <div class="relative flex items-center space-x-2">
                    <div class="relative flex-1">
                        <input wire:model="tel_number" type="tel" id="tel_number" placeholder="เบอร์โทรศัพท์"
                            pattern="[0-9]*" maxlength="10"
                            onkeypress="return (event.charCode !=8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))"
                            x-data="{ tel_number: @entangle('tel_number').live, hasError: @entangle('telNumberHasError').live, isValid: @entangle('telNumberIsValid').live }"
                            :class="hasError ? 'border-red-500' : (isValid ? 'border-green-500' :
                                'border-gray-300 dark:border-gray-600')"
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3" x-data="{ tel_number: @entangle('tel_number').live, hasError: @entangle('telNumberHasError').live, isValid: @entangle('telNumberIsValid').live }"
                            x-show="hasError || isValid">
                            <i x-show="hasError" class="fas fa-times text-red-500"></i>
                            <i x-show="isValid" class="fas fa-check text-green-500"></i>
                        </span>
                    </div>
                    <button type="button" wire:click="checkTelNumber"
                        :class="tel_number.length ? 'bg-green-500 hover:bg-green-600' : 'bg-blue-400 cursor-pointer'"
                        class="mt-1 px-6 py-2 group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-blue-500 backdrop-blur-lg sm:px-6 sm:py-1.5 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-blue-600/50 border border-white/20">
                        <span class="text-base sm:text-lg">Check</span>
                    </button>
                </div>
                <div class="text-sm flex-shrink-0 mt-2">
                    <div x-data="{ hasError: @entangle('telNumberHasError').live }">
                        @error('tel_number')
                            <label class="text-red-500">{{ $message }}</label>
                        @enderror
                    </div>
                    <div x-data="{ isValid: @entangle('telNumberIsValid').live }" x-show="isValid">
                        <label class="text-green-500">เบอร์โทรศัพท์นี้สามารถใช้งานได้</label>
                    </div>
                </div>
            </div>

            <!-- Role Selection -->
            <div class="min-h-[3rem] sm:min-h-[3.5rem] mb-5" x-data="{ showModal: false, instructorModal: false, generalModal: false, insiderModal: false }" x-cloak>
                <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
                    <div class="flex justify-center gap-8 w-full sm:w-auto">
                        <label for="student" class="flex items-center cursor-pointer w-32">
                            <input type="radio" wire:model.live="role" value="student" id="student"
                                class="hidden peer"
                                @click="showModal = true; instructorModal = false; generalModal = false; insiderModal = false">
                            <div
                                class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-blue-500 peer-checked:bg-blue-500 duration-300">
                                <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                            </div>
                            <span class="ml-2 text-gray-700 dark:text-[#EDEDEC]">นักศึกษา</span>
                        </label>

                        <label for="instructor" class="flex items-center cursor-pointer w-32">
                            <input type="radio" wire:model.live="role" value="instructor" id="instructor"
                                class="hidden peer"
                                @click="showModal = false; instructorModal = true; generalModal = false; insiderModal = false">
                            <div
                                class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-red-500 peer-checked:bg-red-500 duration-300">
                                <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                            </div>
                            <span class="ml-2 text-gray-700 dark:text-[#EDEDEC]">วิทยากร</span>
                        </label>
                    </div>

                    <div class="flex justify-center gap-8 w-full sm:w-auto">
                        <label for="general" class="flex items-center cursor-pointer w-32">
                            <input type="radio" wire:model.live="role" value="general" id="general"
                                class="hidden peer"
                                @click="showModal = false; instructorModal = false; generalModal = true; insiderModal = false">
                            <div
                                class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-green-500 peer-checked:bg-green-500 duration-300">
                                <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                            </div>
                            <span class="ml-2 text-gray-700 dark:text-[#EDEDEC]">บุคคลทั่วไป</span>
                        </label>

                        <label for="insider" class="flex items-center cursor-pointer w-32">
                            <input type="radio" wire:model.live="role" value="insider" id="insider"
                                class="hidden peer"
                                @click="showModal = false; instructorModal = false; generalModal = false; insiderModal = true">
                            <div
                                class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-purple-500 peer-checked:bg-purple-500 duration-300">
                                <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                            </div>
                            <span class="ml-2 text-gray-700 dark:text-[#EDEDEC]">บุคคลภายใน</span>
                        </label>
                    </div>
                </div>

                <!-- Modal for Student Role -->
                <div x-show="showModal"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                    x-transition>
                    <div
                        class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                เลือกข้อมูลสำหรับนักศึกษา
                            </label>
                            <button type="button" wire:click="closeStudentModal" @click="showModal = false"
                                class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="mb-4 space-y-4">
                            <!-- Faculty Selection -->
                            <div>
                                <label for="faculty"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    เลือกคณะ
                                </label>
                                <select wire:model.live="faculty" id="faculty"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                    <option value="">-- เลือกคณะ --</option>
                                    @foreach ($faculties as $slug => $name)
                                        <option value="{{ $slug }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Program Selection -->
                            <div>
                                <label for="program"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    เลือกหลักสูตร
                                </label>
                                <select wire:model.live="program" id="program"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                    <option value="">-- เลือกหลักสูตร --</option>
                                    @foreach ($programs as $slug => $name)
                                        <option value="{{ $slug }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Branch Selection -->
                            <div>
                                <label for="branch"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    เลือกสาขา
                                </label>
                                <select wire:model.live="branch" id="branch"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                    <option value="">-- เลือกสาขา --</option>
                                    @foreach ($branches as $slug => $name)
                                        <option value="{{ $slug }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Major Selection (ถ้ามี) -->
                            <div x-show="Object.keys($wire.majors).length > 0">
                                <label for="major"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    เลือกวิชาเอก
                                </label>
                                <select wire:model.live="major" id="major"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                    <option value="">-- เลือกวิชาเอก --</option>
                                    @foreach ($majors as $slug => $name)
                                        <option value="{{ $slug }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="closeStudentModal" @click="showModal = false"
                                class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                ปิด
                            </button>
                            <button type="button" wire:click="saveModal('student')" @click="showModal = false"
                                class="cursor-pointer px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                บันทึก
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal for Instructor Role -->
                <div x-show="instructorModal"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                    x-transition>
                    <div
                        class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                ข้อมูลสำหรับวิทยากร
                            </label>
                            <button type="button" wire:click="closeinstructorModal" @click="instructorModal = false"
                                class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="mb-4 space-y-4">
                            <!-- Academic Position Input -->
                            <div>
                                <label for="academic_position"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    ตำแหน่งวิชาการ
                                </label>
                                <input wire:model.live="academic_position" type="text" id="academic_position"
                                    placeholder="เช่น อาจารย์, รองศาสตราจารย์"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                @error('academic_position')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="closeinstructorModal" @click="instructorModal = false"
                                class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                ปิด
                            </button>
                            <button type="button" wire:click="saveModal('instructor')"
                                @click="instructorModal = false"
                                class="cursor-pointer px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                บันทึก
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal for General Role -->
                <div x-show="generalModal"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                    x-transition>
                    <div
                        class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                ข้อมูลสำหรับบุคคลทั่วไป
                            </label>
                            <button type="button" wire:click="closegeneralModal" @click="generalModal = false"
                                class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="mb-4 space-y-4">
                            <!-- Position Input -->
                            <div>
                                <label for="position"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    ชื่อตำแหน่ง
                                </label>
                                <input wire:model.live="position" type="text" id="position"
                                    placeholder="เช่น ผู้จัดการ, พนักงาน"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                @error('position')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Agency Input -->
                            <div>
                                <label for="agency"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    ชื่อหน่วยงาน
                                </label>
                                <input wire:model.live="agency" type="text" id="agency"
                                    placeholder="เช่น บริษัท ABC, กรม XYZ"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                @error('agency')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="closegeneralModal" @click="generalModal = false"
                                class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                ปิด
                            </button>
                            <button type="button" wire:click="saveModal('general')" @click="generalModal = false"
                                class="cursor-pointer px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                บันทึก
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal for Insider Role -->
                <div x-show="insiderModal"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                    x-transition>
                    <div
                        class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                เลือกข้อมูลสำหรับบุคคลภายใน
                            </label>
                            <button type="button" wire:click="closeinsiderModal" @click="insiderModal = false"
                                class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="mb-4 space-y-4">
                            <div class="flex gap-8">
                                <label for="academic" class="flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="insider_role" value="academic"
                                        id="academic" class="hidden peer">
                                    <div
                                        class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-purple-500 peer-checked:bg-purple-500 duration-300">
                                        <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                                    </div>
                                    <span class="ml-2 text-[#1b1b18] dark:text-[#EDEDEC]">บุคลากรสายวิชาการ</span>
                                </label>

                                <label for="teaching" class="flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="insider_role" value="teaching"
                                        id="teaching" class="hidden peer">
                                    <div
                                        class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-purple-500 peer-checked:bg-purple-500 duration-300">
                                        <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                                    </div>
                                    <span class="ml-2 text-[#1b1b18] dark:text-[#EDEDEC]">บุคลากรสายการสอน</span>
                                </label>
                            </div>
                            @error('insider_role')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="closeinsiderModal" @click="insiderModal = false"
                                class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                ปิด
                            </button>
                            <button type="button" wire:click="saveModal('insider')" @click="insiderModal = false"
                                class="cursor-pointer px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">
                                บันทึก
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="flex items-center">
                <button type="submit"
                    class="cursor-pointer w-full group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-blue-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-blue-600/50 border border-white/20">
                    <span class="text-base sm:text-lg">Create Account</span>
                </button>
            </div>
        </form>

        <!-- Link to Login -->
        <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
            <label class="text-lg">มีบัญชีอยู่แล้ว ?
                <a href="{{ route('login') }}" wire:navigate
                    class="duration-300 font-semibold hover:text-blue-500">เข้าสู่ระบบ</a>
            </label>
        </div>

    </div>
</div>

<script>
    document.title = "Register";
</script>
