<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsLandingController extends Controller  // Changed from NewsController
{
    public function index()
    {
        try {
            $events = DB::table('events')
                ->orderBy('event_date', 'DESC')
                ->get();

            return view('newsLanding', compact('events'));
        } catch (\Exception $e) {
            return view('newsLanding', ['events' => []]);
        }
    }
}