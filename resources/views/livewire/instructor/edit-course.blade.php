<div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if (session('updateCourse'))
        <x-popup message="{{ session('updateCourse') }}" bgColor="bg-green-500" darkBgColor="dark:bg-green-900" />
    @endif

    <div class="dark:bg-[#161615] space-y-2" x-cloak x-transition>
        <div class="flex flex-col">
            <label class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">แก้ไขคอร์ส</label>
            <label class="text-lg text-[#1b1b18] dark:text-[#A1A09A] mt-5">{{ $title }}</label>
        </div>

        <div>
            <hr class="border-b-2 border-gray-300 my-4">
        </div>

        <div>
            @if ($step === 1)
                <form wire:key="step-1-form" class="space-y-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="w-full">
                            <label for="title"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">COURSE
                                NAME</label>
                            <input wire:model.lazy="title" type="text" id="title" placeholder="กรอกชื่อคอร์ส"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                            @error('title')
                                <label class="text-red-500 text-sm mt-2">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="w-full">
                            <label for="duration"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">COURSE
                                LENGTH (Hours)</label>
                            <input wire:model.lazy="duration" type="number" id="duration"
                                placeholder="กรอกระยะเวลาคอร์ส (ชั่วโมง)"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                                required min = "1">
                            @error('duration')
                                <label class="text-red-500 text-sm mt-2">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="w-full">
                            <label for="max_students"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">จำนวนที่เปิดรับสมัคร</label>
                            <input wire:model="max_students" type="number" id="max_students"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                                placeholder="จำนวนเปิดรับสมัคร" required min="1" />
                            @error('max_students')
                                <label class="text-red-500 text-sm mt-2">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="w-full">
                            <label for="certificate"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">CERTIFICATE</label>
                            <div class="space-y-1 pt-2"> <label
                                    class="flex items-center cursor-pointer text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    <input type="radio" wire:model="certificate" value="1" id="yes"
                                        class="hidden peer" required />
                                    <div
                                        class="w-4 h-4 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-blue-500 peer-checked:bg-blue-500 duration-300">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                    <span class="ml-2 text-md text-[#1b1b18] dark:text-[#EDEDEC]">ออกใบรับรองได้</span>
                                </label>
                                <label
                                    class="flex items-center cursor-pointer text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    <input type="radio" wire:model="certificate" value="0" id="no"
                                        class="hidden peer" required />
                                    <div
                                        class="w-4 h-4 border-2 border-gray-500 rounded-full flex items-center justify-center peer-checked:border-red-500 peer-checked:bg-red-500 duration-300">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                    <span
                                        class="ml-2 text-md text-[#1b1b18] dark:text-[#EDEDEC]">ออกใบรับรองไม่ได้</span>
                                </label>
                            </div>
                            @error('certificate')
                                <label class="text-red-500 text-sm mt-2">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="w-full">
                            <label for="description"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">DESCRIPTION</label>
                            <textarea wire:model.lazy="description" id="description" placeholder="กรอกคำอธิบายคอร์ส"
                                class="resize-y block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                                rows="8" required></textarea>
                            @error('description')
                                <label class="text-red-500 text-sm mt-2">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="w-full">
                            <label for="instructor_name"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">INSTRUCTOR
                                NAME</label>
                            <input wire:model.lazy="instructor_name" type="text" id="instructor_name"
                                placeholder="ชื่อวิทยากร"
                                class="cursor-default block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-200 focus:outline-none dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                                readonly>
                            @error('instructor_name')
                                <label class="text-red-500 text-sm mt-2">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="w-full">
                            <label for="academic_position"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">ACADEMIC
                                POSITION</label>
                            <input wire:model.lazy="academic_position" type="text" id="academic_position"
                                placeholder="ตำแหน่ง"
                                class="cursor-default block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-200 focus:outline-none dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                                readonly>
                            @error('academic_position')
                                <label class="text-red-500 text-sm mt-2">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="w-full col-span-1 sm:col-span-2">
                            <div class="space-y-4 transition-all duration-300">
                                @foreach ($co_instructors as $index => $instructor)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6"
                                        wire:key="co-instructor-{{ $index }}">
                                        <div class="w-full">
                                            <label for="co_instructor_name_{{ $index }}"
                                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                                Co-Instructor
                                            </label>
                                            <input type="text"
                                                wire:model.defer="co_instructors.{{ $index }}"
                                                id="co_instructor_name_{{ $index }}"
                                                placeholder="ชื่อวิทยากรร่วม"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]" />
                                            @error("co_instructors.{$index}")
                                                <label class="text-red-500 text-sm mt-2">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="w-full">
                                            <label for="co_instructor_position_{{ $index }}"
                                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                                ACADEMIC POSITION
                                            </label>
                                            <div class="flex items-center space-x-2">
                                                <input type="text"
                                                    wire:model.defer="co_instructor_positions.{{ $index }}"
                                                    id="co_instructor_position_{{ $index }}"
                                                    placeholder="ตำแหน่ง (เช่น อาจารย์ผู้ช่วย)"
                                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]" />
                                                <button type="button"
                                                    wire:click="removeCoInstructor({{ $index }})"
                                                    class="cursor-pointer text-red-500 hover:text-red-700 flex-shrink-0">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            @error("co_instructor_positions.{$index}")
                                                <label class="text-red-500 text-sm mt-2">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" wire:click="addCoInstructor"
                                class="cursor-pointer mt-4 text-blue-500 hover:text-blue-700">
                                <i class="fas fa-plus"></i> เพิ่มวิทยากรร่วม
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="w-full">
                            <label for="start_date"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                วันที่เปิดสมัคร
                            </label>
                            <input wire:model.live="start_date_formatted" type="text" id="start_date"
                                class="cursor-pointer block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] start-date-picker"
                                placeholder="DD/MM/YYYY" required readonly autocomplete="off" />
                            <input wire:model="start_date" type="hidden" />
                            @error('start_date_formatted')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="w-full">
                            <label for="end_date"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                วันที่ปิดรับสมัคร
                            </label>
                            <input wire:model.live="end_date_formatted" type="text" id="end_date"
                                class="cursor-pointer block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] end-date-picker"
                                placeholder="DD/MM/YYYY" required readonly autocomplete="off" />
                            <input wire:model="end_date" type="hidden" />
                            @error('end_date_formatted')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="w-full">
                            <label for="training_date"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                วันที่อบรม
                            </label>
                            <input wire:model.live="training_date_formatted" type="text" id="training_date"
                                class="cursor-pointer block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] training-date-picker"
                                placeholder="DD/MM/YYYY" required readonly autocomplete="off" />
                            <input wire:model="training_date" type="hidden" />
                            @error('training_date_formatted')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="w-full">
                            <label for="end_training_date"
                                class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                วันที่สิ้นสุดการอบรม
                            </label>
                            <input wire:model.live="end_training_date_formatted" type="text"
                                id="end_training_date"
                                class="cursor-pointer block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] end-training-date-picker"
                                placeholder="DD/MM/YYYY" required readonly autocomplete="off" />
                            <input wire:model="end_training_date" type="hidden" />
                            @error('end_training_date_formatted')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="w-full">
                        <label for="image" class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            BANNER
                            <span class="text-red-500 ml-2 font-bold">OPTIONAL</span>
                            <span class="text-red-500 ml-2 font-bold">ขนาดสูงสุด 2MB</span>
                        </label>
                        <input wire:model="image" type="file" id="image"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                            accept="image/png, image/jpeg, image/jpg" />
                    </div>

                    <div class="pt-4">
                        <button type="button" wire:click="nextStep"
                            class="w-full sm:w-auto sm:mx-auto cursor-pointer block group/button relative items-center justify-center overflow-hidden rounded-md bg-blue-500 backdrop-blur-lg px-8 py-3 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-blue-600/50 border border-white/20">
                            <span class="text-base sm:text-lg">ถัดไป</span>
                        </button>
                    </div>

                </form>
            @elseif ($step === 2)
                <form wire:key="step-2-form" class="max-w-6xl space-y-2 m-auto">
                    <div>
                        @foreach ($topics as $topicIndex => $topic)
                            <div class="border border-gray-300 dark:border-gray-600 rounded-2xl p-6 shadow-xl mb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <!-- โซนชื่อหัวข้อ -->
                                    <div class="flex-1 pr-4 break-words">
                                        <span wire:click="toggleTopic({{ $topicIndex }})"
                                            class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] cursor-pointer hover:text-blue-500">
                                            Topic {{ $topicIndex + 1 }}:
                                            {{ $topics[$topicIndex]['title'] ?: 'Topic ใหม่' }}
                                        </span>
                                    </div>
                                    <!-- โซนไอคอน -->
                                    <div class="flex items-center space-x-4">
                                        <button type="button" wire:click="toggleTopic({{ $topicIndex }})"
                                            class="flex items-center">
                                            <svg class="cursor-pointer w-6 h-6 text-gray-600 transition-transform duration-300 {{ $collapsedTopics[$topicIndex] ? 'rotate-180' : 'rotate-0' }}"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </button>
                                        <button type="button" wire:click="removeTopic({{ $topicIndex }})"
                                            class="cursor-pointer group relative flex h-10 w-10 flex-col items-center justify-center overflow-hidden rounded-xl border-2 border-red-800 bg-red-400 hover:bg-red-600">
                                            <svg class="w-6 h-6 fill-white group-hover:fill-gray-100"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div
                                    class="transition-all duration-300 ease-in-out overflow-hidden {{ $collapsedTopics[$topicIndex] ? 'max-h-0 opacity-0' : 'opacity-100' }}">
                                    <div class="w-full rounded-lg mb-4">
                                        <input wire:model="topics.{{ $topicIndex }}.title" type="text"
                                            id="topic-title-{{ $topicIndex }}"
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                                            placeholder="ชื่อหัวข้อ" />
                                        @error("topics.{$topicIndex}.title")
                                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @foreach ($topic['lessons'] as $lessonIndex => $lesson)
                                        <div class="flex justify-between items-center mb-4 space-y-2">
                                            <!-- โซนชื่อบทเรียน -->
                                            <div class="flex-1 pr-4 break-words">
                                                <span
                                                    wire:click="toggleLesson({{ $topicIndex }}, {{ $lessonIndex }})"
                                                    class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] cursor-pointer hover:text-blue-500">
                                                    บทเรียน {{ $lessonIndex + 1 }}:
                                                    {{ $lesson['title'] ?: 'บทเรียนใหม่' }}
                                                </span>
                                            </div>
                                            <!-- โซนไอคอน -->
                                            <div class="flex items-center space-x-4">
                                                <button type="button"
                                                    wire:click="toggleLesson({{ $topicIndex }}, {{ $lessonIndex }})"
                                                    class="flex items-center">
                                                    <svg class="cursor-pointer w-6 h-6 text-gray-600 transition-transform duration-300 {{ $collapsedLessons["$topicIndex-$lessonIndex"] ? 'rotate-180' : 'rotate-0' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                </button>
                                                <button type="button"
                                                    wire:click="removeLesson({{ $topicIndex }}, {{ $lessonIndex }})"
                                                    class="cursor-pointer group relative flex h-10 w-10 flex-col items-center justify-center overflow-hidden rounded-xl border-2 border-red-800 bg-red-400 hover:bg-red-600">
                                                    <svg class="w-6 h-6 fill-white group-hover:fill-gray-100"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div
                                            class="transition-all duration-300 ease-in-out overflow-hidden {{ $collapsedLessons["$topicIndex-$lessonIndex"] ? 'max-h-0 opacity-0' : 'opacity-100' }}">
                                            <div class="w-full rounded-lg mb-4">
                                                <label for="lesson-title-{{ $topicIndex }}-{{ $lessonIndex }}"
                                                    class="block text-md font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    ชื่อบทเรียน
                                                </label>
                                                <input
                                                    wire:model="topics.{{ $topicIndex }}.lessons.{{ $lessonIndex }}.title"
                                                    type="text"
                                                    id="lesson-title-{{ $topicIndex }}-{{ $lessonIndex }}"
                                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                                                    placeholder="ชื่อบทเรียน" />
                                                @error("topics.{$topicIndex}.lessons.{$lessonIndex}.title")
                                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="w-full rounded-lg mb-4">
                                                <label for="lesson-content-{{ $topicIndex }}-{{ $lessonIndex }}"
                                                    class="block text-md font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    เนื้อหาบทเรียน
                                                </label>
                                                <textarea wire:model="topics.{{ $topicIndex }}.lessons.{{ $lessonIndex }}.content"
                                                    id="lesson-content-{{ $topicIndex }}-{{ $lessonIndex }}"
                                                    class="resize-y block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                                                    rows="8" placeholder="เนื้อหาบทเรียน | ตัวอย่าง: เรียนรู้เกี่ยวกับการเขียนโปรแกรมพื้นฐาน"></textarea>
                                                @error("topics.{$topicIndex}.lessons.{$lessonIndex}.content")
                                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="w-full rounded-lg">
                                                <label
                                                    class="block text-md font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    ผลลัพธ์การเรียนรู้
                                                </label>
                                                @foreach ($lesson['learning_outcomes'] as $outcomeIndex => $outcome)
                                                    <div class="flex items-center gap-2 mb-2 relative"
                                                        wire:key="outcome-{{ $topicIndex }}-{{ $lessonIndex }}-{{ $outcomeIndex }}">

                                                        {{-- Input สำหรับ Description --}}
                                                        <input
                                                            wire:model.debounce.200ms="topics.{{ $topicIndex }}.lessons.{{ $lessonIndex }}.learning_outcomes.{{ $outcomeIndex }}.description"
                                                            type="text"
                                                            class="learning-outcome-input block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                                                            placeholder="ผลลัพธ์การเรียนรู้" />

                                                        {{-- Select สำหรับ Tags --}}
                                                        <select
                                                            wire:model="topics.{{ $topicIndex }}.lessons.{{ $lessonIndex }}.learning_outcomes.{{ $outcomeIndex }}.tag_id"
                                                            class="block w-48 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                                            <option value="" disabled selected hidden>Select
                                                                tag
                                                            </option>
                                                            @foreach ($allTags as $tag)
                                                                <option value="{{ $tag->id }}">
                                                                    {{ $tag->slug }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        {{-- ปุ่มลบ --}}
                                                        <button type="button"
                                                            wire:click="removeLearningOutcome({{ $topicIndex }}, {{ $lessonIndex }}, {{ $outcomeIndex }})"
                                                            class="cursor-pointer text-red-500 hover:text-red-700">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    @error("topics.{$topicIndex}.lessons.{$lessonIndex}.learning_outcomes.{$outcomeIndex}.description")
                                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                                    @enderror
                                                @endforeach
                                                <button type="button"
                                                    wire:click.debounce.200ms="addLearningOutcome({{ $topicIndex }}, {{ $lessonIndex }})"
                                                    class="cursor-pointer text-blue-500 hover:text-blue-700 mt-2 mb-10">
                                                    + เพิ่มผลลัพธ์การเรียนรู้
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="flex justify-end">
                                        <button type="button" wire:click="addLesson({{ $topicIndex }})"
                                            class="cursor-pointer mt-4 flex items-center px-4 py-2 rounded-md">
                                            <svg class="w-6 h-6 stroke-blue-500" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 12h12" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v12" />
                                            </svg>
                                            <span class="text-blue-500 text-xl">เพิ่มบทเรียน</span>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                        <button type="button" wire:click="addTopic"
                            class="cursor-pointer mt-4 mb-5 flex items-center rounded-md bg-blue-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-blue-600/50 border border-white/20">
                            <svg class="w-6 h-6 stroke-white dark:stroke-gray-300" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h12" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12" />
                            </svg>
                            <span class="text-white text-lg">เพิ่มหัวข้อ</span>
                        </button>
                    </div>

                    <div class="flex justify-between max-w-5xl mx-auto mt-6">
                        <button type="button" wire:click="prevStep"
                            class="cursor-pointer w-[250px] group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-red-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-red-600/50 border border-white/20">
                            <span class="text-base sm:text-lg">ย้อนกลับ</span>
                        </button>

                        <button type="button" wire:click="nextStep"
                            class="cursor-pointer w-[250px] group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-blue-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-blue-600/50 border border-white/20">
                            <span class="text-base sm:text-lg">ถัดไป</span>
                        </button>
                    </div>
                </form>
            @elseif ($step === 3)
                <div class="max-w-6xl mx-auto mt-8 p-6">
                    <label class="text-2xl font-bold flex justify-center mb-6 text-gray-900 dark:text-gray-100">
                        สรุปข้อมูลคอร์ส
                    </label>

                    <div class="border border-gray-300 dark:border-gray-600 rounded-2xl p-6 shadow-xl mb-4">
                        <div class="flex flex-col sm:flex-row border-b border-gray-300 dark:border-gray-600 py-2">
                            <div class="w-full pr-0 sm:pr-4">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    ชื่อคอร์ส
                                </label>
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                    {{ $title }}
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row border-b border-gray-300 dark:border-gray-600 py-2">
                            <div class="w-full sm:w-1/3 pr-0 sm:pr-4">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    ระยะเวลาอบรม (ชั่วโมง)
                                </label>
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                    {{ $duration }}
                                </label>
                            </div>
                            <div
                                class="w-full sm:w-1/3 pl-0 sm:pl-4 border-t sm:border-t-0 border-gray-300 dark:border-gray-600 sm:border-l pt-2 sm:pt-0">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    จำนวนที่เปิดรับสมัคร(คน)
                                </label>
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                    {{ $max_students }}
                                </label>
                            </div>
                            <div
                                class="w-full sm:w-1/3 pl-0 sm:pl-4 border-t sm:border-t-0 border-gray-300 dark:border-gray-600 sm:border-l pt-2 sm:pt-0">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    ออกใบรับรอง
                                </label>
                                <div class="flex items-center gap-4 mt-1">
                                    <label class="flex items-center">
                                        <span class="ml-2 text-md text-[#1b1b18] dark:text-[#EDEDEC]">
                                            {{ $certificate ? 'ออกใบรับรองได้' : 'ออกใบรับรองไม่ได้' }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row border-b border-gray-300 dark:border-gray-600 py-2">
                            <div class="w-full sm:w-1/2 pr-0 sm:pr-4">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    ชื่อวิทยากร
                                </label>
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                    {{ $instructor_name }}
                                </label>
                            </div>

                            <div
                                class="w-full sm:w-1/2 pl-0 sm:pl-4 border-t sm:border-t-0 border-gray-300 dark:border-gray-600 sm:border-l pt-2 sm:pt-0">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    ตำแหน่ง
                                </label>
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                    {{ $academic_position }}
                                </label>
                            </div>
                        </div>
                        @php
                            // กรองเอาเฉพาะชื่อวิทยากรร่วมที่ถูกกรอกข้อมูลแล้ว (ไม่ใช่ค่าว่าง)
                            $filledCoInstructors = array_filter($co_instructors);
                        @endphp

                        @if (!empty($filledCoInstructors))
                            @foreach ($filledCoInstructors as $index => $instructorName)
                                <div
                                    class="flex flex-col sm:flex-row border-b border-gray-300 dark:border-gray-600 py-2">
                                    <div class="w-full sm:w-1/2 pr-0 sm:pr-4">
                                        <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                            ชื่อวิทยากรร่วม
                                        </label>
                                        <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                            {{ $instructorName }}
                                        </label>
                                    </div>
                                    <div
                                        class="w-full sm:w-1/2 pl-0 sm:pl-4 border-t sm:border-t-0 border-gray-300 dark:border-gray-600 sm:border-l pt-2 sm:pt-0">
                                        <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                            ตำแหน่ง
                                        </label>
                                        <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                            {{ $co_instructor_positions[$index] ?? 'ไม่มีตำแหน่ง' }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex flex-col sm:flex-row border-b border-gray-300 dark:border-gray-600 py-2">
                                <div class="w-full pr-0 sm:pr-4">
                                    <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                        ชื่อวิทยากรร่วม
                                    </label>
                                    <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                        ไม่มีวิทยากรร่วม
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row border-b border-gray-300 dark:border-gray-600 py-2">
                            <div class="w-full sm:w-1/4 pr-0 sm:pr-4">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    วันที่เปิดสมัคร
                                </label>
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                    {{ $start_date_formatted }}
                                </label>
                            </div>
                            <div
                                class="w-full sm:w-1/4 pl-0 sm:pl-4 border-t sm:border-t-0 border-gray-300 dark:border-gray-600 sm:border-l pt-2 sm:pt-0">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    วันที่ปิดรับสมัคร
                                </label>
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                    {{ $end_date_formatted }}
                                </label>
                            </div>
                            <div
                                class="w-full sm:w-1/4 pl-0 sm:pl-4 border-t sm:border-t-0 border-gray-300 dark:border-gray-600 sm:border-l pt-2 sm:pt-0">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    วันที่อบรม
                                </label>
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                    {{ $training_date_formatted }}
                                </label>
                            </div>
                            <div
                                class="w-full sm:w-1/4 pl-0 sm:pl-4 border-t sm:border-t-0 border-gray-300 dark:border-gray-600 sm:border-l pt-2 sm:pt-0">
                                <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                    วันที่ปิดอบรม
                                </label>
                                <label class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2">
                                    {{ $end_training_date_formatted }}
                                </label>
                            </div>
                        </div>

                        <div>
                            @if ($imagePath)
                                <div class="border-b border-gray-300 dark:border-gray-600 py-2">
                                    <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                        รูปภาพคอร์ส
                                    </label>
                                    <div class="mt-2">
                                        <img src="{{ $image->temporaryUrl() }}" alt="Course Image"
                                            class="w-full max-h-64 object-cover rounded-lg" />
                                    </div>
                                </div>
                            @else
                                <div class="border-b border-gray-300 dark:border-gray-600 py-2">
                                    <label class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                        รูปภาพคอร์ส
                                    </label>
                                    <div
                                        class="w-full h-48 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400 mt-2">
                                        ไม่มีรูปภาพ
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-5">
                            <label class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                                Topics & Lessons
                            </label>
                            @foreach ($topics as $topicIndex => $topic)
                                <div class="border-b border-gray-300 dark:border-gray-600 mt-5 pb-4">
                                    <label class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 mt-5">
                                        หัวข้อ {{ $topicIndex + 1 }}:
                                    </label>
                                    <label
                                        class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-1">{{ $topic['title'] }}</label>

                                    <div>
                                        @foreach ($topic['lessons'] as $lessonIndex => $lesson)
                                            <div class="mt-3">
                                                <label
                                                    class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">เรื่องที่
                                                    {{ $lessonIndex + 1 }}: </label>
                                                <label
                                                    class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-1">{{ $lesson['title'] }}</label>
                                            </div>

                                            <div class="mt-3">
                                                <label
                                                    class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">รายละเอียด</label>
                                                <div class="text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 text-thai-distributed"
                                                    lang="{{ preg_match('/[ก-๙]/', $lesson['content']) ? 'th' : 'en' }}">
                                                    {{ $lesson['content'] }}
                                                </div>
                                            </div>

                                            @php
                                                // 1. แก้ไขการกรอง: ให้ตรวจสอบที่ $outcome['description'] แทน
                                                $nonEmptyOutcomes = array_filter(
                                                    $lesson['learning_outcomes'],
                                                    fn($outcome) => !empty(trim($outcome['description'])),
                                                );
                                            @endphp

                                            @if (!empty($nonEmptyOutcomes))
                                                <div class="mt-2 mb-5">
                                                    <label
                                                        class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                                        ผลลัพธ์การเรียนรู้
                                                    </label>
                                                    <ul
                                                        class="list-disc pl-5 text-md text-[#1b1b18] dark:text-[#EDEDEC] mt-1 ml-2 leading-relaxed space-y-2">
                                                        @foreach ($nonEmptyOutcomes as $outcome)
                                                            <li>
                                                                {{-- 2. แก้ไขการแสดงผล: ให้แสดง $outcome['description'] --}}
                                                                {{ $outcome['description'] }}

                                                                {{-- 3. เพิ่มการแสดงผล Tag --}}
                                                                @if (!empty($outcome['tag_id']))
                                                                    @php
                                                                        // ค้นหาชื่อ Tag จาก $allTags ที่เราส่งมา
                                                                        $tagName =
                                                                            $allTags->firstWhere(
                                                                                'id',
                                                                                $outcome['tag_id'],
                                                                            )->slug ?? 'ไม่พบแท็ก';
                                                                    @endphp
                                                                    <label
                                                                        class="ml-2 text-xs font-semibold bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                                                        {{ $tagName }}
                                                                    </label>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <label
                                                    class="block text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 mt-5">
                                                    ไม่มีผลลัพธ์การเรียนรู้
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                <div class="flex justify-between max-w-5xl mx-auto mt-6">
                    <button type="button" wire:click="prevStep"
                        class="cursor-pointer w-[250px] group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-red-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-red-600/50 border border-white/20">
                        <span class="text-base sm:text-lg">ย้อนกลับ</span>
                    </button>

                    <button type="button" wire:click="update"
                        class="cursor-pointer w-[250px] group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-blue-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-blue-600/50 border border-white/20">
                        <span class="text-base sm:text-lg">บันทึก</span>
                    </button>
                </div>
            @endif
        </div>

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('focusLesson', (event) => {
                    const topicIndex = event.topicIndex;
                    const lessonIndex = event.lessonIndex;
                    const inputId = `lesson-title-${topicIndex}-${lessonIndex}`;
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('focusLearningOutcome', (event) => {
                    const {
                        topicIndex,
                        lessonIndex,
                        outcomeIndex
                    } = event;
                    const input = document.querySelector(
                        `[data-index="${topicIndex}-${lessonIndex}-${outcomeIndex}"]`
                    );
                    if (input) {
                        input.focus();
                        const alpineData = input.closest('[x-data]').__x;
                        if (alpineData) {
                            alpineData.showInput = true;
                        }
                    }
                });
            });
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.2/pikaday.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.2/css/pikaday.min.css" />

        <script>
            let startPicker, endPicker, trainingPicker, endTrainingPicker;

            function parseDate(str) {
                if (!str) return null;
                const [day, month, year] = str.split('/').map(Number);
                return new Date(year, month - 1, day);
            }

            function formatDate(date) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            function formatDbDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function initializePikaday() {
                // Pikaday สำหรับ start_date
                const startDateField = document.getElementById('start_date');
                if (startDateField && !startDateField.pikaday) {
                    const startDateValue = startDateField.value;
                    console.log('Initializing start_date Pikaday, value:', startDateValue);
                    const defaultStartDate = parseDate(startDateValue) || new Date();

                    startPicker = new Pikaday({
                        field: startDateField,
                        format: 'DD/MM/YYYY',
                        minDate: new Date(),
                        toString(date) {
                            return formatDate(date);
                        },
                        onSelect: function(date) {
                            const formattedDate = formatDate(date);
                            const dbDate = formatDbDate(date);
                            console.log('Start date selected:', formattedDate, dbDate);
                            @this.set('start_date_formatted', formattedDate);
                            @this.set('start_date', dbDate);

                            if (endPicker) {
                                endPicker.setDate(null);
                                @this.set('end_date_formatted', '');
                                @this.set('end_date', '');
                                endPicker.setMinDate(date);
                            }
                            if (trainingPicker) {
                                trainingPicker.setDate(null);
                                @this.set('training_date_formatted', '');
                                @this.set('training_date', '');
                            }
                            if (endTrainingPicker) {
                                endTrainingPicker.setDate(null);
                                @this.set('end_training_date_formatted', '');
                                @this.set('end_training_date', '');
                            }
                        }
                    });

                    if (startDateValue) {
                        startPicker.setDate(parseDate(startDateValue), true);
                        startPicker.gotoDate(parseDate(startDateValue));
                    }
                    startDateField.pikaday = startPicker;
                }

                // Pikaday สำหรับ end_date
                const endDateField = document.getElementById('end_date');
                if (endDateField && !endDateField.pikaday) {
                    const endDateValue = endDateField.value;
                    console.log('Initializing end_date Pikaday, value:', endDateValue);
                    const defaultEndDate = parseDate(endDateValue) || null;

                    endPicker = new Pikaday({
                        field: endDateField,
                        format: 'DD/MM/YYYY',
                        minDate: parseDate(startDateField.value) || new Date(),
                        toString(date) {
                            return formatDate(date);
                        },
                        onSelect: function(date) {
                            const formattedDate = formatDate(date);
                            const dbDate = formatDbDate(date);
                            console.log('End date selected:', formattedDate, dbDate);
                            @this.set('end_date_formatted', formattedDate);
                            @this.set('end_date', dbDate);

                            if (trainingPicker) {
                                const nextDay = new Date(date);
                                nextDay.setDate(date.getDate() + 1);
                                if (trainingPicker.getDate() && trainingPicker.getDate() < nextDay) {
                                    trainingPicker.setDate(null);
                                    @this.set('training_date_formatted', '');
                                    @this.set('training_date', '');
                                }
                                trainingPicker.setMinDate(nextDay);
                            }
                            if (endTrainingPicker) {
                                endTrainingPicker.setDate(null);
                                @this.set('end_training_date_formatted', '');
                                @this.set('end_training_date', '');
                            }
                        }
                    });

                    if (endDateValue) {
                        endPicker.setDate(parseDate(endDateValue), true);
                        endPicker.gotoDate(parseDate(endDateValue));
                    }
                    endDateField.pikaday = endPicker;
                }

                // Pikaday สำหรับ training_date
                const trainingDateField = document.getElementById('training_date');
                if (trainingDateField && !trainingDateField.pikaday) {
                    const trainingDateValue = trainingDateField.value;
                    console.log('Initializing training_date Pikaday, value:', trainingDateValue);
                    const defaultTrainingDate = parseDate(trainingDateValue) || null;

                    let trainingMinDate = parseDate(endDateField.value);
                    if (trainingMinDate) {
                        trainingMinDate = new Date(trainingMinDate);
                        trainingMinDate.setDate(trainingMinDate.getDate() + 1);
                    } else {
                        trainingMinDate = new Date();
                    }

                    trainingPicker = new Pikaday({
                        field: trainingDateField,
                        format: 'DD/MM/YYYY',
                        minDate: trainingMinDate,
                        toString(date) {
                            return formatDate(date);
                        },
                        onSelect: function(date) {
                            const formattedDate = formatDate(date);
                            const dbDate = formatDbDate(date);
                            console.log('Training date selected:', formattedDate, dbDate);
                            @this.set('training_date_formatted', formattedDate);
                            @this.set('training_date', dbDate);

                            if (endTrainingPicker) {
                                if (endTrainingPicker.getDate() && endTrainingPicker.getDate() < date) {
                                    endTrainingPicker.setDate(null);
                                    @this.set('end_training_date_formatted', '');
                                    @this.set('end_training_date', '');
                                }
                                endTrainingPicker.setMinDate(date);
                            }
                        }
                    });

                    if (trainingDateValue) {
                        trainingPicker.setDate(parseDate(trainingDateValue), true);
                        trainingPicker.gotoDate(parseDate(trainingDateValue));
                    }
                    trainingDateField.pikaday = trainingPicker;
                }

                // Pikaday สำหรับ end_training_date
                const endTrainingDateField = document.getElementById('end_training_date');
                if (endTrainingDateField && !endTrainingDateField.pikaday) {
                    const endTrainingDateValue = endTrainingDateField.value;
                    console.log('Initializing end_training_date Pikaday, value:', endTrainingDateValue);
                    const defaultEndTrainingDate = parseDate(endTrainingDateValue) || null;

                    let endTrainingMinDate = parseDate(trainingDateField.value);
                    if (endTrainingMinDate) {
                        endTrainingMinDate = new Date(endTrainingMinDate);
                    } else {
                        endTrainingMinDate = new Date();
                    }

                    endTrainingPicker = new Pikaday({
                        field: endTrainingDateField,
                        format: 'DD/MM/YYYY',
                        minDate: endTrainingMinDate,
                        toString(date) {
                            return formatDate(date);
                        },
                        onSelect: function(date) {
                            const formattedDate = formatDate(date);
                            const dbDate = formatDbDate(date);
                            console.log('End training date selected:', formattedDate, dbDate);
                            @this.set('end_training_date_formatted', formattedDate);
                            @this.set('end_training_date', dbDate);
                        }
                    });

                    if (endTrainingDateValue) {
                        endTrainingPicker.setDate(parseDate(endTrainingDateValue), true);
                        endTrainingPicker.gotoDate(parseDate(endTrainingDateValue));
                    }
                    endTrainingDateField.pikaday = endTrainingPicker;
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                initializePikaday();
            });

            document.addEventListener('livewire:load', function() {
                Livewire.on('initialPikadaySync', (data) => {
                    console.log('Initial Pikaday sync received:', data);
                    const startDateField = document.getElementById('start_date');
                    const endDateField = document.getElementById('end_date');
                    const trainingDateField = document.getElementById('training_date');
                    const endTrainingDateField = document.getElementById('end_training_date');

                    if (startDateField && startDateField.pikaday && data.start_date_formatted) {
                        startDateField.pikaday.setDate(parseDate(data.start_date_formatted), true);
                        startDateField.pikaday.gotoDate(parseDate(data.start_date_formatted));
                    }
                    if (endDateField && endDateField.pikaday && data.end_date_formatted) {
                        endDateField.pikaday.setDate(parseDate(data.end_date_formatted), true);
                        endDateField.pikaday.gotoDate(parseDate(data.end_date_formatted));
                        endDateField.pikaday.setMinDate(parseDate(data.start_date_formatted) || new Date());
                    }
                    if (trainingDateField && trainingDateField.pikaday && data.training_date_formatted) {
                        trainingDateField.pikaday.setDate(parseDate(data.training_date_formatted), true);
                        trainingDateField.pikaday.gotoDate(parseDate(data.training_date_formatted));
                        const minDate = parseDate(data.end_date_formatted);
                        if (minDate) {
                            minDate.setDate(minDate.getDate() + 1);
                            trainingDateField.pikaday.setMinDate(minDate);
                        }
                    }
                    if (endTrainingDateField && endTrainingDateField.pikaday && data
                        .end_training_date_formatted) {
                        endTrainingDateField.pikaday.setDate(parseDate(data.end_training_date_formatted), true);
                        endTrainingDateField.pikaday.gotoDate(parseDate(data.end_training_date_formatted));
                        endTrainingDateField.pikaday.setMinDate(parseDate(data.training_date_formatted) ||
                            new Date());
                    }
                });

                Livewire.on('updatePikaday', (data) => {
                    console.log('Received updatePikaday:', data);
                    if (startPicker && data.start_date_formatted) {
                        startPicker.setDate(parseDate(data.start_date_formatted), true);
                        startPicker.gotoDate(parseDate(data.start_date_formatted));
                    }
                    if (endPicker && data.end_date_formatted) {
                        endPicker.setDate(parseDate(data.end_date_formatted), true);
                        endPicker.gotoDate(parseDate(data.end_date_formatted));
                        endPicker.setMinDate(parseDate(data.start_date_formatted) || new Date());
                    }
                    if (trainingPicker && data.training_date_formatted) {
                        trainingPicker.setDate(parseDate(data.training_date_formatted), true);
                        trainingPicker.gotoDate(parseDate(data.training_date_formatted));
                        const minDate = parseDate(data.end_date_formatted);
                        if (minDate) {
                            minDate.setDate(minDate.getDate() + 1);
                            trainingPicker.setMinDate(minDate);
                        }
                    }
                    if (endTrainingPicker && data.end_training_date_formatted) {
                        endTrainingPicker.setDate(parseDate(data.end_training_date_formatted), true);
                        endTrainingPicker.gotoDate(parseDate(data.end_training_date_formatted));
                        endTrainingPicker.setMinDate(parseDate(data.training_date_formatted) || new Date());
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const targetNode = document.querySelector('div.relative.w-full');
                if (!targetNode) {
                    console.warn('MutationObserver target node not found');
                    return;
                }

                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.addedNodes.length || mutation.removedNodes.length) {
                            console.log('DOM changed, reinitializing Pikaday');
                            initializePikaday();
                        }
                    });
                });

                observer.observe(targetNode, {
                    childList: true,
                    subtree: true
                });
            });
        </script>
    </div>
</div>

<script>
    document.title = "แก้ไขคอร์สเรียน";
</script>
