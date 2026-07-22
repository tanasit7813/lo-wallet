<div class="w-full mx-auto">
    @if (session('success'))
        <x-popup message="{{ session('success') }}" bgColor="bg-green-500" darkBgColor="dark:bg-green-900" />
    @endif
    @if (session('error'))
        <x-popup message="{{ session('error') }}" bgColor="bg-red-500" darkBgColor="dark:bg-red-900" />
    @endif

    <div class="p-4 sm:p-6 bg-white dark:bg-gray-900 w-full">
        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
            {{ $course->title }}
        </h1>

        <!-- Table of Students -->
        <div class="mt-6">
            <h2
                class="text-base sm:text-lg md:text-2xl lg:text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                รายชื่อผู้ผ่านการอบรม
            </h2>
            @if (empty($students))
                <p class="text-sm sm:text-base md:text-lg lg:text-xl text-gray-900 dark:text-gray-100">
                    ยังไม่มีผู้เรียนที่เริ่มการอบรมในคอร์สนี้
                </p>
            @else
                <div class="overflow-x-auto">
                    <div class="w-full rounded-lg" role="grid">
                        <div class="hidden xl:grid grid-cols-11 gap-px bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 divide-y divide-gray-300 dark:divide-gray-700"
                            role="row">
                            @foreach (['No', 'ชื่อ', 'อีเมล', 'เบอร์มือถือ', 'คณะ', 'สาขา', 'ตำแหน่ง', 'หน่วยงาน', 'ประเภทบุคคลภายใน', 'สถานะ', 'Certificate'] as $header)
                                <div class="py-2 px-3 text-xs sm:text-sm md:text-base lg:text-sm text-gray-900 dark:text-gray-100 text-left font-medium border-r border-gray-300 dark:border-gray-700 last:border-r-0"
                                    role="columnheader">
                                    {{ $header }}
                                </div>
                            @endforeach
                        </div>

                        <!-- Body -->
                        @foreach ($students as $index => $student)
                            <div class="flex flex-col xl:grid xl:grid-cols-11 gap-px bg-white dark:bg-gray-900 xl:bg-gray-50 xl:dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 border xl:border-0 border-gray-300 dark:border-gray-600 mb-4 xl:mb-0 p-4 xl:p-0"
                                role="row">
                                @foreach ([['label' => 'No: ', 'value' => $index + 1], ['label' => 'ชื่อ: ', 'value' => $student['user']->name], ['label' => 'อีเมล: ', 'value' => $student['user']->email], ['label' => 'เบอร์มือถือ: ', 'value' => $student['user']->tel_number ?? 'ไม่ระบุ'], ['label' => 'คณะ: ', 'value' => $student['role_data']['faculty']], ['label' => 'สาขา: ', 'value' => $student['role_data']['branch']], ['label' => 'ตำแหน่ง: ', 'value' => $student['role_data']['position']], ['label' => 'หน่วยงาน: ', 'value' => $student['role_data']['agency']], ['label' => 'ประเภทบุคคลภายใน: ', 'value' => $student['role_data']['insider_role']], ['label' => 'สถานะ: ', 'value' => $student['status']], ['label' => 'Certificate: ', 'value' => 'certificate']] as $cell)
                                    <div class="xl:grid flex-row sm:flex-col items-start py-2 px-3 text-xs sm:text-sm md:text-base lg:text-sm text-gray-900 dark:text-gray-100 xl:border-r xl:last:border-r border-gray-300 dark:border-gray-700 xl:last:pr-0 with-label min-w-0"
                                        style="word-wrap: break-word; word-break: break-all; overflow-wrap: anywhere; hyphens: auto;"
                                        role="cell" data-label="{{ $cell['label'] }}">
                                        @if ($cell['label'] === 'สถานะ: ')
                                            @if ($student['status'] === 'completed')
                                                <span class="text-green-600 dark:text-green-400">ผ่านการอบรม</span>
                                            @else
                                                <span class="text-red-600 dark:text-red-400">ยังไม่ผ่านการอบรม</span>
                                            @endif
                                        @elseif ($cell['label'] === 'Certificate: ')
                                            @if ($student['certificate_status'])
                                                <span class="text-blue-600 dark:text-blue-400 flex flex-col gap-1">
                                                    <span>ร้องขอแล้ว</span>
                                                    <span>({{ $student['certificate_requested_at']->format('d/m/Y H:i') }})</span>
                                                </span>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">ยังไม่ร้องขอ</span>
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
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ route('courses') }}"
                class="inline-block bg-gray-500 text-white px-3 sm:px-4 py-1 sm:py-2 rounded-lg hover:bg-gray-600 text-sm sm:text-base md:text-lg lg:text-xl">
                กลับไปยังรายการคอร์ส
            </a>
        </div>
    </div>
</div>

<script>
    document.title = "{{ $course->title }}";
</script>
