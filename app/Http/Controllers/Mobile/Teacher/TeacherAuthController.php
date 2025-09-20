<?php

namespace App\Http\Controllers\Mobile\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuardianResource;
use App\Http\Resources\TeacherResource;
use App\Models\Guardian;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherAuthController extends Controller
{
    public function login(Request $request)
    {

        $teacher = Teacher::where('phone', $request->get('phone'))->first();
        if ($teacher && Hash::check($request->get('password'), $teacher->password)) {

            $token = $teacher->createToken('TeacherToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'student' => new TeacherResource($teacher),
                'token' => $token
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Invalid login details'
            ]);
        }
    }
    public function logout(Request $request)
    {
        // Delete current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
