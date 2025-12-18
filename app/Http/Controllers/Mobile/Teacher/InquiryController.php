<?php

namespace App\Http\Controllers\Mobile\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->guard('teacher')->user();

        $query = Inquiry::where('teacher_id', $teacher->id)
            ->with(['student.stage', 'student.grade', 'student.division', 'subject']);

        if ($request->filled('answered')) {
            $answered = filter_var($request->input('answered'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (! is_null($answered)) {
                $query->when(
                    $answered,
                    fn ($q) => $q->whereNotNull('answer'),
                    fn ($q) => $q->whereNull('answer')
                );
            }
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', (int) $request->input('month'));
        }

        if ($request->filled('stage_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('stage_id', $request->input('stage_id'));
            });
        }

        $inquiries = $query->latest()->get();

        return InquiryResource::collection($inquiries);
    }

    public function answer(Request $request, Inquiry $inquiry)
    {
        $teacher = auth()->guard('teacher')->user();

        if ($inquiry->teacher_id !== $teacher->id) {
            return response()->json(['message' => 'Not authorized.'], 403);
        }

        $validated = $request->validate([
            'answer' => ['required', 'string', 'max:2000'],
        ]);

        $inquiry->update([
            'answer' => $validated['answer'],
        ]);

        $inquiry->load(['student.stage', 'student.grade', 'student.division', 'subject']);

        return new InquiryResource($inquiry);
    }
}
