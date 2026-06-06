<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Owner;
use App\Models\Address;

class RegistrationController extends Controller
{
    public function showInitialForm()
    {
        return view('auth.login', ['form' => 'register']);
    }

    public function showCompleteForm()
    {
        // Check if registration data exists in session
        if (!session()->has('registration_data')) {
            return redirect()->route('register');
        }

        return view('auth.complete_registration');
    }

    public function checkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $exists = User::where('email', $request->email)->exists();

            return response()->json([
                'exists' => $exists,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Check email error: ' . $e->getMessage());
            return response()->json([
                'exists' => false,
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateUsername(Request $request)
    {
        try {
            $request->validate([
                'firstName' => 'required|string',
                'lastName' => 'required|string'
            ]);

            $firstName = preg_replace('/[^a-zA-Z0-9]/', '', $request->firstName);
            $lastName = preg_replace('/[^a-zA-Z0-9]/', '', $request->lastName);

            $baseUsername = strtolower($firstName . '.' . $lastName);
            $username = $baseUsername;
            $counter = 1;

            // Find unique username
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            return response()->json([
                'username' => $username,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Generate username error: ' . $e->getMessage());
            return response()->json([
                'username' => null,
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeRegistrationData(Request $request)
    {
        try {
            $request->validate([
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'email' => 'required|email',
                'username' => 'required|string',
                'password' => 'required|string|min:8'
            ]);

            session([
                'registration_data' => [
                    'firstName' => $request->firstName,
                    'lastName' => $request->lastName,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => $request->password
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data stored successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Store registration data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processRegistration(Request $request)
    {
        try {
            // Validate all fields
            $validated = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'middleName' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:8',
                'idNumber' => 'required|string',
                'gender' => 'required|string',
                'dateOfBirth' => 'required|date',
                'nationality' => 'required|string',
                'streetAddress' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'postalCode' => 'required|string',
                'country' => 'required|string',
                'mobileNumber' => 'required|string',
                'alternateNumber' => 'nullable|string',
                'company' => 'nullable|string',
                'jobTitle' => 'nullable|string',
                'industry' => 'nullable|string',
            ]);

            // Start database transaction
            DB::beginTransaction();

            // 1. Create User record
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
                'is_archived' => false
            ]);

            // 2. Create Address record
            $address = Address::create([
                'street_no' => $validated['streetAddress'],
                'post_code' => $validated['postalCode'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'country' => $validated['country'],
                'contact' => $validated['mobileNumber'],
                'alt_contact' => $validated['alternateNumber'] ?? null,
                'email' => $validated['email'],
            ]);

            // 3. Create Owner record (matching your exact column names)
            $owner = Owner::create([
                'user_id' => $user->id,
                'address_id' => $address->address_id,
                'national_id' => $validated['idNumber'],           // Changed from id_number
                'first_name' => $validated['firstName'],           // Changed from fname
                'middle_name' => $validated['middleName'] ?? null, // Changed from mname
                'last_name' => $validated['lastName'],             // Changed from lname
                'gender' => $validated['gender'],
                'dob' => $validated['dateOfBirth'],                // Changed from bdate
                'nationality' => $validated['nationality'],
                'company' => $validated['company'] ?? null,
                'job_title' => $validated['jobTitle'] ?? null,
                'industry' => $validated['industry'] ?? null,
            ]);

            // Commit transaction
            DB::commit();

            // Clear session data
            session()->forget('registration_data');

            // Log the user in
            auth()->login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully',
                'username' => $user->username
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
