<div class="w-full mx-auto">
    <div class="p-4 sm:p-6 bg-white dark:bg-[#1b1b18] w-full">
        <!-- Drop-down List -->
        <div class="mb-6">
            <label for="logoOption" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">
                เลือกประเภทใบ Certificate
            </label>
            <select wire:model.live="logoOption" id="logoOption"
                class="w-full max-w-xs p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                <option value="" disabled selected>-- กรุณาเลือก --</option>
                <option value="with_logo">แบบโลโก้ PKRU-Skill Wallet</option>
                <option value="without_logo">แบบโลโก้ มหาวิทยาลัย</option>
            </select>
            @if ($logoOption && $certificate)
                {{-- เปลี่ยนจาก <a> เป็น <button> --}}
                <button wire:click="downloadCertificate" wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="inline-block bg-blue-600 text-white font-semibold rounded-lg px-4 py-2 mt-2 ml-2 hover:bg-blue-700 transition duration-300">
                    <span wire:loading.remove wire:target="downloadCertificate">
                        ดาวน์โหลด Certificate
                    </span>
                    <span wire:loading wire:target="downloadCertificate">
                        กำลังสร้างไฟล์...
                    </span>
                </button>
            @endif
        </div>
    </div>

    @if ($logoOption && $certificate)
        <div class="w-full max-w-7xl mx-auto">
            @if ($logoOption === 'with_logo')
                <div class="relative w-full" style="aspect-ratio: 4/3;">
                    <img src="{{ asset('img/withlogo.png') }}" alt="Logo"
                        class="absolute inset-0 w-full h-full object-contain">
                    <div class="absolute inset-0 flex items-center justify-center" style="top: 40%; height: 9%;">
                        <p class="font-bold text-white text-center px-2 leading-tight"
                            style="font-size: clamp(0.8rem, 2.5vw, 2.5rem); text-shadow: 2px 2px 4px rgba(0,0,0,0.5); ">
                            {{ Auth::user()->name ?? 'ไม่ระบุ' }}
                        </p>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center" style="top: 53%; height: 12%;">
                        <p class="font-bold text-gray-800 text-center px-2 leading-tight">
                            @php
                                $courseTitle = $certificate->course->title ?? 'ไม่ระบุ';
                                if (preg_match('/^(.*?)\s*\(([^)]+)\)$/', $courseTitle, $matches)) {
                                    $mainTitle = trim($matches[1]);
                                    $subTitle = trim($matches[2]);
                                } else {
                                    $mainTitle = $courseTitle;
                                    $subTitle = '';
                                }
                                // Array สำหรับชื่อเดือนภาษาไทย
                                $thaiMonths = [
                                    1 => 'มกราคม',
                                    2 => 'กุมภาพันธ์',
                                    3 => 'มีนาคม',
                                    4 => 'เมษายน',
                                    5 => 'พฤษภาคม',
                                    6 => 'มิถุนายน',
                                    7 => 'กรกฎาคม',
                                    8 => 'สิงหาคม',
                                    9 => 'กันยายน',
                                    10 => 'ตุลาคม',
                                    11 => 'พฤศจิกายน',
                                    12 => 'ธันวาคม',
                                ];
                                // แปลงวันที่เป็นรูปแบบเต็มภาษาไทย
                                $formattedDate = $certificate->requested_at
                                    ? $certificate->requested_at->format('d') .
                                        ' ' .
                                        $thaiMonths[$certificate->requested_at->month] .
                                        ' ' .
                                        ($certificate->requested_at->year + 543)
                                    : 'ไม่ระบุ';
                                // แยกชื่อและยศของผู้อำนวยการ
                                $directorName = $director ? $director->name : 'ไม่ระบุ';
                                $directorPosition = '';
                                if ($director && preg_match('/^(.*?)\s*\(([^)]+)\)$/', $director->name, $matches)) {
                                    $directorName = trim($matches[1]);
                                    $directorPosition = trim($matches[2]);
                                } elseif ($director && $director->position) {
                                    $directorPosition = $director->position;
                                }
                            @endphp
                            <span class="block sm:mt-1"
                                style="font-size: clamp(0.6rem, 1.8vw, 1.5rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                ได้ผ่านการอบรม {{ $mainTitle }}
                            </span>
                            @if ($subTitle)
                                <span class="block sm:mt-1"
                                    style="font-size: clamp(0.6rem, 1.8vw, 1.5rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                    ({{ $subTitle }})
                                </span>
                            @endif
                            <span class="block mt-2 sm:mt-10"
                                style="font-size: clamp(0.6rem, 1.8vw, 1.5rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                ออกให้ ณ วันที่ {{ $formattedDate }}
                            </span>
                        </p>
                    </div>
                    @if ($director)
                        <div class="absolute inset-0 flex items-center justify-center" style="top: 70%; height: 5%;">
                            <img src="{{ asset('img/PKRU1.png') }}" alt="Director Signature"
                                class="block mx-auto max-w-[36vw] max-h-[6vh] sm:max-w-[36vw] sm:max-h-[8vh] object-contain">
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center" style="top: 80%; height: 10%;">
                            <p class="font-bold text-gray-800 text-center px-2 leading-tight">
                                <span class="block sm:mt-1"
                                    style="font-size: clamp(0.5rem, 1.5vw, 1.2rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                    {{ $directorName }}
                                </span>
                                @if ($directorPosition)
                                    <span class="block sm:mt-1"
                                        style="font-size: clamp(0.5rem, 1.5vw, 1.2rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                        ({{ $directorPosition }})
                                    </span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            @elseif ($logoOption === 'without_logo')
                <div class="relative w-full" style="aspect-ratio: 4/3;">
                    <img src="{{ asset('img/withoutlogo.png') }}" alt="Certificate without logo"
                        class="absolute inset-0 w-full h-full object-contain">
                    <div class="absolute inset-0 flex items-center justify-center" style="top: 40%; height: 9%;">
                        <p class="font-bold text-white text-center px-2 leading-tight"
                            style="font-size: clamp(0.8rem, 2.5vw, 2.5rem); text-shadow: 2px 2px 4px rgba(0,0,0,0.5); ">
                            {{ Auth::user()->name ?? 'ไม่ระบุ' }}
                        </p>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center" style="top: 53%; height: 12%;">
                        <p class="font-bold text-gray-800 text-center px-2 leading-tight">
                            @php
                                $courseTitle = $certificate->course->title ?? 'ไม่ระบุ';
                                if (preg_match('/^(.*?)\s*\(([^)]+)\)$/', $courseTitle, $matches)) {
                                    $mainTitle = trim($matches[1]);
                                    $subTitle = trim($matches[2]);
                                } else {
                                    $mainTitle = $courseTitle;
                                    $subTitle = '';
                                }
                                // Array สำหรับชื่อเดือนภาษาไทย
                                $thaiMonths = [
                                    1 => 'มกราคม',
                                    2 => 'กุมภาพันธ์',
                                    3 => 'มีนาคม',
                                    4 => 'เมษายน',
                                    5 => 'พฤษภาคม',
                                    6 => 'มิถุนายน',
                                    7 => 'กรกฎาคม',
                                    8 => 'สิงหาคม',
                                    9 => 'กันยายน',
                                    10 => 'ตุลาคม',
                                    11 => 'พฤศจิกายน',
                                    12 => 'ธันวาคม',
                                ];
                                // แปลงวันที่เป็นรูปแบบเต็มภาษาไทย
                                $formattedDate = $certificate->requested_at
                                    ? $certificate->requested_at->format('d') .
                                        ' ' .
                                        $thaiMonths[$certificate->requested_at->month] .
                                        ' ' .
                                        ($certificate->requested_at->year + 543)
                                    : 'ไม่ระบุ';
                                // แยกชื่อและยศของผู้อำนวยการ
                                $directorName = $director ? $director->name : 'ไม่ระบุ';
                                $directorPosition = '';
                                if ($director && preg_match('/^(.*?)\s*\(([^)]+)\)$/', $director->name, $matches)) {
                                    $directorName = trim($matches[1]);
                                    $directorPosition = trim($matches[2]);
                                } elseif ($director && $director->position) {
                                    $directorPosition = $director->position;
                                }
                            @endphp
                            <span class="block sm:mt-1"
                                style="font-size: clamp(0.6rem, 1.8vw, 1.5rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                ได้ผ่านการอบรม {{ $mainTitle }}
                            </span>
                            @if ($subTitle)
                                <span class="block sm:mt-1"
                                    style="font-size: clamp(0.6rem, 1.8vw, 1.5rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                    ({{ $subTitle }})
                                </span>
                            @endif
                            <span class="block mt-2 sm:mt-10"
                                style="font-size: clamp(0.6rem, 1.8vw, 1.5rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                ออกให้ ณ วันที่ {{ $formattedDate }}
                            </span>
                        </p>
                    </div>
                    @if ($director)
                        <div class="absolute inset-0 flex items-center justify-center" style="top: 70%; height: 5%;">
                            <img src="{{ asset('img/PKRU1.png') }}" alt="Director Signature"
                                class="block mx-auto max-w-[36vw] max-h-[6vh] sm:max-w-[36vw] sm:max-h-[8vh] object-contain">
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center" style="top: 80%; height: 10%;">
                            <p class="font-bold text-gray-800 text-center px-2 leading-tight">
                                <span class="block sm:mt-1"
                                    style="font-size: clamp(0.5rem, 1.5vw, 1.2rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                    {{ $directorName }}
                                </span>
                                @if ($directorPosition)
                                    <span class="block sm:mt-1"
                                        style="font-size: clamp(0.5rem, 1.5vw, 1.2rem); text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                        ({{ $directorPosition }})
                                    </span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @elseif ($logoOption && !$certificate)
        <p class="text-lg sm:text-xl text-red-600 dark:text-red-400">
            ไม่สามารถโหลดใบ Certificate ได้
        </p>
    @endif

    <script>
        document.title = "ใบ Certificate - {{ $certificate->course->title ?? 'ไม่ระบุ' }}";
    </script>
</div>
