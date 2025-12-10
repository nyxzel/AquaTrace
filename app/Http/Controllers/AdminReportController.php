<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Report;
use App\Models\Vessel;
use App\Models\Owner;
use App\Models\Port;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    // Show all reports
    public function index()
    {
        $reports = Report::with(['vessel.owner.user', 'port.address'])
            ->where('is_archived', 0)
            ->orderBy('incident_date', 'desc')
            ->get();

        // Monthly counts - Fixed to return proper array
        $monthly_raw = Report::selectRaw('MONTH(incident_date) as month, COUNT(*) as total')
            ->whereYear('incident_date', now()->year)
            ->where('is_archived', 0)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill all 12 months with 0 as default
        $monthly_data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthly_data[] = $monthly_raw[$i] ?? 0;
        }

        // Vessel type distribution
        $vessel_types = Vessel::select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->get();

        $vessel_types_mapped = [
            'Cargo Ships' => 0,
            'Tankers' => 0,
            'Fishing Vessels' => 0,
            'Passenger Ships' => 0,
            'Other' => 0,
        ];

        foreach ($vessel_types as $v) {
            $type = strtolower($v->type ?? '');
            $count = $v->total;

            if (str_contains($type, 'cargo')) {
                $vessel_types_mapped['Cargo Ships'] += $count;
            } elseif (str_contains($type, 'tanker')) {
                $vessel_types_mapped['Tankers'] += $count;
            } elseif (str_contains($type, 'fishing')) {
                $vessel_types_mapped['Fishing Vessels'] += $count;
            } elseif (str_contains($type, 'passenger')) {
                $vessel_types_mapped['Passenger Ships'] += $count;
            } else {
                $vessel_types_mapped['Other'] += $count;
            }
        }

        return view('admin_reports', compact('reports', 'monthly_data', 'vessel_types_mapped'));
    }

    // Archive report
    public function archive(Request $request)
    {
        try {
            $report = Report::findOrFail($request->report_id);
            $report->is_archived = 1;
            $report->updated_on = now();
            $report->save();

            return response()->json(['success' => true, 'message' => 'Report deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Update report
    public function update(Request $request)
    {
        try {
            $report = Report::findOrFail($request->report_id);

            // Find related records
            $vessel = Vessel::where('name', $request->vessel_name)->first();
            $port = Port::where('name', $request->port_name)->first();

            if (!$vessel) {
                return response()->json(['success' => false, 'message' => 'Vessel not found'], 404);
            }

            if (!$port) {
                return response()->json(['success' => false, 'message' => 'Port not found'], 404);
            }

            // CORRECT column name based on your DB
            $report->related_vessel = $vessel->vessel_id;
            $report->port_id = $port->port_id;
            $report->report_type = $request->incident_type;
            $report->description = $request->description;
            $report->severity = $request->severity;
            $report->status = $request->status;
            $report->updated_on = now();

            $report->save();

            return response()->json(['success' => true, 'message' => 'Report updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
