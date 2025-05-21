@extends('layouts.app')

@section('title', 'Управление всеми мероприятиями')

@section('content')
<div class="flex flex-col min-h-screen">
    <header-component :show-nav-links="true"></header-component>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold mb-6">Управление всеми мероприятиями</h1>
            
            <div class="mb-8">
                <a href="{{ route('profile') }}" class="inline-block px-6 py-3 bg-gray-600 text-white rounded-full hover:bg-gray-700">
                    Вернуться в профиль
                </a>
            </div>
            
            <div class="events-list">
                <h2 class="text-xl font-bold mb-4">Все мероприятия</h2>
                
                @if(count($events) === 0)
                    <p class="text-gray-500">Мероприятий пока нет</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden shadow-lg">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left">ID</th>
                                    <th class="py-3 px-4 text-left">Название</th>
                                    <th class="py-3 px-4 text-left">Тип</th>
                                    <th class="py-3 px-4 text-left">Дата</th>
                                    <th class="py-3 px-4 text-left">Организатор</th>
                                    <th class="py-3 px-4 text-left">Статус</th>
                                    <th class="py-3 px-4 text-left">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr class="border-b hover:bg-gray-100">
                                        <td class="py-3 px-4">{{ $event->id }}</td>
                                        <td class="py-3 px-4">{{ $event->name }}</td>
                                        <td class="py-3 px-4">{{ $event->eventType->name }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($event->event_date)->format('d.m.Y H:i') }}</td>
                                        <td class="py-3 px-4">{{ $event->user->name }} {{ $event->user->surname }}</td>
                                        <td class="py-3 px-4">
                                            @if($event->is_active)
                                                <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Активно</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-200 text-red-800 rounded-full text-xs">Неактивно</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <a href="/events/{{ $event->id }}/edit" class="px-3 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700 text-sm">
                                                    Редактировать
                                                </a>
                                                <form action="/events/{{ $event->id }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это мероприятие?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                                        Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
</div>
@endsection