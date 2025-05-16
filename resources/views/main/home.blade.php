<x-app-layout>
    <x-slot name="style">
        <style>
            .animate-ken-burns {
                animation: ken-burns 20s ease infinite alternate;
            }

            @keyframes ken-burns {
                0% {
                    transform: scale(1);
                }

                100% {
                    transform: scale(1.1);
                }
            }
        </style>
    </x-slot>
    <div class="relative bg-gradient-to-br from-primary-900 via-primary-700 to-primary-500">
        <div class="absolute inset-0 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?q=80&w=2144&auto=format&fit=crop"
                alt="City View" class="w-full h-full object-cover animate-ken-burns">
            <div class="absolute inset-0 bg-black opacity-40"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 ">
            <div class="text-center">
                <h1
                    class="text-5xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl drop-shadow-lg animate-fade-in-up">
                    {{ $settings['title_home'] }}
                </h1>
                <p class="mt-8 max-w-2xl mx-auto text-xl text-gray-100 leading-relaxed animate-fade-in-up delay-200">
                    {{ $settings['description_home'] }}
                </p>
                <div
                    class="mt-12 max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center animate-fade-in-up delay-300">
                    <div class="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:gap-5">
                        <a href="#places"
                            class="group flex items-center justify-center px-8 py-4 border-2 border-white text-lg font-bold rounded-full text-white hover:bg-white hover:text-primary-700 transition-all duration-300 transform hover:scale-105 md:py-5 md:text-xl md:px-12">
                            <span>استكشف الأماكن</span>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.header')
    @include('main.list', ['places' => $places])
</x-app-layout>
