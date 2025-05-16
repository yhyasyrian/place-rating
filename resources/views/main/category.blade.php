<x-app-layout>
    @include('layouts.header')
    <h1 class="text-2xl font-bold text-center my-4">{{ $category->name ?? $title }}</h1>
    @include('main.list',['places' => $places])
</x-app-layout>
