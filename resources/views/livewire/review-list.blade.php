<x-card-page>
    @foreach ($reviews as $review)
    <div class="bg-white border rounded-lg shadow-sm p-6 mb-4 hover:shadow-lg transition ease-linear">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $review->user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" src="{{ $review->user->profile_photo_url }}" alt="{{ $review->user->name }}">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            @foreach(['service', 'price', 'cleanliness', 'quality'] as $key)
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-600">{{ __('place.'.$key) }}:</span>
                <div class="flex items-center">
                    <span class="text-sm font-bold ml-1">{{ $review->{$key.'_rating'} }}</span>
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <x-star-icon class="h-4 w-4 {{ $i <= $review->{$key.'_rating'} ? 'text-yellow-400' : 'text-gray-300' }}" />
                        @endfor
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <p class="text-gray-700 leading-relaxed">{{ $review->review }}</p>
        <livewire:like-review wire:key="like-review-{{ $review->id }}" :is-liked="$review->is_liked" :review="$review" :count="$review->users_like_count" />
    </div>
    @endforeach
    @if ($hasMorePages)
    <div class="flex justify-center mt-4">
        <x-button wire:click="loadMore" wire:loading.attr="disabled">
            تحميل المزيد
                <x-spinner />
        </x-button>
    </div>
    @endif
</x-card-page>
