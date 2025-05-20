@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <header-component :is-home-page="true" :show-nav-links="true"></header-component>
    
    <main>
        <artists-list :artists="{{ json_encode($artists) }}"></artists-list>
        <events-list :events="{{ json_encode($events) }}"></events-list>
    </main>
    
    <footer-component bg-color="bg-blue-200"></footer-component>
@endsection