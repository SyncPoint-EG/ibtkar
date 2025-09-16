<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\ActionPoint;
use App\Models\Guardian;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentAuthController
{

    public function register(StudentRequest $request)
    {
        DB::beginTransaction();
        $validated = $request->validated();
        if(!$request->image){
            $validated->unset('image');
        }
//        $validated['verification_code'] = rand(1000, 9999);
        $student = Student::create($request->validated());
        $student->generateVerificationCode();
        if($request->referral_code){
            $this->handleReferralCode($request , $student);
        }
        if($request->guardian_number && !Guardian::query()->where('phone', $request->guardian_number)->exists()){
            $guardian = Guardian::create([
                'phone' => $request->guardian_number,
                'password'=> $request->guardian_number,
            ]);
            $student->guardian_id = $guardian->id;
            $student->save();
        }

        DB::commit();

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
        $referral_points = ActionPoint::where('action_name', 'successful_referral')->value('points');
        $referrer->increment('points', $referral_points);
    }

    public function verifyPhone(Request $request,  $studentId)
    {
        $student = Student::findOrFail($studentId) ;
        if ($student->verification_code == $request->get('verification_code')) {
            $student->status = true;
            $student->verification_code = null;
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
        if($student && $student->mac_address && $student->mac_address != $request->mac_address){
            return response()->json([
                'success' => false,
                'message' => 'The user is already logged in from other device',
            ]);
        }
        if ($student && Hash::check($request->get('password'), $student->password)) {

            if($student->mac_address == null ){
                $student->mac_address = $request->mac_address;
            }
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
        $user = auth('student')->user(); // get the authenticated user
        $user->mac_address = null ;
        $user->save();
        // delete all tokens for this user
        $user->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }


    public function deleteAccount()
    {
        $user = auth('student')->user();
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);
    }
}
