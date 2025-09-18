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
            'bio' => 'nullable|string',
        ]);

        $lesson->attachments()->create([
            'name' => $request->name,
            'file' => $request->file('file'),
            'bio' => $request->bio,
            'is_featured' => $request->has('is_featured'),
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

    public function toggleFeatured(LessonAttachment $attachment): \Illuminate\Http\JsonResponse
    {
        try {
            $attachment->is_featured = !$attachment->is_featured;
            $attachment->save();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.lesson_attachment.featured_status_updated'),
                'is_featured' => $attachment->is_featured
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('dashboard.common.error') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
