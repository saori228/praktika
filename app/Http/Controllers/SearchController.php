<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Artist;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $events = [];
        $sliderEvents = [];
        
        if ($query) {
            $events = Event::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->with(['eventType', 'artist'])
                ->get();
        } else {
            $events = Event::with(['eventType', 'artist'])->take(10)->get();
        }
        
        // Получаем события для слайдера
        $sliderEvents = Event::with(['eventType', 'artist'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        return view('search', [
            'events' => $events,
            'sliderEvents' => $sliderEvents,
            'query' => $query
        ]);
    }
}