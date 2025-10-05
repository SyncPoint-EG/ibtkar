<?php

namespace App\Http\Controllers\Mobile\Guardian;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentProfileResource;

class HomeController extends Controller
{
    public function getChildren()
    {
        $guardian = auth()->guard('guardian')->user();
        $children = $guardian->children;

        return StudentProfileResource::collection($children);
    }
}
