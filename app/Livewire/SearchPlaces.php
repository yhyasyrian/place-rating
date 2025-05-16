<?php

namespace App\Livewire;

use App\Models\Place;
use Livewire\Component;

class SearchPlaces extends Component
{
    public $search = '';

    public function render()
    {
        return view('livewire.search-places',['suggestions' => $this->updateSuggestions()]);
    }
    public function updateSuggestions() : array {
        if (empty($this->search)) {
            return [];
        }
        $search = str($this->search)->trim()->replace(['_','%'],'')->toString();
        return Place::where('name', 'like', '%' . $search . '%')
            ->take(5)
            ->pluck('name')
            ->toArray();
    }
}
