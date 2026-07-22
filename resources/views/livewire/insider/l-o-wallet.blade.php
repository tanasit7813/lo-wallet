<div class="w-full mx-auto p-4 sm:p-6 dark:bg-gray-900 min-h-screen">
    <div
        class="w-full lg:w-[210mm] lg:h-[297mm] mx-auto bg-white dark:bg-gray-900 shadow-md rounded-lg p-4 sm:p-6 lg:p-[15mm] border border-gray-200 dark:border-gray-700 overflow-auto print:shadow-none print:border-none print:p-[15mm] flex flex-col print:overflow-visible print:w-[210mm] print:h-[297mm]">

        <div class="flex justify-end items-center mb-4 sm:mb-6">
            <div class="text-xs sm:text-sm md:text-base lg:text-base text-gray-600 dark:text-gray-400">
                {{ now()->format('d/m/Y') }}
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-4 pb-4">
            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100">
                LOWallet - Transcript
            </h1>
            <div
                class="mt-10 sm:mt-10 grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 text-xs sm:text-sm md:text-base lg:text-base text-gray-900 dark:text-gray-100">
                <!-- Left Column -->
                <div class="text-left space-y-2">
                    <p class="user-info-label" data-label="ชื่อ: ">{{ $userData['name'] }}</p>
                    <p class="user-info-label" data-label="อีเมล: ">{{ $userData['email'] }}</p>
                    <p class="user-info-label" data-label="เบอร์โทร: ">{{ $userData['tel_number'] }}</p>
                </div>
                <!-- Right Column -->
                <div class="text-left space-y-2">
                    <p class="user-role-info-label" data-label="ประเภทบุคลากร: ">
                        {{ $userData['insider_role'] === 'academic' ? 'บุคลากรสายวิชาการ' : ($userData['insider_role'] === 'teaching' ? 'บุคลากรสายการสอน' : '-') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="mt-4 sm:mt-6 flex-1">
            @if (empty($completions))
                <div
                    class="text-center text-sm sm:text-base md:text-lg lg:text-xl text-gray-900 dark:text-gray-100 py-6 sm:py-10">
                    คุณยังไม่มีผลการเรียนรู้จากคอร์สที่ผ่านการอบรม
                </div>
            @else
                <?php
                // จำนวนรายการสูงสุดต่อคอลัมน์
                $itemsPerColumn = 4;
                // จำนวนรายการสูงสุดต่อหน้า (2 คอลัมน์ x 4 รายการ for desktop, 1 คอลัมน์ for mobile/iPad)
                $itemsPerPageDesktop = $itemsPerColumn * 2;
                $itemsPerPageMobile = $itemsPerColumn; // For mobile/iPad, 1 column
                // แบ่งข้อมูลเป็นกลุ่มๆ (กลุ่มละ 8 รายการสำหรับ desktop, 4 รายการสำหรับ mobile/iPad)
                $pagesDesktop = array_chunk($completions, $itemsPerPageDesktop);
                $pagesMobile = array_chunk($completions, $itemsPerPageMobile);
                ?>

                <!-- Desktop View (≥1024px) -->
                <div class="hidden lg:block">
                    @foreach ($pagesDesktop as $page)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 page-break">
                            <!-- Left Column -->
                            <div>
                                <!-- Header -->
                                <div
                                    class="flex font-semibold text-xs sm:text-sm md:text-base lg:text-base text-gray-900 dark:text-gray-100 bg-gray-200 dark:bg-gray-800 p-2 border border-gray-300 dark:border-gray-700">
                                    <div class="flex-1">COURSE TITLE</div>
                                    <div class="w-16 text-center">RESULT</div>
                                </div>
                                <!-- Content -->
                                <div class="space-y-4 mt-2">
                                    @foreach (array_slice($page, 0, $itemsPerColumn) as $completion)
                                        <div
                                            class="text-xs sm:text-sm md:text-base lg:text-base text-gray-800 dark:text-gray-200">
                                            <div class="flex pb-2">
                                                <div class="flex-1">
                                                    {{ $completion['course_title'] }}
                                                    @if (!empty($completion['learning_outcomes']))
                                                        <ul
                                                            class="list-disc list-inside text-xs sm:text-sm md:text-sm lg:text-sm mt-2">
                                                            @foreach ($completion['learning_outcomes'] as $outcome)
                                                                <li>{{ $outcome }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                                <div class="w-16 text-center">
                                                    {{ $completion['completed_at'] && $completion['completed_at'] !== 'ยังไม่ระบุ' ? 'ผ่าน' : 'ไม่ผ่าน' }}
                                                </div>
                                            </div>
                                            <!-- Divider -->
                                            @if (!$loop->last)
                                                <hr class="border-gray-300 dark:border-gray-600">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div>
                                <!-- Header -->
                                <div
                                    class="flex font-semibold text-xs sm:text-sm md:text-base lg:text-base text-gray-900 dark:text-gray-100 bg-gray-200 dark:bg-gray-800 p-2 border border-gray-300 dark:border-gray-700">
                                    <div class="flex-1">COURSE TITLE</div>
                                    <div class="w-16 text-center">RESULT</div>
                                </div>
                                <!-- Content -->
                                <div class="space-y-4 mt-2">
                                    @foreach (array_slice($page, $itemsPerColumn, $itemsPerColumn) as $completion)
                                        <div
                                            class="text-xs sm:text-sm md:text-base lg:text-base text-gray-800 dark:text-gray-200">
                                            <div class="flex pb-2">
                                                <div class="flex-1">
                                                    {{ $completion['course_title'] }}
                                                    @if (!empty($completion['learning_outcomes']))
                                                        <ul
                                                            class="list-disc list-inside text-xs sm:text-sm md:text-sm lg:text-sm mt-2">
                                                            @foreach ($completion['learning_outcomes'] as $outcome)
                                                                <li>{{ $outcome }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                                <div class="w-16 text-center">
                                                    {{ $completion['completed_at'] && $completion['completed_at'] !== 'ยังไม่ระบุ' ? 'ผ่าน' : 'ไม่ผ่าน' }}
                                                </div>
                                            </div>
                                            <!-- Divider -->
                                            @if (!$loop->last)
                                                <hr class="border-gray-300 dark:border-gray-600">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile/iPad View (<1024px) -->
                <div class="block lg:hidden">
                    @foreach ($pagesMobile as $page)
                        <div class="space-y-4 page-break">
                            <!-- Header -->
                            <div
                                class="flex font-semibold text-xs sm:text-sm text-gray-900 dark:text-gray-100 bg-gray-200 dark:bg-gray-800 p-2 border border-gray-300 dark:border-gray-700">
                                <div class="flex-1">COURSE TITLE</div>
                                <div class="w-16 text-center">RESULT</div>
                            </div>
                            <!-- Content -->
                            <div class="space-y-4 mt-2">
                                @foreach ($page as $completion)
                                    <div class="text-xs sm:text-sm text-gray-800 dark:text-gray-200">
                                        <div class="flex pb-2">
                                            <div class="flex-1">
                                                {{ $completion['course_title'] }}
                                                @if (!empty($completion['learning_outcomes']))
                                                    <ul class="list-disc list-inside text-xs sm:text-sm mt-2">
                                                        @foreach ($completion['learning_outcomes'] as $outcome)
                                                            <li>{{ $outcome }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                            <div class="w-16 text-center">
                                                {{ $completion['completed_at'] && $completion['completed_at'] !== 'ยังไม่ระบุ' ? 'ผ่าน' : 'ไม่ผ่าน' }}
                                            </div>
                                        </div>
                                        <!-- Divider -->
                                        @if (!$loop->last)
                                            <hr class="border-gray-300 dark:border-gray-600">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Footer and Signatures -->
        <div class="mt-auto pt-4 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
            <!-- Left: LOWallet Signature -->
            <div class="text-center w-full sm:w-1/2">
                <p class="text-gray-900 dark:text-gray-100">___________________________</p>
                <p class="text-xs sm:text-sm md:text-base lg:text-base text-gray-700 dark:text-gray-300">
                    ลายเซ็น LOWallet
                </p>
            </div>

            <!-- Right: Course Owner Signature -->
            <div class="text-center w-full sm:w-1/2">
                <p class="text-gray-900 dark:text-gray-100">___________________________</p>
                <p class="text-xs sm:text-sm md:text-base lg:text-base text-gray-700 dark:text-gray-300">
                    ลายเซ็นเจ้าของคอร์ส
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.title = "LOWallet";
</script>
