<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Artist;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Получаем бронирования пользователя
        $bookings = Booking::where('user_id', $user->id)
            ->with(['event', 'bookingSeats.venueZone'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Определяем, является ли пользователь организатором
        $isOrganizer = $user->role === 'organizer';
        
        // Определяем, является ли пользователь администратором
        $isAdmin = $user->role === 'admin';
        
        return view('profile.index', compact('user', 'bookings', 'isOrganizer', 'isAdmin'));
    }
    
    public function organizer()
    {
        $user = Auth::user();
        
        // Проверяем, что пользователь является организатором
        if ($user->role !== 'organizer') {
            return redirect()->route('profile')->with('error', 'У вас нет доступа к этой странице');
        }
        
        // Получаем мероприятия организатора
        $events = Event::where('user_id', $user->id)
            ->with(['eventType', 'artist'])
            ->orderBy('event_date', 'desc')
            ->get();
        
        return view('profile.organizer', compact('events'));
    }
    
    public function adminEvents()
    {
        // Проверяем, что пользователь является администратором
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('profile')->with('error', 'У вас нет доступа к этой странице');
        }
        
        // Получаем все мероприятия со связанными данными
        $events = Event::with(['eventType', 'artist', 'user'])
            ->orderBy('event_date', 'desc')
            ->get();
        
        return view('profile.admin_events', compact('events'));
    }
}