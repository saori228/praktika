@extends('layouts.app')

@section('title', $artist->name)

@section('content')
<div class="flex flex-col min-h-screen">
    <header-component 
        :is-artist-page="true" 
        :artist="{{ json_encode($artist) }}"
        :event-date="'{{ $eventDate }}'"
        :event-id="{{ $eventId }}"
        :show-nav-links="true"
        style="background-image: url('/images/headers/{{ $artist->id }}.jpg');"
    ></header-component>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <div class="artist-description mb-8">
                <div class="md:w-2/3 mx-auto">
                    <p class="text-gray-700">{{ $artist->description }}</p>
                </div>
            </div>
        </div>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
</div>
@endsection