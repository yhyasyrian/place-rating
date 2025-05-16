<footer class="bg-primary-800 text-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-between">
            <div class="w-full sm:w-1/2 lg:w-1/4 mb-8 sm:mb-0">
                <h3 class="text-lg font-bold mb-4">عن الموقع</h3>
                <p class="text-sm">{!! nl2br(e($settings['description'])) !!}</p>
            </div>
            <div class="w-full sm:w-1/2 lg:w-1/4 mb-8 sm:mb-0">
                <h3 class="text-lg font-bold mb-4">الروابط السريعة</h3>
                <ul class="text-sm">
                    <li class="mb-2"><a href="{{ route('home') }}" class="hover:text-primary-300">الرئيسية</a></li>
                    @auth
                        <li class="mb-2"><a href="{{ route('bookmarks') }}" class="hover:text-primary-300">المفضلة</a></li>
                    @endauth
                    <li class="mb-2"><a href="#" class="hover:text-primary-300">التصنيفات</a></li>
                </ul>
            </div>
            <div class="w-full sm:w-1/2 lg:w-1/4">
                <h3 class="text-lg font-bold mb-4">تواصل معنا</h3>
                <p class="text-sm">{!! nl2br(e($settings['contact_message'])) !!}</p>
                <nav class="flex lg:justify-center gap-2">
                    @foreach (['facebook','twitter','instagram','linkedin','youtube','whatsapp'] as $value)
                        <a href="{{ $settings[$value] }}" class="hover:text-primary-300"><i class="fab fa-{{ $value }} text-2xl md:text-3xl xl:text-4xl"></i></a>
                    @endforeach
                </nav>
            </div>
        </div>
        <div class="border-t border-primary-700 mt-8 pt-8 text-center text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.
        </div>
    </div>
</footer>
