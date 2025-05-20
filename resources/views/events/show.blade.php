@extends('layouts.app')

@section('title', $artist->name)

@section('content')
    <header-component 
        :is-artist-page="true" 
        :artist="{{ json_encode($artist) }}"
        :event-date="'{{ $eventDate }}'"
        :event-id="{{ $eventId }}"
        :show-nav-links="true"
    ></header-component>
    
    <main class="py-8">
        <div class="container mx-auto px-4">
            <div class="artist-details mb-8">
                <h1 class="text-2xl md:text-3xl font-bold mb-4">{{ $artist->name }}</h1>
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 mb-4 md:mb-0">
                        <img src="{{ $artist->image_path ?? '/images/artists/artist-placeholder.jpg' }}" alt="{{ $artist->name }}" class="w-full h-64 object-cover rounded-lg">
                    </div>
                    <div class="md:w-2/3 md:pl-8">
                        <p class="text-gray-700 mb-4">{{ $artist->description }}</p>
                    </div>
                </div>
            </div>
            
            @if(count($events) > 0)
                <div class="artist-events">
                    <h2 class="text-xl md:text-2xl font-bold mb-4">Мероприятия с участием {{ $artist->name }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($events as $event)
                            <a href="/events/{{ $event->id }}" class="event-card bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                                <img src="{{ $event->image_path ?? '/images/events/event-placeholder.jpg' }}" alt="{{ $event->name }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold">{{ $event->name }}</h3>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($event->event_date)->format('d.m.Y H:i') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
@endsection