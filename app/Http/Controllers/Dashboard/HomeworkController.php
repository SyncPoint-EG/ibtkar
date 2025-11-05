<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Traits\ResolvesLessonCascade;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    use ResolvesLessonCascade;

    public function index()
    {
        $homework = Homework::with('lesson')->paginate(10);

        return view('dashboard.homework.index', compact('homework'));
    }

    public function create(Request $request)
    {
        $cascadePrefill = $this->resolveLessonCascadePrefill(
            $request,
            null,
            $request->session()->getOldInput('lesson_id')
        );

        return view('dashboard.homework.create', compact('cascadePrefill'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lesson_id' => 'nullable|exists:lessons,id',
            'due_date' => 'nullable|date|after:today',
        ]);

        $homework = Homework::create($request->all());

        return redirect()->route('homework.show', $homework)
            ->with('success', 'Homework created successfully.');
    }

    public function show(Homework $homework)
    {
        $homework->load(['lesson', 'questions.options']);

        return view('dashboard.homework.show', compact('homework'));
    }

    public function edit(Request $request, Homework $homework)
    {
        $homework->loadMissing('lesson.chapter.course.stage', 'lesson.chapter.course.grade');

        $cascadePrefill = $this->resolveLessonCascadePrefill(
            $request,
            $homework->lesson,
            $request->session()->getOldInput('lesson_id')
        );

        return view('dashboard.homework.edit', compact('homework', 'cascadePrefill'));
    }

    public function update(Request $request, Homework $homework)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lesson_id' => 'required|exists:lessons,id',
            'due_date' => 'nullable|date',
        ]);

        $homework->update($request->all());

        return redirect()->route('homework.show', $homework)
            ->with('success', 'Homework updated successfully.');
    }

    public function destroy(Homework $homework)
    {
        $homework->delete();

        return redirect()->route('homework.index')
            ->with('success', 'Homework deleted successfully.');
    }

    public function toggleStatus(Homework $homework)
    {
        $homework->update(['is_active' => ! $homework->is_active]);

        $status = $homework->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Homework {$status} successfully.");
    }

    public function submissions(Homework $homework)
    {
        $homework->load('attempts.student', 'attempts.answers.question.options');

        return view('dashboard.homework.submissions', compact('homework'));
    }
}
