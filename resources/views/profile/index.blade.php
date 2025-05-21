@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<div class="flex flex-col min-h-screen">
    <header-component :show-nav-links="true"></header-component>
    
    <main class="flex-grow">
        <profile-component 
            :user="{{ json_encode($user) }}" 
            :bookings="{{ json_encode($bookings) }}"
            :is-organizer="{{ json_encode($isOrganizer) }}"
            :is-admin="{{ json_encode($isAdmin ?? false) }}"
        ></profile-component>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
</div>
@endsection