<div class="container px-4 py-6 mx-auto max-w-6xl">
    <div class="flex flex-col space-y-6">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <label
                class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $course->title }}
            </label>

        </div>
        <div>
            @php
                $tags = $course->topics
                    ->pluck('lessons')
                    ->flatten()
                    ->pluck('learningOutcomes')
                    ->flatten()
                    ->pluck('tag')
                    ->filter() // กรองอันที่ไม่มี tag ออก
                    ->pluck('slug')
                    ->unique() // เอาเฉพาะชื่อที่ไม่ซ้ำกัน
                    ->values();
            @endphp

            @if ($tags->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach ($tags as $tagName)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold 
                   bg-blue-100 text-blue-800 
                   dark:bg-blue-900 dark:text-blue-200">
                            {{ $tagName }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="text-base sm:text-lg md:text-xl lg:text-xl text-[#1b1b18] dark:text-[#EDEDEC] flex justify-end">
            <div class="flex flex-col space-y-3 sm:space-y-4 text-right">
                <div>
                    <span class="font-bold text-base sm:text-lg md:text-xl lg:text-xl">วันที่เปิดรับสมัคร</span>
                    <div class="text-sm sm:text-base md:text-lg lg:text-lg">
                        {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->locale('th')->translatedFormat('j F') . ' ' . (\Carbon\Carbon::parse($course->start_date)->year + 543) : 'ไม่พบข้อมูล' }}
                    </div>
                </div>
                <div>
                    <span class="font-bold text-base sm:text-lg md:text-xl lg:text-xl">วันที่ปิดรับสมัคร</span>
                    <div class="text-sm sm:text-base md:text-lg lg:text-lg">
                        {{ $course->end_date ? \Carbon\Carbon::parse($course->end_date)->locale('th')->translatedFormat('j F') . ' ' . (\Carbon\Carbon::parse($course->end_date)->year + 543) : 'ไม่พบข้อมูล' }}
                    </div>
                </div>
                <div>
                    <span class="font-bold text-base sm:text-lg md:text-xl lg:text-xl">ระยะเวลา</span>
                    <div class="text-sm sm:text-base md:text-lg lg:text-lg">
                        {{ $course->duration ? $course->duration . ' ชั่วโมง' : 'ไม่พบข้อมูล' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructor and Position -->
        <div class="space-y-2">
            <div class="text-base sm:text-lg md:text-xl lg:text-xl">
                <span class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">โดย: </span>
                <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $instructorName }}</span>
            </div>
            <div class="text-base sm:text-lg md:text-xl lg:text-xl">
                <span class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">ตำแหน่ง: </span>
                <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $academicPosition }}</span>
            </div>
            @forelse ($coInstructors as $coInstructor)
                <div class="text-base sm:text-lg md:text-xl lg:text-xl">
                    <span class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">วิทยากรร่วม: </span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $coInstructor['name'] }}</span>
                </div>
                <div class="text-base sm:text-lg md:text-xl lg:text-xl">
                    <span class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">ตำแหน่ง: </span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $coInstructor['position'] }}</span>
                </div>
            @empty
                <span class="text-[#1b1b18] dark:text-[#EDEDEC]">ไม่มีวิทยากรร่วม</span>
            @endforelse
        </div>

        <!-- Course Image -->
        <div class="w-full">
            <img src="{{ asset('storage/' . $course->image) }}" alt=""
                class="w-full h-48 sm:h-64 md:h-80 lg:h-100 object-cover rounded-lg">
        </div>

        <!-- Description -->
        <div>
            <label
                class="text-xl sm:text-2xl md:text-3xl lg:text-3xl font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">
                เกี่ยวกับหลักสูตร
            </label>
        </div>
        <div class="text-thai-distributed">
            <p class="text-base sm:text-lg md:text-xl lg:text-xl text-[#1b1b18] dark:text-[#EDEDEC]">
                {{ $course->description }}</p>
        </div>

        <!-- Course Content -->
        <div class="flex flex-col">
            <label
                class="text-xl sm:text-2xl md:text-3xl lg:text-3xl font-semibold mb-4 text-[#1b1b18] dark:text-[#EDEDEC]">
                Course Content
            </label>

            @if ($course->topics->isEmpty())
                <label class="text-base sm:text-lg md:text-xl lg:text-xl text-[#1b1b18] dark:text-[#EDEDEC]">
                    No topics available for this course.
                </label>
            @else
                <ul class="space-y-4">
                    @foreach ($course->topics as $topic)
                        <li>
                            <span
                                class="font-medium text-base sm:text-lg md:text-xl lg:text-xl text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $topic->title }}
                            </span>
                            @if ($topic->lessons->isEmpty())
                                <span
                                    class="text-base sm:text-lg md:text-xl lg:text-xl text-[#1b1b18] dark:text-[#EDEDEC] italic pl-4">
                                    No lessons available for this topic.
                                </span>
                            @else
                                <ul class="list-disc pl-6 mt-2 space-y-1">
                                    @foreach ($topic->lessons as $lesson)
                                        <li
                                            class="text-base sm:text-lg md:text-xl lg:text-xl text-[#1b1b18] dark:text-[#EDEDEC]">
                                            {{ $lesson->title }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Learning Outcomes -->
        <div class="mt-6">
            <h2
                class="text-xl sm:text-2xl md:text-3xl lg:text-3xl font-semibold mb-4 text-[#1b1b18] dark:text-[#EDEDEC]">
                สิ่งที่ผู้เรียนจะได้รับ มีดังนี้
            </h2>

            @php
                // รวม learning outcomes ทั้งหมดมาไว้ในที่เดียวเพื่อจัดการได้ง่ายขึ้น
                $allOutcomes = $course->topics->pluck('lessons')->flatten()->pluck('learningOutcomes')->flatten();
            @endphp

            @if ($allOutcomes->isEmpty())
                <p class="text-base sm:text-lg md:text-xl lg:text-xl text-[#1b1b18] dark:text-[#EDEDEC]">
                    No learning outcomes available for this course.
                </p>
            @else
                {{-- เปลี่ยนมาใช้ list เดียวเพื่อให้ดูเป็น summary ที่สะอาดตา --}}
                <ul class="list-disc pl-6 space-y-2">
                    @foreach ($allOutcomes as $outcome)
                        <li class="text-base sm:text-lg md:text-xl lg:text-xl text-[#1b1b18] dark:text-[#EDEDEC]">
                            {{ $outcome->description }}
                            @if ($outcome->tag)
                                <span
                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $outcome->tag->name }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Enroll Button -->
        @if (auth()->check() && in_array(auth()->user()->role, ['student', 'general', 'insider']))
            <div x-data="{ showConfirm: false }" class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 mt-5">
                @if (!$isEnrolled)
                    @if (!$isFull)
                        <!-- Enroll Button with Pop-up Confirm -->
                        <button @click="showConfirm = true"
                            class="cursor-pointer relative flex items-center px-4 sm:px-6 py-2 sm:py-3 overflow-hidden font-medium transition-all bg-indigo-500 dark:bg-indigo-600 rounded-md group">
                            <span
                                class="relative w-full text-left text-white text-base sm:text-lg md:text-xl lg:text-xl transition-colors duration-200 ease-in-out group-hover:text-white">
                                Enroll Now
                            </span>
                        </button>

                        <!-- Pop-up Confirm for Enroll -->
                        <div x-show="showConfirm"
                            class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 dark:bg-gray-950/50 backdrop-blur-sm">
                            <div
                                class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg max-w-xs sm:max-w-sm w-full">
                                <p
                                    class="text-base sm:text-lg md:text-xl lg:text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 leading-relaxed break-words text-center">
                                    คุณต้องการจะสมัครคอร์สเรียน <br>
                                    <span class="font-bold">"{{ $course->title }}"</span> นี้หรือไม่? <br>
                                    <span class="text-base sm:text-lg md:text-xl lg:text-xl">
                                        วันที่อบรม:
                                        {{ $course->training_date ? \Carbon\Carbon::parse($course->training_date)->locale('th')->translatedFormat('j F') . ' ' . (\Carbon\Carbon::parse($course->training_date)->year + 543) : 'ไม่พบข้อมูล' }}
                                    </span>
                                </p>
                                <div class="flex justify-center space-x-4">
                                    <button @click="showConfirm = false"
                                        class="cursor-pointer px-5 sm:px-7 py-2 bg-red-500 dark:bg-red-700 text-white rounded-lg text-base sm:text-lg md:text-xl lg:text-xl font-medium transition-all duration-300 hover:brightness-110 hover:shadow-md w-20 sm:w-24 text-center">
                                        ไม่ใช่
                                    </button>
                                    <button wire:click="enroll" @click="showConfirm = false"
                                        class="cursor-pointer px-5 sm:px-7 py-2 bg-green-600 dark:bg-green-700 text-white dark:text-[#EDEDEC] rounded-lg text-base sm:text-lg md:text-xl lg:text-xl font-medium transition-all duration-300 hover:brightness-110 hover:shadow-md w-20 sm:w-24 text-center">
                                        ใช่
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Case: Not Enrolled, Course Full -->
                        <div class="bg-yellow-100 dark:bg-yellow-900/50 border-l-4 border-yellow-500 dark:border-yellow-400 text-yellow-700 dark:text-yellow-200 p-4"
                            role="alert">
                            <label class="text-base sm:text-lg md:text-xl lg:text-xl">
                                ขออภัย คอร์สเต็มแล้ว!
                            </label>
                        </div>
                    @endif
                @else
                    <!-- Enrollment Status -->
                    @if ($enrollmentStatus === 'pending')
                        <label class="text-xl sm:text-2xl md:text-3xl lg:text-3xl text-green-600 dark:text-green-400">
                            คุณได้ลงทะเบียนเรียนแล้ว!
                        </label>
                        <label class="text-xl sm:text-2xl md:text-3xl lg:text-3xl text-green-600 dark:text-green-400">
                            วันที่เปิดอบรม
                        </label>
                        <div class="text-xl sm:text-2xl md:text-3xl lg:text-3xl text-green-600 dark:text-green-400">
                            {{ $course->training_date ? \Carbon\Carbon::parse($course->training_date)->locale('th')->translatedFormat('j F') . ' ' . (\Carbon\Carbon::parse($course->training_date)->year + 543) : 'ไม่พบข้อมูล' }}
                        </div>
                    @elseif ($enrollmentStatus === 'confirmed')
                        @if (in_array(auth()->user()->role, ['student', 'general', 'insider']))
                            @if ($hasApprovedCertificate)
                                <label
                                    class="text-xl sm:text-2xl md:text-3xl lg:text-3xl text-green-600 dark:text-green-400">
                                    เจ้าหน้าที่ได้ทำการส่ง Certificate ไปยังบัญชีของท่านแล้ว
                                </label>
                            @elseif ($hasCertificate)
                                <label
                                    class="text-xl sm:text-2xl md:text-3xl lg:text-3xl text-green-600 dark:text-green-400">
                                    วิทยากรได้ทำการส่งคำขอ Certificate ไปยังเจ้าหน้าที่เรียบร้อยแล้ว
                                </label>
                            @endif
                        @endif
                    @endif
                @endif
            </div>
        @endif
    </div>
</div>

<script>
    document.title = "{{ $course->title }}";
</script>
