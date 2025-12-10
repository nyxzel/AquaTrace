<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class UserNewsController extends Controller
{
    public function index()
    {
        // Fetch news ordered by date descending
        $news_items = Event::orderBy('event_date', 'DESC')->get();

        return view('user_news', [
            'news_items' => $news_items
        ]);
    }
}
