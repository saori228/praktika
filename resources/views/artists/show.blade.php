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
        style="background-image: url('{{ $artist->header_image ?? '/images/headers/' . $artist->id . '.jpg' }}');"
    ></header-component>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <artist-detail 
                :artist="{{ json_encode($artist) }}"
                :event="{{ $event ? json_encode($event) : 'null' }}"
            ></artist-detail>
        </div>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
</div>
@endsection