<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class ArtistController extends Controller
{
    public function show($id)
    {
        try {
            // Логируем начало выполнения метода
            Log::info('Начало выполнения метода show в ArtistController', ['artist_id' => $id]);
            
            $artist = Artist::findOrFail($id);
            
            // Получаем ближайшее мероприятие артиста
            $event = Event::where('artist_id', $id)
                ->where('event_date', '>=', now())
                ->orderBy('event_date', 'asc')
                ->first();
            
            // Если нет будущих мероприятий, берем последнее прошедшее
            if (!$event) {
                $event = Event::where('artist_id', $id)
                    ->orderBy('event_date', 'desc')
                    ->first();
            }
            
            // Определяем дату и ID события для кнопки "Купить билет"
            $eventDate = '';
            $eventId = 0;
            
            if ($event) {
                $eventDate = \Carbon\Carbon::parse($event->event_date)->format('d.m.Y');
                $eventId = $event->id;
                
                // Логируем информацию о найденном мероприятии
                Log::info('Найдено мероприятие для артиста', [
                    'artist_id' => $id,
                    'event_id' => $eventId,
                    'event_name' => $event->name,
                    'event_date' => $eventDate
                ]);
            } else {
                Log::warning('Мероприятия для артиста не найдены', ['artist_id' => $id]);
            }
            
            // Определяем дату события в зависимости от артиста
            if ($artist->name === 'Три дня дождя') {
                $eventDate = '18 июня';
            } elseif ($artist->name === 'OG Buda') {
                $eventDate = '22 июня';
            } elseif ($artist->name === 'SQWOZ BAB') {
                $eventDate = '26 июля';
            } elseif ($artist->name === 'Хаски') {
                $eventDate = '9 августа';
            } elseif ($artist->name === 'OFFSET') {
                $eventDate = '15 сентября';
            }
            
            return view('artists.show', [
                'artist' => $artist,
                'event' => $event,
                'eventDate' => $eventDate,
                'eventId' => $eventId
            ]);
        } catch (\Exception $e) {
            // Логируем ошибку
            Log::error('Ошибка при загрузке страницы артиста', [
                'artist_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Перенаправляем на главную страницу с сообщением об ошибке
            return redirect()->route('home')->with('error', 'Не удалось загрузить страницу артиста. Пожалуйста, попробуйте позже.');
        }
    }
}