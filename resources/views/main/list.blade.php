<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 py-4 px-2 container mx-auto md:max-w-5xl">
    @forelse ($places as $place)
    <x-card :photo="$place->imageLink" :name="$place->name" :link="route('place.show', $place->slug)">
        <div class="flex flex-col gap-2 px-4 pb-2 flex-1">
            <h2 class="text-lg font-bold">
                <a href="{{ route('place.show', $place->slug) }}" class="text-primary-500 hover:text-primary-700 transition duration-200 ease-in-out">{{ $place->name }}</a>
            </h2>
            <p class="text-sm text-gray-500">{{ str($place->description)->limit(100) }}</p>
            <div class="flex-1"></div>
            <h6 class="w-fit text-xs text-gray-600 inline-block bg-gray-100 px-2 py-1 rounded-full">{{ $place->address }}</h6>
        </div>
    </x-card>
    @empty
    <div class="sm:col-span-2 md:col-span-3 text-center text-gray-500">
        <h2 class="text-2xl font-bold">لا يوجد مكان {{ isset($error) ? $error : ' في هذه الفئة' }}</h2>
    </div>
    @endforelse
    <div class="sm:col-span-2 md:col-span-3 mt-4">
    {{ $places->links('vendor.pagination.tailwind') }}
</div>
</div>
