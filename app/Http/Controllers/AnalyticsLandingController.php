<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsLandingController extends Controller
{
    public function index()
    {
        try {
            // Get total vessels
            $totalVessels = DB::table('vessels')
                ->where('is_archived', 0)
                ->count();

            // Get active vessels (positions in last 24 hours)
            $activeVessels = DB::table('vessels')
                ->join('positions', 'vessels.vessel_id', '=', 'positions.vessel_id')
                ->where('vessels.is_archived', 0)
                ->where('positions.recorded_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 24 HOUR)'))
                ->distinct()
                ->count('vessels.vessel_id');

            // Get total ports
            $totalPorts = DB::table('ports')
                ->whereNull('deleted_at')
                ->count();

            // Get total users
            $totalUsers = DB::table('users')
                ->where('is_archived', 0)
                ->where('role', 'user')
                ->count();

            // **NEW: Get vessel status counts based on latest position speed**
            $vesselStatusCounts = DB::table('vessels as v')
                ->leftJoin(DB::raw('(
                    SELECT vessel_id, MAX(position_id) as latest_position_id 
                    FROM positions 
                    GROUP BY vessel_id
                ) latest'), 'v.vessel_id', '=', 'latest.vessel_id')
                ->leftJoin('positions as p', 'latest.latest_position_id', '=', 'p.position_id')
                ->where('v.is_archived', 0)
                ->selectRaw('
                    SUM(CASE WHEN p.speed > 15 THEN 1 ELSE 0 END) as active_count,
                    SUM(CASE WHEN p.speed > 0 AND p.speed <= 15 THEN 1 ELSE 0 END) as in_transit_count,
                    SUM(CASE WHEN p.speed = 0 OR p.speed IS NULL THEN 1 ELSE 0 END) as docked_count
                ')
                ->first();

            // Extract counts
            $activeCount = $vesselStatusCounts->active_count ?? 0;
            $inTransitCount = $vesselStatusCounts->in_transit_count ?? 0;
            $dockedCount = $vesselStatusCounts->docked_count ?? 0;

            // Get vessel categories
            $vesselCategories = DB::table('vessels')
                ->select('type', DB::raw('COUNT(*) as count'))
                ->where('is_archived', 0)
                ->groupBy('type')
                ->get();

            // Calculate percentages
            $totalForPercentage = $vesselCategories->sum('count');
            $categories = $vesselCategories->map(function ($item) use ($totalForPercentage) {
                return [
                    'type' => $item->type,
                    'count' => $item->count,
                    'percentage' => $totalForPercentage > 0 ? round(($item->count / $totalForPercentage) * 100) : 0
                ];
            });

            // Get ports with positions
            $ports = DB::table('ports')
                ->join('address', 'ports.address_id', '=', 'address.address_id')
                ->join('positions', 'ports.position_id', '=', 'positions.position_id')
                ->select(
                    'ports.port_id',
                    'ports.name',
                    'address.city',
                    'address.state',
                    'positions.latitude',
                    'positions.longitude'
                )
                ->whereNull('ports.deleted_at')
                ->get();

            // Count vessels near each port
            $portsWithVesselCount = $ports->map(function ($port) {
                $latThreshold = 0.005;
                $lngThreshold = 0.005;

                $vesselCount = DB::table('vessels')
                    ->join('positions', function ($join) {
                        $join->on('vessels.vessel_id', '=', 'positions.vessel_id')
                            ->whereIn('positions.position_id', function ($query) {
                                $query->select(DB::raw('MAX(position_id)'))
                                    ->from('positions')
                                    ->groupBy('vessel_id');
                            });
                    })
                    ->where('vessels.is_archived', 0)
                    ->whereBetween('positions.latitude', [
                        $port->latitude - $latThreshold,
                        $port->latitude + $latThreshold
                    ])
                    ->whereBetween('positions.longitude', [
                        $port->longitude - $lngThreshold,
                        $port->longitude + $lngThreshold
                    ])
                    ->count();

                return [
                    'port_id' => $port->port_id,
                    'name' => $port->name,
                    'city' => $port->city,
                    'state' => $port->state,
                    'latitude' => $port->latitude,
                    'longitude' => $port->longitude,
                    'vessel_count' => $vesselCount
                ];
            });

            // Get ALL vessels with latest position
            $vessels = DB::table('vessels')
                ->join('positions', function ($join) {
                    $join->on('vessels.vessel_id', '=', 'positions.vessel_id')
                        ->whereIn('positions.position_id', function ($query) {
                            $query->select(DB::raw('MAX(position_id)'))
                                ->from('positions')
                                ->groupBy('vessel_id');
                        });
                })
                ->select(
                    'vessels.vessel_id',
                    'vessels.name',
                    'vessels.type',
                    'positions.latitude',
                    'positions.longitude',
                    'positions.speed'
                )
                ->where('vessels.is_archived', 0)
                ->whereNotNull('positions.latitude')
                ->whereNotNull('positions.longitude')
                ->get();

            return view('analyticsLanding', compact(
                'totalVessels',
                'activeVessels',
                'totalPorts',
                'totalUsers',
                'activeCount',        // NEW
                'inTransitCount',     // NEW
                'dockedCount',        // NEW
                'categories',
                'portsWithVesselCount',
                'vessels'
            ));
        } catch (\Exception $e) {
            \Log::error('Analytics error: ' . $e->getMessage());

            return view('analyticsLanding', [
                'totalVessels' => 0,
                'activeVessels' => 0,
                'totalPorts' => 0,
                'totalUsers' => 0,
                'activeCount' => 0,
                'inTransitCount' => 0,
                'dockedCount' => 0,
                'categories' => collect([]),
                'portsWithVesselCount' => collect([]),
                'vessels' => collect([])
            ]);
        }
    }
}
