@props(['courseInfo'])
<div
    class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg flex justify-between items-center transition hover:bg-gray-100 dark:hover:bg-gray-700">
    <div>
        <h3 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $courseInfo['course']->title }}</h3>
    </div>
    <div class="text-right">
        @if ($courseInfo['enrollment_count'] >= $courseInfo['course']->max_students)
            <span
                class="px-3 py-1 text-sm font-semibold text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-200 rounded-full">เต็มแล้ว</span>
        @else
            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $courseInfo['enrollment_count'] }} /
                {{ $courseInfo['course']->max_students }}</span>
        @endif
    </div>
</div>
