@extends('layouts.app')

@section('title', 'Поиск мероприятий')

@section('content')
    <header-component :show-nav-links="false"></header-component>
    
    <main>
        <search-component 
            :initial-events='@json($events)'
            :initial-slider-events='@json($sliderEvents)'
        ></search-component>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
@endsection