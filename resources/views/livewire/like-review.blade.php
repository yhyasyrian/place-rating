<button wire:click="toggleLike" class='flex items-center gap-1'>
    <svg @class([
            'fill-gray-500 hover:fill-red-500 transition-colors duration-200 h-5 w-5',
            'fill-red-500' => $isLiked
        ]) xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
    <span class="text-sm">{{ $count }}</span>
</button>
