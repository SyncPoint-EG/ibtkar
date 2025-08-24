<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use Illuminate\Http\Request;

class LessonAttachmentController extends Controller
{
    public function store(Request $request, Lesson $lesson)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,ppt,pptx,xls,xlsx|max:20480',
        ]);

        $lesson->attachments()->create([
            'name' => $request->name,
            'file' => $request->file('file'),
        ]);

        return back()->with('success', 'Attachment uploaded successfully.');
    }

    public function destroy(LessonAttachment $attachment)
    {
        // delete the file from storage
        \Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
    }
}