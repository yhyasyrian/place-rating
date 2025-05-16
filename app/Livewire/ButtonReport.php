<?php

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Component;

class ButtonReport extends Component
{
    #[Locked]
    public $place;
    public function render()
    {
        return view('livewire.button-report');
    }
    public function reportPlace()
    {
        if (!auth()->check()) {
            $this->dispatch('toastify', message: 'يجب عليك الدخول لتبليغ الموقع', type: 'error');
            return;
        }
        if ($this->place->reports()->where('user_id', auth()->id())->exists()) {
            $this->dispatch('toastify', message: 'لقد قمت بالتبليغ عن هذا الموقع من قبل', type: 'error');
            return;
        }
        $this->place->reports()->create([
            'user_id' => auth()->id(),
        ]);
        $this->dispatch('toastify', message: 'تم تبليغ الموقع', type: 'success');
    }
}
