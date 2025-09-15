@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.course.edit') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('dashboard.course.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.course.edit') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-tooltip">{{ __('dashboard.course.edit') }} {{ __('dashboard.course.title') }} #{{ $course->id }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                            <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                                            <li><a data-action="close"><i class="icon-cross2"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="card-text">
                                            <p>{{ __('dashboard.course.update_info') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('courses.update', $course->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="form-group">
            <label for="name">{{ __("dashboard.course.fields.name") }}</label>
            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                   name="name" value="{{ isset($course) ? $course->name : old('name') }}"
                   placeholder="{{ __("dashboard.course.fields.name") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.name") }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="year">{{ __("dashboard.course.fields.year") }}</label>
            <input type="date" id="year" class="form-control @error('year') is-invalid @enderror"
                   name="year" value="{{ isset($course) ? $course->year : old('year') }}"
                   data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.year") }}">
            @error('year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="teacher_id">{{ __("dashboard.course.fields.teacher_id") }}</label>
            <select id="teacher_id" name="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.teacher_id") }}">
                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.course.fields.teacher_id") }}</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ isset($course) && $course->teacher_id == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
            @error('teacher_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="education_type_id">{{ __("dashboard.course.fields.education_type_id") }}</label>
            <select id="education_type_id" name="education_type_id" class="form-control @error('education_type_id') is-invalid @enderror" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.education_type_id") }}">
                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.course.fields.education_type_id") }}</option>
                @foreach($educationTypes as $educationType)
                    <option value="{{ $educationType->id }}" {{ isset($course) && $course->education_type_id == $educationType->id ? 'selected' : '' }}>
                        {{ $educationType->name }}
                    </option>
                @endforeach
            </select>
            @error('education_type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="stage_id">{{ __("dashboard.course.fields.stage_id") }}</label>
            <select id="stage_id" name="stage_id" class="form-control @error('stage_id') is-invalid @enderror" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.stage_id") }}">
                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.course.fields.stage_id") }}</option>
                @foreach($stages as $stage)
                    <option value="{{ $stage->id }}" {{ isset($course) && $course->stage_id == $stage->id ? 'selected' : '' }}>
                        {{ $stage->name }}
                    </option>
                @endforeach
            </select>
            @error('stage_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="grade_id">{{ __("dashboard.course.fields.grade_id") }}</label>
            <select id="grade_id" name="grade_id" class="form-control @error('grade_id') is-invalid @enderror" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.grade_id") }}">
                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.course.fields.grade_id") }}</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" {{ isset($course) && $course->grade_id == $grade->id ? 'selected' : '' }}>
                        {{ $grade->name }}
                    </option>
                @endforeach
            </select>
            @error('grade_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="division_id">{{ __("dashboard.course.fields.division_id") }}</label>
            <select id="division_id" name="division_id" class="form-control @error('division_id') is-invalid @enderror" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.division_id") }}">
                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.course.fields.division_id") }}</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}" {{ isset($course) && $course->division_id == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                @endforeach
            </select>
            @error('division_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="semister_id">{{ __("dashboard.course.fields.semister_id") }}</label>
            <select id="semister_id" name="semister_id" class="form-control @error('semister_id') is-invalid @enderror" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.semister_id") }}">
                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.course.fields.semister_id") }}</option>
                @foreach($semisters as $semister)
                    <option value="{{ $semister->id }}" {{ isset($course) && $course->semister_id == $semister->id ? 'selected' : '' }}>
                        {{ $semister->name }}
                    </option>
                @endforeach
            </select>
            @error('semister_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
                    <label for="is_featured">{{ __("dashboard.course.fields.is_featured") }}</label>
                    <input type="checkbox" id="is_featured"
                           name="is_featured" value="1"
                        {{ ($course->is_featured ?? old('is_featured')) ? 'checked' : '' }}>
                    @error('is_featured')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
            <label for="price">{{ __("dashboard.course.fields.price") }}</label>
            <input type="number" id="price" class="form-control @error('price') is-invalid @enderror"
                   name="price" value="{{ isset($course) ? $course->price : old('price') }}"
                   step="0.01"
                   placeholder="{{ __("dashboard.course.fields.price") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.price") }}">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="subject_id">{{ __("dashboard.course.fields.subject_id") }}</label>
            <select id="subject_id" name="subject_id" class="form-control @error('subject_id') is-invalid @enderror" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.course.fields.subject_id") }}">
                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.course.fields.subject_id") }}</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ isset($course) && $course->subject_id == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('courses.index') }}" class="btn btn-warning mr-1">
                                                    <i class="icon-cross2"></i> {{ __('dashboard.common.cancel') }}
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="icon-check2"></i> {{ __('dashboard.common.update') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
