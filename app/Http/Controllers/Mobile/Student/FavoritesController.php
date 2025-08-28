<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function addToFavorite(Request $request)
    {
        $request->validate([
            'lessons_ids' => ['required', 'array'],
            'lessons_ids.*' => ['required', 'exists:lessons,id'],
        ]);

        $student = auth('student')->user();
        $student->favorites()->attach($request->lessons_ids);
        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);

    }
}
