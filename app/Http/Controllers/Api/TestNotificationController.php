<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestNotificationRequest;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Notifications\FirebasePushNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class TestNotificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TestNotificationRequest $request): JsonResponse
    {
//        $authRecipient = $this->resolveAuthenticatedRecipient($request);
        $recipients = $this->gatherRecipients($request, null );

        if ($recipients->isEmpty()) {
            return response()->json([
                'message' => 'No recipients matched the provided identifiers.',
            ], 422);
        }

        Notification::send(
            $recipients,
            new FirebasePushNotification(
                $request->string('title')->toString(),
                $request->string('body')->toString(),
                $request->input('data', [])
            )
        );

        return response()->json([
            'message' => 'Notification dispatched successfully.',
            'dispatched_to' => $recipients->map(function ($recipient) {
                return [
                    'type' => class_basename($recipient),
                    'id' => $recipient->getKey(),
                ];
            })->values(),
        ]);
    }

    /**
     * Build the recipient collection based on the request payload.
     */
    protected function gatherRecipients(TestNotificationRequest $request, ?object $authRecipient): Collection
    {
        $recipients = collect();

        $recipientType = $request->input('recipient_type', 'students');
        $sendStudents = in_array($recipientType, ['students', 'both'], true);
        $sendGuardians = in_array($recipientType, ['guardians', 'both'], true);

        $includeAuth = $request->has('send_to_auth')
            ? $request->boolean('send_to_auth')
            : (bool) $authRecipient;

        if ($includeAuth && $authRecipient) {
            $recipients->push($authRecipient);
        }

        $studentIds = $request->input('student_ids', []);
        if ($sendStudents && ! empty($studentIds)) {
            $recipients = $recipients->merge(
                Student::whereIn('id', $studentIds)->get()
            );
        }

        $guardianIds = $request->input('guardian_ids', []);
        if ($sendGuardians && ! empty($guardianIds)) {
            $recipients = $recipients->merge(
                Guardian::whereIn('id', $guardianIds)->get()
            );
        }

        $teacherIds = $request->input('teacher_ids', []);
        if (! empty($teacherIds)) {
            $recipients = $recipients->merge(
                Teacher::whereIn('id', $teacherIds)->get()
            );
        }

        $stageIds = $request->input('stage_ids', []);
        $gradeIds = $request->input('grade_ids', []);
        $divisionIds = $request->input('division_ids', []);
        $hasHierarchyFilters = ! empty($stageIds) || ! empty($gradeIds) || ! empty($divisionIds);

        if ($sendStudents && $hasHierarchyFilters) {
            $recipients = $recipients->merge(
                Student::query()
                    ->when(! empty($stageIds), fn ($query) => $query->whereIn('stage_id', $stageIds))
                    ->when(! empty($gradeIds), fn ($query) => $query->whereIn('grade_id', $gradeIds))
                    ->when(! empty($divisionIds), fn ($query) => $query->whereIn('division_id', $divisionIds))
                    ->get()
            );
        }

        if ($sendGuardians && $hasHierarchyFilters) {
            $recipients = $recipients->merge(
                Guardian::query()
                    ->whereHas('children', function ($query) use ($stageIds, $gradeIds, $divisionIds) {
                        $query
                            ->when(! empty($stageIds), fn ($q) => $q->whereIn('stage_id', $stageIds))
                            ->when(! empty($gradeIds), fn ($q) => $q->whereIn('grade_id', $gradeIds))
                            ->when(! empty($divisionIds), fn ($q) => $q->whereIn('division_id', $divisionIds));
                    })
                    ->get()
            );
        }

        return $recipients
            ->filter()
            ->unique(fn ($recipient) => get_class($recipient).':'.$recipient->getKey())
            ->values();
    }

    /**
     * Resolve the authenticated recipient from available guards.
     */
    protected function resolveAuthenticatedRecipient(TestNotificationRequest $request): ?object
    {
        // Attempt the default guard first.
        if ($request->user()) {
            return $request->user();
        }

        $guards = array_filter([
            config('sanctum.guard'),
            'student',
            'guardian',
            'teacher',
            'web',
        ]);

        foreach ($guards as $guard) {
            try {
                $user = auth($guard)->user();
            } catch (\InvalidArgumentException $e) {
                continue;
            }

            if ($user) {
                return $user;
            }
        }

        return null;
    }
}
