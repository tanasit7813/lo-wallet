<div class="flex-shrink-0">
    <!-- Sidebar Content -->
    <div :class="{ 'translate-x-0': isSidebarOpen, '-translate-x-full': !isSidebarOpen }"
        class="fixed inset-y-0 left-0 w-64 bg-gray-100 dark:bg-[#1b1b18] p-6 shadow-lg transform transition-transform duration-300 z-30 flex flex-col justify-between lg:transform-none lg:translate-x-0 lg:w-64 lg:h-screen lg:overflow-y-auto"
        x-cloak>

        <!-- ส่วนที่ 1: Menu -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <label class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Menu</label>
                <button @click="isSidebarOpen = false" class="cursor-pointer md:hidden text-[#1b1b18] dark:text-[#EDEDEC]"
                    aria-label="Close sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <ul class="space-y-4">
                <li>
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="cursor-pointer block px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300 w-full text-left {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'text-[#1b1b18] dark:text-[#EDEDEC]' }}">
                        Dashboard
                    </a>
                </li>
            </ul>
        </div>

        @if (in_array(auth()->user()->role, ['student', 'general', 'insider']))
            <div class="flex-1 mt-6">
                <label class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    @if (auth()->user()->role === 'student')
                        Student Menu
                    @elseif (auth()->user()->role === 'general')
                        General Menu
                    @else
                        Insider Menu
                    @endif
                </label>
                <ul class="space-y-4 mt-4">
                    <li>
                        <a href="{{ route('courses') }}" wire:navigate
                            class="block px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300 w-full text-left {{ request()->routeIs('courses') ? 'bg-blue-500 text-white' : 'text-[#1b1b18] dark:text-[#EDEDEC]' }}">
                            คอร์สเรียนทั้งหมด
                        </a>
                    </li>
                    <li>
                        <a href="{{ route(auth()->user()->role === 'student' ? 'my-courses' : (auth()->user()->role === 'general' ? 'my-courses-general' : 'my-courses-insider')) }}"
                            wire:navigate
                            class="block px-4 py-2 text-[#1b1b18] dark:text-[#EDEDEC] rounded-md hover:bg-blue-500 hover:text-white transition duration-300 {{ request()->routeIs(auth()->user()->role === 'student' ? 'my-courses' : (auth()->user()->role === 'general' ? 'my-courses-general' : 'my-courses-insider')) ? 'bg-blue-500 text-white' : '' }}">
                            คอร์สที่ลงทะเบียน
                        </a>
                    </li>
                    <li>
                        <a href="{{ route(auth()->user()->role === 'student' ? 'my-certificates' : (auth()->user()->role === 'general' ? 'my-certificates-general' : 'my-certificates-insider')) }}"
                            wire:navigate
                            class="block px-4 py-2 text-[#1b1b18] dark:text-[#EDEDEC] rounded-md hover:bg-blue-500 hover:text-white transition duration-300 {{ request()->routeIs(auth()->user()->role === 'student' ? 'my-certificates' : (auth()->user()->role === 'general' ? 'my-certificates-general' : 'my-certificates-insider')) ? 'bg-blue-500 text-white' : '' }}">
                            Certificate ของฉัน
                        </a>
                    </li>
                    <li>
                        <a href="{{ route(auth()->user()->role === 'student' ? 'my-lowallet' : (auth()->user()->role === 'general' ? 'my-lowallet-general' : 'my-lowallet-insider')) }}"
                            wire:navigate
                            class="block px-4 py-2 text-[#1b1b18] dark:text-[#EDEDEC] rounded-md hover:bg-blue-500 hover:text-white transition duration-300 {{ request()->routeIs(auth()->user()->role === 'student' ? 'my-lowallet' : (auth()->user()->role === 'general' ? 'my-lowallet-general' : 'my-lowallet-insider')) ? 'bg-blue-500 text-white' : '' }}">
                            LOWallet
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if (auth()->user()->role === 'instructor')
            <div class="flex-1 mt-6">
                <label class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Instructor Menu</label>
                <ul class="space-y-4 mt-4">
                    <li>
                        <a href="{{ route('courses') }}" wire:navigate
                            class="block px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300 w-full text-left {{ request()->routeIs('courses') ? 'bg-blue-500 text-white' : 'text-[#1b1b18] dark:text-[#EDEDEC]' }}">
                            คอร์สเรียนทั้งหมด
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('instructor.courses.create') }}"
                            class="block px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300 w-full text-left {{ request()->routeIs('instructor.courses.create') ? 'bg-blue-500 text-white' : 'text-[#1b1b18] dark:text-[#EDEDEC]' }}">
                            เพิ่มคอร์สเรียน
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if (auth()->user()->role === 'officer')
            <div class="flex-1 mt-6">
                <label class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Officer Menu</label>
                <ul class="space-y-4 mt-4">
                    <li>
                        <a href="{{ route('courses') }}" wire:navigate
                            class="block px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300 w-full text-left {{ request()->routeIs('courses') ? 'bg-blue-500 text-white' : 'text-[#1b1b18] dark:text-[#EDEDEC]' }}">
                            คอร์สเรียนทั้งหมด
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('officer.certificate-requests') }}" wire:navigate
                            class="block px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300 w-full text-left {{ request()->routeIs('officer.certificate-requests') ? 'bg-blue-500 text-white' : 'text-[#1b1b18] dark:text-[#EDEDEC]' }}">
                            จัดการ Certificate
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('officer.signature-management') }}" wire:navigate
                            class="block px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300 w-full text-left {{ request()->routeIs('officer.signature-management') ? 'bg-blue-500 text-white' : 'text-[#1b1b18] dark:text-[#EDEDEC]' }}">
                            จัดการลายเซ็น
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        <div>
            <!-- เมนูย่อย (Settings, Logout) -->
            <ul x-show="isProfileOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
                class="space-y-2 mb-2 bg-gray-200 dark:bg-[#2b2b28] p-2 rounded-md">
                <!-- New div for full name and email -->
                <div class="px-4 py-3 bg-gray-200 dark:bg-[#2b2b28] rounded-md mb-2">
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-[#1b1b18] dark:text-[#EDEDEC] text-sm">{{ auth()->user()->email }}</p>
                </div>

                <hr class="border-gray-300" />

                <li class="cursor-pointer">
                    <a href="#" wire:navigate
                        class="flex items-center px-4 py-2 text-[#1b1b18] dark:text-[#EDEDEC] rounded-md hover:bg-blue-500 hover:text-white transition duration-300 {{ request()->routeIs('settings') ? 'bg-blue-500 text-white' : '' }}">
                        <span class="flex items-center justify-center w-5 h-5 mr-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
                                <path
                                    d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
                            </svg>
                        </span>
                        Settings
                    </a>
                </li>

                <li class="cursor-pointer">
                    <livewire:logout />
                </li>
            </ul>

            <!-- Profile (คลิกเพื่อแสดง/ซ่อนเมนูย่อย) -->
            <div @click="isProfileOpen = !isProfileOpen"
                class="flex items-center justify-between px-4 py-3 text-[#1b1b18] dark:text-[#EDEDEC] border border-gray-300 dark:border-gray-600 rounded-md hover:bg-blue-500 hover:text-white transition duration-300 cursor-pointer">
                <span
                    class="font-normal overflow-hidden whitespace-nowrap">{{ auth()->user()->role_display_name }}</span>
                <svg :class="{ 'rotate-0': isProfileOpen, 'rotate-180': !isProfileOpen }"
                    class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>

    </div>

    <!-- Overlay (แสดงเมื่อ sidebar เปิดบนมือถือ) -->
    <div x-show="isSidebarOpen" @click="isSidebarOpen = false"
        class="fixed inset-0 bg-black/50 dark:bg-black/70 z-20 lg:hidden" x-cloak>
    </div>
</div>
