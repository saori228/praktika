<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Отображение профиля пользователя
     */
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::with(['event', 'bookingSeats'])
            ->where('user_id', $user->id)
            ->get();
        
        return view('profile.index', [
            'user' => $user,
            'bookings' => $bookings,
            'isOrganizer' => $user->role === 'organizer'
        ]);
    }
    
    /**
     * Обновление данных пользователя
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'surname' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
                'regex:/(.*)gmail\.com$/i'
            ],
        ]);
        
        $user->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Данные успешно обновлены'
        ]);
    }
    
    /**
     * Обновление пароля пользователя
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|max:30|regex:/[A-Z]/',
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Пароль успешно обновлен'
        ]);
    }
    
    /**
     * Страница организатора
     */
    public function organizer()
    {
        $user = Auth::user();
        
        if ($user->role !== 'organizer') {
            return redirect()->route('profile');
        }
        
        $events = $user->events()->with(['eventType', 'artist', 'venueZones'])->get();
        
        return view('profile.organizer', [
            'user' => $user,
            'events' => $events
        ]);
    }
}