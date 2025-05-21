@extends('layouts.app')

@section('title', 'Поиск мероприятий')

@section('content')
<div class="flex flex-col min-h-screen">
    <header-component 
        :is-artist-page="false"
        :is-event-page="false"
        :is-home-page="false"
        :show-nav-links="true"
    ></header-component>
    
    <main class="flex-grow py-8">
        <search-component 
            :initial-events="{{ json_encode($events ?? []) }}"
            :initial-slider-events="{{ json_encode($sliderEvents ?? []) }}"
            :event-types="{{ json_encode($eventTypes ?? []) }}"
        ></search-component>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
</div>
@endsection