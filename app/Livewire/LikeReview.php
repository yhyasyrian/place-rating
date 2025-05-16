<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;

class LikeReview extends Component
{
    public Review $review;
    public bool $isLiked;
    public int $count;
    public function render()
    {
        return view('livewire.like-review');
    }
    public function toggleLike()
    {
        $review = $this->review;
        if (auth()->check()) {
            if ($this->isLiked) {
                $review->usersLike()->detach(auth()->user()->id);
                $this->count--;
                $this->isLiked = false;
                $this->dispatch('toastify',message:'تم إلغاء الإعجاب بالمراجعة',type:'success');
            } else {
                $review->usersLike()->attach(auth()->user()->id);
                $this->count++;
                $this->isLiked = true;
                $this->dispatch('toastify',message:'تم الإعجاب بالمراجعة',type:'success');
            }
        } else {
            $this->dispatch('toastify',message:'يجب عليك الدخول للإعجاب بالمراجعة',type:'error');
        }
    }
}

