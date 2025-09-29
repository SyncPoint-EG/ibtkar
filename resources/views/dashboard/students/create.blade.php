@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.student.create') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('students.index') }}">{{ __('dashboard.student.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.student.create') }}
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
                                    <h4 class="card-title"
                                        id="basic-layout-tooltip">{{ __('dashboard.student.create_new') }}</h4>
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
                                            <p>{{ __('dashboard.student.fill_required') }}</p>
                                        </div>

                                        @if ($errors->any())

                                            <div class="alert alert-danger">

                                                <ul>

                                                    @foreach ($errors->all() as $error)

                                                        <li>{{ $error }}</li>

                                                    @endforeach

                                                </ul>

                                            </div>

                                        @endif
                                        <form class="form" method="POST" action="{{ route('students.store') }}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label
                                                        for="first_name">{{ __("dashboard.student.fields.first_name") }}</label>
                                                    <input type="text" id="first_name"
                                                           class="form-control @error('first_name') is-invalid @enderror"
                                                           name="first_name"
                                                           value="{{ isset($student) ? $student->first_name : old('first_name') }}"
                                                           placeholder="{{ __("dashboard.student.fields.first_name") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.student.fields.first_name") }}">
                                                    @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="last_name">{{ __("dashboard.student.fields.last_name") }}</label>
                                                    <input type="text" id="last_name"
                                                           class="form-control @error('last_name') is-invalid @enderror"
                                                           name="last_name"
                                                           value="{{ isset($student) ? $student->last_name : old('last_name') }}"
                                                           placeholder="{{ __("dashboard.student.fields.last_name") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.student.fields.last_name") }}">
                                                    @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="phone">{{ __("dashboard.student.fields.phone") }}</label>
                                                    <input type="text" id="phone"
                                                           class="form-control @error('phone') is-invalid @enderror"
                                                           name="phone"
                                                           value="{{ isset($student) ? $student->phone : old('phone') }}"
                                                           placeholder="{{ __("dashboard.student.fields.phone") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.student.fields.phone") }}">
                                                    @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="password">{{ __("dashboard.student.fields.password") }}</label>
                                                    <input type="text" id="password"
                                                           class="form-control @error('password') is-invalid @enderror"
                                                           name="password"
                                                           value="{{ isset($student) ? $student->password : old('password') }}"
                                                           placeholder="{{ __("dashboard.student.fields.password") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.student.fields.password") }}">
                                                    @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="governorate_id">{{ __("dashboard.student.fields.governorate_id") }}</label>
                                                    <select id="governorate_id" name="governorate_id"
                                                            class="form-control @error('governorate_id') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.student.fields.governorate_id") }}">
                                                        <option
                                                            value="">{{ __("dashboard.common.select") }} {{ __("dashboard.student.fields.governorate_id") }}</option>
                                                        @foreach($governorates as $governorate)
                                                            <option
                                                                value="{{ $governorate->id }}" {{ isset($student) && $student->governorate_id == $governorate->id ? 'selected' : '' }}>
                                                                {{ $governorate->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('governorate_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="district_id">{{ __("dashboard.student.fields.district_id") }}</label>
                                                    <select id="district_id" name="district_id"
                                                            class="form-control @error('district_id') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.student.fields.district_id") }}">
                                                        <option
                                                            value="">{{ __("dashboard.common.select") }} {{ __("dashboard.student.fields.district_id") }}</option>
                                                        @foreach($districts as $district)
                                                            <option
                                                                value="{{ $district->id }}" {{ isset($student) && $student->district_id == $district->id ? 'selected' : '' }}>
                                                                {{ $district->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('district_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="center_id">{{ __("dashboard.student.fields.center_id") }}</label>
                                                    <select id="center_id" name="center_id"
                                                            class="form-control @error('center_id') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.student.fields.center_id") }}">
                                                        <option
                                                            value="">{{ __("dashboard.common.select") }} {{ __("dashboard.student.fields.center_id") }}</option>
                                                        @foreach($centers as $center)
                                                            <option
                                                                value="{{ $center->id }}" {{ isset($student) && $student->center_id == $center->id ? 'selected' : '' }}>
                                                                {{ $center->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('center_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="stage_id">{{ __("dashboard.student.fields.stage_id") }}</label>
                                                    <select id="stage_id" name="stage_id"
                                                            class="form-control @error('stage_id') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.student.fields.stage_id") }}">
                                                        <option
                                                            value="">{{ __("dashboard.common.select") }} {{ __("dashboard.student.fields.stage_id") }}</option>
                                                        @foreach($stages as $stage)
                                                            <option
                                                                value="{{ $stage->id }}" {{ isset($student) && $student->stage_id == $stage->id ? 'selected' : '' }}>
                                                                {{ $stage->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('stage_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="grade_id">{{ __("dashboard.student.fields.grade_id") }}</label>
                                                    <select id="grade_id" name="grade_id"
                                                            class="form-control @error('grade_id') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.student.fields.grade_id") }}">
                                                        <option
                                                            value="">{{ __("dashboard.common.select") }} {{ __("dashboard.student.fields.grade_id") }}</option>
                                                        @foreach($grades as $grade)
                                                            <option
                                                                value="{{ $grade->id }}" {{ isset($student) && $student->grade_id == $grade->id ? 'selected' : '' }}>
                                                                {{ $grade->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('grade_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="division_id">{{ __("dashboard.student.fields.division_id") }}</label>
                                                    <select id="division_id" name="division_id"
                                                            class="form-control @error('division_id') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.student.fields.division_id") }}">
                                                        <option
                                                            value="">{{ __("dashboard.common.select") }} {{ __("dashboard.student.fields.division_id") }}</option>
                                                        @foreach($divisions as $division)
                                                            <option
                                                                value="{{ $division->id }}" {{ isset($student) && $student->division_id == $division->id ? 'selected' : '' }}>
                                                                {{ $division->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('division_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="education_type_id">{{ __("dashboard.student.fields.education_type_id") }}</label>
                                                    <select id="education_type_id" name="education_type_id"
                                                            class="form-control @error('education_type_id') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.student.fields.education_type_id") }}">
                                                        <option
                                                            value="">{{ __("dashboard.common.select") }} {{ __("dashboard.student.fields.education_type_id") }}</option>
                                                        @foreach($educationTypes as $educationType)
                                                            <option
                                                                value="{{ $educationType->id }}" {{ isset($student) && $student->education_type_id == $educationType->id ? 'selected' : '' }}>
                                                                {{ $educationType->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('education_type_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="gender">{{ __("dashboard.student.fields.gender") }}</label>
                                                    <select id="gender" name="gender"
                                                            class="form-control @error('gender') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.student.fields.gender") }}">
                                                        <option
                                                            value="">{{ __("dashboard.common.select") }} {{ __("dashboard.student.fields.gender") }}</option>
                                                        <option
                                                            value="Male" {{ isset($student) && $student->gender == 'male' ? 'selected' : '' }}>{{ __('dashboard.common.male') }}</option>
                                                        <option
                                                            value="Female" {{ isset($student) && $student->gender == 'female' ? 'selected' : '' }}>{{ __('dashboard.common.female') }}</option>
                                                    </select>
                                                    @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="birth_date">{{ __("dashboard.student.fields.birth_date") }}</label>
                                                    <input type="date" id="birth_date"
                                                           class="form-control @error('birth_date') is-invalid @enderror"
                                                           name="birth_date"
                                                           value="{{ isset($student) ? $student->birth_date : old('birth_date') }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.student.fields.birth_date") }}">
                                                    @error('birth_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
{{--                                                <div class="form-group">--}}
{{--                                                    <label--}}
{{--                                                        for="status">{{ __("dashboard.student.fields.status") }}</label>--}}
{{--                                                    <select id="status" name="status"--}}
{{--                                                            class="form-control @error('status') is-invalid @enderror"--}}
{{--                                                            data-toggle="tooltip" data-trigger="hover"--}}
{{--                                                            data-placement="top"--}}
{{--                                                            data-title="{{ __("dashboard.student.fields.status") }}">--}}
{{--                                                        <option--}}
{{--                                                            value="0" {{ isset($student) && !$student->status ? 'selected' : '' }}>{{ __("dashboard.common.no") }}</option>--}}
{{--                                                        <option--}}
{{--                                                            value="1" {{ isset($student) && $student->status ? 'selected' : '' }}>{{ __("dashboard.common.yes") }}</option>--}}
{{--                                                    </select>--}}
{{--                                                    @error('status')--}}
{{--                                                    <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                                    @enderror--}}
{{--                                                </div>--}}
                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('students.index') }}" class="btn btn-warning mr-1">
                                                    <i class="icon-cross2"></i> {{ __('dashboard.common.cancel') }}
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="icon-check2"></i> {{ __('dashboard.common.save') }}
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
