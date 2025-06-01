<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Artist;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index()
    {
        try {
            // Получаем все события для страницы поиска
            $events = Event::with(['eventType', 'artist'])->get();
            
            // Получаем события для слайдера по их точным названиям
            $sliderEvents = collect();
            
            // Определяем названия событий, которые должны быть в слайдере
            $eventNames = ['OFFSET', 'ОЧИ', 'Последняя сказка', 'Хаски'];
            
            // Ищем события по названиям и добавляем их в коллекцию в нужном порядке
            foreach ($eventNames as $name) {
                $event = Event::with(['eventType', 'artist'])
                    ->where('name', 'like', "%{$name}%")
                    ->first();
                
                if ($event) {
                    $sliderEvents->push($event);
                }
            }
            
            // Получаем все типы мероприятий
            $eventTypes = EventType::all();
            
            // Для отладки выведем ID событий в слайдере
            Log::info('Slider events:', $sliderEvents->pluck('id', 'name')->toArray());
            
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
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');
            
            $eventsQuery = Event::with(['eventType', 'artist']);
            
            // Фильтр по поисковому запросу
            if ($query) {
                $eventsQuery->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhereHas('artist', function($q2) use ($query) {
                          $q2->where('name', 'like', "%{$query}%");
                      });
                });
            }
            
            // Фильтр по типу события
            if ($type) {
                $eventsQuery->whereHas('eventType', function($q) use ($type) {
                    $q->where('name', $type);
                });
            }
            
            // Фильтр по дате
            if ($dateFrom || $dateTo) {
                $eventsQuery->where(function($q) use ($dateFrom, $dateTo) {
                    if ($dateFrom && $dateTo) {
                        // Диапазон дат
                        $q->whereBetween('event_date', [
                            Carbon::parse($dateFrom)->startOfDay(),
                            Carbon::parse($dateTo)->endOfDay()
                        ]);
                    } elseif ($dateFrom) {
                        // От даты
                        $q->where('event_date', '>=', Carbon::parse($dateFrom)->startOfDay());
                    } elseif ($dateTo) {
                        // До даты
                        $q->where('event_date', '<=', Carbon::parse($dateTo)->endOfDay());
                    }
                });
            }
            
            $events = $eventsQuery->orderBy('event_date', 'asc')->get();
            
            return response()->json([
                'events' => $events,
                'total' => $events->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in search API: ' . $e->getMessage());
            return response()->json([
                'error' => 'Произошла ошибка при поиске',
                'events' => []
            ], 500);
        }
    }
    
    // Метод для получения информации о конкретном событии
    public function getEventInfo($id)
    {
        try {
            $event = Event::with(['eventType', 'artist'])->findOrFail($id);
            return response()->json([
                'event' => $event
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting event info: ' . $e->getMessage());
            return response()->json([
                'error' => 'Событие не найдено',
                'event' => null
            ], 404);
        }
    }
    
    // Метод для API слайдера
    public function getSliderEvents()
    {
        try {
            $sliderEvents = collect();
            
            // Определяем названия событий, которые должны быть в слайдере
            $eventNames = ['OFFSET', 'ОЧИ', 'Последняя сказка', 'Хаски'];
            
            // Ищем события по названиям и добавляем их в коллекцию в нужном порядке
            foreach ($eventNames as $name) {
                $event = Event::with(['eventType', 'artist'])
                    ->where('name', 'like', "%{$name}%")
                    ->first();
                
                if ($event) {
                    $sliderEvents->push($event);
                }
            }
            
            // Для отладки выведем ID событий в слайдере
            Log::info('API Slider events:', $sliderEvents->pluck('id', 'name')->toArray());
            
            return response()->json([
                'events' => $sliderEvents
            ]);
        } catch (\Exception $e) {
            Log::error('Error in slider events API: ' . $e->getMessage());
            return response()->json([
                'error' => 'Произошла ошибка при получении событий для слайдера',
                'events' => []
            ], 500);
        }
    }
}
