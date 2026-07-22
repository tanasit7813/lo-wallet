<div class="min-h-screen flex items-center justify-center w-full">
    <div
        class="flex flex-col gap-6 w-full max-w-full sm:max-w-md p-4 sm:p-6 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-lg rounded-lg">

        <div class="flex flex-col items-center space-y-2">
            <label class="text-xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">เข้าสู่ระบบ</label>
            <label class="text-lg text-[#706f6c] dark:text-[#A1A09A]">กรอกข้อมูลเพื่อเข้าสู่ระบบ</label>
        </div>

        <!-- แจ้งเตือนข้อผิดพลาด -->
        @if (session('error'))
            <div x-data="{ show: false }" x-init="show = true;
            setTimeout(() => show = false, 5100)" x-show="show" x-cloak
                x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
                class="text-center mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 rounded-md">
                <span class="text-red-600 dark:text-red-400 text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form -->
        <form wire:submit.prevent="login" class="flex flex-col gap-4">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">อีเมล</label>
                <input wire:model="email" type="email" id="email" placeholder="email@example.com"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password"
                    class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">รหัสผ่าน</label>
                <input wire:model="password" type="password" id="password" placeholder="รหัสผ่าน"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                @error('password')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="cursor-pointer w-full group/button relative inline-flex items-center justify-center overflow-hidden rounded-md bg-blue-500 backdrop-blur-lg px-4 sm:px-6 py-2 text-base font-semibold text-white transition-all duration-300 ease-in-out hover:shadow hover:shadow-blue-600/50 border border-white/20">
                <span class="text-base sm:text-lg">เข้าสู่ระบบ</span>
            </button>
        </form>

        <!-- Link to Register -->
        <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
            <label class="text-lg">ยังไม่มีบัญชี?
                <a href="{{ route('register') }}" wire:navigate
                    class="duration-300 font-semibold hover:text-blue-500">สมัครสมาชิก</a>
            </label>
        </div>

        <!-- Pop-up Notification -->
        @if (session('logout_success'))
            <x-popup message="คุณได้ออกจากระบบเรียบร้อยแล้ว" bgColor="bg-red-500" darkBgColor="dark:bg-red-900" />
        @endif
    </div>
</div>

<script>
    document.title = "Login";
</script>
