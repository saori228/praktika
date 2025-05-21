@extends('layouts.app')

@section('title', 'Панель организатора')

@section('content')
<div class="flex flex-col min-h-screen">
    <header-component :show-nav-links="true"></header-component>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold mb-6">Панель организатора</h1>
            
            <div class="mb-8">
                <a href="/events/create" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700">
                    Создать новое мероприятие
                </a>
            </div>
            
            <div class="events-list">
                <h2 class="text-xl font-bold mb-4">Ваши мероприятия</h2>
                
                @if(count($events) === 0)
                    <p class="text-gray-500">У вас пока нет созданных мероприятий</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($events as $event)
                            <div class="event-card bg-white rounded-lg overflow-hidden shadow-lg">
                                <img src="{{ $event->image_path ?? '/images/events/event-placeholder.jpg' }}" alt="{{ $event->name }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold mb-2">{{ $event->name }}</h3>
                                    <p class="text-gray-600 mb-2">{{ \Carbon\Carbon::parse($event->event_date)->format('d.m.Y H:i') }}</p>
                                    <div class="flex space-x-2">
                                        <a href="/events/{{ $event->id }}/edit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            Редактировать
                                        </a>
                                        <form action="/events/{{ $event->id }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это мероприятие?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
</div>
@endsection