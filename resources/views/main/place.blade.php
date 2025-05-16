<x-app-layout>
    <x-slot name="style">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    </x-slot>
    @include('layouts.header')
    <div class="container mx-auto px-4 max-w-6xl py-4 flex flex-col gap-4">
        <x-card-page>
            <h2 class="text-xl font-bold border-b pb-2">تفصيل الموقع</h2>
            <div class="flex flex-col-reverse sm:flex-row flex-wrap gap-y-2 mt-4">
                <div class="flex flex-col gap-2 w-fit sm:w-2/3">
                    <h1 class="text-2xl font-bold">{{ $place->name }}</h1>
                    <p class="text-gray-600">{{ $place->description }}</p>
                    <address class="text-gray-500 font-light select-all">{{ $place->address }}</address>
                    <div class="flex gap-2 mt-4">
                        <livewire:button-add-to-bookmarks :place="$place" />
                        <livewire:button-report :place="$place" />
                    </div>
                    <div id="map" style="width: 100%; height: 400px;"></div>
                </div>
                <div class="w-full sm:w-1/3 px-4">
                    <img src="{{ $place->imageLink }}" alt="{{ $place->name }}"
                        class="w-full object-cover aspect-square rounded-lg hover:brightness-[1.25] hover:shadow-2xl transition-all duration-300">
                </div>
            </div>
        </x-card-page>
        <x-card-page>
            <h2 class="text-xl font-bold border-b pb-2">التقييمات</h2>
            <div class="mt-4 space-y-4">
                @foreach(['service', 'price', 'cleanliness', 'quality'] as $key)
                    <div class="flex items-center gap-4">
                        <div class="w-24 text-sm font-bold">{{ __('place.' . $key) }}</div>
                        <div class="flex items-center">
                            <span class="text-xl font-bold">{{ $place->reviewsAverage()[$key . '_average'] }}</span>
                            <x-star-icon class="h-5 w-5 text-yellow-400" />
                        </div>
                        <div class="flex-1 h-4 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-400 rounded-full"
                                style="width: {{ $place->reviewsAverage()[$key . '_average'] / 5 * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <p class="text-lg text-gray-600">إجمالي التقييمات: <span
                        class="font-bold">{{ $place->reviewsAverage()['count'] }}</span></p>
            </div>
        </x-card-page>
        <livewire:review-list :place="$place" />
        <x-card-page>
            <h2 class="text-xl font-bold border-b pb-2">إضافة مراجعة</h2>
            <form action="{{ route('place.store', ['place' => $place]) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                @foreach(['service', 'price', 'cleanliness', 'quality'] as $key)
                    <div class="flex items-center gap-4">
                        <h3 class="w-24 text-sm font-bold">{{ __('place.' . $key) }}</h3>
                        <div class="flex flex-row-reverse items-center gap-2 stars">
                            @for($i = 5; $i >= 1; $i--)
                                <label class="cursor-pointer">
                                    <input type="radio" name="{{ $key }}_rating" value="{{ $i }}" class="hidden peer"
                                        onchange="updateStars(this)">
                                    <x-star-icon class="h-8 w-8 text-gray-300 hover:text-yellow-400 transition-colors"
                                        data-rating="{{ $i }}" />
                                </label>
                            @endfor
                        </div>
                    </div>
                @endforeach
                <div>
                    <label for="comment" class="block text-sm font-bold mb-2">المراجعة</label>
                    <textarea id="comment" name="comment" rows="4"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-400 focus:ring focus:ring-primary-200"></textarea>
                </div>
                <div class="flex justify-end">
                    @if(auth()->check())
                        <x-button type="submit">
                            إرسال المراجعة
                        </x-button>
                    @else
                        <span class="text-red-500">
                            يجب عليك تسجيل الدخول لإرسال مراجعة
                        </span>
                    @endif
                </div>
                <x-input-error for="comment" />
                <x-input-error for="service_rating" />
                <x-input-error for="price_rating" />
                <x-input-error for="cleanliness_rating" />
                <x-input-error for="quality_rating" />
            </form>
        </x-card-page>
    </div>
    <x-slot name="script">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            function updateStars(input) {
                const rating = parseInt(input.value);
                const currentStars = input.closest('.flex').querySelectorAll('svg');
                currentStars.forEach(star => {
                    const starRating = parseInt(star.dataset.rating);
                    if (starRating <= rating) {
                        star.classList.add('text-yellow-400');
                        star.classList.remove('text-gray-300');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }
            document.addEventListener('DOMContentLoaded', function () {
                const map = L.map('map');
                L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);
                map.setView([{{ $place->latitude }}, {{ $place->longitude }}], 8);
                L.marker([{{ $place->latitude }}, {{ $place->longitude }}]).addTo(map);
            });
        </script>
    </x-slot>
</x-app-layout>
