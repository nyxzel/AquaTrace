<?php

namespace App\Http\Controllers;

use App\Models\NotifVesselRequest;
use App\Models\Owner;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserVesselRegisterController extends Controller
{
    /**
     * Show vessel registration form
     */
    public function showRegistrationForm()
    {
        return view('user_vessel_register');
    }

    /**
     * SUBMIT NEW VESSEL REGISTRATION
     * User submits vessel specs (NO position data yet)
     */
    public function submitRegistration(Request $request)
    {
        $validated = $request->validate([
            'vessel_name'       => 'required|string|max:255',
            'imo_number'        => 'required|string|max:50|unique:notif_vessel_request,imo_number',
            'mmsi_number'       => 'required|string|max:50|unique:notif_vessel_request,mmsi_number',
            'call_sign'         => 'required|string|max:50',
            'vessel_type'       => 'required|string|max:100',
            'flag_state'        => 'required|string|max:100',
            'length_overall'    => 'required|numeric|min:0',
            'gross_tonnage'     => 'required|numeric|min:0',
            'year_built'        => 'required|integer|min:1900|max:2100',
            'additional_notes'  => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Get or create owner record
            $owner = Owner::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $user->first_name ?? 'Unknown',
                    'last_name'  => $user->last_name ?? 'User'
                ]
            );

            // Check for duplicate pending requests
            $existingRequest = NotifVesselRequest::where('owner_id', $owner->owner_id)
                ->where('status', 'pending')
                ->where(function ($query) use ($validated) {
                    $query->where('imo_number', $validated['imo_number'])
                        ->orWhere('mmsi_number', $validated['mmsi_number']);
                })
                ->first();

            if ($existingRequest) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending registration with this IMO or MMSI number'
                ], 400);
            }

            // Check if vessel already exists
            $existingVessel = Vessel::where(function ($query) use ($validated) {
                $query->where('imo', $validated['imo_number'])
                    ->orWhere('mmsi', $validated['mmsi_number']);
            })->where('is_archived', false)->first();

            if ($existingVessel) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'A vessel with this IMO or MMSI is already registered'
                ], 400);
            }

            // Create registration request
            $regRequest = NotifVesselRequest::create([
                'user_id'          => $user->id,
                'owner_id'         => $owner->owner_id,
                'vessel_name'      => $validated['vessel_name'],
                'imo_number'       => $validated['imo_number'],
                'mmsi_number'      => $validated['mmsi_number'],
                'call_sign'        => $validated['call_sign'],
                'vessel_type'      => $validated['vessel_type'],
                'flag_state'       => $validated['flag_state'],
                'length_overall'   => $validated['length_overall'],
                'gross_tonnage'    => $validated['gross_tonnage'],
                'year_built'       => $validated['year_built'],
                'additional_notes' => $validated['additional_notes'] ?? null,
                'status'           => 'pending',
                'submitted_at'     => now()
            ]);

            DB::commit();

            return response()->json([
                'success'      => true,
                'request_id'   => $regRequest->id,
                'message'      => 'Vessel registration submitted successfully! Awaiting admin approval.',
                'redirect_url' => route('user.register.boat')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error submitting registration. Please try again.'
            ], 500);
        }
    }

    /**
     * GET USER'S REGISTERED VESSELS + REGISTRATION STATUS
     */
    public function getUserVessels()
    {
        try {
            $user = Auth::user();
            $owner = Owner::where('user_id', $user->id)->first();

            if (!$owner) {
                return response()->json([
                    'success' => true,
                    'vessels' => [],
                    'pending' => [],
                    'rejected' => []
                ]);
            }

            // Approved vessels only
            $vessels = $owner->vessels()
                ->where('is_archived', false)
                ->with('latestPosition')
                ->get();

            // Pending registrations
            $pending = NotifVesselRequest::where('owner_id', $owner->owner_id)
                ->where('status', 'pending')
                ->orderBy('submitted_at', 'desc')
                ->get();

            // Rejected registrations
            $rejected = NotifVesselRequest::where('owner_id', $owner->owner_id)
                ->where('status', 'rejected')
                ->orderBy('submitted_at', 'desc')
                ->get();

            return response()->json([
                'success'  => true,
                'vessels'  => $vessels,
                'pending'  => $pending,
                'rejected' => $rejected
            ]);
        } catch (\Exception $e) {
            \Log::error('Get user vessels error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching vessels'
            ], 500);
        }
    }

    /**
     * GET SINGLE REGISTRATION REQUEST STATUS
     */
    public function getRegistrationStatus($request_id)
    {
        try {
            $regRequest = NotifVesselRequest::where('id', $request_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'status'  => $regRequest->status,
                'request' => $regRequest
            ]);
        } catch (\Exception $e) {
            \Log::error('Get registration status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration request not found'
            ], 404);
        }
    }

    /**
     * UPDATE PENDING REGISTRATION
     * User can edit pending registration specs (not position)
     */
    public function updateRegistration(Request $request, $request_id)
    {
        try {
            $regRequest = NotifVesselRequest::where('id', $request_id)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->firstOrFail();

            $validated = $request->validate([
                'vessel_name'      => 'sometimes|string|max:255',
                'imo_number'       => 'sometimes|string|max:50|unique:notif_vessel_request,imo_number,' . $request_id,
                'mmsi_number'      => 'sometimes|string|max:50|unique:notif_vessel_request,mmsi_number,' . $request_id,
                'call_sign'        => 'sometimes|string|max:50',
                'vessel_type'      => 'sometimes|string|max:100',
                'flag_state'       => 'sometimes|string|max:100',
                'length_overall'   => 'sometimes|numeric|min:0',
                'gross_tonnage'    => 'sometimes|numeric|min:0',
                'year_built'       => 'sometimes|integer|min:1900|max:2100',
                'additional_notes' => 'sometimes|nullable|string|max:1000'
            ]);

            // Additional check: If IMO/MMSI changed, verify against approved vessels
            if (isset($validated['imo_number']) && $validated['imo_number'] !== $regRequest->imo_number) {
                $existingVessel = Vessel::where('imo', $validated['imo_number'])
                    ->where('is_archived', false)
                    ->first();

                if ($existingVessel) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This IMO number is already registered to an active vessel'
                    ], 400);
                }
            }

            if (isset($validated['mmsi_number']) && $validated['mmsi_number'] !== $regRequest->mmsi_number) {
                $existingVessel = Vessel::where('mmsi', $validated['mmsi_number'])
                    ->where('is_archived', false)
                    ->first();

                if ($existingVessel) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This MMSI number is already registered to an active vessel'
                    ], 400);
                }
            }

            $regRequest->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Registration updated successfully',
                'data' => $regRequest
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Update registration error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating registration'
            ], 500);
        }
    }

    /**
     * CANCEL PENDING REGISTRATION
     */
    public function cancelRegistration($request_id)
    {
        try {
            $regRequest = NotifVesselRequest::where('id', $request_id)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->firstOrFail();

            $regRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registration cancelled'
            ]);
        } catch (\Exception $e) {
            \Log::error('Cancel registration error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling registration'
            ], 500);
        }
    }
}
