@extends('layouts.app')

@section('title', $event->name)

@section('content')
    <header-component 
        :is-artist-page="false" 
        :artist="{{ json_encode($artist) }}"
        :event-date="'{{ $eventDate }}'"
        :event-id="{{ $eventId }}"
        :show-nav-links="true"
    ></header-component>
    
    <main class="py-8">
        <div class="container mx-auto px-4">
            <div class="event-details mb-8">
                <h1 class="text-2xl md:text-3xl font-bold mb-4">{{ $event->name }}</h1>
                
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="md:w-1/3">
                        <img src="{{ $event->image_path }}" alt="{{ $event->name }}" class="w-full rounded-lg shadow-lg">
                    </div>
                    
                    <div class="md:w-2/3">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="mb-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $event->eventType->name === 'concert' ? 'Концерт' : 
                                       ($event->eventType->name === 'theater' ? 'Театр' : 
                                       ($event->eventType->name === 'movie' ? 'Кино' : $event->eventType->name)) }}
                                </span>
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium ml-2">
                                    {{ $eventDate }}
                                </span>
                            </div>
                            
                            <p class="text-gray-700 mb-6">{{ $event->description }}</p>
                            
                            @if($event->artist)
                            <div class="mb-6">
                                <h2 class="text-xl font-semibold mb-2">Об артисте</h2>
                                <div class="flex items-center mb-3">
                                    <img src="{{ $artist->image_path }}" alt="{{ $artist->name }}" class="w-12 h-12 rounded-full object-cover mr-3">
                                    <span class="font-medium">{{ $artist->name }}</span>
                                </div>
                                <p class="text-gray-700">{{ $artist->description }}</p>
                            </div>
                            @endif
                            
                            <div class="mt-6">
                                <a href="/events/{{ $event->id }}/booking" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Забронировать билеты
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
@endsection