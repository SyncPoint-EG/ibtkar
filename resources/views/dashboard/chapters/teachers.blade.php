@extends('dashboard.layouts.master')

@section('page_styles')
<style>
    .teacher-card {
        transition: transform .2s;
        border-radius: 15px;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    }
    .teacher-card:hover {
        transform: scale(1.05);
    }
    .teacher-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.chapter.teachers_chapters') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.chapter.teachers_chapters') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    @foreach($teachers as $teacher)
                        <div class="col-md-6">
                            <div class="card teacher-card p-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                                            <img src="{{ $teacher->image ?? asset('dashboard/app-assets/images/portrait/small/avatar-s-1.png') }}" alt="teacher image" class="teacher-img">
                                        </div>
                                        <div class="col-md-8">
                                            <h5 class="card-title">{{ $teacher->name }}</h5>
                                            <p class="card-text">{{ __('dashboard.chapter.number_of_chapters') }}: {{ $teacher->chapters_count }}</p>
                                            <a href="{{ route('chapters.teacher.grades', ['teacher_id' => $teacher->id]) }}" class="btn btn-primary">{{ __('dashboard.common.view_grades') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
