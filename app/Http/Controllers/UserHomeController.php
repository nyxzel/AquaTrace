<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event; // <-- IMPORTANT: use Event model

class UserHomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $display_name = $user->username ?? ($user->name ?? 'User');

        // Fetch 2 recent events (actual news)
        $news_items = Event::orderBy('event_date', 'desc')
            ->take(2)
            ->get();

        return view('user_home', [
            'user' => $user,
            'display_name' => $display_name,
            'news_items' => $news_items,
        ]);
    }
}
