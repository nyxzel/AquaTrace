<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Vessel;
use App\Models\Port;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserReportController extends Controller
{
    public function index()
    {
        try {
            // Get the logged-in user's owner record
            $owner = \App\Models\Owner::where('user_id', Auth::id())->first();

            // Get reports
            $reports = Report::with(['vessel', 'port'])
                ->where('created_by', Auth::id())
                ->where('is_archived', 0)
                ->orderBy('date_created', 'desc')
                ->get();

            // Get vessels - FIXED: Use owner_id from the owner table
            if ($owner) {
                $vessels = Vessel::where('owner_id', $owner->owner_id)
                    ->where('is_archived', 0)
                    ->orderBy('name')
                    ->get();
            } else {
                $vessels = collect();
            }

            $ports = Port::whereNull('deleted_at')
                ->orderBy('name')
                ->get();

            return view('user_reports', compact('reports', 'vessels', 'ports'));
        } catch (\Exception $e) {
            \Log::error('Error loading reports: ' . $e->getMessage());
            return back()->with('error', 'Failed to load reports. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'incident_date' => 'required|date|before_or_equal:today',
                'incident_time' => 'required|date_format:H:i',
                'vessel_id' => 'required|exists:vessels,vessel_id',
                'port_id' => 'required|exists:ports,port_id',
                'incident_type' => 'required|string|max:100',
                'severity' => 'required|in:LOW,MEDIUM,HIGH',
                'description' => 'required|string|max:2000',
                'additional_notes' => 'nullable|string|max:1000',
            ]);

            // Get the owner first
            $owner = \App\Models\Owner::where('user_id', Auth::id())->first();
            if (!$owner) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must have an owner record to report incidents.'
                ], 403);
            }

            // Vessel must belong to this owner
            $vessel = Vessel::where('vessel_id', $validated['vessel_id'])
                ->where('owner_id', $owner->owner_id)
                ->first();

            if (!$vessel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid vessel selection. You can only report incidents for your own vessels.'
                ], 403);
            }

            // Check port (assuming deleted_at column exists, no is_archived)
            $port = Port::where('port_id', $validated['port_id'])
                ->whereNull('deleted_at')
                ->first();

            if (!$port) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid port selection.'
                ], 400);
            }

            $report = Report::create([
                'report_type' => $validated['incident_type'],
                'severity' => $validated['severity'],
                'description' => $validated['description'],
                'additional_notes' => $validated['additional_notes'] ?? null,
                'incident_date' => $validated['incident_date'],
                'incident_time' => $validated['incident_time'],
                'related_vessel' => $validated['vessel_id'],
                'port_id' => $validated['port_id'],
                'status' => 'Pending',
                'created_by' => Auth::id(),
                'date_created' => now(),
                'updated_on' => now(),
                'is_archived' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Report submitted successfully!',
                'report' => $report->load(['vessel', 'port'])
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit report. Please try again.'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $report = Report::with(['vessel', 'port'])
                ->where('report_id', $id)
                ->where('created_by', Auth::id())
                ->firstOrFail();

            return view('user_reports.show', compact('report'));
        } catch (\Exception $e) {
            return redirect()->route('user.reports')
                ->with('error', 'Report not found.');
        }
    }

    public function destroy($id)
    {
        try {
            $report = Report::where('report_id', $id)
                ->where('created_by', Auth::id())
                ->where('status', 'Pending')
                ->firstOrFail();

            $report->update([
                'is_archived' => 1,
                'updated_on' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Report deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete report. Only pending reports can be deleted.'
            ], 400);
        }
    }
}
