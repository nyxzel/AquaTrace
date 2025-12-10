<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Address;
use App\Models\Owner;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function showInitialForm()
    {
        return view('auth.login', ['form' => 'register']);
    }

    public function showCompleteForm(Request $request)
    {
        // Check if session has registration data
        if (!$request->session()->has('registration_data')) {
            return redirect()->route('register')->with('error', 'Please complete the initial registration first.');
        }

        return view('auth.complete_registration');
    }

    public function processRegistration(Request $request)
    {
        // Get initial registration data from session
        $initialData = $request->session()->get('registration_data');

        if (!$initialData) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please start registration again.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users,username|max:100',
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if email already exists
            if (User::where('email', $initialData['email'])->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already registered.'
                ], 422);
            }

            // Create user
            $user = User::create([
                'username' => $request->username,
                'email' => $initialData['email'],
                'password' => Hash::make($initialData['password']),
                'role' => 'user',
                'is_archived' => 0,
            ]);

            // Create address
            $address = Address::create([
                'street_no' => $request->streetAddress,
                'post_code' => $request->postalCode,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'contact' => $request->mobileNumber,
                'email' => $initialData['email']
            ]);

            // Create owner profile
            Owner::create([
                'user_id' => $user->id,
                'address_id' => $address->address_id,
                'first_name' => $initialData['firstName'],
                'middle_name' => $request->middleName,
                'last_name' => $initialData['lastName'],
                'dob' => $request->dateOfBirth,
                'gender' => $request->gender,
                'national_id' => $request->idNumber,
                'nationality' => $request->nationality,
                'company' => $request->company,
                'job_title' => $request->jobTitle,
                'industry' => $request->industry
            ]);

            // Clear session data
            $request->session()->forget('registration_data');

            // Log the user in automatically
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully',
                'user_id' => $user->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeRegistrationData(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $request->session()->put('registration_data', [
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return response()->json(['success' => true]);
    }
}
