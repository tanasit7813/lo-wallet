<div class="w-full mx-auto">
    @if (session('success'))
        <x-popup message="{{ session('success') }}" bgColor="bg-green-500" darkBgColor="dark:bg-green-900" />
    @endif
    @if (session('error'))
        <x-popup message="{{ session('error') }}" bgColor="bg-red-500" darkBgColor="dark:bg-red-900" />
    @endif

    <div class="p-4 sm:p-6 bg-white dark:bg-[#1b1b18] w-full">
        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-6">
            จัดการคำขอใบ Certificate
        </h1>

        <!-- Table of Certificate Requests -->
        <div class="mt-6">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h2 class="text-sm sm:text-base md:text-lg lg:text-xl font-semibold text-gray-900 dark:text-gray-100">
                    จัดการใบ Certificate
                </h2>
            </div>
            @if (!empty($certificateRequests))
                <div class="overflow-x-auto">
                    <div class="w-full rounded-lg" role="grid">
                        <div class="hidden xl:grid grid-cols-[minmax(50px,50px)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(200px,200px)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(150px,150px)] gap-1 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 divide-y divide-gray-300 dark:divide-gray-700"
                            role="row">
                            @foreach (['No', 'ชื่อ', 'คณะ', 'สาขา', 'ตำแหน่ง', 'หน่วยงาน', 'ประเภทบุคคลภายใน', 'ชื่อคอร์ส', 'สถานะการอบรม', 'สถานะคำขอ', 'วันที่ร้องขอ', 'การดำเนินการ'] as $index => $header)
                                <div class="py-2 px-2 text-xs sm:text-sm md:text-base lg:text-sm text-gray-900 dark:text-gray-100 text-left font-medium border-r border-gray-300 dark:border-gray-700 last:border-r-0"
                                    class="{{ $index === 7 ? 'truncate' : '' }}"
                                    style="{{ $index === 7 ? 'max-width: 200px;' : '' }}" role="columnheader">
                                    {{ $header }}
                                </div>
                            @endforeach
                        </div>

                        @foreach ($certificateRequests as $index => $request)
                            <div class="flex flex-col xl:grid xl:grid-cols-[minmax(50px,50px)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(200px,200px)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(100px,1fr)_minmax(150px,150px)] gap-1 bg-white dark:bg-gray-900 xl:bg-gray-50 xl:dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 border xl:border-0 border-gray-300 dark:border-gray-600 mb-4 xl:mb-0 p-2 xl:p-0"
                                role="row">
                                @foreach ([['label' => 'No: ', 'value' => $index + 1], ['label' => 'ชื่อ: ', 'value' => $request['user']->name], ['label' => 'คณะ: ', 'value' => $request['role_data']['faculty']], ['label' => 'สาขา: ', 'value' => $request['role_data']['branch']], ['label' => 'ตำแหน่ง: ', 'value' => $request['role_data']['position']], ['label' => 'หน่วยงาน: ', 'value' => $request['role_data']['agency']], ['label' => 'ประเภทบุคคลภายใน: ', 'value' => $request['role_data']['insider_role']], ['label' => 'ชื่อคอร์ส: ', 'value' => $request['course']->title], ['label' => 'สถานะการอบรม: ', 'value' => $request['course_completion_status']], ['label' => 'สถานะคำขอ: ', 'value' => $request['status']], ['label' => 'วันที่ร้องขอ: ', 'value' => $request['requested_at']->format('d/m/Y H:i')], ['label' => 'การดำเนินการ: ', 'value' => 'action']] as $cell)
                                    <div class="xl:grid flex-row sm:flex-col items-start py-1 px-2 text-xs sm:text-sm md:text-base lg:text-sm text-gray-900 dark:text-gray-100 xl:border-r xl:last:border-r border-gray-300 dark:border-gray-700 xl:last:pr-0 with-label min-w-0"
                                        class="{{ $cell['label'] === 'ชื่อคอร์ส: ' ? 'truncate' : '' }}"
                                        style="{{ $cell['label'] === 'คณะ: ' || $cell['label'] === 'สาขา: ' || $cell['label'] === 'ชื่อคอร์ส: ' ? 'overflow-wrap: break-word; word-break: break-word;' : 'word-wrap: break-word; word-break: break-all; overflow-wrap: anywhere; hyphens: auto;' }}"
                                        role="cell" data-label="{{ $cell['label'] }}"
                                        @if ($cell['label'] === 'ชื่อคอร์ส: ') title="{{ $cell['value'] }}" @endif>
                                        @if ($cell['label'] === 'สถานะการอบรม: ')
                                            @if ($request['course_completion_status'] === 'completed')
                                                <span class="text-green-600 dark:text-green-400">ผ่านการอบรม</span>
                                            @else
                                                <span class="text-red-600 dark:text-red-400">ยังไม่ผ่านการอบรม</span>
                                            @endif
                                        @elseif ($cell['label'] === 'สถานะคำขอ: ')
                                            @if ($request['status'] === 'pending')
                                                <span class="text-yellow-600 dark:text-yellow-400">รอการอนุมัติ</span>
                                            @elseif ($request['status'] === 'approved')
                                                <span class="text-green-600 dark:text-green-400">อนุมัติแล้ว</span>
                                            @elseif ($request['status'] === 'rejected')
                                                <span class="text-red-600 dark:text-red-400">ถูกปฏิเสธ</span>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">ไม่ทราบสถานะ</span>
                                            @endif
                                        @elseif ($cell['label'] === 'การดำเนินการ: ')
                                            @if ($request['status'] === 'pending')
                                                <div class="flex space-x-2">
                                                    <button wire:click="approve({{ $request['id'] }})"
                                                        class="cursor-pointer px-2 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-xs sm:text-sm md:text-base">
                                                        อนุมัติ
                                                    </button>
                                                    <button wire:click="openDenyModal({{ $request['id'] }})"
                                                        class="cursor-pointer px-2 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 text-xs sm:text-sm md:text-base">
                                                        ปฏิเสธ
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">ดำเนินการแล้ว</span>
                                            @endif
                                        @else
                                            {{ $cell['value'] }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm sm:text-base md:text-lg lg:text-xl text-gray-900 dark:text-gray-100">
                    ยังไม่มีผู้ลงทะเบียนในคอร์สนี้
                </p>
            @endif
        </div>
    </div>

    <!-- Deny Confirmation Modal -->
    <div x-data="{ open: @entangle('showDenyModal') }" x-show="open"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg w-full max-w-xs sm:max-w-sm md:max-w-sm">
            <h3 class="text-sm sm:text-base md:text-lg lg:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                ปฏิเสธคำขอใบ Certificate
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4 text-xs sm:text-sm md:text-base lg:text-base">
                คุณต้องการปฏิเสธคำขอใบ Certificate ของ <span x-text="selectedRequest?.name"></span> หรือไม่?
            </p>
            <div class="mb-4">
                <textarea wire:model="denialReason"
                    class="w-full p-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" rows="4"
                    placeholder="ระบุเหตุผล..."></textarea>
                @error('denialReason')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button @click="open = false"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm sm:text-base md:text-lg lg:text-base">
                    ยกเลิก
                </button>
                <button wire:click="deny"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm sm:text-base md:text-lg lg:text-base">
                    ปฏิเสธ
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    document.title = "จัดการคำขอ Certificate";
</script>
