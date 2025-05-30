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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
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
                        
                        <div>
                            <label for="event_time" class="block text-gray-700 font-medium mb-2">Время проведения</label>
                            <input 
                                type="time" 
                                id="event_time" 
                                name="event_time" 
                                value="{{ old('event_time') }}" 
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                        </div>
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
                    
                    <!-- Изображение для страницы поиска -->
                    <div class="mb-4">
                        <label for="image_path" class="block text-gray-700 font-medium mb-2">Изображение для страницы поиска</label>
                        <div class="mb-2">
                            <select 
                                id="image_path" 
                                name="image_path" 
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="toggleCustomImagePath()"
                            >
                                <option value="">Выберите изображение</option>
                                <option value="/images/events/tri-dnya-dozhdya.jpg" {{ old('image_path') == '/images/events/tri-dnya-dozhdya.jpg' ? 'selected' : '' }}>
                                    Три дня дождя
                                </option>
                                <option value="/images/events/og-buda.jpg" {{ old('image_path') == '/images/events/og-buda.jpg' ? 'selected' : '' }}>
                                    OG Buda
                                </option>
                                <option value="/images/events/sqwoz-bab.jpg" {{ old('image_path') == '/images/events/sqwoz-bab.jpg' ? 'selected' : '' }}>
                                    SQWOZ BAB
                                </option>
                                <option value="/images/events/husky.jpg" {{ old('image_path') == '/images/events/husky.jpg' ? 'selected' : '' }}>
                                    Хаски
                                </option>
                                <option value="/images/events/offset.jpg" {{ old('image_path') == '/images/events/offset.jpg' ? 'selected' : '' }}>
                                    OFFSET
                                </option>
                                <option value="/images/events/theater.jpg" {{ old('image_path') == '/images/events/theater.jpg' ? 'selected' : '' }}>
                                    Последняя сказка (Театр)
                                </option>
                                <option value="/images/events/movie.jpg" {{ old('image_path') == '/images/events/movie.jpg' ? 'selected' : '' }}>
                                    ОЧИ (Кино)
                                </option>
                                <option value="/images/events/concert-default.jpg" {{ old('image_path') == '/images/events/concert-default.jpg' ? 'selected' : '' }}>
                                    Концерт (по умолчанию)
                                </option>
                                <option value="/images/events/theater-default.jpg" {{ old('image_path') == '/images/events/theater-default.jpg' ? 'selected' : '' }}>
                                    Театр (по умолчанию)
                                </option>
                                <option value="/images/events/movie-default.jpg" {{ old('image_path') == '/images/events/movie-default.jpg' ? 'selected' : '' }}>
                                    Кино (по умолчанию)
                                </option>
                                <option value="custom">Свой путь к изображению</option>
                            </select>
                        </div>
                        <div id="custom-image-path" style="display: none;">
                            <input 
                                type="text" 
                                name="custom_image_path" 
                                placeholder="Введите путь к изображению (например: /images/events/my-event.jpg)"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('custom_image_path') }}"
                            >
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Изображение для отображения в списке событий на странице поиска</p>
                    </div>
                    
                    <!-- Изображение для страницы показа события -->
                    <div class="mb-4">
                        <label for="display_image" class="block text-gray-700 font-medium mb-2">Изображение для страницы события</label>
                        <div class="mb-2">
                            <select 
                                id="display_image" 
                                name="display_image" 
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="toggleCustomDisplayImage()"
                            >
                                <option value="">Выберите изображение</option>
                                <option value="/images/events/show/tri-dnya-dozhdya-show.jpg" {{ old('display_image') == '/images/events/show/tri-dnya-dozhdya-show.jpg' ? 'selected' : '' }}>
                                    Три дня дождя (показ)
                                </option>
                                <option value="/images/events/show/og-buda-show.jpg" {{ old('display_image') == '/images/events/show/og-buda-show.jpg' ? 'selected' : '' }}>
                                    OG Buda (показ)
                                </option>
                                <option value="/images/events/show/sqwoz-bab-show.jpg" {{ old('display_image') == '/images/events/show/sqwoz-bab-show.jpg' ? 'selected' : '' }}>
                                    SQWOZ BAB (показ)
                                </option>
                                <option value="/images/events/show/husky-show.jpg" {{ old('display_image') == '/images/events/show/husky-show.jpg' ? 'selected' : '' }}>
                                    Хаски (показ)
                                </option>
                                <option value="/images/events/show/offset-show.jpg" {{ old('display_image') == '/images/events/show/offset-show.jpg' ? 'selected' : '' }}>
                                    OFFSET (показ)
                                </option>
                                <option value="/images/events/show/theater-show.jpg" {{ old('display_image') == '/images/events/show/theater-show.jpg' ? 'selected' : '' }}>
                                    Театр (показ)
                                </option>
                                <option value="/images/events/show/movie-show.jpg" {{ old('display_image') == '/images/events/show/movie-show.jpg' ? 'selected' : '' }}>
                                    Кино (показ)
                                </option>
                                <option value="/images/events/show/concert-show-default.jpg" {{ old('display_image') == '/images/events/show/concert-show-default.jpg' ? 'selected' : '' }}>
                                    Концерт (показ по умолчанию)
                                </option>
                                <option value="/images/events/show/theater-show-default.jpg" {{ old('display_image') == '/images/events/show/theater-show-default.jpg' ? 'selected' : '' }}>
                                    Театр (показ по умолчанию)
                                </option>
                                <option value="/images/events/show/movie-show-default.jpg" {{ old('display_image') == '/images/events/show/movie-show-default.jpg' ? 'selected' : '' }}>
                                    Кино (показ по умолчанию)
                                </option>
                                <option value="custom">Свой путь к изображению</option>
                            </select>
                        </div>
                        <div id="custom-display-image" style="display: none;">
                            <input 
                                type="text" 
                                name="custom_display_image" 
                                placeholder="Введите путь к изображению (например: /images/events/show/my-event-show.jpg)"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('custom_display_image') }}"
                            >
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Изображение для отображения на странице события и бронирования</p>
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

<script>
function toggleCustomImagePath() {
    const select = document.getElementById('image_path');
    const customDiv = document.getElementById('custom-image-path');
    
    if (select.value === 'custom') {
        customDiv.style.display = 'block';
    } else {
        customDiv.style.display = 'none';
    }
}

function toggleCustomDisplayImage() {
    const select = document.getElementById('display_image');
    const customDiv = document.getElementById('custom-display-image');
    
    if (select.value === 'custom') {
        customDiv.style.display = 'block';
    } else {
        customDiv.style.display = 'none';
    }
}

// Показать поля для кастомных изображений при загрузке страницы, если они выбраны
document.addEventListener('DOMContentLoaded', function() {
    toggleCustomImagePath();
    toggleCustomDisplayImage();
});
</script>
@endsection
