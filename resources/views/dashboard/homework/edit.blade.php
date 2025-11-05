@extends('dashboard.layouts.master')

@php
    $lessonCascadePrefill = array_merge([
        'stage_id' => null,
        'grade_id' => null,
        'course_id' => null,
        'chapter_id' => null,
        'lesson_id' => null,
    ], $cascadePrefill ?? []);
@endphp

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Edit Homework: {{ $homework->title }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('homework.index') }}">Homework</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('homework.show', $homework) }}">{{ $homework->title }}</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card px-1">
                            <div class="card-header">
                                <h4 class="card-title">Edit Homework Details</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('homework.update', $homework) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="title">Homework Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title', $homework->title) }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stage_id">{{ __('dashboard.stage.title') }} *</label>
                                                <select id="stage_id" class="form-control" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="grade_id">{{ __('dashboard.grade.title') }} *</label>
                                                <select id="grade_id" class="form-control" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="course_id">{{ __('dashboard.course.title') }} *</label>
                                                <select id="course_id" class="form-control" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="chapter_id">{{ __('dashboard.chapter.title') }} *</label>
                                                <select id="chapter_id" class="form-control" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lesson_id">{{ __('dashboard.lesson.title') }} *</label>
                                                <select class="form-control @error('lesson_id') is-invalid @enderror"
                                                        id="lesson_id" name="lesson_id" required></select>
                                                @error('lesson_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="4">{{ old('description', $homework->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="due_date">Due Date</label>
                                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                                       id="due_date" name="due_date"
                                                       value="{{ old('due_date', $homework->due_date ? $homework->due_date->format('Y-m-d') : '') }}">
                                                @error('due_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="is_active">Status</label>
                                                <select class="form-control" id="is_active" name="is_active">
                                                    <option value="1" {{ old('is_active', $homework->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('is_active', $homework->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="icon-check2"></i> Update Homework
                                        </button>
                                        <a href="{{ route('homework.show', $homework) }}" class="btn btn-secondary">
                                            <i class="icon-cross"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Current Information</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Current Lesson:</strong></td>
                                        <td>{{ $homework->lesson->title }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Questions:</strong></td>
                                        <td>{{ $homework->questions->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Marks:</strong></td>
                                        <td>{{ $homework->total_marks }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Current Status:</strong></td>
                                        <td>
                                        <span class="badge badge-{{ $homework->is_active ? 'success' : 'danger' }}">
                                            {{ $homework->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        </td>
                                    </tr>
                                    @if($homework->due_date)
                                        <tr>
                                            <td><strong>Current Due Date:</strong></td>
                                            <td>{{ $homework->due_date->format('Y-m-d') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $homework->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td>{{ $homework->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>

                                @if($homework->description)
                                    <div class="mt-1">
                                        <strong>Current Description:</strong>
                                        <p class="text-muted">{{ $homework->description }}</p>
                                    </div>
                                @endif

                                <div class="mt-2">
                                    <a href="{{ route('homework.show', $homework) }}" class="btn btn-info btn-sm btn-block">
                                        <i class="icon-eye"></i> View Homework Details
                                    </a>
                                    @if($homework->questions->count() > 0)
                                        <a href="{{ route('homework.show', $homework) }}#questionsList" class="btn btn-success btn-sm btn-block">
                                            <i class="icon-list"></i> Manage Questions ({{ $homework->questions->count() }})
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    @include('dashboard.partials.lesson-cascade-script')
    <script>
        (function () {
            const cascadePrefill = @json($lessonCascadePrefill);

            window.initLessonCascade({
                stageSelector: '#stage_id',
                gradeSelector: '#grade_id',
                courseSelector: '#course_id',
                chapterSelector: '#chapter_id',
                lessonSelector: '#lesson_id',
                placeholder: '{{ __('dashboard.common.select') }}',
                routes: {
                    stages: '{{ route('api.stages') }}',
                    grades: '{{ route('api.stages.grades', ['stage' => '__stage__']) }}',
                    courses: '{{ route('api.courses.by-filters') }}',
                    chapters: '{{ route('api.courses.chapters', ['course' => '__course__']) }}',
                    lessons: '{{ route('api.chapters.lessons', ['chapter' => '__chapter__']) }}',
                },
                prefill: cascadePrefill
            });
        })();
    </script>
@endsection
