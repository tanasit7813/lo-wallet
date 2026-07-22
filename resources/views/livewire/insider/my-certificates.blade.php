<div class="w-full mx-auto p-4 sm:p-6 bg-white dark:bg-[#1b1b18]">
    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-6">
        Certificate ของฉัน</h1>

    @if (session('success'))
        <x-popup message="{{ session('success') }}" bgColor="bg-green-500" darkBgColor="dark:bg-green-900" />
    @endif
    @if (session('error'))
        <x-popup message="{{ session('error') }}" bgColor="bg-red-500" darkBgColor="dark:bg-red-900" />
    @endif

    @if ($certificates->isEmpty())
        <p class="text-base sm:text-lg md:text-xl lg:text-xl text-gray-900 dark:text-white">
            คุณยังไม่ได้ขอใบ Certificate ใด ๆ
        </p>
    @else
        <div class="overflow-x-auto">
            <div class="w-full rounded-lg" role="grid">
                <!-- Header (Hidden on Mobile and iPad, Shown on Desktop) -->
                <div class="hidden lg:grid grid-cols-[100px_2fr_1fr_1fr_1fr] bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 divide-y divide-gray-300 dark:divide-gray-700"
                    role="row">
                    <div class="py-2 px-4 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white text-left font-medium border-r border-gray-300 dark:border-gray-700"
                        role="columnheader">No</div>
                    <div class="py-2 px-4 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white text-left font-medium border-r border-gray-300 dark:border-gray-700"
                        role="columnheader">ชื่อคอร์ส</div>
                    <div class="py-2 px-4 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white text-left font-medium border-r border-gray-300 dark:border-gray-700"
                        role="columnheader">สถานะ</div>
                    <div class="py-2 px-4 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white text-left font-medium border-r border-gray-300 dark:border-gray-700"
                        role="columnheader">วันที่ร้องขอ</div>
                    <div class="py-2 px-4 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white text-left font-medium"
                        role="columnheader">การดำเนินการ</div>
                </div>

                <!-- Body -->
                @foreach ($certificates as $index => $certificate)
                    <div class="flex flex-col lg:grid grid-cols-1 lg:grid-cols-[100px_2fr_1fr_1fr_1fr] bg-white dark:bg-[#161615] lg:bg-gray-50 lg:dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 border lg:border-0 border-gray-300 dark:border-gray-600 rounded-lg mb-4 lg:mb-0 p-4 lg:p-0"
                        role="row">
                        <div class="flex lg:grid flex-row lg:flex-col items-start lg:items-center py-2 px-4 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white lg:border-r border-gray-300 dark:border-gray-700 with-label"
                            role="cell" data-label="No: ">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex lg:grid flex-row lg:flex-col items-start py-2 px-4 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white lg:border-r border-gray-300 dark:border-gray-700 with-label"
                            role="cell" data-label="ชื่อคอร์ส: ">
                            {{ $certificate->course->title }}
                        </div>
                        <div class="flex lg:grid flex-row lg:flex-col items-start py-2 px-4 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white lg:border-r border-gray-300 dark:border-gray-700 with-label"
                            role="cell" data-label="สถานะ: ">
                            @if ($certificate->status === 'pending')
                                <span class="text-yellow-600 dark:text-yellow-400">รอการอนุมัติ</span>
                            @elseif ($certificate->status === 'approved')
                                <span class="text-green-600 dark:text-green-400">อนุมัติแล้ว</span>
                            @elseif ($certificate->status === 'rejected')
                                <span class="text-red-600 dark:text-red-400">ถูกปฏิเสธ</span>
                                @if ($certificate->denial_reason)
                                    <br>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        เหตุผล: {{ $certificate->denial_reason }}
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-500 dark:text-gray-400">ไม่ทราบสถานะ</span>
                            @endif
                        </div>
                        <div class="flex lg:grid flex-row lg:flex-col items-start py-2 px-4 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white lg:border-r border-gray-300 dark:border-gray-700 with-label"
                            role="cell" data-label="วันที่ร้องขอ: ">
                            {{ $certificate->requested_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="flex lg:grid flex-row lg:flex-col items-start lg:items-center py-2 px-4 pt-4 lg:pt-2 text-base sm:text-lg md:text-xl lg:text-lg text-gray-900 dark:text-white with-label"
                            role="cell" data-label="การดำเนินการ: ">
                            @if ($certificate->status === 'approved')
                                <a href="{{ route('view-certificate-page-insider', $certificate->id) }}"
                                    class="inline-block px-4 sm:px-5 py-2 bg-blue-500 dark:bg-blue-600 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-blue-500 text-center transition duration-300 text-sm sm:text-base md:text-lg lg:text-base">
                                    View Certificate
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    document.title = "Certificate ของฉัน";
</script>
