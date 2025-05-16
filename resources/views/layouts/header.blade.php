<div class="py-8 bg-white border-b border-primary-100" id="places">
    @livewire('search-places')
    <div class="flex items-center flex-wrap justify-center gap-2 p-4">
        @foreach ($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}"
                class="bg-primary-800 text-white px-4 py-2 rounded-md hover:bg-primary-900 hover:scale-105 transition-all duration-300"
            >{{ $category->name }}</a>
        @endforeach
    </div>
</div>
