@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
    <header-component :show-nav-links="true"></header-component>
    
    <main>
        <profile-component 
            :user="{{ json_encode($user) }}" 
            :bookings="{{ json_encode($bookings) }}"
            :is-organizer="{{ json_encode($isOrganizer) }}"
        ></profile-component>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
@endsection