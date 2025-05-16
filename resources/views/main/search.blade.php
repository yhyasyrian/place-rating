<x-app-layout>
    @include('layouts.header')
    @include('main.list',['places' => $places, 'error' => 'في كلمة البحث هذه'])
</x-app-layout>
