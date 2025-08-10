<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\SubjectResource;
use App\Models\Banner;
use App\Models\Subject;
use App\Services\BannerService;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected SubjectService $subjectService;
    protected BannerService $bannerService;

    public function __construct(SubjectService $subjectService , BannerService $bannerService)
    {
        $this->subjectService = $subjectService;
        $this->bannerService = $bannerService;

    }
    public function getBanners()
    {
        $banners = $this->bannerService->getAllPaginated();
        return BannerResource::collection($banners);
    }
    public function getSubjects()
    {
        $subjects = $this->subjectService->getAllPaginated();
        return SubjectResource::collection($subjects);
    }
    public function getSubject(Subject $subject)
    {
        return new SubjectResource($subject);
    }
}
