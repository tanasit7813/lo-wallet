<div class="w-full mx-auto p-4 sm:p-6 bg-white dark:bg-gray-900">
    @if (session('updateCourse'))
        <x-popup message="{{ session('updateCourse') }}" bgColor="bg-green-500" darkBgColor="dark:bg-green-600" />
    @endif
    @if (session('success'))
        <x-popup message="{{ session('success') }}" bgColor="bg-green-500" darkBgColor="dark:bg-green-600" />
    @endif
    @if (session('error'))
        <x-popup message="{{ session('error') }}" bgColor="bg-red-500" darkBgColor="dark:bg-red-600" />
    @endif

    <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
        {{ $course->title }}
    </h1>

    <div class="space-y-4 text-sm sm:text-base md:text-lg lg:text-xl">
        <div>
            <label class="font-bold text-gray-700 dark:text-gray-300">โดย:</label>
            <span class="text-gray-900 dark:text-gray-100">{{ $instructorName }}</span>
        </div>
        <div>
            <label class="font-bold text-gray-700 dark:text-gray-300">ตำแหน่ง:</label>
            <span class="text-gray-900 dark:text-gray-100">{{ $academicPosition }}</span>
        </div>
        <div>
            <label class="font-bold text-gray-700 dark:text-gray-300">จำนวนผู้ลงทะเบียน:</label>
            <span class="text-gray-900 dark:text-gray-100">{{ $enrollmentCount }} คน</span>
        </div>
        <div>
            <label class="font-bold text-gray-700 dark:text-gray-300">รายละเอียดคอร์ส:</label>
            <p class="text-gray-900 dark:text-gray-100 text-thai-distributed">{{ $course->description }}</p>
        </div>
    </div>

    <div class="mt-4 sm:mt-6" x-data="{
        showAcceptConfirm: false,
        showDenyConfirm: false,
        showAddUserModal: false,
        selectedStudent: null,
        selectedRemark: '',
        customRemark: '',
        errorMessage: '',
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        tel_number: '',
        role: '',
        faculty: '',
        program: '',
        branch: '',
        major: '',
        academic_position: '',
        position: '',
        agency: '',
        insider_role: '',
        emailHasError: false,
        emailIsValid: false,
        telNumberHasError: false,
        telNumberIsValid: false,
        showModal: false,
        generalModal: false,
        insiderModal: false,
        emailTouched: false,
        telNumberTouched: false,
    }">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h2 class="text-sm sm:text-base md:text-lg lg:text-xl font-semibold text-gray-900 dark:text-gray-100">
                รายชื่อผู้ลงทะเบียน
            </h2>
            <button @click="showAddUserModal = true"
                class="cursor-pointer px-2 sm:px-3 md:px-4 py-1 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs sm:text-sm md:text-base whitespace-nowrap">
                + เพิ่มรายชื่อ
            </button>
        </div>
        @if (!empty($enrolledStudents))
            <div class="overflow-x-auto">
                <div class="w-full rounded-lg" role="grid">
                    <div class="hidden xl:grid grid-cols-12 gap-px bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 divide-y divide-gray-300 dark:divide-gray-700"
                        role="row">
                        @foreach (['No', 'ชื่อ', 'อีเมล', 'เบอร์มือถือ', 'คณะ', 'สาขา', 'ตำแหน่ง', 'หน่วยงาน', 'ประเภทบุคคลภายใน', 'สถานะ', 'หมายเหตุ', 'การดำเนินการ'] as $header)
                            <div class="py-2 px-2 text-xs sm:text-sm md:text-base lg:text-sm text-gray-900 dark:text-gray-100 text-left font-medium border-r border-gray-300 dark:border-gray-700 last:border-r-0"
                                role="columnheader">
                                {{ $header }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Body -->
                    @foreach ($enrolledStudents as $index => $enrollment)
                        <div class="flex flex-col xl:grid xl:grid-cols-12 gap-px bg-white dark:bg-gray-900 xl:bg-gray-50 xl:dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 border xl:border-0 border-gray-300 dark:border-gray-600 mb-4 xl:mb-0 p-4 xl:p-0"
                            role="row">
                            @foreach ([['label' => 'No: ', 'value' => $index + 1], ['label' => 'ชื่อ: ', 'value' => $enrollment['user']->name], ['label' => 'อีเมล: ', 'value' => $enrollment['user']->email ?? 'ไม่ระบุ'], ['label' => 'เบอร์มือถือ: ', 'value' => $enrollment['user']->tel_number ?? 'ไม่ระบุ'], ['label' => 'คณะ: ', 'value' => $enrollment['role_data']['faculty']], ['label' => 'สาขา: ', 'value' => $enrollment['role_data']['branch']], ['label' => 'ตำแหน่ง: ', 'value' => $enrollment['role_data']['position']], ['label' => 'หน่วยงาน: ', 'value' => $enrollment['role_data']['agency']], ['label' => 'ประเภทบุคคลภายใน: ', 'value' => $enrollment['role_data']['insider_role']], ['label' => 'สถานะ: ', 'value' => $enrollment['status']], ['label' => 'หมายเหตุ: ', 'value' => $enrollment['remark'] ?? 'ไม่มี'], ['label' => 'การดำเนินการ: ', 'value' => 'action']] as $cell)
                                <div class="xl:grid flex-row sm:flex-col items-start py-2 px-3 text-xs sm:text-sm md:text-base lg:text-sm text-gray-900 dark:text-gray-100 xl:border-r xl:last:border-r border-gray-300 dark:border-gray-700 xl:last:pr-0 with-label min-w-0"
                                    style="word-wrap: break-word; word-break: break-all; overflow-wrap: anywhere; hyphens: auto;"
                                    role="cell" data-label="{{ $cell['label'] }}">
                                    @if ($cell['label'] === 'สถานะ: ')
                                        @if ($enrollment['status'] === 'pending')
                                            <span class="text-yellow-600 dark:text-yellow-400">รอยืนยัน</span>
                                        @elseif ($enrollment['status'] === 'confirmed')
                                            <span class="text-green-600 dark:text-green-400">ยืนยัน</span>
                                        @elseif ($enrollment['status'] === 'denied')
                                            <span class="text-red-600 dark:text-red-400">ปฏิเสธ</span>
                                        @endif
                                    @elseif ($cell['label'] === 'การดำเนินการ: ')
                                        @if ($enrollment['status'] === 'pending')
                                            <div class="flex space-x-2">
                                                <button
                                                    @click="selectedStudent = { id: {{ $enrollment['id'] }}, name: '{{ addslashes($enrollment['user']->name) }}' }; showAcceptConfirm = true"
                                                    class="cursor-pointer px-2 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-xs sm:text-sm md:text-base"
                                                    aria-label="ยอมรับการลงทะเบียนของ {{ addslashes($enrollment['user']->name) }}">
                                                    อนุมัติ
                                                </button>
                                                <button
                                                    @click="selectedStudent = { id: {{ $enrollment['id'] }}, name: '{{ addslashes($enrollment['user']->name) }}' }; showDenyConfirm = true; selectedRemark = ''; customRemark = ''; errorMessage = ''"
                                                    class="cursor-pointer px-2 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 text-xs sm:text-sm md:text-base"
                                                    aria-label="ปฏิเสธการลงทะเบียนของ {{ addslashes($enrollment['user']->name) }}">
                                                    ปฏิเสธ
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">ดำเนินการแล้ว</span>
                                        @endif
                                    @else
                                        @if ($cell['label'] === 'อีเมล: ')
                                            <div
                                                style="word-wrap: break-word; word-break: break-all; overflow-wrap: anywhere; max-width: 100%; line-height: 1.4; white-space: normal;">
                                                {{ $cell['value'] }}
                                            </div>
                                        @else
                                            <div
                                                style="word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; max-width: 100%; line-height: 1.4; white-space: normal;">
                                                {{ $cell['value'] }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-sm sm:text-base md:text-lg lg:text-xl text-gray-900 dark:text-gray-100">
                ยังไม่มีผู้ลงทะเบียนในคอร์สนี้
            </p>
        @endif

        <!-- Pop-up Confirm for Accept -->
        <div x-show="showAcceptConfirm"
            class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 dark:bg-gray-950/50 backdrop-blur-sm">
            <div
                class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg w-full max-w-xs sm:max-w-sm md:max-w-sm">
                <h3
                    class="text-sm sm:text-base md:text-lg lg:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    ยืนยันการลงทะเบียน
                </h3>
                <p
                    class="text-gray-700 dark:text-gray-300 mb-4 text-xs sm:text-xl md:text-xl lg:text-xl flex flex-col gap-1 text-center">
                    <span>ยืนยันการลงทะเบียนของ</span>
                    <span>
                        <span x-text="selectedStudent?.name || ''"
                            class="font-bold text-yellow-600 dark:text-yellow-400"></span>
                        หรือไม่ ?
                    </span>
                </p>
                <div class="flex justify-center space-x-2">
                    <button type="button" @click="showAcceptConfirm = false"
                        class="cursor-pointer min-w-[120px] group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-gray-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-gray-600/50 border border-white/20 mr-2">
                        <span class="text-base sm:text-lg">ยกเลิก</span>
                    </button>
                    <button wire:click="acceptEnrollment(selectedStudent?.id)" @click="showAcceptConfirm = false"
                        class="cursor-pointer min-w-[120px] group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-green-600 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-green-700/50 border border-white/20 mr-2">
                        ยืนยัน
                    </button>
                </div>
            </div>
        </div>

        <!-- Pop-up Confirm for Deny -->
        <div x-show="showDenyConfirm"
            class="fixed inset-0 flex items-center justify-center bg-gray-900/30 dark:bg-gray-950/50 backdrop-blur-sm bg-opacity-50 z-50">
            <div
                class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg w-full max-w-xs sm:max-w-sm md:max-w-sm">
                <h3
                    class="text-sm sm:text-base md:text-lg lg:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    ปฏิเสธการลงทะเบียน
                </h3>
                <p
                    class="text-gray-700 dark:text-gray-300 mb-4 text-xs sm:text-xl md:text-xl lg:text-xl flex flex-col gap-1 text-center">
                    <span>คุณต้องการปฏิเสธการลงทะเบียนของ</span>
                    <span>
                        <span x-text="selectedStudent?.name || ''"
                            class="font-bold text-yellow-600 dark:text-yellow-400"></span>
                        หรือไม่ ?
                    </span>
                </p>
                <div class="mb-4">
                    <label
                        class="block text-gray-700 dark:text-gray-300 mb-2 text-xs sm:text-sm md:text-base lg:text-base">
                        เลือกหมายเหตุ:
                    </label>
                    <select x-model="selectedRemark"
                        class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 text-xs sm:text-sm md:text-base"
                        @change="if (selectedRemark !== 'อื่นๆ') customRemark = ''">
                        <option value="">เลือกหมายเหตุ</option>
                        <option value="ไม่ครบตามเงื่อนไข">ไม่ครบตามเงื่อนไข</option>
                        <option value="ข้อมูลไม่ถูกต้อง">ข้อมูลไม่ถูกต้อง</option>
                        <option value="อื่นๆ">อื่นๆ</option>
                    </select>
                </div>
                <div x-show="selectedRemark === 'อื่นๆ'" class="mb-4">
                    <label
                        class="block text-gray-700 dark:text-gray-300 mb-2 text-xs sm:text-sm md:text-base lg:text-base">
                        ระบุหมายเหตุ:
                    </label>
                    <textarea x-model="customRemark"
                        class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 text-xs sm:text-sm md:text-base"
                        placeholder="ระบุหมายเหตุเพิ่มเติม"></textarea>
                </div>
                <div x-show="errorMessage" class="text-red-500 mb-4 text-xs sm:text-sm md:text-base"
                    x-text="errorMessage"></div>
                <div class="flex justify-end space-x-2">
                    <button @click="showDenyConfirm = false"
                        class="cursor-pointer px-3 sm:px-4 py-1 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-xs sm:text-sm md:text-base">
                        ยกเลิก
                    </button>
                    <button
                        @click="if (!selectedRemark || (selectedRemark === 'อื่นๆ' && !customRemark.trim())) { errorMessage = 'กรุณาระบุหมายเหตุ'; return; } else { errorMessage = ''; $wire.denyEnrollment(selectedStudent?.id, selectedRemark, customRemark); showDenyConfirm = false; }"
                        class="cursor-pointer px-3 sm:px-4 py-1 sm:py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-xs sm:text-sm md:text-base">
                        ปฏิเสธ
                    </button>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div x-show="showAddUserModal"
            class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 dark:bg-gray-950/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg w-full max-w-2xl">
                <div class="flex justify-between items-center mb-4">
                    <label
                        class="text-sm sm:text-base md:text-lg lg:text-lg font-semibold text-gray-900 dark:text-gray-100">
                        เพิ่มรายชื่อผู้ใช้
                    </label>
                    <button @click="showAddUserModal = false; $wire.resetForm()"
                        class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Member Selection -->
                <div class="mb-6" x-data="{ memberType: @entangle('memberType').live }">
                    <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                        เลือกประเภทสมาชิก
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model.live="memberType" value="existing" class="hidden peer">
                            <div
                                class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-blue-500 peer-checked:bg-blue-500 duration-300">
                                <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                            </div>
                            <span class="ml-2 text-gray-700 dark:text-[#EDEDEC]">เป็นสมาชิกแล้ว</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model.live="memberType" value="new" class="hidden peer">
                            <div
                                class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-green-500 peer-checked:bg-green-500 duration-300">
                                <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                            </div>
                            <span class="ml-2 text-gray-700 dark:text-[#EDEDEC]">ยังไม่เป็นสมาชิก</span>
                        </label>
                    </div>
                    @error('memberType')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Existing Member Search -->
                <div x-show="$wire.memberType === 'existing'" x-transition class="mb-6">
                    <div class="mb-4">
                        <label for="searchName"
                            class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            ค้นหาสมาชิก
                        </label>
                        <div class="flex gap-2">
                            <input wire:model.live="searchName" type="text" id="searchName"
                                placeholder="พิมพ์ชื่อเพื่อค้นหา..."
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                            <button type="button" wire:click="searchExistingMember"
                                class="cursor-pointer px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                ค้นหา
                            </button>
                        </div>
                    </div>

                    <!-- Search Results -->
                    @if ($searchResults)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                ผลการค้นหา
                            </label>
                            <div
                                class="max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                @forelse($searchResults as $user)
                                    <div
                                        class="p-3 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <div class="flex justify-between items-center gap-4">
                                            <div class="flex-1">
                                                <!-- Name and Role on the same line -->
                                                <div class="flex justify-between items-center">
                                                    <div class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                        {{ $user->name }}
                                                    </div>
                                                    <div class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                        {{ $user->roleData->display_name ?? 'ไม่ระบุ' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <div class="flex items-end">
                                                @if ($user->enrollments()->where('course_id', $course->id)->exists())
                                                    <button type="button"
                                                        class="px-4 py-1.5 bg-gray-400 text-white text-sm rounded cursor-not-allowed"
                                                        disabled>
                                                        ลงทะเบียนแล้ว
                                                    </button>
                                                @elseif ($user->role === 'instructor')
                                                    <button type="button"
                                                        class="px-4 py-1.5 bg-gray-400 text-white text-sm rounded cursor-not-allowed"
                                                        disabled>
                                                        ไม่สามารถลงทะเบียนได้
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        wire:click="enrollExistingMember({{ $user->id }})"
                                                        class="cursor-pointer px-4 py-1.5 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition-colors">
                                                        ลงทะเบียน
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-3 text-center text-gray-500 dark:text-gray-400">
                                        ไม่พบสมาชิกที่ค้นหา
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>

                <!-- New Member Registration Form (existing form) -->
                <div x-show="$wire.memberType === 'new'" x-transition>
                    <form wire:submit.prevent="addUser" class="flex flex-col gap-1 sm:gap-1">
                        <!-- Name -->
                        <div class="relative min-h-[6rem] sm:min-h-[6.5rem]">
                            <div class="flex items-center justify-between">
                                <label for="name"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    ชื่อ <span class="text-red-500">(ไม่ต้องใส่คำนำหน้า)</span>
                                </label>
                            </div>
                            <div class="relative">
                                <input wire:model.lazy="name" type="text" id="name"
                                    placeholder="ชื่อ-นามสกุล"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                            </div>
                            <div class="text-sm flex-shrink-0 mt-2" x-cloak>
                                @error('name')
                                    <label class="text-red-500">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="relative min-h-[6rem] sm:min-h-[6.5rem]">
                            <div class="flex items-center justify-between">
                                <label for="email"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    อีเมล
                                </label>
                            </div>
                            <div class="relative flex items-center space-x-2">
                                <div class="relative flex-1">
                                    <input wire:model="email" type="email" id="email"
                                        placeholder="email@example.com" x-data="{ email: @entangle('email').live, hasError: @entangle('emailHasError').live, isValid: @entangle('emailIsValid').live }"
                                        :class="hasError ? 'border-red-500' : (isValid ? 'border-green-500' :
                                            'border-gray-300 dark:border-gray-600')"
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3"
                                        x-data="{ email: @entangle('email').live, hasError: @entangle('emailHasError').live, isValid: @entangle('emailIsValid').live }" x-show="hasError || isValid">
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
                        <div class="relative min-h-[6rem] sm:min-h-[6.5rem]" x-data="{ showPassword: false }">
                            <div class="flex items-center justify-between">
                                <label for="password"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    รหัสผ่าน
                                </label>
                                <span class="flex items-center cursor-pointer" @click="showPassword = !showPassword">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                                        class="text-gray-500 dark:text-gray-400 text-base"></i>
                                </span>
                            </div>
                            <div class="relative">
                                <input wire:model.live="password" :type="showPassword ? 'text' : 'password'"
                                    id="password" placeholder="รหัสผ่าน" x-model="password"
                                    :class="password.length > 0 ? (password.length >= 8 ? 'border-green-500' :
                                            'border-red-500') :
                                        'border-gray-300 dark:border-gray-600'"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3"
                                    x-show="password.length > 0" x-cloak>
                                    <i x-show="password.length < 8" class="fas fa-times text-red-500 text-base"></i>
                                    <i x-show="password.length >= 8"
                                        class="fas fa-check text-green-500 text-base"></i>
                                </span>
                            </div>
                            <div class="text-sm flex-shrink-0 mt-2" x-cloak>
                                <label x-show="password.length > 0 && password.length < 8"
                                    class="text-red-500">*รหัสผ่านควรมีความยาว 8 ตัวอักษรขึ้นไป</label>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="relative min-h-[5rem] sm:min-h-[5.5rem]" x-data="{ showConfirmPassword: false }">
                            <div class="flex items-center justify-between">
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    ยืนยันรหัสผ่าน
                                </label>
                                <span class="flex items-center cursor-pointer"
                                    @click="showConfirmPassword = !showConfirmPassword">
                                    <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                                        class="text-gray-500 dark:text-gray-400 text-base"></i>
                                </span>
                            </div>
                            <div class="relative">
                                <input wire:model.live="password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation"
                                    placeholder="ยืนยันรหัสผ่าน" x-model="password_confirmation"
                                    :class="password_confirmation.length > 0 ? (password === password_confirmation ?
                                            'border-green-500' : 'border-red-500') :
                                        'border-gray-300 dark:border-gray-600'"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3"
                                    x-show="password_confirmation.length > 0" x-cloak>
                                    <i x-show="password !== password_confirmation"
                                        class="fas fa-times text-red-500 text-base"></i>
                                    <i x-show="password === password_confirmation"
                                        class="fas fa-check text-green-500 text-base"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Telephone Number -->
                        <div class="relative min-h-[6rem] sm:min-h-[6.5rem]">
                            <div class="flex items-center justify-between">
                                <label for="tel_number"
                                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    เบอร์โทรศัพท์
                                </label>
                            </div>
                            <div class="relative flex items-center space-x-2">
                                <div class="relative flex-1">
                                    <input wire:model="tel_number" type="tel" id="tel_number"
                                        placeholder="เบอร์โทรศัพท์" pattern="[0-9]*" maxlength="10"
                                        onkeypress="return (event.charCode !=8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))"
                                        x-data="{ tel_number: @entangle('tel_number').live, hasError: @entangle('telNumberHasError').live, isValid: @entangle('telNumberIsValid').live }"
                                        :class="hasError ? 'border-red-500' : (isValid ? 'border-green-500' :
                                            'border-gray-300 dark:border-gray-600')"
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3"
                                        x-data="{ tel_number: @entangle('tel_number').live, hasError: @entangle('telNumberHasError').live, isValid: @entangle('telNumberIsValid').live }" x-show="hasError || isValid">
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
                        <div class="min-h-[3rem] sm:min-h-[3.5rem] mb-5">
                            <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
                                <div class="flex justify-center gap-8 w-full sm:w-auto">
                                    <label for="student" class="flex items-center cursor-pointer w-32">
                                        <input type="radio" wire:model.live="role" value="student" id="student"
                                            class="hidden peer"
                                            @click="showModal = true; generalModal = false; insiderModal = false">
                                        <div
                                            class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-blue-500 peer-checked:bg-blue-500 duration-300">
                                            <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                                        </div>
                                        <span class="ml-2 text-gray-700 dark:text-[#EDEDEC]">นักศึกษา</span>
                                    </label>
                                </div>

                                <div class="flex justify-center gap-8 w-full sm:w-auto">
                                    <label for="general" class="flex items-center cursor-pointer w-32">
                                        <input type="radio" wire:model.live="role" value="general" id="general"
                                            class="hidden peer"
                                            @click="showModal = false; generalModal = true; insiderModal = false">
                                        <div
                                            class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-green-500 peer-checked:bg-green-500 duration-300">
                                            <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                                        </div>
                                        <span class="ml-2 text-gray-700 dark:text-[#EDEDEC]">บุคคลทั่วไป</span>
                                    </label>

                                    <label for="insider" class="flex items-center cursor-pointer w-32">
                                        <input type="radio" wire:model.live="role" value="insider" id="insider"
                                            class="hidden peer"
                                            @click="showModal = false; generalModal = false; insiderModal = true">
                                        <div
                                            class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-purple-500 peer-checked:bg-purple-500 duration-300">
                                            <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                                        </div>
                                        <span class="ml-2 text-gray-700 dark:text-[#EDEDEC]">บุคคลภายใน</span>
                                    </label>
                                </div>
                            </div>
                            <div class="text-sm flex-shrink-0 mt-2">
                                @error('role')
                                    <label class="text-red-500">{{ $message }}</label>
                                @enderror
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
                                        <button type="button" wire:click="closeStudentModal"
                                            @click="showModal = false; $wire.resetForm()"
                                            class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                            ปิด
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
                                                @foreach ($faculties as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('faculty')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
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
                                                @foreach ($programs as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('program')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
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
                                                @foreach ($branches as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('branch')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
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
                                                @foreach ($majors as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('major')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" wire:click="closeStudentModal"
                                            @click="showModal = false"
                                            class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                            ปิด
                                        </button>
                                        <button type="button" wire:click="saveModal('student')"
                                            @click="showModal = false"
                                            class="cursor-pointer px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
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
                                        <button type="button" wire:click="closegeneralModal"
                                            @click="generalModal = false; $wire.resetForm()"
                                            class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                            ปิด
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
                                        <button type="button" wire:click="closegeneralModal"
                                            @click="generalModal = false"
                                            class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                            ปิด
                                        </button>
                                        <button type="button" wire:click="saveModal('general')"
                                            @click="generalModal = false"
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
                                        <button type="button" wire:click="closeinsiderModal"
                                            @click="insiderModal = false; $wire.resetForm()"
                                            class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                            ปิด
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
                                                <span
                                                    class="ml-2 text-[#1b1b18] dark:text-[#EDEDEC]">บุคลากรสายวิชาการ</span>
                                            </label>

                                            <label for="teaching" class="flex items-center cursor-pointer">
                                                <input type="radio" wire:model.live="insider_role" value="teaching"
                                                    id="teaching" class="hidden peer">
                                                <div
                                                    class="w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-purple-500 peer-checked:bg-purple-500 duration-300">
                                                    <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                                                </div>
                                                <span
                                                    class="ml-2 text-[#1b1b18] dark:text-[#EDEDEC]">บุคลากรสายการสอน</span>
                                            </label>
                                        </div>
                                        @error('insider_role')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" wire:click="closeinsiderModal"
                                            @click="insiderModal = false"
                                            class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                            ปิด
                                        </button>
                                        <button type="button" wire:click="saveModal('insider')"
                                            @click="insiderModal = false"
                                            class="cursor-pointer px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">
                                            บันทึก
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center mt-4">
                            <button type="button" @click="showAddUserModal = false; $wire.resetForm()"
                                class="cursor-pointer w-full group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-gray-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-gray-600/50 border border-white/20 mr-2">
                                <span class="text-base sm:text-lg">ยกเลิก</span>
                            </button>
                            <button type="submit"
                                class="cursor-pointer w-full group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-blue-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-blue-600/50 border border-white/20">
                                <span class="text-base sm:text-lg">สร้างบัญชี</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Close button for member type selection -->
                <div x-show="$wire.memberType === 'existing'" class="flex justify-end mt-4">
                    <button type="button" @click="showAddUserModal = false; $wire.resetSearchForm()"
                        class="cursor-pointer px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                        ปิด
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('courses') }}"
            class="inline-block bg-gray-500 text-white px-3 sm:px-4 py-1 sm:py-2 rounded-lg hover:bg-gray-600 text-sm sm:text-base md:text-lg lg:text-xl">
            กลับไปยังรายการคอร์ส
        </a>
    </div>
</div>

<script>
    document.title = "{{ $course->title }}";
</script>
