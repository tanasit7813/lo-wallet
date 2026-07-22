<div class="w-full mx-auto p-4 sm:p-6 bg-white dark:bg-[#1b1b18]">
    <div class="mx-auto mb-5">
        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-6">
            คอร์สเรียนทั้งหมด
        </h1>
    </div>
    {{-- ตรวจสอบว่ามีคอร์สเรียนอย่างน้อย 1 คอร์สหรือไม่ --}}
    @if ($courses->isNotEmpty())
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 sm:gap-6 md:gap-4 lg:gap-6 xl:gap-4 2xl:gap-6">
            @foreach ($courses as $course)
                <div
                    class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-gray-700 rounded-xl hover:shadow-lg hover:shadow-gray-500/50 dark:hover:shadow-gray-600/50 duration-300 overflow-hidden shadow-md min-w-0 flex flex-col">
                    @if ($course->image)
                        <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image"
                            class="w-full h-40 sm:h-44 md:h-48 lg:h-52 xl:h-48 2xl:h-52 object-cover rounded-t-md flex-shrink-0">
                    @else
                        <div
                            class="w-full h-40 sm:h-44 md:h-48 lg:h-52 xl:h-48 2xl:h-52 flex items-center justify-center rounded-t-lg dark:bg-[#161615] border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 text-sm sm:text-base md:text-lg lg:text-base xl:text-sm 2xl:text-base flex-shrink-0">
                            <img src="{{ asset('img/insert-img.png') }}" alt="Course Image"
                                class="w-full h-full object-cover rounded-t-md">
                        </div>
                    @endif

                    <div class="p-3 sm:p-4 md:p-4 lg:p-5 xl:p-4 2xl:p-5 flex flex-col space-y-2 flex-grow min-h-0">
                        {{-- ชื่อคอร์ส --}}
                        <div class="flex-shrink-0">
                            <label
                                class="font-bold text-base md:text-lg text-[#1b1b18] dark:text-[#EDEDEC] line-clamp-2 block">
                                {{ $course->title }}
                            </label>
                        </div>
                        <hr class="border-gray-300 dark:border-gray-600 flex-shrink-0" />

                        {{-- ชื่อวิทยากร --}}
                        <div class="flex-shrink-0">
                            <label class="font-bold text-md text-[#1b1b18] dark:text-[#EDEDEC]">โดย:
                            </label>
                            <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] break-words">
                                {{ $course->instructors->first() ? $course->instructors->first()->user->name : 'ไม่ระบุ' }}
                            </label>
                        </div>

                        {{-- ส่วนที่แสดงจำนวนผู้ลงทะเบียน --}}
                        <div class="flex-shrink-0">
                            <label class="font-bold text-md text-[#1b1b18] dark:text-[#EDEDEC]">
                                จำนวนที่รับ:
                            </label>

                            @if ($course->enrollments_count >= $course->max_students)
                                <span class="text-md font-semibold text-red-600 dark:text-red-400">
                                    เต็มแล้ว
                                </span>
                            @else
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] break-words">
                                    {{ $course->enrollments_count }} / {{ $course->max_students }}
                                </label>
                            @endif
                        </div>

                        <div class="flex flex-col gap-2 sm:gap-3 md:gap-2 lg:gap-3 xl:gap-2 2xl:gap-3 mt-auto pt-3">
                            @if (in_array(auth()->user()->role, ['student', 'general', 'insider']))
                                <a href="{{ route('course.detail', $course->id) }}"
                                    class="w-full text-center px-3 sm:px-4 md:px-3 lg:px-4 xl:px-3 2xl:px-4 py-2 sm:py-2.5 md:py-2 lg:py-2.5 xl:py-2 2xl:py-2.5 bg-blue-500 dark:bg-blue-600 text-white rounded-md text-xs sm:text-sm md:text-base lg:text-sm xl:text-xs 2xl:text-sm font-semibold hover:bg-blue-600 dark:hover:bg-blue-500 transition duration-300">
                                    รายละเอียด
                                </a>
                            @elseif (auth()->user()->role === 'instructor' || auth()->user()->role === 'officer')
                                <a href="{{ route('instructor.course.detail', $course->id) }}"
                                    class="w-full text-center px-2 sm:px-3 md:px-2 lg:px-3 xl:px-2 2xl:px-3 py-2 sm:py-2.5 md:py-2 lg:py-2.5 xl:py-2 2xl:py-2.5 bg-blue-500 dark:bg-blue-600 text-white rounded-md text-xs sm:text-sm md:text-base lg:text-sm xl:text-xs 2xl:text-sm font-semibold hover:bg-blue-600 dark:hover:bg-blue-500 transition duration-300 border border-white/20 dark:border-gray-600">
                                    รายชื่อผู้ลงทะเบียน
                                </a>
                                <a href="{{ route('instructor.course_completions', $course->id) }}"
                                    class="w-full text-center px-2 sm:px-3 md:px-2 lg:px-3 xl:px-2 2xl:px-3 py-2 sm:py-2.5 md:py-2 lg:py-2.5 xl:py-2 2xl:py-2.5 bg-green-600 dark:bg-green-700 text-white rounded-md text-xs sm:text-sm md:text-base lg:text-sm xl:text-xs 2xl:text-sm font-semibold hover:bg-green-700 dark:hover:bg-green-600 transition duration-300 border border-white/20 dark:border-gray-600">
                                    รายชื่อผู้ผ่านการอบรม
                                </a>
                                @if (auth()->user()->role === 'instructor')
                                    <a href="{{ route('instructor.course.edit', $course->id) }}"
                                        class="w-full text-center px-2 sm:px-3 md:px-2 lg:px-3 xl:px-2 2xl:px-3 py-2 sm:py-2.5 md:py-2 lg:py-2.5 xl:py-2 2xl:py-2.5 bg-red-500 dark:bg-red-600 text-white rounded-md text-xs sm:text-sm md:text-base lg:text-sm xl:text-xs 2xl:text-sm font-semibold hover:bg-red-600 dark:hover:bg-red-500 transition duration-300 border border-white/20 dark:border-gray-600">
                                        แก้ไขคอร์ส
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- หากไม่มีคอร์สเรียน ให้แสดงข้อความนี้ --}}
        <div class="w-full text-center py-16">
            <p class="text-gray-500 dark:text-gray-400 text-xl sm:text-2xl md:text-3xl lg:text-3xl">
                ไม่พบคอร์สที่เปิดให้ลงทะเบียน ณ เวลานี้
            </p>
        </div>
    @endif
</div>

<script>
    document.title = "คอร์สเรียนทั้งหมด";
</script>
