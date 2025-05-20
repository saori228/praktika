@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="flex flex-col min-h-screen">
    <header-component :show-nav-links="true"></header-component>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
                <h1 class="text-2xl font-bold mb-6 text-center">Регистрация</h1>
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-medium mb-2">Имя</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            required 
                            autofocus
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="surname" class="block text-gray-700 font-medium mb-2">Фамилия</label>
                        <input 
                            type="text" 
                            id="surname" 
                            name="surname" 
                            value="{{ old('surname') }}" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('surname') border-red-500 @enderror"
                            required
                        >
                        @error('surname')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="role" class="block text-gray-700 font-medium mb-2">Роль</label>
                        <select 
                            id="role" 
                            name="role" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror"
                            required
                        >
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Пользователь</option>
                            <option value="organizer" {{ old('role') == 'organizer' ? 'selected' : '' }}>Организатор</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium mb-2">Пароль</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                            required
                        >
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Подтверждение пароля</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                    </div>
                    
                    <div class="flex flex-col space-y-4">
                        <button 
                            type="submit" 
                            class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Зарегистрироваться
                        </button>
                        
                        <div class="text-center">
                            <p class="text-sm text-gray-600">
                                Уже есть аккаунт? 
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">
                                    Войти
                                </a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <footer-component bg-color="bg-white"></footer-component>
</div>
@endsection