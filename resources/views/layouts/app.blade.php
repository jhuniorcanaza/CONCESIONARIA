@props(['title' => '', 'bodyClass' => ''])

<x-base-layout :$title :$bodyClass>

    <x-layouts.header />

    {{ $slot }}
    
</x-base-layout>