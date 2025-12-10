<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    // Display all users
    public function index()
    {
        $owners = Owner::with(['user', 'address'])
            ->whereHas('user', function ($query) {
                $query->where('is_archived', 0);
            })
            ->get()
            ->map(function ($owner) {
                return [
                    'owner_id' => $owner->owner_id,
                    'user_id' => $owner->user_id,
                    'address_id' => $owner->address_id,
                    'username' => $owner->user->username ?? '',
                    'password' => $owner->user->password ?? '',
                    'first_name' => $owner->first_name,
                    'middle_name' => $owner->middle_name,
                    'last_name' => $owner->last_name,
                    'dob' => $owner->dob,
                    'gender' => $owner->gender,
                    'national_id' => $owner->national_id,
                    'nationality' => $owner->nationality,
                    'street_no' => $owner->address->street_no ?? '',
                    'post_code' => $owner->address->post_code ?? '',
                    'city' => $owner->address->city ?? '',
                    'state' => $owner->address->state ?? '',
                    'country' => $owner->address->country ?? '',
                    'email' => $owner->address->email ?? '',
                    'contact' => $owner->address->contact ?? '',
                    'company' => $owner->company,
                    'job_title' => $owner->job_title,
                    'industry' => $owner->industry,
                ];
            });

        return view('admin_users', compact('owners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
            'nationality' => 'required',
            'street_no' => 'required',
            'post_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'national_id' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Auto-generate username
            $username = strtolower(str_replace(' ', '', $request->first_name))
                . '.'
                . strtolower(str_replace(' ', '', $request->last_name));

            // Create user
            $user = User::create([
                'username' => $username,
                'password' => $request->password, // will be hashed automatically if using cast
                'email' => $request->email,
                'is_archived' => 0
            ]);

            // Create address
            $address = Address::create([
                'street_no' => $request->street_no,
                'post_code' => $request->post_code,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'contact' => $request->phone,
                'email' => $request->email
            ]);

            // Create owner and link correct IDs
            $owner = Owner::create([
                'user_id' => $user->id, // <-- use 'id' here, not 'user_id'
                'address_id' => $address->address_id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'national_id' => $request->national_id,
                'nationality' => $request->nationality,
                'company' => $request->company,
                'job_title' => $request->position,
                'industry' => $request->industry
            ]);

            DB::commit();

            // Return newly created owner with related user and address
            $ownerWithRelations = Owner::with(['user', 'address'])->find($owner->owner_id);

            return response()->json([
                'success' => true,
                'message' => 'User added successfully',
                'data' => [
                    'owner_id' => $ownerWithRelations->owner_id,
                    'username' => $ownerWithRelations->user->username,
                    'first_name' => $ownerWithRelations->first_name,
                    'last_name' => $ownerWithRelations->last_name,
                    'email' => $ownerWithRelations->address->email,
                    'contact' => $ownerWithRelations->address->contact,
                    'company' => $ownerWithRelations->company,
                    'job_title' => $ownerWithRelations->job_title,
                    'industry' => $ownerWithRelations->industry
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Owner $owner)
    {
        // Load related user and address
        $owner->load('user', 'address');

        // 1️⃣ Validate input
        $validator = Validator::make($request->all(), [
            'password' => 'nullable|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
            'nationality' => 'required',
            'street_no' => 'required',
            'post_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'national_id' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2️⃣ Start DB transaction
        DB::beginTransaction();
        try {
            // Update User
            $userData = [
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = $request->password;
            }

            $owner->user->update($userData);

            // Update Address
            $owner->address->update([
                'street_no' => $request->street_no,
                'post_code' => $request->post_code,
                'city'      => $request->city,
                'state'     => $request->state,
                'country'   => $request->country,
                'contact'   => $request->phone,
                'email'     => $request->email
            ]);

            // Update Owner
            $owner->update([
                'first_name'  => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name'   => $request->last_name,
                'dob'         => $request->dob,
                'gender'      => $request->gender,
                'national_id' => $request->national_id,
                'nationality' => $request->nationality,
                'company'     => $request->company,
                'job_title'   => $request->position,
                'industry'    => $request->industry
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Owner $owner)
    {
        DB::beginTransaction();
        try {
            // Soft delete the related user
            $owner->user->update(['is_archived' => 1]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
