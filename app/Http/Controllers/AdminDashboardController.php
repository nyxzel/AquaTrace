<?php

namespace App\Http\Controllers;

use App\Models\Vessel;
use App\Models\Port;
use App\Models\User;
use App\Models\Report;
use App\Models\Position;
use App\Models\Voyage;
use App\Models\NotifVesselRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

class AdminDashboardController extends BaseController
{
    /**
     * DASHBOARD - Show overview with map, and vessels
     */
    public function dashboard()
    {
        // Additional security check
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        try {
            // Fetch counts
            $vesselCount = Vessel::where('is_archived', false)->count();
            $portCount = Port::whereNull('deleted_at')->count();
            $userCount = User::count();
            $reportCount = Report::where('is_archived', false)->count();

            // Ports with coordinates for map display
            $ports = DB::table('ports')
                ->join('positions', 'ports.position_id', '=', 'positions.position_id')
                ->whereNull('ports.deleted_at')
                ->select(
                    'ports.port_id',
                    'ports.name',
                    'positions.latitude',
                    'positions.longitude'
                )
                ->get();

            // Vessels with latest position - Get all vessel data properly
            $vessels = Vessel::select(
                'vessels.vessel_id',
                'vessels.name',
                'vessels.type',
                'vessels.flag',
                'vessels.imo',
                'vessels.mmsi',
                'vessels.call_sign',
                'vessels.LoA',
                'vessels.gross_tonnage',
                'vessels.year_built',
                'vessels.created_at',
                'positions.latitude',
                'positions.longitude',
                'positions.speed',
                'positions.recorded_at'
            )
                ->leftJoin('positions', function ($join) {
                    $join->on('vessels.vessel_id', '=', 'positions.vessel_id')
                        ->whereIn('positions.position_id', function ($query) {
                            $query->select(DB::raw('MAX(position_id)'))
                                ->from('positions')
                                ->groupBy('vessel_id');
                        });
                })
                ->where('vessels.is_archived', false)
                ->whereNotNull('positions.latitude')
                ->whereNotNull('positions.longitude')
                ->orderBy('vessels.created_at', 'desc')
                ->get();

            // Get pending registration requests with relationships
            $pendingOwners = NotifVesselRequest::where('status', 'pending')
                ->with(['user', 'owner'])
                ->orderBy('submitted_at', 'desc')
                ->get();

            return view('admin_dashboard', compact(
                'vesselCount',
                'portCount',
                'userCount',
                'reportCount',
                'vessels',
                'ports',
                'pendingOwners'
            ));

            // return view('admin_dashboard_vue', compact(
            //     'vesselCount', 'portCount', 'userCount', 'reportCount',
            //     'vessels', 'ports', 'pendingOwners'
            // ));

        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading dashboard');
        }
    }

    /**
     * GET VESSEL POSITIONS (API)
     */
    public function getVesselPositions()
    {
        try {
            $vessels = Vessel::where('is_archived', false)
                ->with('latestPosition')
                ->get();

            return response()->json([
                'success' => true,
                'vessels' => $vessels
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching positions'
            ], 500);
        }
    }

    /**
     * APPROVE REGISTRATION + SAVE POSITION + CREATE VOYAGE
     * This is the main approval endpoint - admin accepts user's vessel request
     */
    public function approveAndSavePosition(Request $request)
    {
        $validated = $request->validate([
            'request_id'      => 'required|integer|exists:notif_vessel_request,id',
            'owner_id'        => 'required|integer|exists:owners,owner_id',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'speed'           => 'nullable|numeric|min:0',
            'name'            => 'required|string|max:255',
            'type'            => 'required|string|max:100',
            'flag'            => 'required|string|max:255',
            'imo'             => 'required|string|max:50',
            'mmsi'            => 'required|string|max:50',
            'call_sign'       => 'required|string|max:50',
            'LoA'             => 'required|numeric|min:0',
            'gross_tonnage'   => 'required|numeric|min:0',
            'year_built'      => 'required|integer|min:1900|max:2100'
        ]);

        try {
            DB::beginTransaction();

            $regRequest = NotifVesselRequest::findOrFail($validated['request_id']);

            // Verify the request is still pending
            if ($regRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed'
                ], 400);
            }

            // Create vessel from registration data with admin modifications
            $vessel = Vessel::create([
                'name'            => $validated['name'],
                'type'            => $validated['type'],
                'flag'            => $validated['flag'],
                'imo'             => $validated['imo'],
                'mmsi'            => $validated['mmsi'],
                'call_sign'       => $validated['call_sign'],
                'LoA'             => $validated['LoA'],
                'gross_tonnage'   => $validated['gross_tonnage'],
                'year_built'      => $validated['year_built'],
                'owner_id'        => $validated['owner_id'],
                'admin_id'        => Auth::id(),
                'is_archived'     => false
            ]);

            // Find the nearest port to the vessel's position
            $nearestPort = $this->findNearestPort($validated['latitude'], $validated['longitude']);

            // Create voyage with 'docked' status
            $voyage = Voyage::create([
                'vessel_id'      => $vessel->vessel_id,
                'departure_port' => null,  // No departure port yet since it's being placed
                'arrival_port'   => $nearestPort ? $nearestPort->port_id : null,
                'departure_date' => null,
                'arrival_date'   => now()->toDateString(),
                'status'         => 'docked'
            ]);

            // Create initial position linked to the voyage
            Position::create([
                'vessel_id'   => $vessel->vessel_id,
                'voyage_id'   => $voyage->voyage_id,
                'latitude'    => $validated['latitude'],
                'longitude'   => $validated['longitude'],
                'speed'       => $validated['speed'] ?? 0,
                'recorded_at' => now()
            ]);

            // Update registration status to approved
            $regRequest->status = 'approved';
            $regRequest->approved_by = Auth::id();
            $regRequest->approved_at = now();
            $regRequest->save();

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Vessel approved and registered successfully with docked status!',
                'vessel_id' => $vessel->vessel_id,
                'voyage_id' => $voyage->voyage_id,
                'vessel'    => $vessel->fresh(['latestPosition'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Approve vessel error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error approving vessel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FIND NEAREST PORT TO GIVEN COORDINATES (ENHANCED WITH CACHING)
     * Helper function to determine which port the vessel is near
     */
    private function findNearestPort($latitude, $longitude, $maxDistance = 50)
    {
        // Cache key for port data (refreshes every 30 minutes)
        $cacheKey = 'ports_with_positions';

        // Get all ports with their positions (cached)
        $ports = cache()->remember($cacheKey, 1800, function () {
            return Port::join('positions', 'ports.position_id', '=', 'positions.position_id')
                ->whereNull('ports.deleted_at')
                ->select(
                    'ports.port_id',
                    'ports.name',
                    'positions.latitude',
                    'positions.longitude'
                )
                ->get();
        });

        if ($ports->isEmpty()) {
            return null;
        }

        $nearestPort = null;
        $minDistance = PHP_FLOAT_MAX;

        // Calculate distance to each port using Haversine formula
        foreach ($ports as $port) {
            $distance = $this->calculateDistance(
                $latitude,
                $longitude,
                $port->latitude,
                $port->longitude
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearestPort = $port;
            }
        }

        // Only return port if within max distance (default 50km)
        if ($minDistance <= $maxDistance) {
            $nearestPort->distance = round($minDistance, 2);
            return $nearestPort;
        }

        return null;
    }

    /**
     * CALCULATE DISTANCE BETWEEN TWO COORDINATES (Haversine formula)
     * Returns distance in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * REJECT VESSEL REGISTRATION
     */
    public function rejectVesselRegistration(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|integer|exists:notif_vessel_request,id',
            'reason'     => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $regRequest = NotifVesselRequest::findOrFail($validated['request_id']);

            // Verify the request is still pending
            if ($regRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed'
                ], 400);
            }

            // Update registration status to rejected
            $regRequest->reject(Auth::id(), $validated['reason'] ?? null);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vessel registration rejected'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reject vessel error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting registration'
            ], 500);
        }
    }

    /**
     * UPDATE VESSEL POSITION WITH INTELLIGENT STATUS MANAGEMENT
     */
    public function updateVesselPosition(Request $request)
    {
        $validated = $request->validate([
            'vessel_id'  => 'required|integer|exists:vessels,vessel_id',
            'latitude'   => 'required|numeric|between:-90,90',
            'longitude'  => 'required|numeric|between:-180,180',
            'speed'      => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $vessel = Vessel::findOrFail($validated['vessel_id']);

            // Get the current active voyage for this vessel
            $activeVoyage = Voyage::where('vessel_id', $validated['vessel_id'])
                ->whereIn('status', ['active', 'in_transmit', 'docked'])
                ->latest()
                ->first();

            // Determine new status based on position and speed
            $newStatus = $this->determineVoyageStatus(
                $validated['latitude'],
                $validated['longitude'],
                $validated['speed'] ?? 0,
                $activeVoyage
            );

            // Update voyage status if changed
            if ($activeVoyage && $activeVoyage->status !== $newStatus['status']) {
                $activeVoyage->status = $newStatus['status'];

                // Update port information if vessel has docked
                if ($newStatus['status'] === 'docked' && $newStatus['port_id']) {
                    $activeVoyage->arrival_port = $newStatus['port_id'];
                    $activeVoyage->arrival_date = now()->toDateString();
                }

                $activeVoyage->save();
            }

            // Create new position linked to the voyage
            Position::create([
                'vessel_id'   => $validated['vessel_id'],
                'voyage_id'   => $activeVoyage ? $activeVoyage->voyage_id : null,
                'latitude'    => $validated['latitude'],
                'longitude'   => $validated['longitude'],
                'speed'       => $validated['speed'] ?? 0,
                'recorded_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Position updated successfully',
                'status' => $newStatus['status'],
                'near_port' => $newStatus['port_name'] ?? null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update position error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating position'
            ], 500);
        }
    }

    /**
     * DETERMINE VOYAGE STATUS BASED ON POSITION AND SPEED
     */
    private function determineVoyageStatus($latitude, $longitude, $speed, $currentVoyage)
    {
        $nearestPort = $this->findNearestPort($latitude, $longitude);
        $distanceToPort = $nearestPort ? $this->calculateDistance(
            $latitude,
            $longitude,
            $nearestPort->latitude,
            $nearestPort->longitude
        ) : null;

        // Vessel is near a port (within 2km) and moving slowly (< 2 knots) = DOCKED
        if ($distanceToPort && $distanceToPort <= 2 && $speed < 2) {
            return [
                'status' => 'docked',
                'port_id' => $nearestPort->port_id,
                'port_name' => $nearestPort->name
            ];
        }

        // Vessel is moving = IN_TRANSMIT
        if ($speed >= 2) {
            return [
                'status' => 'in_transmit',
                'port_id' => null,
                'port_name' => null
            ];
        }

        // Default to current status or docked if stopped far from port
        return [
            'status' => $currentVoyage ? $currentVoyage->status : 'docked',
            'port_id' => null,
            'port_name' => null
        ];
    }

    /**
     * DELETE VESSEL (Archive)
     */
    public function deleteVessel(Request $request)
    {
        $validated = $request->validate([
            'vessel_id' => 'required|integer|exists:vessels,vessel_id'
        ]);

        try {
            DB::beginTransaction();

            $vessel = Vessel::findOrFail($validated['vessel_id']);
            $vessel->is_archived = true;
            $vessel->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vessel deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete vessel error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting vessel'
            ], 500);
        }
    }

    /**
     * GET VESSEL DETAILS
     */
    public function getVesselDetails($vessel_id)
    {
        try {
            $vessel = Vessel::with('owner', 'latestPosition')
                ->findOrFail($vessel_id);

            return response()->json([
                'success' => true,
                'vessel'  => $vessel
            ]);
        } catch (\Exception $e) {
            \Log::error('Get vessel details error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Vessel not found'
            ], 404);
        }
    }

    /**
     * START NEW VOYAGE
     * Allows admin to manually start a new voyage for a vessel
     */
    public function startVoyage(Request $request)
    {
        $validated = $request->validate([
            'vessel_id' => 'required|integer|exists:vessels,vessel_id',
            'departure_port' => 'required|integer|exists:ports,port_id',
            'arrival_port' => 'nullable|integer|exists:ports,port_id'
        ]);

        try {
            DB::beginTransaction();

            // Check if vessel has an active voyage
            $activeVoyage = Voyage::where('vessel_id', $validated['vessel_id'])
                ->whereIn('status', ['active', 'in_transmit'])
                ->first();

            if ($activeVoyage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vessel already has an active voyage'
                ], 400);
            }

            // Create new voyage
            $voyage = Voyage::create([
                'vessel_id' => $validated['vessel_id'],
                'departure_port' => $validated['departure_port'],
                'arrival_port' => $validated['arrival_port'] ?? null,
                'departure_date' => now()->toDateString(),
                'arrival_date' => null,
                'status' => 'active'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Voyage started successfully',
                'voyage' => $voyage
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Start voyage error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error starting voyage'
            ], 500);
        }
    }

    /**
     * END VOYAGE
     * Marks a voyage as completed when vessel reaches destination
     */
    public function endVoyage(Request $request)
    {
        $validated = $request->validate([
            'voyage_id' => 'required|integer|exists:voyage,voyage_id',
            'arrival_port' => 'required|integer|exists:ports,port_id'
        ]);

        try {
            DB::beginTransaction();

            $voyage = Voyage::findOrFail($validated['voyage_id']);

            if (!in_array($voyage->status, ['active', 'in_transmit'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This voyage is not active'
                ], 400);
            }

            $voyage->update([
                'status' => 'docked',
                'arrival_port' => $validated['arrival_port'],
                'arrival_date' => now()->toDateString()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Voyage ended successfully',
                'voyage' => $voyage->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('End voyage error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error ending voyage'
            ], 500);
        }
    }

    /**
     * GET VESSEL VOYAGE HISTORY
     */
    public function getVesselVoyageHistory($vessel_id)
    {
        try {
            $voyages = Voyage::where('vessel_id', $vessel_id)
                ->with(['departurePort', 'arrivalPort'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'voyages' => $voyages
            ]);
        } catch (\Exception $e) {
            \Log::error('Get voyage history error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching voyage history'
            ], 500);
        }
    }

    /**
     * GET VESSEL POSITION HISTORY
     */
    public function getVesselPositionHistory(Request $request, $vessel_id)
    {
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'limit' => 'nullable|integer|min:1|max:1000'
        ]);

        try {
            $query = Position::where('vessel_id', $vessel_id)
                ->orderBy('recorded_at', 'desc');

            if (isset($validated['from_date'])) {
                $query->where('recorded_at', '>=', $validated['from_date']);
            }

            if (isset($validated['to_date'])) {
                $query->where('recorded_at', '<=', $validated['to_date']);
            }

            $positions = $query->limit($validated['limit'] ?? 100)->get();

            return response()->json([
                'success' => true,
                'positions' => $positions,
                'count' => $positions->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Get position history error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching position history'
            ], 500);
        }
    }

    /**
     * GET DASHBOARD STATISTICS
     * Enhanced statistics for admin dashboard
     */
    public function getDashboardStats()
    {
        try {
            $stats = [
                'vessels' => [
                    'total' => Vessel::where('is_archived', false)->count(),
                    'docked' => Vessel::whereHas('currentVoyage', function ($q) {
                        $q->where('status', 'docked');
                    })->count(),
                    'in_transit' => Vessel::whereHas('currentVoyage', function ($q) {
                        $q->where('status', 'in_transmit');
                    })->count(),
                    'active' => Vessel::whereHas('currentVoyage', function ($q) {
                        $q->where('status', 'active');
                    })->count()
                ],
                'ports' => [
                    'total' => Port::whereNull('deleted_at')->count(),
                    'active_vessels' => DB::table('voyage')
                        ->where('status', 'docked')
                        ->whereNotNull('arrival_port')
                        ->distinct('vessel_id')
                        ->count()
                ],
                'users' => [
                    'total' => User::where('is_archived', false)->count(),
                    'owners' => User::where('role', 'user')
                        ->where('is_archived', false)
                        ->count(),
                    'admins' => User::where('role', 'admin')
                        ->where('is_archived', false)
                        ->count()
                ],
                'reports' => [
                    'total' => Report::where('is_archived', false)->count(),
                    'pending' => Report::where('status', 'Pending')
                        ->where('is_archived', false)
                        ->count(),
                    'under_investigation' => Report::where('status', 'Under Investigation')
                        ->where('is_archived', false)
                        ->count(),
                    'resolved' => Report::where('status', 'Resolved')
                        ->where('is_archived', false)
                        ->count()
                ],
                'registrations' => [
                    'pending' => NotifVesselRequest::where('status', 'pending')->count(),
                    'approved_today' => NotifVesselRequest::where('status', 'approved')
                        ->whereDate('approved_at', today())
                        ->count(),
                    'rejected_today' => NotifVesselRequest::where('status', 'rejected')
                        ->whereDate('rejected_at', today())
                        ->count()
                ]
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            \Log::error('Get dashboard stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics'
            ], 500);
        }
    }

    /**
     * BATCH OPERATIONS - Update multiple vessel positions
     */
    public function batchUpdatePositions(Request $request)
    {
        $validated = $request->validate([
            'updates' => 'required|array',
            'updates.*.vessel_id' => 'required|integer|exists:vessels,vessel_id',
            'updates.*.latitude' => 'required|numeric|between:-90,90',
            'updates.*.longitude' => 'required|numeric|between:-180,180',
            'updates.*.speed' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $results = [];
            foreach ($validated['updates'] as $update) {
                try {
                    // Update each vessel position
                    $vessel = Vessel::findOrFail($update['vessel_id']);
                    $activeVoyage = $vessel->currentVoyage;

                    Position::create([
                        'vessel_id' => $update['vessel_id'],
                        'voyage_id' => $activeVoyage ? $activeVoyage->voyage_id : null,
                        'latitude' => $update['latitude'],
                        'longitude' => $update['longitude'],
                        'speed' => $update['speed'] ?? 0,
                        'recorded_at' => now()
                    ]);

                    $results[] = [
                        'vessel_id' => $update['vessel_id'],
                        'success' => true
                    ];
                } catch (\Exception $e) {
                    $results[] = [
                        'vessel_id' => $update['vessel_id'],
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Batch update completed',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Batch update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error in batch update'
            ], 500);
        }
    }

    /**
     * CLEAR OLD POSITION DATA
     * Cleanup positions older than specified days (for performance)
     */
    public function cleanupOldPositions(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:30|max:365'
        ]);

        try {
            $cutoffDate = now()->subDays($validated['days']);

            $deletedCount = Position::where('recorded_at', '<', $cutoffDate)
                ->whereNotIn('position_id', function ($query) {
                    // Keep the latest position for each vessel
                    $query->select(DB::raw('MAX(position_id)'))
                        ->from('positions')
                        ->groupBy('vessel_id');
                })
                ->delete();

            return response()->json([
                'success' => true,
                'message' => "Deleted {$deletedCount} old position records",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Cleanup error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cleaning up old positions'
            ], 500);
        }
    }
}
