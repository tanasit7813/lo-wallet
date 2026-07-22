<div class="w-full mx-auto">
    @if (session('success'))
        <x-popup message="{{ session('success') }}" bgColor="bg-green-500" darkBgColor="dark:bg-green-900" />
    @endif
    @if (session('error'))
        <x-popup message="{{ session('error') }}" bgColor="bg-red-500" darkBgColor="dark:bg-red-900" />
    @endif

    <div class="p-4 sm:p-6 bg-white dark:bg-[#1b1b18] w-full">
        @if ($certificate)
            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-6">
                ใบ Certificate - {{ $certificate->course->title ?? 'ไม่ระบุ' }}
            </h1>

            <!-- Certificate Content -->
            <div
                class="max-w-4xl mx-auto bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg p-8">
                <!-- Header -->
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('img/logo-center.svg') }}" alt="Logo"
                            class="h-16 sm:h-20 md:h-24 object-contain">
                    </div>
                    <p class="text-lg sm:text-xl text-gray-700 dark:text-gray-300 mb-6">
                        ใบรับรองการผ่านการอบรม
                    </p>
                </div>

                <!-- Main Content -->
                <div class="text-center space-y-4">
                    <p class="text-lg sm:text-xl text-gray-900 dark:text-white">
                        ขอรับรองว่า
                    </p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ Auth::user()->name ?? 'ไม่ระบุ' }}
                    </p>
                    <p class="text-lg sm:text-xl text-gray-900 dark:text-white">
                        ตำแหน่ง: {{ $general ? $general->position ?? 'ไม่ระบุ' : 'ไม่ระบุ' }}
                    </p>
                    <p class="text-lg sm:text-xl text-gray-900 dark:text-white">
                        หน่วยงาน: {{ $general ? $general->agency ?? 'ไม่ระบุ' : 'ไม่ระบุ' }}
                    </p>
                    <p class="text-lg sm:text-xl text-gray-900 dark:text-white">
                        ได้ผ่านการอบรม <span class="font-semibold">{{ $certificate->course->title ?? 'ไม่ระบุ' }}</span>
                    </p>
                    <p class="text-lg sm:text-xl text-gray-900 dark:text-white">
                        ระยะเวลาอบรม: {{ $certificate->course->duration ?? 'ไม่ระบุ' }} ชั่วโมง
                    </p>
                    <p class="text-lg sm:text-xl text-gray-900 dark:text-white">
                        เมื่อวันที่:
                        {{ $certificate->requested_at ? $certificate->requested_at->format('d/m/Y') : 'ไม่ระบุ' }}
                    </p>
                </div>

                <!-- Signatures -->
                <div class="mt-12 flex justify-between items-center">
                    <div class="text-center">
                        <p class="text-gray-900 dark:text-white">___________________________</p>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300">
                            ลายเซ็น LOWallet
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-900 dark:text-white">___________________________</p>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300">
                            ลายเซ็นเจ้าของคอร์ส
                        </p>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('my-certificates-general') }}"
                    class="inline-block bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 text-sm sm:text-base md:text-xl lg:text-xl">
                    กลับไปยัง Certificate ของฉัน
                </a>
            </div>
        @else
            <p class="text-lg sm:text-xl text-red-600 dark:text-red-400">
                ไม่สามารถโหลดใบ Certificate ได้
            </p>
        @endif
    </div>

    <script>
        document.title = "ใบ Certificate - {{ $certificate->course->title ?? 'ไม่ระบุ' }}";
    </script>
</div>
