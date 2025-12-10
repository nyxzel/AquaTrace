<?php

namespace App\Http\Controllers;

use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminVesselController extends Controller
{
    /**
     * Display all active vessels
     */
    public function index()
    {
        $vessels = Vessel::with(['owner.user'])
            ->where('is_archived', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin_vessel', compact('vessels'));
    }

    /**
     * Update vessel information
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'imo' => 'required|string|max:50',
                'mmsi' => 'required|string|max:50',
                'call_sign' => 'required|string|max:50',
                'type' => 'required|string|max:100',
                'flag' => 'required|string|max:255',
                'LoA' => 'required|numeric|min:0',
                'gross_tonnage' => 'required|numeric|min:0',
                'year_built' => 'required|integer|min:1900|max:2100'
            ]);

            $vessel = Vessel::findOrFail($id);
            $vessel->update($validated);

            Log::info('Vessel updated successfully', ['vessel_id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Vessel updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating vessel', [
                'vessel_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update vessel'
            ], 500);
        }
    }

    /**
     * Soft delete vessel (Archive)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $vessel = Vessel::where('vessel_id', $id)
                ->where('is_archived', 0)
                ->firstOrFail();

            Log::info('Attempting to archive vessel', [
                'vessel_id' => $id,
                'vessel_name' => $vessel->name
            ]);

            // Update is_archived to 1 (true)
            $vessel->is_archived = 1;
            $vessel->save();

            // Verify the update
            $vessel->refresh();

            if ($vessel->is_archived != 1) {
                throw new \Exception('Failed to archive vessel');
            }

            DB::commit();

            Log::info('Vessel archived successfully', [
                'vessel_id' => $id,
                'is_archived' => $vessel->is_archived
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Vessel archived successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            Log::warning('Vessel not found for archiving', ['vessel_id' => $id]);

            return response()->json([
                'status' => 'error',
                'message' => 'Vessel not found or already archived'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error archiving vessel', [
                'vessel_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to archive vessel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get archived vessels (optional - for viewing archived vessels)
     */
    public function archived()
    {
        $vessels = Vessel::with(['owner.user'])
            ->where('is_archived', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin_vessel_archived', compact('vessels'));
    }

    /**
     * Restore archived vessel (optional - to unarchive)
     */
    public function restore($id)
    {
        try {
            $vessel = Vessel::where('vessel_id', $id)
                ->where('is_archived', 1)
                ->firstOrFail();

            $vessel->is_archived = 0;
            $vessel->save();

            Log::info('Vessel restored successfully', ['vessel_id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Vessel restored successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error restoring vessel', [
                'vessel_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to restore vessel'
            ], 500);
        }
    }
}
