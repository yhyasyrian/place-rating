<form class="flex items-center justify-center gap-2 p-4" action="{{ route('search') }}" method="post">
    @csrf
    <x-input autocomplete="off" wire:model.live.debounce.500ms="search" class="max-w-2xl w-full" type="text" name="search" list="suggestions" placeholder="بحث" />
    <datalist id="suggestions">
        @foreach ($suggestions as $suggestion)
        <option value="{{ $suggestion }}">{{ $suggestion }}</option>
        @endforeach
    </datalist>
    <x-button type="submit">بحث</x-button>
</form>
