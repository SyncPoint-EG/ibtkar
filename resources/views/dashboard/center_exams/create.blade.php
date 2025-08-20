@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Create New Center Exam</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('center-exams.index') }}">Center Exams</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card px-1">
                            <div class="card-header">
                                <h4 class="card-title">Center Exam Details</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('center-exams.store') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="title">Exam Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="center_id">Center *</label>
                                        <select class="form-control @error('center_id') is-invalid @enderror"
                                                id="center_id" name="center_id" required>
                                            <option value="">Select Center</option>
                                            @foreach($centers as $center)
                                                <option value="{{ $center->id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>
                                                    {{ $center->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('center_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="teacher_id">{{ trans('dashboard.center_exam.fields.teacher') }} *</label>
                                        <select class="form-control @error('teacher_id') is-invalid @enderror"
                                                id="teacher_id" name="teacher_id" required>
                                            <option value="">{{ trans('dashboard.center_exam.placeholders.select_teacher') }}</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="stage_id">Stage *</label>
                                        <select class="form-control @error('stage_id') is-invalid @enderror"
                                                id="stage_id" name="stage_id" required>
                                            <option value="">Select Stage</option>
                                            @foreach($stages as $stage)
                                                <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>
                                                    {{ $stage->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('stage_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="grade_id">Grade *</label>
                                        <select class="form-control @error('grade_id') is-invalid @enderror"
                                                id="grade_id" name="grade_id" required>
                                            <option value="">Select Grade</option>
                                            @foreach($grades as $grade)
                                                <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                                    {{ $grade->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('grade_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="division_id">Division (Optional)</label>
                                        <select class="form-control @error('division_id') is-invalid @enderror"
                                                id="division_id" name="division_id">
                                            <option value="">Select Division</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('division_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="total_marks">Total Marks *</label>
                                                <input type="number" class="form-control @error('total_marks') is-invalid @enderror"
                                                       id="total_marks" name="total_marks" value="{{ old('total_marks') }}" required>
                                                @error('total_marks')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="passing_marks">Passing Marks *</label>
                                                <input type="number" class="form-control @error('passing_marks') is-invalid @enderror"
                                                       id="passing_marks" name="passing_marks" value="{{ old('passing_marks') }}" required>
                                                @error('passing_marks')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="duration_minutes">Duration (minutes) *</label>
                                                <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                                       id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes') }}" required>
                                                @error('duration_minutes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_time">Start Time</label>
                                                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror"
                                                       id="start_time" name="start_time" value="{{ old('start_time') }}">
                                                @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_time">End Time</label>
                                                <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror"
                                                       id="end_time" name="end_time" value="{{ old('end_time') }}">
                                                @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="is_active">Status</label>
                                        <select class="form-control" id="is_active" name="is_active">
                                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="icon-check2"></i> Create Center Exam
                                        </button>
                                        <a href="{{ route('center-exams.index') }}" class="btn btn-secondary">
                                            <i class="icon-cross"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
