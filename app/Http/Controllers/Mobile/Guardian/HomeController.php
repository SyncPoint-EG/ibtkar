<?php

namespace App\Http\Controllers\Mobile\Guardian;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentProfileResource;
use App\Models\Charge;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function getChildren()
    {
        $guardian = auth()->guard('guardian')->user();
        $children = $guardian->children;

        return StudentProfileResource::collection($children);
    }

    public function getChildWallet($studentId)
    {
        $student = Student::findOrFail($studentId);

        $authorizationResult = $this->authorizeChild($student);
        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }

        $student = $authorizationResult;

        return response()->json([
            'status' => true,
            'student_id' => $student->id,
            'wallet' => $student->wallet,
        ]);
    }

    public function getChildCharges($studentId)
    {
        $student = Student::findOrFail($studentId);

        $authorizationResult = $this->authorizeChild($student);
        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }

        $student = $authorizationResult;

        $charges = Charge::where('student_id', $student->id)
            ->latest()
            ->get([
                'id',
                'amount',
                'type',
                'payment_method',
                'payment_status',
                'payment_image',
                'phone_number',
                'created_at',
            ]);

        return response()->json([
            'status' => true,
            'student_id' => $student->id,
            'charges' => $charges,
        ]);
    }

    public function getChildPayments($studentId)
    {
        $student = Student::findOrFail($studentId);

        $authorizationResult = $this->authorizeChild($student);
        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }

        $student = $authorizationResult;

        $payments = Payment::where('student_id', $student->id)
            ->latest()
            ->get([
                'id',
                'amount',
                'discount',
                'total_amount',
                'payment_method',
                'payment_status',
                'payment_image',
                'phone_number',
                'payment_code',
                'course_id',
                'chapter_id',
                'lesson_id',
                'created_at',
            ]);

        return response()->json([
            'status' => true,
            'student_id' => $student->id,
            'payments' => $payments,
        ]);
    }

    public function getChildPoints($studentId)
    {
        $student = Student::findOrFail($studentId);

        $authorizationResult = $this->authorizeChild($student);
        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }

        $student = $authorizationResult;

        $logs = $student->pointLogs()
            ->latest()
            ->get(['id', 'action_name', 'points', 'created_at']);

        return response()->json([
            'status' => true,
            'student_id' => $student->id,
            'points' => $student->points,
            'logs' => $logs,
        ]);
    }

    protected function authorizeChild(Student $student): Student|JsonResponse
    {
        $guardian = auth()->guard('guardian')->user();

        if ($student->guardian_id !== $guardian->id) {
            return response()->json([
                'status' => false,
                'message' => 'The requested student does not belong to the authenticated guardian.',
            ], 403);
        }

        return $student;
    }
}
