{{-- resources/views/dashboard/exams/edit.blade.php --}}

@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('Edit Exam') }}: {{ $exam->title }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">{{ __('Exams') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('exams.show', $exam) }}">{{ $exam->title }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Edit') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row">
                    <div class="col-md-8 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Edit Exam Information') }}</h4>
                            </div>

                            <div class="card-body collapse in">
                                <form action="{{ route('exams.update', $exam) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="title">{{ __('Exam Title') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title', $exam->title) }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">{{ __('Description') }}</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="3">{{ old('description', $exam->description) }}</textarea>
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
                                                        <option value="{{ $lesson->id }}"
                                                            {{ old('lesson_id', $exam->lesson_id) == $lesson->id ? 'selected' : '' }}>
                                                            {{ $lesson->title }}
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
                                                       id="duration_minutes" name="duration_minutes"
                                                       value="{{ old('duration_minutes', $exam->duration_minutes) }}"
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
                                                       id="start_date" name="start_date"
                                                       value="{{ old('start_date', $exam->start_date ? $exam->start_date->format('Y-m-d\TH:i') : '') }}">
                                                @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date">{{ __('End Date') }}</label>
                                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                                       id="end_date" name="end_date"
                                                       value="{{ old('end_date', $exam->end_date ? $exam->end_date->format('Y-m-d\TH:i') : '') }}">
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
                                                {{ old('is_active', $exam->is_active) ? 'checked' : '' }}>
                                            <label for="is_active">{{ __('Active') }}</label>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success">
                                            <i class="icon-check2"></i> {{ __('Update Exam') }}
                                        </button>
                                        <a href="{{ route('exams.show', $exam) }}" class="btn btn-secondary">
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
                                <h4 class="card-title">{{ __('Exam Statistics') }}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ __('Total Questions') }}
                                        <span class="tag tag-primary tag-pill">{{ $exam->questions->count() }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ __('Total Marks') }}
                                        <span class="tag tag-success tag-pill">{{ $exam->total_marks }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ __('True/False Questions') }}
                                        <span class="tag tag-info tag-pill">{{ $exam->questions->where('question_type', 'true_false')->count() }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ __('Multiple Choice Questions') }}
                                        <span class="tag tag-warning tag-pill">{{ $exam->questions->where('question_type', 'multiple_choice')->count() }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ __('Essay Questions') }}
                                        <span class="tag tag-secondary tag-pill">{{ $exam->questions->where('question_type', 'essay')->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Quick Actions') }}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('exams.show', $exam) }}" class="btn btn-primary btn-block">
                                        <i class="icon-eye"></i> {{ __('View Questions') }}
                                    </a>

                                    <form action="{{ route('exams.toggle-active', $exam) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-{{ $exam->is_active ? 'secondary' : 'success' }} btn-block">
                                            <i class="icon-{{ $exam->is_active ? 'pause' : 'play3' }}"></i>
                                            {{ $exam->is_active ? __('Deactivate') : __('Activate') }}
                                        </button>
                                    </form>

                                    <form action="{{ route('exams.destroy', $exam) }}" method="POST"
                                          onsubmit="return confirm('{{ __('Are you sure you want to delete this exam? This action cannot be undone.') }}')"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="icon-trash"></i> {{ __('Delete Exam') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
