<?php

namespace App\Http\Controllers\Mobile\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function index()
    {
        $stories = Story::with('teacher')->get();
        return response()->json($stories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|file|mimes:jpg,jpeg,png,mp4|max:5120',
            'description' => 'nullable|string',
        ]);

        $type = $request->file('content')->getMimeType() == 'video/mp4' ? 'video' : 'image';

        $story = Story::create([
            'teacher_id' => auth()->id(),
            'content' => $request->file('content'),
            'description' => $request->description,
            'type' => $type,
        ]);

        return response()->json($story, 201);
    }
}
