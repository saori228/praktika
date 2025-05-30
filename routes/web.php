<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Аутентификация
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Защищенные маршруты
Route::middleware('auth')->group(function () {
    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
// Маршруты для профиля
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/organizer', [ProfileController::class, 'organizer'])->name('profile.organizer');
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    });
    
    // Бронирование
    Route::post('/events/{id}/booking', [EventController::class, 'bookingStore'])->name('events.booking.store');
    
    // Администратор
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/events', [AdminController::class, 'events'])->name('admin.events');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::delete('/admin/events/{id}', [AdminController::class, 'destroyEvent'])->name('admin.events.destroy');
        
    });
});

// Публичные маршруты
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{id}/booking', [EventController::class, 'booking'])->name('events.booking');
Route::get('/artists/{id}', [ArtistController::class, 'show'])->name('artists.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// API маршруты
Route::get('/api/events/{id}/unavailable-seats', [EventController::class, 'getUnavailableSeats']);

// Маршруты для администратора
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/events', [ProfileController::class, 'adminEvents'])->name('admin.events');
});
Route::get('/api/events/{id}', [SearchController::class, 'getEventInfo'])->name('api.events.info');
// Добавьте эти маршруты, если их еще нет
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::post('/api/search', [App\Http\Controllers\SearchController::class, 'search'])->name('api.search');

// Добавьте API маршруты для получения событий
Route::get('/api/events', function() {
    $events = App\Models\Event::with(['eventType', 'artist'])->get();
    return response()->json(['events' => $events]);
})->name('api.events');

// Обновленный маршрут для получения событий слайдера
Route::get('/api/slider-events', [SearchController::class, 'getSliderEvents'])->name('api.slider-events');
