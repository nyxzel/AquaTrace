<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminEventController extends Controller
{
    /**
     * Display the admin news page
     */
    public function index()
    {
        return view('admin_news');
    }

    /**
     * Fetch all news/events
     */
    public function fetch()
    {
        try {
            $events = DB::table('events')
                ->orderBy('event_date', 'desc')
                ->orderBy('event_id', 'desc')
                ->get();

            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch events: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new news/event
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'event_type' => 'required|string|max:100',
            'event_date' => 'required|date',
            'description' => 'required|string',
            'status' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            // Insert the event
            $eventId = DB::table('events')->insertGetId([
                'event_type' => $request->event_type,
                'description' => $request->description,
                'event_date' => $request->event_date,
                'status' => $request->status,
                'logged_by' => 1, // Use authenticated user or default to 1
            ]);

            // Fetch the newly created event
            $event = DB::table('events')
                ->where('event_id', $eventId)
                ->first();

            return response()->json($event, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an event
     */
    public function destroy($id)
    {
        try {
            $deleted = DB::table('events')
                ->where('event_id', $id)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'message' => 'Deleted successfully'
                ]);
            }

            return response()->json([
                'error' => 'Event not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete event: ' . $e->getMessage()
            ], 500);
        }
    }
}
