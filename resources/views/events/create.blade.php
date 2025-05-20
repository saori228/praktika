@extends('layouts.app')

@section('title', 'Создание мероприятия')

@section('content')
<div class="flex flex-col min-h-screen">
    <header-component :show-nav-links="true"></header-component>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl md:text-3xl font-bold mb-6">Создание мероприятия</h1>
            
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <form action="{{ route('events.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-medium mb-2">Название</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-medium mb-2">Описание</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="event_date" class="block text-gray-700 font-medium mb-2">Дата проведения</label>
                        <input 
                            type="date" 
                            id="event_date" 
                            name="event_date" 
                            value="{{ old('event_date') }}" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                    </div>
                    
                    <div class="mb-4">
                        <label for="event_type_id" class="block text-gray-700 font-medium mb-2">Тип мероприятия</label>
                        <select 
                            id="event_type_id" 
                            name="event_type_id" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                            <option value="">Выберите тип</option>
                            @foreach($eventTypes as $type)
                                <option value="{{ $type->id }}" {{ old('event_type_id') == $type->id ? 'selected' : '' }}>
                                    @if($type->name == 'concert')
                                        Концерт
                                    @elseif($type->name == 'theater')
                                        Театр
                                    @elseif($type->name == 'movie')
                                        Кино
                                    @else
                                        {{ $type->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="artist_id" class="block text-gray-700 font-medium mb-2">Артист</label>
                        <select 
                            id="artist_id" 
                            name="artist_id" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Выберите артиста</option>
                            @foreach($artists as $artist)
                                <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                                    {{ $artist->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image_path" class="block text-gray-700 font-medium mb-2">Путь к изображению</label>
                        <input 
                            type="text" 
                            id="image_path" 
                            name="image_path" 
                            value="{{ old('image_path') }}" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="/images/events/your-image.jpg"
                        >
                    </div>
                    
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Создать
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
</div>
@endsection