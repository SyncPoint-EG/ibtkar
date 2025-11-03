<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardNotificationRequest;
use App\Models\Division;
use App\Models\Guardian;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Student;
use App\Traits\FirebaseNotify;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class NotificationController extends Controller
{
    use FirebaseNotify;

    public function index(): View
    {
        $stages = Stage::orderBy('name')->get();
        $grades = Grade::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();

        return view('dashboard.notifications.index', compact('stages', 'grades', 'divisions'));
    }

    public function store(DashboardNotificationRequest $request): RedirectResponse
    {
        $recipients = $this->resolveRecipients($request);

        if ($recipients->isEmpty()) {
            return back()
                ->withInput()
                ->with('error', __('dashboard.notification.empty_recipients'));
        }

        $data = [
            'type' => 'dashboard_manual',
            'recipient_type' => $request->input('recipient_type'),
            'stage_ids' => collect($request->input('stage_ids', []))->map(fn ($id) => (string) $id)->all(),
            'grade_ids' => collect($request->input('grade_ids', []))->map(fn ($id) => (string) $id)->all(),
            'division_ids' => collect($request->input('division_ids', []))->map(fn ($id) => (string) $id)->all(),
        ];

        $this->sendAndStoreFirebaseNotification(
            $recipients,
            $request->string('title')->toString(),
            $request->string('body')->toString(),
            $data
        );

        return back()->with('success', __('dashboard.notification.success'));
    }

    protected function resolveRecipients(DashboardNotificationRequest $request): Collection
    {
        $recipientType = $request->input('recipient_type');
        $stageIds = $request->input('stage_ids', []);
        $gradeIds = $request->input('grade_ids', []);
        $divisionIds = $request->input('division_ids', []);

        $recipients = collect();

        if (in_array($recipientType, ['students', 'both'], true)) {
            $students = Student::query()
                ->when(! empty($stageIds), fn ($query) => $query->whereIn('stage_id', $stageIds))
                ->when(! empty($gradeIds), fn ($query) => $query->whereIn('grade_id', $gradeIds))
                ->when(! empty($divisionIds), fn ($query) => $query->whereIn('division_id', $divisionIds))
                ->get();

            $recipients = $recipients->merge($students);
        }

        if (in_array($recipientType, ['guardians', 'both'], true)) {
            $guardians = Guardian::query()
                ->when(
                    ! empty($stageIds) || ! empty($gradeIds) || ! empty($divisionIds),
                    function ($query) use ($stageIds, $gradeIds, $divisionIds) {
                        $query->whereHas('children', function ($childQuery) use ($stageIds, $gradeIds, $divisionIds) {
                            $childQuery
                                ->when(! empty($stageIds), fn ($q) => $q->whereIn('stage_id', $stageIds))
                                ->when(! empty($gradeIds), fn ($q) => $q->whereIn('grade_id', $gradeIds))
                                ->when(! empty($divisionIds), fn ($q) => $q->whereIn('division_id', $divisionIds));
                        });
                    }
                )
                ->get();

            $recipients = $recipients->merge($guardians);
        }

        return $recipients
            ->filter()
            ->unique(fn ($recipient) => get_class($recipient).':'.$recipient->getKey())
            ->values();
    }
}
