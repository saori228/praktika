<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Отображение панели администратора
     */
    public function index()
    {
        return view('admin.index', [
            'user' => Auth::user(),
            'eventsCount' => Event::count(),
            'usersCount' => User::count()
        ]);
    }
    
    /**
     * Отображение списка всех мероприятий
     */
    public function events()
    {
        $events = Event::with(['eventType', 'artist', 'user'])->get();
        
        return view('admin.events', [
            'user' => Auth::user(),
            'events' => $events
        ]);
    }
    
    /**
     * Отображение списка всех пользователей
     */
    public function users()
    {
        $users = User::all();
        
        return view('admin.users', [
            'user' => Auth::user(),
            'users' => $users
        ]);
    }
    
    /**
     * Удаление мероприятия
     */
    public function destroyEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        
        return redirect()->route('admin.events')->with('success', 'Мероприятие успешно удалено');
    }
}
