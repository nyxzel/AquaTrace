<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\Vessel;
use App\Models\Owner;
use App\Models\Port;
use App\Models\Position;
use App\Models\NotifVesselRequest;
use App\Models\Voyage;

class UserVesselController extends Controller
{
    /**
     * Display user's vessels page (HTML VIEW)
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $owner = Owner::where('user_id', $user->id)->first();

        $vessels = collect([]);
        $pendingRequests = collect([]);
        $rejectedRequests = collect([]);
        $statistics = [
            'total' => 0,
            'active' => 0,
            'docked' => 0,
            'transit' => 0
        ];

        if ($owner) {
            $vessels = Vessel::where('owner_id', $owner->owner_id)
                ->where('is_archived', 0)
                ->with([
                    'owner.address',
                    'latestPosition' => function ($query) {
                        $query->orderBy('position_id', 'desc')
                            ->orderBy('recorded_at', 'desc');
                    },
                    'activeVoyage.departurePort',
                    'activeVoyage.arrivalPort',
                    'latestVoyage' => function ($query) {
                        $query->orderBy('voyage_id', 'desc')
                            ->orderBy('updated_at', 'desc');
                    }
                ])
                ->get()
                ->each(function ($vessel) {
                    $vessel->load('latestPosition', 'latestVoyage');
                });

            $pendingRequests = NotifVesselRequest::where('owner_id', $owner->owner_id)
                ->where('status', 'pending')
                ->orderBy('submitted_at', 'desc')
                ->get();

            $rejectedRequests = NotifVesselRequest::where('owner_id', $owner->owner_id)
                ->where('status', 'rejected')
                ->orderBy('rejected_at', 'desc')
                ->limit(5)
                ->get();

            $total = $vessels->count();
            $active = 0;
            $docked = 0;
            $transit = 0;

            foreach ($vessels as $vessel) {
                if ($vessel->latestPosition) {
                    $speed = (float) $vessel->latestPosition->speed;
                    if ($speed > 15) {
                        $active++;
                    } elseif ($speed > 0) {
                        $transit++;
                    } else {
                        $docked++;
                    }
                } else {
                    $docked++;
                }
            }

            $statistics = [
                'total' => $total,
                'active' => $active,
                'docked' => $docked,
                'transit' => $transit
            ];
        }

        $ports = $this->getPorts();

        return view('user_vessel', compact('vessels', 'ports', 'statistics', 'pendingRequests', 'rejectedRequests'));
    }

    /**
     * Get all active ports
     */
    private function getPorts()
    {
        return Port::whereNull('deleted_at')
            ->with(['position', 'address'])
            ->get()
            ->filter(function ($port) {
                return $port->position &&
                    $port->position->latitude &&
                    $port->position->longitude;
            })
            ->map(function ($port) {
                return [
                    'id' => $port->port_id,
                    'name' => $port->name,
                    'latitude' => (float) $port->position->latitude,
                    'longitude' => (float) $port->position->longitude,
                    'full_location' => $port->name . ($port->address ? ' - ' . $port->address->city : '')
                ];
            })
            ->values();
    }

    /**
     * Add offset to coordinates to place vessel NEAR port (not on it)
     * Uses larger, more visible offsets
     */
    private function addVesselOffset($latitude, $longitude, $minMeters = 500, $maxMeters = 1500)
    {
        // Convert meters to approximate degrees
        // 1 degree latitude ≈ 111,000 meters

        $angle = mt_rand(0, 360) * (M_PI / 180);
        $distance = mt_rand($minMeters, $maxMeters); // Random distance within radius

        // Convert meters to degrees
        $latOffset = ($distance / 111000); // degrees latitude
        $lngOffset = ($distance / (111000 * cos($latitude * M_PI / 180))); // degrees longitude

        $newLat = $latitude + ($latOffset * cos($angle));
        $newLng = $longitude + ($lngOffset * sin($angle));

        return [
            'latitude' => round($newLat, 5),
            'longitude' => round($newLng, 5)
        ];
    }

    /**
     * Get unique scattered position near a port
     * Ensures vessels don't stack on each other and stay in water
     */
    private function getScatteredPortPosition($latitude, $longitude, $portId, $vesselId, $status = 'docked')
    {
        // Get all vessels currently at or near this port
        $existingVessels = Position::select('positions.*')
            ->join('voyage', 'positions.voyage_id', '=', 'voyage.voyage_id')
            ->where(function ($query) use ($portId) {
                $query->where('voyage.departure_port', $portId)
                    ->orWhere('voyage.arrival_port', $portId);
            })
            ->where('positions.vessel_id', '!=', $vesselId)
            ->orderBy('positions.position_id', 'desc')
            ->get()
            ->unique('vessel_id');

        // Use seed based on vessel ID for consistent positioning
        mt_srand($vesselId + ($portId * 1000));

        $attempts = 0;
        $maxAttempts = 100;
        $minDistance = 0.001; // Minimum distance between vessels (roughly 100m)

        // Adjust radius based on status and number of vessels
        $vesselCount = $existingVessels->count();
        $baseRadius = 50; // Base radius in meters - CHANGED to 50
        $maxRadius = 150 + ($vesselCount * 25); // Expand radius with more vessels - CHANGED to 150

        do {
            $attempts++;

            // Create circular distribution around port
            $angle = (($vesselId * 137.5) + ($attempts * 45)) % 360; // Golden angle distribution
            $radiusRatio = 0.5 + ($attempts * 0.05); // Expand outward with attempts
            $distance = $baseRadius + (($maxRadius - $baseRadius) * $radiusRatio);

            // Convert to degrees
            $latOffset = ($distance / 111000) * cos($angle * M_PI / 180);
            $lngOffset = ($distance / (111000 * cos($latitude * M_PI / 180))) * sin($angle * M_PI / 180);

            $coords = [
                'latitude' => round($latitude + $latOffset, 5),
                'longitude' => round($longitude + $lngOffset, 5)
            ];

            $tooClose = false;

            // Check distance from all existing vessels
            foreach ($existingVessels as $existing) {
                $distance = $this->calculateDistance(
                    $coords['latitude'],
                    $coords['longitude'],
                    $existing->latitude,
                    $existing->longitude
                );

                if ($distance < $minDistance) {
                    $tooClose = true;
                    break;
                }
            }

            if (!$tooClose || $attempts >= $maxAttempts) {
                mt_srand(); // Reset random seed
                return $coords;
            }
        } while ($attempts < $maxAttempts);

        // If we couldn't find a spot, place at maximum range
        mt_srand(); // Reset random seed
        return $this->addVesselOffset($latitude, $longitude, 150, 250); // CHANGED to 150-250
    }

    /**
     * Calculate distance between two coordinates (in degrees)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        return sqrt(pow($lat2 - $lat1, 2) + pow($lon2 - $lon1, 2));
    }

    /**
     * Update vessel location
     */
    public function updateLocation(Request $request, $vesselId)
    {
        try {
            $validated = $request->validate([
                'current_location' => 'required|string',
                'destination' => 'nullable|string',
                'status' => 'required|in:Active,Docked,In Transit',
                'arrival' => 'nullable|date',
                'departure' => 'nullable|date',
                'notes' => 'nullable|string|max:1000'
            ]);

            Log::info('Update Location Request', [
                'vessel_id' => $vesselId,
                'validated_data' => $validated
            ]);

            $user = Auth::user();
            $owner = Owner::where('user_id', $user->id)->first();

            if (!$owner) {
                return response()->json(['success' => false, 'error' => 'Owner not found'], 404);
            }

            $vessel = Vessel::where('vessel_id', $vesselId)
                ->where('owner_id', $owner->owner_id)
                ->where('is_archived', 0)
                ->first();

            if (!$vessel) {
                return response()->json(['success' => false, 'error' => 'Vessel not found'], 404);
            }

            // Parse coordinates
            $currentCoords = explode(',', $validated['current_location']);
            if (count($currentCoords) != 2) {
                return response()->json(['success' => false, 'error' => 'Invalid current location format'], 400);
            }

            $currentLatitude = (float) trim($currentCoords[0]);
            $currentLongitude = (float) trim($currentCoords[1]);

            $destLatitude = null;
            $destLongitude = null;
            if (!empty($validated['destination'])) {
                $destCoords = explode(',', $validated['destination']);
                if (count($destCoords) == 2) {
                    $destLatitude = (float) trim($destCoords[0]);
                    $destLongitude = (float) trim($destCoords[1]);
                }
            }

            // Find port IDs
            $departurePortId = $this->findPortByCoordinates($currentLatitude, $currentLongitude);
            $arrivalPortId = null;
            if ($destLatitude && $destLongitude) {
                $arrivalPortId = $this->findPortByCoordinates($destLatitude, $destLongitude);
            }

            // Determine status and position
            $voyageStatus = 'docked';
            $speed = 0.00;
            $positionLat = $currentLatitude;
            $positionLng = $currentLongitude;

            switch ($validated['status']) {
                case 'Active':
                    $voyageStatus = 'active';
                    $speed = 25.00;

                    if ($destLatitude && $destLongitude && $arrivalPortId) {
                        // Active vessel - position NEAR destination port (in water, circling area)
                        $coords = $this->getScatteredPortPosition(
                            $destLatitude,
                            $destLongitude,
                            $arrivalPortId,
                            $vessel->vessel_id,
                            'active'
                        );
                        $positionLat = $coords['latitude'];
                        $positionLng = $coords['longitude'];

                        Log::info("Active vessel positioned NEAR destination", [
                            'destination_port' => [$destLatitude, $destLongitude],
                            'vessel_position' => [$positionLat, $positionLng],
                            'offset_meters' => '~100-400m'
                        ]);
                    } else {
                        // No destination - stay near current location
                        $coords = $this->addVesselOffset($currentLatitude, $currentLongitude, 100, 300);
                        $positionLat = $coords['latitude'];
                        $positionLng = $coords['longitude'];
                    }
                    break;

                case 'Docked':
                    $voyageStatus = 'docked';
                    $speed = 0.00;

                    // Docked - position NEAR current port (moored/anchored nearby)
                    if ($departurePortId) {
                        $coords = $this->getScatteredPortPosition(
                            $currentLatitude,
                            $currentLongitude,
                            $departurePortId,
                            $vessel->vessel_id,
                            'docked'
                        );
                        $positionLat = $coords['latitude'];
                        $positionLng = $coords['longitude'];
                    } else {
                        // No port match - use offset
                        $coords = $this->addVesselOffset($currentLatitude, $currentLongitude, 100, 300);
                        $positionLat = $coords['latitude'];
                        $positionLng = $coords['longitude'];
                    }

                    Log::info("Docked vessel positioned NEAR port", [
                        'port' => [$currentLatitude, $currentLongitude],
                        'vessel_position' => [$positionLat, $positionLng]
                    ]);
                    break;

                case 'In Transit':
                    $voyageStatus = 'in_transmit';
                    $speed = 15.00;

                    if ($destLatitude && $destLongitude && $arrivalPortId) {
                        // In Transit - position NEAR destination port (waiting/approaching in water)
                        // Same behavior as Active vessels
                        $coords = $this->getScatteredPortPosition(
                            $destLatitude,
                            $destLongitude,
                            $arrivalPortId,
                            $vessel->vessel_id,
                            'in_transit'
                        );
                        $positionLat = $coords['latitude'];
                        $positionLng = $coords['longitude'];

                        Log::info("In Transit vessel positioned NEAR destination (approach area)", [
                            'destination_port' => [$destLatitude, $destLongitude],
                            'vessel_position' => [$positionLat, $positionLng],
                            'status' => 'approaching in water'
                        ]);
                    } else {
                        // No destination - drift near current location (in water)
                        if ($departurePortId) {
                            // Stay near departure port if we know it
                            $coords = $this->getScatteredPortPosition(
                                $currentLatitude,
                                $currentLongitude,
                                $departurePortId,
                                $vessel->vessel_id,
                                'departing'
                            );
                        } else {
                            // Random position near current location
                            $coords = $this->addVesselOffset($currentLatitude, $currentLongitude, 100, 300);
                        }
                        $positionLat = $coords['latitude'];
                        $positionLng = $coords['longitude'];
                    }
                    break;
            }

            DB::beginTransaction();

            try {
                // Complete any active voyages
                Voyage::where('vessel_id', $vessel->vessel_id)
                    ->whereIn('status', ['active', 'in_transmit'])
                    ->update(['status' => 'docked', 'updated_at' => now()]);

                // Create new voyage
                $voyage = Voyage::create([
                    'vessel_id' => $vessel->vessel_id,
                    'departure_port' => $departurePortId,
                    'arrival_port' => $arrivalPortId,
                    'departure_date' => !empty($validated['departure']) ? Carbon::parse($validated['departure']) : now(),
                    'arrival_date' => !empty($validated['arrival']) ? Carbon::parse($validated['arrival']) : null,
                    'status' => $voyageStatus
                ]);

                // Create position record
                $position = Position::create([
                    'vessel_id' => $vessel->vessel_id,
                    'voyage_id' => $voyage->voyage_id,
                    'latitude' => $positionLat,
                    'longitude' => $positionLng,
                    'speed' => $speed,
                    'recorded_at' => now()
                ]);

                // Update vessel notes
                if (!empty($validated['notes'])) {
                    $vessel->update(['additional_notes' => $validated['notes']]);
                }

                DB::commit();

                Log::info("Vessel location updated successfully", [
                    'vessel_id' => $vessel->vessel_id,
                    'voyage_id' => $voyage->voyage_id,
                    'position_id' => $position->position_id,
                    'status' => $voyageStatus,
                    'coordinates' => [$positionLat, $positionLng]
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Vessel location updated successfully',
                    'data' => [
                        'voyage_id' => $voyage->voyage_id,
                        'position_id' => $position->position_id,
                        'position' => [
                            'latitude' => (float) $positionLat,
                            'longitude' => (float) $positionLng,
                            'speed' => (float) $speed,
                            'status' => $validated['status']
                        ],
                        'voyage' => [
                            'departure_port' => $voyage->departurePort->name ?? 'Unknown',
                            'arrival_port' => $voyage->arrivalPort->name ?? 'Unknown',
                            'status' => $voyageStatus
                        ]
                    ]
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update Location Error: ' . $e->getMessage(), [
                'vessel_id' => $vesselId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to update vessel location',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find port by coordinates
     */
    private function findPortByCoordinates($latitude, $longitude, $tolerance = 0.01)
    {
        $port = Port::whereHas('position', function ($query) use ($latitude, $longitude, $tolerance) {
            $query->whereBetween('latitude', [$latitude - $tolerance, $latitude + $tolerance])
                ->whereBetween('longitude', [$longitude - $tolerance, $longitude + $tolerance]);
        })->first();

        return $port ? $port->port_id : null;
    }

    /**
     * Get map data
     */
    public function getMapData()
    {
        try {
            $user = Auth::user();
            $owner = Owner::where('user_id', $user->id)->first();

            if (!$owner) {
                return response()->json(['vessels' => [], 'ports' => []]);
            }

            $vessels = Vessel::where('owner_id', $owner->owner_id)
                ->where('is_archived', 0)
                ->with('latestPosition')
                ->get()
                ->map(function ($vessel) {
                    $position = $vessel->latestPosition;

                    if (!$position) {
                        return null;
                    }

                    $speed = (float) $position->speed;
                    $status = 'Docked';

                    if ($speed > 15) {
                        $status = 'Active';
                    } elseif ($speed > 0) {
                        $status = 'In Transit';
                    }

                    return [
                        'id' => $vessel->vessel_id,
                        'name' => $vessel->name,
                        'type' => $vessel->type,
                        'status' => $status,
                        'latitude' => (float) $position->latitude,
                        'longitude' => (float) $position->longitude,
                        'speed' => $speed,
                        'recorded_at' => $position->recorded_at->format('Y-m-d H:i:s')
                    ];
                })
                ->filter()
                ->values();

            $ports = $this->getPorts();

            return response()->json([
                'success' => true,
                'vessels' => $vessels,
                'ports' => $ports
            ]);
        } catch (\Exception $e) {
            Log::error('Get Map Data Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'vessels' => [],
                'ports' => []
            ]);
        }
    }
}
