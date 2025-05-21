<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Artist;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function index()
    {
        try {
            // Получаем все события для страницы поиска
            $events = Event::with(['eventType', 'artist'])->get();
            
            // Получаем события для слайдера (например, последние 5)
            $sliderEvents = Event::with(['eventType', 'artist'])
                ->orderBy('event_date', 'desc')
                ->take(5)
                ->get();
            
            // Получаем все типы мероприятий
            $eventTypes = EventType::all();
            
            return view('search', compact('events', 'sliderEvents', 'eventTypes'));
        } catch (\Exception $e) {
            Log::error('Error in search page: ' . $e->getMessage());
            return view('search', ['events' => [], 'sliderEvents' => [], 'eventTypes' => []]);
        }
    }
    
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $type = $request->input('type');
            
            $eventsQuery = Event::with(['eventType', 'artist']);
            
            if ($query) {
                $eventsQuery->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhereHas('artist', function($q2) use ($query) {
                          $q2->where('name', 'like', "%{$query}%");
                      });
                });
            }
            
            if ($type) {
                $eventsQuery->whereHas('eventType', function($q) use ($type) {
                    $q->where('name', $type);
                });
            }
            
            $events = $eventsQuery->get();
            
            return response()->json([
                'events' => $events
            ]);
        } catch (\Exception $e) {
            Log::error('Error in search API: ' . $e->getMessage());
            return response()->json([
                'error' => 'Произошла ошибка при поиске',
                'events' => []
            ], 500);
        }
    }
}