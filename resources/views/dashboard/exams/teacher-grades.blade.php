@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('Grades for') }} {{ $teacher->name }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                             <li class="breadcrumb-item"><a
                                    href="{{ route('exams.teachers') }}">{{ __('Teachers') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Grades for') }} {{ $teacher->name }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    @forelse($grades as $grade)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body p-1">
                                    <h4 class="card-title">{{ $grade->name }}</h4>
                                    <a href="{{ route('exams.index', ['teacher_id' => $teacher->id, 'grade_id' => $grade->id]) }}"
                                       class="btn btn-primary">{{ __('Show Exams') }}</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-body">
                                    <p>{{ __('No grades found for this teacher.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
