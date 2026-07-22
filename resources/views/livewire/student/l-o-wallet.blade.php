<div class="w-full mx-auto p-4 sm:p-6 dark:bg-gray-900 min-h-screen">
    <div
        class="w-full lg:w-[210mm] lg:h-[297mm] mx-auto bg-white dark:bg-gray-900 shadow-md rounded-lg p-4 sm:p-6 lg:p-[15mm] border border-gray-200 dark:border-gray-700 overflow-auto print:shadow-none print:border-none print:p-[15mm] flex flex-col print:overflow-visible print:w-[210mm] print:h-[297mm] relative">

        <div class="flex justify-end items-center mb-4 sm:mb-6">
            <div class="text-xs sm:text-sm md:text-base lg:text-base text-gray-600 dark:text-gray-400">
                {{ now()->format('d/m/Y') }}
            </div>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex flex-row items-center space-x-2 w-32 sm:w-40 lg:w-48 flex-shrink-0">
                <img src="{{ asset('img/PKRU1.png') }}" alt="PKRU Logo 2" class="w-full object-contain print:w-full">
            </div>
            <!-- Centered Title -->
            <div class="flex-1 text-center">
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100">
                    PKRU Skill Wallet Transcript
                </h1>
            </div>
        </div>

        <!-- User Info -->
        <div
            class="mt-4 sm:mt-6 grid grid-cols-2 gap-2 sm:gap-4 text-xs sm:text-sm md:text-base lg:text-base text-gray-900 dark:text-gray-100">
            <!-- Row 1 -->
            <div class="text-left">
                <p class="user-info-label" data-label="ชื่อ: ">{{ $userData['name'] }}</p>
            </div>
            <div class="text-left">
                <p class="user-info-label" data-label="หลักสูตร: ">{{ $userData['program'] }}</p>
            </div>
            <!-- Row 2 -->
            <div class="text-left">
                <p class="user-info-label" data-label="คณะ: ">{{ $userData['faculty'] }}</p>
            </div>
            <div class="text-left">
                <p class="user-info-label" data-label="สาขา: ">{{ $userData['branch'] }}</p>
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
                $itemsPerPage = 8; // Unified items per page for all views
                $pages = array_chunk($completions, $itemsPerPage);
                ?>

                <!-- Single Table for All Views -->
                @foreach ($pages as $page)
                    <div class="page-break">
                        <table class="w-full border border-gray-300 dark:border-gray-700">
                            <!-- Header -->
                            <thead>
                                <tr
                                    class="bg-gray-200 dark:bg-gray-800 text-xs sm:text-sm md:text-base lg:text-base text-gray-900 dark:text-gray-100 font-semibold">
                                    <th class="p-2 text-left">ชื่อคอร์ส</th>
                                    <th class="w-16 p-2 text-center">ผลลัพธ์</th>
                                </tr>
                            </thead>
                            <!-- Content -->
                            <tbody>
                                @foreach ($page as $completion)
                                    <tr
                                        class="text-xs sm:text-sm md:text-base lg:text-base text-gray-800 dark:text-gray-200">
                                        <td class="px-2 pb-2 course-title align-top">
                                            {{ $completion['course_title'] }}
                                            @if (!empty($completion['learning_outcomes']))
                                                <ul class="list-disc pl-5 mt-2 space-y-1 text-xs sm:text-sm">
                                                    @foreach ($completion['learning_outcomes'] as $outcome)
                                                        <li>
                                                            {{ $outcome['description'] }}
                                                            {{-- แสดง tag ถ้ามี --}}
                                                            @if ($outcome['tag_name'])
                                                                <span
                                                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                                    {{ $outcome['tag_name'] }}
                                                                </span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td class="w-16 p-2 text-center result align-top">
                                            {{ $completion['completed_at'] && $completion['completed_at'] !== 'ยังไม่ระบุ' ? 'ผ่าน' : 'ไม่ผ่าน' }}
                                        </td>
                                    </tr>
                                    @if (!$loop->last)
                                        <td colspan="2" class="border-t border-gray-300 dark:border-gray-600">
                                        </td>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Footer and Signatures -->
        <div class="mt-auto pt-4 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
            <!-- Left: LOWallet Signature -->
            <div class="text-center w-full sm:w-1/3">
                <p class="text-gray-900 dark:text-gray-100">___________________________</p>
                <p class="text-xs sm:text-sm md:text-base lg:text-base text-gray-700 dark:text-gray-300">
                    ลายเซ็นเจ้าของคอร์ส
                </p>
            </div>

            <!-- Right: Course Owner Signature -->
            <div class="text-center w-full sm:w-1/3">
                @if ($directorName)
                    <p class="text-xs sm:text-sm md:text-base lg:text-base text-gray-800 dark:text-gray-200">
                        {{ $directorName }}
                    </p>
                @else
                    <p class="text-transparent text-xs sm:text-sm md:text-base lg:text-base">&nbsp;</p>
                @endif

                <div class="border-t border-gray-900 dark:border-gray-100 mt-1"></div>

                <p class="text-xs sm:text-sm md:text-base lg:text-base text-gray-700 dark:text-gray-300 mt-1">
                    ลายเซ็นผู้อำนวยการ
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.title = "LOWallet";
</script>
