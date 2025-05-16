<div class="bg-white rounded-lg shadow-md overflow-hidden transition duration-200 ease-in-out hover:shadow-lg hover:-translate-y-2 border border-gray-100 flex flex-col">
    @isset($photo)
    @empty($link)
    <div class="w-full aspect-square mb-4">
        <img src="{{ $photo }}" alt="{{ $name ?? $photo }}" class="w-full h-full object-cover rounded-t-lg">
    </div>
    @else
    <a href="{{ $link }}">
        <div class="w-full aspect-square mb-4">
            <img src="{{ $photo }}" alt="{{ $name ?? $photo }}" class="w-full h-full object-cover rounded-t-lg">
        </div>
    </a>
    @endempty
    @endisset
    {{ $slot }}
</div>
