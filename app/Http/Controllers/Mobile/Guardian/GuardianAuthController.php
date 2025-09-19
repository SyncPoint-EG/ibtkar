<?php

namespace App\Http\Controllers\Mobile\Guardian;

use App\Http\Requests\GuardianRequest;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\GuardianResource;
use App\Http\Resources\StudentResource;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuardianAuthController
{

    public function register(GuardianRequest $request)
    {
        $validated = $request->validated();
        $validated['verification_code'] = rand(1000, 9999);
        $guardian = Guardian::create($request->validated());


        // sending code must be here


        return response()->json([
            'message' => 'Guardian registered successfully',
            'guardian' => new GuardianResource($guardian),
            'code' => $guardian->verification_code,
        ]);
    }

    public function verifyPhone(Request $request, Guardian $guardian)
    {
        if ($guardian->verification_code == $request->get('verification_code')) {
            $guardian->status = true;
            $guardian->verifivation_code = null;
            $guardian->save();
        }
        $token = $guardian->createToken('GuardianToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'You Are logged in successfully',
            'student' => new GuardianResource($guardian),
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {

        $guardian = Guardian::where('phone', $request->get('phone'))->first();
        if ($guardian && Hash::check($request->get('password'), $guardian->password)) {

            if ($request->fcm_token) {
                $guardian->devices()->updateOrCreate(
                    [
                        'fcm_token' => $request->fcm_token,
                    ],
                    [
                        'fcm_token' => $request->fcm_token,
                    ]
                );
            }

            $token = $guardian->createToken('GuardianToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'guardian' => new GuardianResource($guardian),
                'token' => $token
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Invalid login details'
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:students,phone',
        ]);
        $guardian = Guardian::where('phone', $request->get('phone'))->first();
        if ($guardian) {
            $code = $guardian->generateVerificationCode();
            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your mobile number',
                'code' => $code,
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = auth('guardian')->user(); // get the authenticated user

        if ($request->fcm_token) {
            $user->devices()->where('fcm_token', $request->fcm_token)->delete();
        }

        // Delete current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
