<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\LuckWheelResource;
use App\Models\LuckWheelItem;
use Illuminate\Http\Request;

class LuckWheelController extends Controller
{
    public function index(){
        $items = LuckWheelItem::all();
        return LuckWheelResource::collection($items);
    }
}
