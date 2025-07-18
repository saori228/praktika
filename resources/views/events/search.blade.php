@extends('layouts.app')

@section('title', 'Бронирование билетов - ' . $event->name)

@section('content')
    <header-component :show-nav-links="true"></header-component>
    
    <main>
        <event-booking :event="{{ json_encode($event) }}"></event-booking>
    </main>
    
    <footer-component bg-color="bg-gray-100"></footer-component>
@endsection