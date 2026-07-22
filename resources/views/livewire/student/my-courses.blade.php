<div class="w-full mx-auto p-4 sm:p-6 bg-white dark:bg-[#1b1b18]">
    <label class="block text-xl sm:text-2xl md:text-3xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-6">
        คอร์สที่ลงทะเบียน
    </label>

    @if (session('success'))
        <x-popup message="{{ session('success') }}" bgColor="bg-green-500" darkBgColor="dark:bg-green-900" />
    @endif
    @if (session('error'))
        <x-popup message="{{ session('error') }}" bgColor="bg-red-500" darkBgColor="dark:bg-red-900" />
    @endif

    <div>
        @if ($enrolledCourses->isEmpty())
            <p class="text-base sm:text-lg md:text-xl lg:text-xl text-gray-900 dark:text-white">
                คุณยังไม่ได้ลงทะเบียนคอร์ส
            </p>
        @else
            <div class="overflow-x-auto">
                <div class="w-full rounded-lg" role="grid">
                    <div class="hidden lg:grid grid-cols-[100px_2fr_1fr_1fr] gap-px bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 divide-y divide-gray-300 dark:divide-gray-700"
                        role="row">
                        @foreach (['รูปภาพ', 'ชื่อคอร์ส', 'วันที่เริ่มอบรม', 'สถานะ'] as $header)
                            <div class="py-2 px-3 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-gray-100 text-left font-medium border-r border-gray-300 dark:border-gray-700 last:border-r-0"
                                role="columnheader">
                                {{ $header }}
                            </div>
                        @endforeach
                    </div>

                    @foreach ($enrolledCourses as $enrollment)
                        <div class="flex flex-col lg:grid grid-cols-[100px_2fr_1fr_1fr] gap-px bg-white dark:bg-gray-900 lg:bg-gray-50 lg:dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 border lg:border-0 border-gray-300 dark:border-gray-600 rounded-lg mb-4 lg:mb-0 p-4 lg:p-0"
                            role="row">
                            @foreach ([['label' => 'รูปภาพ: ', 'value' => 'image'], ['label' => 'ชื่อคอร์ส: ', 'value' => $enrollment->course->title], ['label' => 'วันที่เริ่มอบรม: ', 'value' => $enrollment->course->training_date ? \Carbon\Carbon::parse($enrollment->course->training_date)->format('d/m/Y') : 'ไม่ระบุ'], ['label' => 'สถานะ: ', 'value' => $enrollment->status]] as $cell)
                                <div class="flex lg:grid flex-row sm:flex-col items-start py-2 px-3 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-gray-100 lg:border-r border-gray-300 dark:border-gray-700 last:border-r-0 course-with-label min-w-0"
                                    style="overflow-wrap: break-word; word-break: break-word; max-width: 100%; line-height: 1.4; white-space: normal;"
                                    role="cell" data-label="{{ $cell['label'] }}"
                                    @if ($cell['label'] === 'ชื่อคอร์ส: ') title="{{ $cell['value'] }}" @endif>
                                    @if ($cell['label'] === 'รูปภาพ: ')
                                        @if ($enrollment->course->image)
                                            <img src="{{ asset('storage/' . $enrollment->course->image) }}"
                                                alt="Course Image"
                                                class="w-16 h-16 sm:w-24 sm:h-18 object-cover rounded-md image-full">
                                        @else
                                            <img src="{{ asset('img/insert-img.png') }}" alt="BANNER COURSE"
                                                class="w-16 h-16 sm:w-24 sm:h-18 object-cover rounded-md image-full">
                                        @endif
                                    @elseif ($cell['label'] === 'ชื่อคอร์ส: ')
                                        <a href="{{ route('course.detail', $enrollment->course->id) }}" wire:navigate
                                            class="text-blue-600 dark:text-blue-400 hover:underline"
                                            style="overflow-wrap: break-word; word-break: break-word; max-width: 100%; line-height: 1.4; white-space: normal;"
                                            title="{{ $cell['value'] }}">
                                            {{ $cell['value'] }}
                                        </a>
                                    @elseif ($cell['label'] === 'สถานะ: ')
                                        @if ($enrollment->status === 'pending')
                                            <span class="text-yellow-600 dark:text-yellow-400">รอยืนยัน</span>
                                        @elseif ($enrollment->status === 'confirmed')
                                            <span class="text-green-600 dark:text-green-400">ยืนยัน</span>
                                        @elseif ($enrollment->status === 'denied')
                                            <span class="text-red-600 dark:text-red-400">ปฏิเสธ</span>
                                        @endif
                                    @else
                                        <div
                                            style="overflow-wrap: break-word; word-break: break-word; max-width: 100%; line-height: 1.4; white-space: normal;">
                                            {{ $cell['value'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.title = "คอร์สที่ลงทะเบียน";
</script>
