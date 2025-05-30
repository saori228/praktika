@extends('layouts.app')

@section('title', 'Бронирование билетов - ' . $event->name)

@section('content')
<div class="bg-gray-100 min-h-screen">
    <header-component :show-nav-links="true"></header-component>
    
    <main class="py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl md:text-3xl font-bold mb-6 text-center">Бронирование билетов</h1>
            
            <event-booking 
                :event="{{ json_encode($event) }}"
            ></event-booking>
        </div>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
</div>
@endsection
