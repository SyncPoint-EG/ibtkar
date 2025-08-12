{{-- resources/views/dashboard/exams/create.blade.php --}}

@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('Create New Exam') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">{{ __('Exams') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Create') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row">
                    <div class="col-md-8 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Exam Information') }}</h4>
                            </div>

                            <div class="card-body collapse in">
                                <form action="{{ route('exams.store') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="title">{{ __('Exam Title') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">{{ __('Description') }}</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lesson_id">{{ __('Lesson') }} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('lesson_id') is-invalid @enderror"
                                                        id="lesson_id" name="lesson_id" required>
                                                    <option value="">{{ __('Select Lesson') }}</option>
                                                    @foreach($lessons as $lesson)
                                                        <option value="{{ $lesson->id }}" {{ old('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                                            {{ $lesson->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('lesson_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="duration_minutes">{{ __('Duration (Minutes)') }} <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                                       id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}"
                                                       min="1" required>
                                                @error('duration_minutes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_date">{{ __('Start Date') }}</label>
                                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror"
                                                       id="start_date" name="start_date" value="{{ old('start_date') }}">
                                                @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date">{{ __('End Date') }}</label>
                                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                                @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="checkbox">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" class="checkbox" id="is_active" name="is_active" value="1"
                                                {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label for="is_active">{{ __('Active') }}</label>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success">
                                            <i class="icon-check2"></i> {{ __('Create Exam') }}
                                        </button>
                                        <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                                            <i class="icon-cross2"></i> {{ __('Cancel') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Instructions') }}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="alert alert-info">
                                    <h5><i class="icon-info"></i> {{ __('Creating an Exam') }}</h5>
                                    <p>{{ __('Fill in the basic exam information. After creating the exam, you can add questions of different types:') }}</p>
                                    <ul>
                                        <li>{{ __('True/False Questions') }}</li>
                                        <li>{{ __('Multiple Choice Questions') }}</li>
                                        <li>{{ __('Essay Questions') }}</li>
                                    </ul>
                                    <p><strong>{{ __('Note:') }}</strong> {{ __('The total marks will be calculated automatically based on the questions you add.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
