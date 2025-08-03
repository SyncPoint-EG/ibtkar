<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Guardian;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentAuthController
{

    public function register(StudentRequest $request)
    {
        $validated = $request->validated();
        $validated['verification_code'] = rand(1000, 9999);
        $student = Student::create($request->validated());
        if($request->referral_code){
            $this->handleReferralCode($request , $student);
        }
        if($request->guardian_number && !Guardian::query()->where('phone', $request->guardian_number)->exists()){
            Guardian::create([
                'phone' => $request->guardian_number,
                'password'=> $request->guardian_number,
            ]);
        }


        // sending code must be here


        return response()->json([
            'message' => 'Student registered successfully',
            'student' => new StudentResource($student),
            'code' => $student->verification_code,
        ]);
    }
    public function handleReferralCode(Request $request , Student $student)
    {
        $referrer = Student::where('referral_code', $request->input('referral_code'))->first();
        $data['referred_by'] = $referrer ? $referrer->id : null;
        $student->update($data);
        $referral_points = Setting::where('key', 'referral points')->value('value');
        $referrer->increment('points', $referral_points);
    }

    public function verifyPhone(Request $request, Student $student)
    {
        if ($student->verification_code == $request->get('verification_code')) {
            $student->status = true;
            $student->verifivation_code = null;
            $student->save();
        }
        $token = $student->createToken('StudentToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'You Are logged in successfully',
            'student' => new StudentResource($student),
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {

        $student = Student::where('phone', $request->get('phone'))->first();
        if ($student && Hash::check($request->get('password'), $student->password)) {

            $token = $student->createToken('StudentToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'student' => new StudentResource($student),
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
        $student = Student::where('phone', $request->get('phone'))->first();
        if ($student) {
            $code = $student->generateVerificationCode();
            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your mobile number',
                'code' => $code,
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
