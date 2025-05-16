<?php

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Component;

class ButtonAddToBookmarks extends Component
{
    #[Locked]
    public $place;
    public $isBookmarked = false;
    public function mount()
    {
        if (!auth()->check())
            $this->isBookmarked = false;
        else $this->isBookmarked = auth()->user()->bookmarks()->where('place_id', $this->place->id)->exists();
    }
    public function render()
    {
        return view('livewire.button-add-to-mookmarks');
    }
    public function addToBookmarks()
    {
        if (!auth()->check()) {
            $this->dispatch('toastify', message: 'يجب عليك الدخول لإضافة المكان إلى المفضلة', type: 'error');
            return;
        }
        if ($this->isBookmarked) {
            auth()->user()->bookmarks()->detach($this->place->id);
            $this->isBookmarked = false;
            $this->dispatch('toastify', message: 'تم إزالة المكان من المفضلة', type: 'success');
        } else {
            auth()->user()->bookmarks()->attach($this->place->id);
            $this->isBookmarked = true;
            $this->dispatch('toastify', message: 'تم إضافة المكان إلى المفضلة', type: 'success');
        }
    }
}
