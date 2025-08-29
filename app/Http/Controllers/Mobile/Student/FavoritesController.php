<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function listFavorites()
    {
        $student = auth('student')->user();
        return LessonResource::collection($student->favorites);
    }
    public function addToFavorite(Request $request)
    {
        $request->validate([
            'lesson_id' => ['required', 'exists:lessons,id'],
        ]);

        $student = auth('student')->user();
        $student->favorites()->attach([$request->lessons_ids]);
        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);

    }

    public function removeFromFavorite(Request $request){
        $request->validate([
            'lesson_id' => ['required', 'exists:lessons,id'],
        ]);
        $student = auth('student')->user();
        $student->favorites()->detach([$request->lessons_ids]);
        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
