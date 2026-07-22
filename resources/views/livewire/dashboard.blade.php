{{-- เริ่มต้นการตรวจสอบ Role หลัก --}}
@if (in_array(auth()->user()->role, ['instructor', 'officer']))

    {{-- ====================================================== --}}
    {{-- ## Layout สำหรับ Instructor (แสดงผลแบบ 2 แถว) ## --}}
    {{-- ====================================================== --}}
    <div class="flex flex-col gap-6">
        {{-- แถวที่ 1: Div ยินดีต้อนรับ (ใช้ร่วมกัน) --}}
        <div class="w-full p-6 bg-white dark:bg-[#1b1b18] rounded-lg shadow-lg">
            <div class="text-4xl font-bold mb-6 text-[#1b1b18] dark:text-white">
                <span>ยินดีต้อนรับ, {{ auth()->user()->name }}</span>
            </div>
            <div class="text-xl mb-6 text-[#1b1b18] dark:text-white">
                <span>คุณกำลังอยู่ใน Dashboard สำหรับ:
                    <strong>{{ auth()->user()->role_display_name }}</strong>
                </span>
            </div>
            <div class="p-4 bg-green-100 dark:bg-green-900 rounded-md">
                <div class="text-xl text-[#1b1b18] dark:text-white">
                    <span>คุณสามารถจัดการคอร์สหรือเนื้อหาการสอนได้ที่ Menu ด้านซ้ายมือ</span>
                </div>
            </div>
        </div>

        {{-- แถวที่ 2: เนื้อหาตาม Role --}}
        <div class="w-full p-6 bg-white dark:bg-[#1b1b18] rounded-lg shadow-lg">

            @if (auth()->user()->role === 'instructor')
                {{-- เนื้อหาสำหรับ INSTRUCTOR --}}
                <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-white mb-6">คอร์สของคุณ</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($instructorCourses as $courseInfo)
                        <x-course-card :courseInfo="$courseInfo" />
                    @empty
                        <p class="md:col-span-2 lg:col-span-3 text-center text-gray-500 dark:text-gray-400 py-8">
                            คุณยังไม่มีคอร์สที่เป็นเจ้าของ</p>
                    @endforelse
                </div>
            @elseif (auth()->user()->role === 'officer')
                {{-- เนื้อหาสำหรับ OFFICER --}}
                <div>
                    {{-- 1. ส่วนของคอร์สที่ยังเปิดรับ --}}
                    <h2 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-6">คอร์สที่ยังเปิดรับสมัคร</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($availableCourses as $courseInfo)
                            <x-course-card :courseInfo="$courseInfo" />
                        @empty
                            <p class="md:col-span-2 lg:col-span-3 text-center text-gray-500 dark:text-gray-400 py-8">
                                ไม่มีคอร์สที่เปิดรับสมัครในขณะนี้</p>
                        @endforelse
                    </div>

                    <hr class="my-8 border-gray-300 dark:border-gray-700">

                    {{-- 2. ส่วนของคอร์สที่เต็มแล้ว --}}
                    <h2 class="text-2xl font-bold text-red-600 dark:text-red-500 mb-6">คอร์สที่เต็มแล้ว</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($fullCourses as $courseInfo)
                            <x-course-card :courseInfo="$courseInfo" />
                        @empty
                            <p class="md:col-span-2 lg:col-span-3 text-center text-gray-500 dark:text-gray-400 py-8">
                                ไม่มีคอร์สที่เต็มในขณะนี้</p>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    {{-- ==================================================================== --}}
    {{-- ## Layout สำหรับ Student / General / Insider (แสดงผลแบบ 2 คอลัมน์) ## --}}
    {{-- ==================================================================== --}}
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Div ที่ 1: ส่วนของเนื้อหาเดิม (ยินดีต้อนรับ) --}}
        <div class="lg:w-1/2 p-6 bg-white dark:bg-[#1b1b18] rounded-lg shadow-lg">
            <div class="text-4xl font-bold mb-6 text-[#1b1b18] dark:text-white">
                <span>ยินดีต้อนรับ, {{ auth()->user()->name }}</span>
            </div>
            <div class="text-xl mb-6 text-[#1b1b18] dark:text-white">
                <span>คุณกำลังอยู่ใน Dashboard สำหรับ:
                    <strong>{{ auth()->user()->role_display_name }}</strong>
                </span>
            </div>

            @if (in_array(auth()->user()->role, ['student', 'general', 'insider']))
                <div class="p-4 bg-green-100 dark:bg-green-900 rounded-md">
                    <div class="text-xl text-[#1b1b18] dark:text-white">
                        <span>คุณสามารถดูรายชื่อคอร์สและคอร์สที่ลงทะเบียนได้ที่ Menu ด้านซ้ายมือ</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Div ที่ 2: แสดง Tags ที่มีสไตล์เหมือนกับ l-o-wallet --}}
        @if (in_array(auth()->user()->role, ['student', 'general', 'insider']))
            <div x-data="{
                rememberingModalOpen: false,
                understandingModalOpen: false,
                applyingModalOpen: false,
                analyzingModalOpen: false,
                evaluatingModalOpen: false,
                creatingModalOpen: false
            }" x-init="initSkillChart()"
                class="lg:w-1/2 p-6 bg-white dark:bg-[#1b1b18] rounded-lg shadow-lg">

                <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-white mb-6">
                    ทักษะที่เรียนรู้แล้ว
                </h2>

                @if ($totalTags > 0)
                    <div class="w-full max-w-lg mx-auto mb-8 h-[320px] sm:h-[420px] flex items-center justify-center">
                        <canvas id="skillChart"></canvas>
                    </div>

                    <hr class="border-gray-300 dark:border-gray-700 my-6">

                    {{-- ส่วนที่ 2: Skill Cards (รายละเอียด) --}}
                    <h3 class="text-xl font-bold text-[#1b1b18] dark:text-white mb-4 text-center">
                        รายละเอียด
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-center">
                        <div @click="rememberingModalOpen = true" class="cursor-pointer">
                            <x-skill-card title="การจดจำ" :count="$tagCounts['Remembering']" colorName="sky" />
                        </div>
                        <div @click="understandingModalOpen = true" class="cursor-pointer">
                            <x-skill-card title="การทำความเข้าใจ" :count="$tagCounts['Understanding']" colorName="emerald" />
                        </div>
                        <div @click="applyingModalOpen = true" class="cursor-pointer">
                            <x-skill-card title="การประยุกต์" :count="$tagCounts['Applying']" colorName="amber" />
                        </div>
                        <div @click="analyzingModalOpen = true" class="cursor-pointer">
                            <x-skill-card title="การวิเคราะห์" :count="$tagCounts['Analyzing']" colorName="rose" />
                        </div>
                        <div @click="evaluatingModalOpen = true" class="cursor-pointer">
                            <x-skill-card title="การประเมิน" :count="$tagCounts['Evaluating']" colorName="indigo" />
                        </div>
                        <div @click="creatingModalOpen = true" class="cursor-pointer">
                            <x-skill-card title="การสร้างสรรค์" :count="$tagCounts['Creating']" colorName="purple" />
                        </div>
                    </div>

                    {{-- 🎯 3. เพิ่มโค้ด Modal สำหรับ "การจดจำ" --}}
                    <div x-show="rememberingModalOpen"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                        x-transition x-cloak>
                        <div @click.outside="rememberingModalOpen = false"
                            class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    Bloom Taxonomy: การจดจำ
                                </label>
                                <button type="button" @click="rememberingModalOpen = false"
                                    class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="mb-4 space-y-2 max-h-60 overflow-y-auto">
                                <p class="text-md text-gray-600 dark:text-gray-400">
                                    ทักษะนี้ได้รับมาจากคอร์สต่อไปนี้:
                                </p>
                                <ul class="list-disc list-inside text-[#1b1b18] dark:text-[#EDEDEC]">
                                    @forelse ($tagCourses['Remembering'] as $courseTitle)
                                        <li>{{ $courseTitle }}</li>
                                    @empty
                                        <li class="text-gray-500">ไม่พบข้อมูลคอร์ส</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" @click="rememberingModalOpen = false"
                                    class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                    ปิด
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-show="understandingModalOpen"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                        x-transition x-cloak>
                        <div @click.outside="understandingModalOpen = false"
                            class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    Bloom Taxonomy: การทำความเข้าใจ
                                </label>
                                <button type="button" @click="understandingModalOpen = false"
                                    class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="mb-4 space-y-2 max-h-60 overflow-y-auto">
                                <p class="text-md text-gray-600 dark:text-gray-400">
                                    ทักษะนี้ได้รับมาจากคอร์สต่อไปนี้:
                                </p>
                                <ul class="list-disc list-inside text-[#1b1b18] dark:text-[#EDEDEC]">
                                    @forelse ($tagCourses['Understanding'] as $courseTitle)
                                        <li>{{ $courseTitle }}</li>
                                    @empty
                                        <li class="text-gray-500">ไม่พบข้อมูลคอร์ส</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="understandingModalOpen = false"
                                    class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                    ปิด
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 🎯 5. เพิ่มโค้ด Modal สำหรับ "การประยุกต์" --}}
                    <div x-show="applyingModalOpen"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                        x-transition x-cloak>
                        <div @click.outside="applyingModalOpen = false"
                            class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    Bloom Taxonomy: การประยุกต์
                                </label>
                                <button type="button" @click="applyingModalOpen = false"
                                    class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="mb-4 space-y-2 max-h-60 overflow-y-auto">
                                <p class="text-md text-gray-600 dark:text-gray-400">
                                    ทักษะนี้ได้รับมาจากคอร์สต่อไปนี้:
                                </p>
                                <ul class="list-disc list-inside text-[#1b1b18] dark:text-[#EDEDEC]">
                                    @forelse ($tagCourses['Applying'] as $courseTitle)
                                        <li>{{ $courseTitle }}</li>
                                    @empty
                                        <li class="text-gray-500">ไม่พบข้อมูลคอร์ส</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="applyingModalOpen = false"
                                    class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                    ปิด
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 🎯 6. เพิ่มโค้ด Modal สำหรับ "การวิเคราะห์" --}}
                    <div x-show="analyzingModalOpen"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                        x-transition x-cloak>
                        <div @click.outside="analyzingModalOpen = false"
                            class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    Bloom Taxonomy: การวิเคราะห์
                                </label>
                                <button type="button" @click="analyzingModalOpen = false"
                                    class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="mb-4 space-y-2 max-h-60 overflow-y-auto">
                                <p class="text-md text-gray-600 dark:text-gray-400">
                                    ทักษะนี้ได้รับมาจากคอร์สต่อไปนี้:
                                </p>
                                <ul class="list-disc list-inside text-[#1b1b18] dark:text-[#EDEDEC]">
                                    @forelse ($tagCourses['Analyzing'] as $courseTitle)
                                        <li>{{ $courseTitle }}</li>
                                    @empty
                                        <li class="text-gray-500">ไม่พบข้อมูลคอร์ส</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="analyzingModalOpen = false"
                                    class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                    ปิด
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 🎯 7. เพิ่มโค้ด Modal สำหรับ "การประเมิน" --}}
                    <div x-show="evaluatingModalOpen"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                        x-transition x-cloak>
                        <div @click.outside="evaluatingModalOpen = false"
                            class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    Bloom Taxonomy: การประเมิน
                                </label>
                                <button type="button" @click="evaluatingModalOpen = false"
                                    class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="mb-4 space-y-2 max-h-60 overflow-y-auto">
                                <p class="text-md text-gray-600 dark:text-gray-400">
                                    ทักษะนี้ได้รับมาจากคอร์สต่อไปนี้:
                                </p>
                                <ul class="list-disc list-inside text-[#1b1b18] dark:text-[#EDEDEC]">
                                    @forelse ($tagCourses['Evaluating'] as $courseTitle)
                                        <li>{{ $courseTitle }}</li>
                                    @empty
                                        <li class="text-gray-500">ไม่พบข้อมูลคอร์ส</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="evaluatingModalOpen = false"
                                    class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                    ปิด
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 🎯 8. เพิ่มโค้ด Modal สำหรับ "การสร้างสรรค์" --}}
                    <div x-show="creatingModalOpen"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900/30 backdrop-blur-sm"
                        x-transition x-cloak>
                        <div @click.outside="creatingModalOpen = false"
                            class="bg-white/80 dark:bg-[#161615]/80 rounded-lg shadow-lg p-6 w-full max-w-md backdrop-blur-md">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                    Bloom Taxonomy: การสร้างสรรค์
                                </label>
                                <button type="button" @click="creatingModalOpen = false"
                                    class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="mb-4 space-y-2 max-h-60 overflow-y-auto">
                                <p class="text-md text-gray-600 dark:text-gray-400">
                                    ทักษะนี้ได้รับมาจากคอร์สต่อไปนี้:
                                </p>
                                <ul class="list-disc list-inside text-[#1b1b18] dark:text-[#EDEDEC]">
                                    @forelse ($tagCourses['Creating'] as $courseTitle)
                                        <li>{{ $courseTitle }}</li>
                                    @empty
                                        <li class="text-gray-500">ไม่พบข้อมูลคอร์ส</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="creatingModalOpen = false"
                                    class="cursor-pointer px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                                    ปิด
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- กรณีที่ยังไม่มีข้อมูลทักษะเลย --}}
                    <div class="flex-grow flex items-center justify-center py-10">
                        <p class="text-gray-500 dark:text-gray-400">
                            ยังไม่มีข้อมูลทักษะที่เรียนรู้แล้ว
                        </p>
                    </div>
                @endif

            </div>
        @endif
    </div>

@endif

<script>
    document.title = "Dashboard";
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let skillChart = null;

    function initSkillChart() {
        const canvas = document.getElementById('skillChart');
        if (!canvas || @json($totalTags) <= 0) {
            return;
        }
        if (skillChart) {
            skillChart.destroy();
        }

        const labels = @json($chartLabels);
        const data = @json($chartData);
        const isDarkMode = document.documentElement.classList.contains('dark');
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.1)';
        const labelColor = isDarkMode ? '#EDEDEC' : '#1b1b18';

        skillChart = new Chart(canvas, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนทักษะ',
                    data: [], // <-- 🎯 **จุดที่ 1: เริ่มต้นด้วยข้อมูลว่างเปล่า**
                    backgroundColor: 'rgba(56, 189, 248, 0.2)',
                    borderColor: '#38bdf8',
                    pointBackgroundColor: '#38bdf8',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#38bdf8',
                    borderWidth: 2,
                }]
            },
            options: {
                // ใช้อนิเมชัน config แบบเรียบง่าย
                animation: {
                    duration: 800,
                    easing: 'easeOutQuad'
                },
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    r: {
                        angleLines: {
                            color: gridColor
                        },
                        grid: {
                            color: gridColor
                        },
                        pointLabels: {
                            color: labelColor,
                            font: {
                                size: 12
                            }
                        },
                        ticks: {
                            display: false,
                            stepSize: 1
                        },
                        suggestedMin: 0,
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        // ... (ส่วน tooltip เหมือนเดิม)
                    }
                }
            }
        });

        // --- 🎯 จุดที่ 2: ใช้ setTimeout เพื่อป้อนข้อมูลและสั่ง update กราฟ ---
        setTimeout(() => {
            if (skillChart) {
                skillChart.data.datasets[0].data = data; // ใส่ข้อมูลจริงเข้าไป
                skillChart.update(); // สั่งให้กราฟวาดใหม่ (พร้อมอนิเมชัน)
            }
        }, 50); // หน่วงเวลาเล็กน้อยเพื่อให้กราฟถูกสร้างเสร็จก่อน
    }
</script>
