@props(['message', 'bgColor' => 'bg-green-500', 'darkBgColor' => 'dark:bg-green-700'])
<div x-data="{ showPopup: false }" x-init="setTimeout(() => showPopup = true, 300);
setTimeout(() => showPopup = false, 5300)" x-if="showPopup" x-show="showPopup" x-cloak
    x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
    class="fixed bottom-4 right-4 w-fit {{ $bgColor }} {{ $darkBgColor }} text-white p-4 rounded-lg shadow-lg z-[9999]">
    <div class="flex items-center space-x-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="text-xl font-normal">{{ $message }}</span>
    </div>
    <div class="w-full bg-white/30 dark:bg-gray-300/30 h-1 mt-2 rounded-full overflow-hidden">
        <div class="bg-white dark:bg-gray-300 h-full rounded-full animate-progress"></div>
    </div>
</div>
