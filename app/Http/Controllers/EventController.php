<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Artist;
use App\Models\VenueZone;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Отображение списка мероприятий
     */
    public function index()
    {
        $events = Event::with(['eventType', 'artist'])->get();
        
        return view('events.index', [
            'events' => $events
        ]);
    }
    
    /**
     * Отображение страницы создания мероприятия
     */
    public function create()
    {
        $eventTypes = EventType::all();
        $artists = Artist::all();
        
        return view('events.create', [
            'eventTypes' => $eventTypes,
            'artists' => $artists
        ]);
    }
    
    /**
     * Сохранение нового мероприятия
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'event_date' => 'required|date',
        'event_time' => 'required',
        'event_type_id' => 'required|exists:event_types,id',
        'image_path' => 'nullable|string',
    ]);
    
    // Объединяем дату и время
    $eventDateTime = $validated['event_date'] . ' ' . $validated['event_time'];
    
    $event = Event::create([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'event_date' => $eventDateTime,
        'event_type_id' => $validated['event_type_id'],
        'image_path' => $validated['image_path'],
        'user_id' => Auth::id(),
    ]);
    
    // Создаем зоны в зависимости от типа мероприятия
    if ($request->event_type_id == 1) { // Концерт
        $this->createConcertZones($event);
    } elseif ($request->event_type_id == 2) { // Театр
        $this->createTheaterZones($event);
    } elseif ($request->event_type_id == 3) { // Кино
        $this->createMovieZones($event);
    }
    
    return redirect()->route('profile.organizer')->with('success', 'Мероприятие успешно создано');
}
    
    /**
 * Отображение мероприятия
 */
public function show($id)
{
    try {
        $event = Event::with(['eventType', 'artist', 'venueZones'])
            ->findOrFail($id);
        
        // Логируем для отладки
        \Illuminate\Support\Facades\Log::info('Showing event: ' . $id);
        \Illuminate\Support\Facades\Log::info('Event name: ' . $event->name);
        
        // Подготавливаем переменные для представления
        $artist = $event->artist;
        $eventDate = \Carbon\Carbon::parse($event->event_date)->format('d.m.Y H:i');
        $eventId = $event->id;
        
        // Если у мероприятия нет артиста, создаем пустой объект
        if (!$artist) {
            $artist = new \stdClass();
            $artist->name = $event->name;
            $artist->description = $event->description;
            $artist->image_path = $event->image_path;
        }
        
        return view('events.show', compact('event', 'artist', 'eventDate', 'eventId'));
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error showing event: ' . $e->getMessage());
        return redirect()->route('home')->with('error', 'Мероприятие не найдено');
    }
}
    
    /**
     * Отображение страницы редактирования мероприятия
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $eventTypes = EventType::all();
        $artists = Artist::all();
        
        // Проверяем, что пользователь является владельцем мероприятия
        if (Auth::id() !== $event->user_id && Auth::user()->email !== 'sasha123no@gmail.com') {
            return redirect()->route('profile.organizer')->with('error', 'У вас нет прав на редактирование этого мероприятия');
        }
        
        return view('events.edit', [
            'event' => $event,
            'eventTypes' => $eventTypes,
            'artists' => $artists
        ]);
    }
    
/**
 * Обновление мероприятия
 */
public function update(Request $request, $id)
{
    $event = Event::findOrFail($id);
    
    // Проверяем права доступа: пользователь должен быть либо владельцем мероприятия, либо администратором
    if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
        return redirect()->route('profile')->with('error', 'У вас нет доступа к редактированию этого мероприятия');
    }
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'event_date' => 'required|date',
        'event_type_id' => 'required|exists:event_types,id',
        'artist_id' => 'nullable|exists:artists,id',
        'is_active' => 'boolean',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    // Обработка изображения
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('events', 'public');
        $validated['image_path'] = 'storage/' . $imagePath;
    }
    
    // Обновляем мероприятие
    $event->update($validated);
    
    // Обновляем зоны мест, если они были отправлены
    if ($request->has('zones')) {
        foreach ($request->zones as $zoneId => $zoneData) {
            $zone = $event->venueZones()->find($zoneId);
            if ($zone) {
                $zone->update([
                    'name' => $zoneData['name'],
                    'price' => $zoneData['price'],
                    'capacity' => $zoneData['capacity'] ?? 0,
                ]);
            }
        }
    }
    
    // Добавляем новые зоны, если они были отправлены
    if ($request->has('new_zones')) {
        foreach ($request->new_zones as $newZone) {
            if (!empty($newZone['name']) && !empty($newZone['price'])) {
                $event->venueZones()->create([
                    'name' => $newZone['name'],
                    'price' => $newZone['price'],
                    'capacity' => $newZone['capacity'] ?? 0,
                ]);
            }
        }
    }
    
    return redirect()->route('events.edit', $event->id)->with('success', 'Мероприятие успешно обновлено');
}

/**
 * Удаление мероприятия
 */
public function destroy($id)
{
    $event = Event::findOrFail($id);
    
    // Проверяем права доступа: пользователь должен быть либо владельцем мероприятия, либо администратором
    if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
        return redirect()->route('profile')->with('error', 'У вас нет доступа к удалению этого мероприятия');
    }
    
    // Удаляем мероприятие
    $event->delete();
    
    // Перенаправляем пользователя
    if (Auth::user()->role === 'admin' && request()->header('referer') && strpos(request()->header('referer'), 'admin/events') !== false) {
        return redirect()->route('admin.events')->with('success', 'Мероприятие успешно удалено');
    }
    
    return redirect()->route('profile')->with('success', 'Мероприятие успешно удалено');
}
    
    /**
  * Отображение страницы бронирования
     */
    public function booking($id)
    {
        try {
            // Логируем начало выполнения метода
            Log::info('Начало выполнения метода booking', ['event_id' => $id]);
            
            // Получаем мероприятие с зонами и местами
            $event = Event::with(['eventType', 'artist', 'venueZones.seats'])->findOrFail($id);
            
            // Логируем успешное получение мероприятия
            Log::info('Мероприятие найдено', [
                'event_id' => $id, 
                'event_name' => $event->name,
                'event_type' => $event->eventType->name,
                'zones_count' => $event->venueZones->count()
            ]);
            
            return view('events.booking', [
                'event' => $event
            ]);
        } catch (\Exception $e) {
            // Логируем ошибку
            Log::error('Ошибка при загрузке страницы бронирования', [
                'event_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Перенаправляем на страницу поиска с сообщением об ошибке
            return redirect()->route('search')->with('error', 'Не удалось загрузить страницу бронирования. Пожалуйста, попробуйте позже.');
        }
    }
    
    /**
     * Сохранение бронирования
     */
/**
 * Сохранение бронирования
 */
public function bookingStore(Request $request, $id)
{
    $event = Event::findOrFail($id);
    
    $validated = $request->validate([
        'seats' => 'required|array',
        'total_price' => 'required|numeric|min:0'
    ]);
    
    try {
        DB::beginTransaction();
        
        // Создаем бронирование
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'total_price' => $validated['total_price'],
            'status' => 'confirmed'
        ]);
        
        // Создаем места для бронирования
        foreach ($validated['seats'] as $seat) {
            BookingSeat::create([
                'booking_id' => $booking->id,
                'venue_zone_id' => $seat['zone_id'],
                'seat_id' => $seat['seat_id'],
                'price' => $seat['price']
            ]);
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Бронирование успешно создано',
            'booking_id' => $booking->id
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Ошибка при создании бронирования: ' . $e->getMessage()
        ], 500);
    }
}
    
    /**
     * Получение недоступных мест
     */
    public function getUnavailableSeats($id)
    {
        $event = Event::findOrFail($id);
        
        // Получаем все забронированные места для этого мероприятия
        $bookedSeats = BookingSeat::whereHas('booking', function ($query) use ($id) {
            $query->where('event_id', $id);
        })->pluck('seat_id')->toArray();
        
        return response()->json([
            'seats' => $bookedSeats
        ]);
    }
    
    /**
     * Создание зон для концерта
     */
    private function createConcertZones($event)
    {
        // Танцпол
        $dancefloor = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Танцпол',
            'price' => 2500,
            'capacity' => 5000
        ]);
        
        // Премиум зона
        $premium = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Премиум',
            'price' => 3500,
            'capacity' => 200
        ]);
        
        // VIP зона
        $vip = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'VIP зона',
            'price' => 4500,
            'capacity' => 1000
        ]);
        
        // Создаем места для танцпола
        for ($i = 0; $i < 5000; $i++) {
            Seat::create([
                'venue_zone_id' => $dancefloor->id,
                'row' => floor($i / 100) + 1,
                'number' => ($i % 100) + 1,
                'is_available' => true
            ]);
        }
        
        // Создаем места для премиум зоны
        for ($i = 0; $i < 200; $i++) {
            Seat::create([
                'venue_zone_id' => $premium->id,
                'row' => floor($i / 10) + 1,
                'number' => ($i % 10) + 1,
                'is_available' => true
            ]);
        }
        
        // Создаем места для VIP зоны
        for ($i = 0; $i < 1000; $i++) {
            Seat::create([
                'venue_zone_id' => $vip->id,
                'row' => floor($i / 50) + 1,
                'number' => ($i % 50) + 1,
                'is_available' => true
            ]);
        }
    }
    
    /**
     * Создание зон для театра
     */
    private function createTheaterZones($event)
    {
        // Обычные места
        $regular = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Обычные места',
            'price' => 250,
            'capacity' => 150
        ]);
        
        // Места на втором этаже
        $balcony = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Второй этаж',
            'price' => 500,
            'capacity' => 25
        ]);
        
        // Создаем обычные места (10 рядов по 15 мест)
        for ($row = 1; $row <= 10; $row++) {
            for ($seat = 1; $seat <= 15; $seat++) {
                Seat::create([
                    'venue_zone_id' => $regular->id,
                    'row' => $row,
                    'number' => $seat,
                    'is_available' => true
                ]);
            }
        }
        
        // Создаем места на втором этаже (25 мест)
        for ($i = 1; $i <= 25; $i++) {
            Seat::create([
                'venue_zone_id' => $balcony->id,
                'row' => 1,
                'number' => $i,
                'is_available' => true
            ]);
        }
    }
    
    /**
     * Создание зон для кино
     */
    private function createMovieZones($event)
    {
        // Обычные места
        $regular = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Обычные места',
            'price' => 250,
            'capacity' => 150
        ]);
        
        // VIP места
        $vip = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'VIP места',
            'price' => 450,
            'capacity' => 20
        ]);
        
        // Создаем обычные места (10 рядов по 15 мест)
        for ($row = 1; $row <= 10; $row++) {
            for ($seat = 1; $seat <= 15; $seat++) {
                Seat::create([
                    'venue_zone_id' => $regular->id,
                    'row' => $row,
                    'number' => $seat,
                    'is_available' => true
                ]);
            }
        }
        
        // Создаем VIP места (2 ряда по 5 мест с каждой стороны)
        for ($row = 1; $row <= 2; $row++) {
            for ($side = 0; $side <= 1; $side++) {
                for ($seat = 1; $seat <= 5; $seat++) {
                    Seat::create([
                        'venue_zone_id' => $vip->id,
                        'row' => $row,
                        'number' => $side * 10 + $seat, // Левая сторона: 1-5, правая сторона: 11-15
                        'is_available' => true
                    ]);
                }
            }
        }
    }
}