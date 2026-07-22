@extends('layouts.app')

@section('content')
    <!-- Main Content Section -->
    <div class="flex items-center justify-center w-full h-screen">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            <!-- Text Content -->
            <div
                class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                <img src="{{ asset('img/logo.svg') }}" alt="Logo">

                <div class="mt-5">
                    <label class="text-[#706f6c] dark:text-[#A1A09A]">Laravel has an incredibly rich ecosystem.</label>
                </div>
                <div class="mb-5">
                    <label class="text-[#706f6c] dark:text-[#A1A09A]">We suggest starting with the following.</label>
                </div>

                <!-- Register Link -->
                <ul class="flex gap-3 text-sm leading-normal">
                    <li>
                        <div class="flex">
                            <a href="{{ route('login') }}" wire:navigate
                                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-10 py-4 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                                <span class="text-xl font-bold">Get Start</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Image Section -->
            <div
                class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
                <div
                    class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
                </div>
                <img src="https://www.thetilt.com/wp-content/uploads/2021/12/Online-Course-2.jpeg" alt="Online Course"
                    class="w-full h-full object-cover" />
            </div>
        </main>
    </div>

    <script>
        document.title = "LOWallet";
    </script>
@endsection
