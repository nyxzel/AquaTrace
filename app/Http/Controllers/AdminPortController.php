<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Port;

class AdminPortController extends Controller
{
    /**
     * Display all ports with their positions
     */
    public function index()
    {
        // Using Eloquent with eager loading for relationships
        $ports = Port::with(['address', 'position'])
            ->get();

        return view('admin_ports', compact('ports'));
    }

    /**
     * Store new port
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            // 1. Create address first
            $addressId = DB::table('address')->insertGetId([
                'city' => $request->city,
                'state' => $request->state,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 2. Create position
            $positionId = DB::table('positions')->insertGetId([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'recorded_at' => now()
            ]);

            // 3. Create port
            DB::table('ports')->insert([
                'name' => $request->name,
                'address_id' => $addressId,
                'position_id' => $positionId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Soft delete a port
     */
    public function archive(Request $request)
    {
        try {
            $port = DB::table('ports')->where('port_id', $request->port_id)->first();

            if ($port) {
                // Soft delete the port
                DB::table('ports')
                    ->where('port_id', $request->port_id)
                    ->update(['deleted_at' => now()]);

                return response()->json(['status' => 'success']);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Port not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
