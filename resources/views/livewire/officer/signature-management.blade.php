<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">จัดการลายเซ็น</h2>

    <!-- แจ้งเตือน -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- ฟอร์มเพิ่ม/แก้ไข -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'create' }}">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ชื่อ</label>
                    <input type="text" wire:model="name" id="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="position"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">ตำแหน่ง</label>
                    <input type="text" wire:model="position" id="position"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                    @error('position')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="startdate"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">วันที่เข้ารับตำแหน่ง</label>
                    <input type="date" wire:model="startdate" id="startdate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                    @error('startdate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    {{ $isEditing ? 'อัปเดต' : 'เพิ่ม' }}
                </button>
                @if ($isEditing)
                    <button type="button" wire:click="resetForm"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">ยกเลิก</button>
                @endif
            </div>
        </form>
    </div>

    <!-- ตารางแสดงข้อมูล -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        ชื่อ</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        ตำแหน่ง</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        วันที่เข้ารับตำแหน่ง</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        การจัดการ</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($directors as $director)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                            {{ $director->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                            {{ $director->position }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                            {{ $director->startdate }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="edit({{ $director->id }})"
                                class="text-blue-500 hover:text-blue-700">แก้ไข</button>
                            <button wire:click="delete({{ $director->id }})"
                                class="text-red-500 hover:text-red-700 ml-4">ลบ</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $directors->links() }}
        </div>
    </div>
</div>
