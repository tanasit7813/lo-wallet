    <!-- resources/views/layouts/app-with-sidebar.blade.php -->
    @extends('layouts.app')

    @section('content')
        @auth
            <div class="flex min-h-screen" x-data="{
                isSidebarOpen: false,
                isProfileOpen: false
            }" x-cloak>
                <!-- Include Sidebar -->
                @include('layouts.sidebar')

                <!-- Content (ฝั่งขวา) -->
                <div @click="isProfileOpen = false" :class="{ 'ml-64': isSidebarOpen }"
                    class="flex-1 flex flex-col min-h-screen lg:ml-64 transition-all duration-300">
                    <!-- Hamburger Menu Button (แสดงบนมือถือ) -->
                    <div class="p-4 lg:hidden">
                        <button @click="isSidebarOpen = true" class="cursor-pointer text-[#1b1b18] dark:text-[#EDEDEC] z-40"
                            aria-label="Open sidebar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- Main Content -->
                    <div class="flex-1 p-6 bg-white dark:bg-[#161615] overflow-y-auto">
                        @if (session('login_success'))
                            <x-popup message="เข้าสู่ระบบในฐานะ {{ auth()->user()->role_display_name }} เรียบร้อยแล้ว"
                                bgColor="bg-green-500" darkBgColor="dark:bg-green-900" />
                        @elseif (session('register_success'))
                            <x-popup message="สมัครสมาชิกสำเร็จ ยินดีต้อนรับ {{ session('register_success')['name'] }}"
                                bgColor="bg-green-500" darkBgColor="dark:bg-green-900" />
                        @endif

                        <!-- Existing Flash Messages -->
                        <div x-show="flash?.show" class="flash-message" :class="flash?.type" x-data="{ show: true }"
                            x-show="show" x-init="setTimeout(() => show = false, 5000)">
                            <span x-text="flash?.message"></span>
                        </div>

                        <!-- Content ที่จะมาจาก view อื่นๆ -->
                        @yield('page-content')
                    </div>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center min-h-screen bg-white dark:bg-[#161615] space-y-8">
                <div
                    class="w-full p-6 bg-red-100 dark:bg-red-900 border border-red-500 dark:border-red-700 rounded-lg shadow-lg text-center max-w-md">
                    <div class="mb-10">
                        <label class="text-2xl font-bold text-red-500 dark:text-white">คุณยังไม่ได้เข้าสู่ระบบ</label>
                    </div>
                    <a href="{{ route('login') }}" wire:navigate
                        class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-6 py-4 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                        <span class="text-xl font-bold">กลับไปหน้าเข้าสู่ระบบ</span>
                    </a>
                </div>
            </div>
        @endauth
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Alpine.store('flash', {
                    show: false,
                    type: '',
                    message: ''
                });

                Livewire.on('redirect', (event) => {
                    Livewire.navigate(event.url);
                });

                Livewire.on('course-created', () => {
                    Alpine.store('flash', {
                        show: true,
                        type: 'success',
                        message: 'สร้างคอร์สสำเร็จ!'
                    });
                });
            });
        </script>
    @endpush
