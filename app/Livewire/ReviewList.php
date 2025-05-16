<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Locked;
class ReviewList extends Component
{
    private const LIMIT_PER_PAGE = 12;
    #[Locked]
    public $place;
    // as array get as pagination
    public $reviews;
    public $page = 1;
    public bool $hasMorePages = false;
    public function mount()
    {
        $reviews = $this->place->reviews()->with(['user'])->withCount('usersLike')->likedByCurrentUser()->paginate(self::LIMIT_PER_PAGE,page:$this->page++);
        $this->reviews = $reviews->items();
        $this->hasMorePages = $reviews->hasMorePages();
    }
    public function render()
    {
        return view('livewire.review-list');
    }
    public function loadMore()
    {
        $reviews = $this->place->reviews()->with(['user'])->withCount('usersLike')->likedByCurrentUser()->paginate(self::LIMIT_PER_PAGE,page:$this->page++);
        if (count($reviews->items()) > 0) {
            $this->reviews = array_merge($this->reviews,$reviews->items());
            $this->hasMorePages = $reviews->hasMorePages();
        }
    }
}
