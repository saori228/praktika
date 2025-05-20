<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Artist;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::with(['eventType', 'artist'])->take(4)->get();
        $artists = Artist::take(4)->get();
        
        return view('home', [
            'events' => $events,
            'artists' => $artists
        ]);
    }
}