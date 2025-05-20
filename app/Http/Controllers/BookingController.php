<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Seat;
use App\Models\VenueZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function create($eventId)
    {
        $event = Event::with(['venueZones.seats', 'artist'])->findOrFail($eventId);
        return view('bookings.create', compact('event'));
    }
    
    public function store(Request $request, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'seats' => 'required|array|min:1',
            'seats.*' => 'required|exists:seats,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $event = Event::findOrFail($eventId);
        $seats = Seat::whereIn('id', $request->seats)->with('venueZone')->get();
        
        // Проверяем, что все места доступны
        foreach ($seats as $seat) {
            if (!$seat->is_available) {
                return redirect()->back()->with('error', 'Некоторые из выбранных мест уже забронированы');
            }
            
            // Проверяем, что все места принадлежат этому мероприятию
            if ($seat->venueZone->event_id != $event->id) {
                return redirect()->back()->with('error', 'Некорректные данные');
            }
        }
        
        // Вычисляем общую стоимость
        $totalPrice = 0;
        foreach ($seats as $seat) {
            $totalPrice += $seat->venueZone->price;
        }
        
        DB::beginTransaction();
        
        try {
            // Создаем бронирование
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'total_price' => $totalPrice,
            ]);
            
            // Добавляем места к бронированию и помечаем их как недоступные
            foreach ($seats as $seat) {
                $booking->bookingSeats()->create([
                    'seat_id' => $seat->id,
                    'price' => $seat->venueZone->price,
                ]);
                
                $seat->update(['is_available' => false]);
            }
            
            DB::commit();
            
            return redirect()->route('profile.index')->with('success', 'Билеты успешно забронированы');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Произошла ошибка при бронировании: ' . $e->getMessage());
        }
    }
}