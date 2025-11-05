@extends('dashboard.layouts.master')

@php
    $lessonCascadePrefill = array_merge([
        'stage_id' => null,
        'grade_id' => null,
        'course_id' => null,
        'chapter_id' => null,
        'lesson_id' => null,
    ], $cascadePrefill ?? []);
    $selectedExamType = old('exam_type', $exam->lesson_id ? 'lesson' : 'teacher');
@endphp

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.exam.edit') }}: {{ $exam->title }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">{{ __('dashboard.exam.list') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('exams.show', $exam) }}">{{ $exam->title }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.common.edit') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('dashboard.exam.information') }}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <form action="{{ route('exams.update', $exam->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="lesson_id" id="hidden_lesson_id" value="{{ $exam->lesson_id }}">
                                        <input type="hidden" name="course_id" id="hidden_course_id" value="{{ $exam->course_id }}">

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="title">{{ __('dashboard.exam.fields.title') }} <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                               id="title" name="title" value="{{ old('title', $exam->title) }}" required>
                                                        @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="description">{{ __('dashboard.exam.fields.description') }}</label>
                                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                                  id="description" name="description" rows="1">{{ old('description', $exam->description) }}</textarea>
                                                        @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>{{ __('dashboard.exam.exam_for') }}</label>
                                                <div class="input-group">
                                                    <label class="display-inline-block custom-control custom-radio mr-1">
                                                        <input type="radio" name="exam_type" value="lesson" class="custom-control-input" {{ $selectedExamType === 'lesson' ? 'checked' : '' }}>
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description">{{ __('dashboard.exam.types.lesson') }}</span>
                                                    </label>
                                                    <label class="display-inline-block custom-control custom-radio">
                                                        <input type="radio" name="exam_type" value="teacher" class="custom-control-input" {{ $selectedExamType === 'teacher' ? 'checked' : '' }}>
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description">{{ __('dashboard.exam.types.teacher') }}</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <hr>

                                            <!-- Section for Teacher -->
                                            <div id="teacher-section" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="teacher_id">{{ __('dashboard.teacher.title') }}</label>
                                                            <select class="form-control" id="teacher_id" name="teacher_id" data-placeholder="{{ __('dashboard.common.select') }}">
                                                                <option value="">{{ __('dashboard.common.select') }}</option>
                                                                @foreach($teachers as $teacher)
                                                                    <option value="{{ $teacher->id }}" {{ $exam->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="teacher_course_id">{{ __('dashboard.course.title') }}</label>
                                                            <select class="form-control" id="teacher_course_id" data-placeholder="{{ __('dashboard.common.select') }}" disabled>
                                                                <option value="">{{ __('dashboard.common.select') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Section for Lesson -->
                                            <div id="lesson-section">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stage_id">{{ __('dashboard.stage.title') }} *</label>
                                                            <select class="form-control" id="stage_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="grade_id">{{ __('dashboard.grade.title') }} *</label>
                                                            <select class="form-control" id="grade_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="lesson_course_id">{{ __('dashboard.course.title') }} *</label>
                                                            <select class="form-control" id="lesson_course_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="chapter_id">{{ __('dashboard.chapter.title') }} *</label>
                                                            <select class="form-control" id="chapter_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="lesson_id">{{ __('dashboard.lesson.title') }} *</label>
                                                            <select class="form-control" id="lesson_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="duration_minutes">{{ __('dashboard.exam.fields.duration_minutes') }} <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                                               id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="1" required>
                                                        @error('duration_minutes')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="total_marks">{{ __('dashboard.exam.fields.total_marks') }} <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control @error('total_marks') is-invalid @enderror"
                                                               id="total_marks" name="total_marks" value="{{ old('total_marks', $exam->total_marks) }}" min="1" required>
                                                        @error('total_marks')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="start_date">{{ __('dashboard.exam.fields.start_date') }}</label>
                                                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror"
                                                               id="start_date" name="start_date" value="{{ old('start_date', $exam->start_date ? $exam->start_date->format('Y-m-d\\TH:i') : '') }}">
                                                        @error('start_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="end_date">{{ __('dashboard.exam.fields.end_date') }}</label>
                                                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                                               id="end_date" name="end_date" value="{{ old('end_date', $exam->end_date ? $exam->end_date->format('Y-m-d\\TH:i') : '') }}">
                                                        @error('end_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="pass_degree">{{ __('dashboard.exam.fields.pass_degree') }} <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control @error('pass_degree') is-invalid @enderror"
                                                               id="pass_degree" name="pass_degree" value="{{ $exam->pass_degree }}" min="1" required>
                                                        @error('pass_degree')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="display-inline-block custom-control custom-checkbox">
                                                    <input type="hidden" name="is_active" value="0">
                                                    <input type="checkbox" name="is_active" value="1" class="custom-control-input" {{ old('is_active', $exam->is_active) ? 'checked' : '' }}>
                                                    <span class="custom-control-indicator"></span>
                                                    <span class="custom-control-description">{{ __('dashboard.exam.fields.is_active') }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-success">
                                                <i class="icon-check2"></i> {{ __('dashboard.common.update') }}
                                            </button>
                                            <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                                                <i class="icon-cross2"></i> {{ __('dashboard.common.cancel') }}
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
    </div>
@endsection

@section('page_scripts')
    @include('dashboard.partials.lesson-cascade-script')
    <script>
        $(function () {
            const placeholder = '{{ __("dashboard.common.select") }}';
            const cascadePrefill = @json($lessonCascadePrefill);
            const examContext = {
                lesson_id: {{ $exam->lesson_id ?? 'null' }},
                teacher_id: {{ $exam->teacher_id ?? 'null' }},
                course_id: {{ $exam->course_id ?? 'null' }},
            };

            const toastError = function (message) {
                if (!message) {
                    return;
                }

                if (window.toastr && typeof window.toastr.error === 'function') {
                    window.toastr.error(message);
                } else {
                    console.error(message);
                    if (window.alert) {
                        window.alert(message);
                    }
                }
            };

            const resetTeacherCourses = function () {
                const $courseSelect = $('#teacher_course_id');
                $courseSelect
                    .empty()
                    .append(`<option value="">${placeholder}</option>`)
                    .prop('disabled', true);
                $('#hidden_course_id').val('');
            };

            const loadTeacherCourses = function (teacherId, selectedCourseId) {
                if (!teacherId) {
                    resetTeacherCourses();
                    return;
                }

                const $courseSelect = $('#teacher_course_id');
                $courseSelect.prop('disabled', true);

                $.get(`/api/teachers/${teacherId}/courses`)
                    .done(function (data) {
                        $courseSelect
                            .empty()
                            .append(`<option value="">${placeholder}</option>`);

                        if (Array.isArray(data) && data.length) {
                            data.forEach(function (course) {
                                const isSelected = selectedCourseId && Number(selectedCourseId) === Number(course.id)
                                    ? ' selected'
                                    : '';
                                $courseSelect.append(`<option value="${course.id}"${isSelected}>${course.name}</option>`);
                            });
                        }

                        $courseSelect.prop('disabled', false);

                        if (selectedCourseId) {
                            $('#hidden_course_id').val(selectedCourseId);
                        }
                    })
                    .fail(function () {
                        toastError('Failed to load courses for the selected teacher.');
                    });
            };

            window.initLessonCascade({
                stageSelector: '#stage_id',
                gradeSelector: '#grade_id',
                courseSelector: '#lesson_course_id',
                chapterSelector: '#chapter_id',
                lessonSelector: '#lesson_id',
                placeholder: placeholder,
                routes: {
                    stages: '{{ route('api.stages') }}',
                    grades: '{{ route('api.stages.grades', ['stage' => '__stage__']) }}',
                    courses: '{{ route('api.courses.by-filters') }}',
                    chapters: '{{ route('api.courses.chapters', ['course' => '__course__']) }}',
                    lessons: '{{ route('api.chapters.lessons', ['chapter' => '__chapter__']) }}',
                },
                prefill: cascadePrefill,
                onLessonChange: function (lessonId) {
                    $('#hidden_lesson_id').val(lessonId || '');
                }
            });

            const toggleExamType = function (type) {
                $('#teacher-section').toggle(type === 'teacher');
                $('#lesson-section').toggle(type === 'lesson');

                if (type === 'teacher') {
                    $('#hidden_lesson_id').val('');
                } else {
                    resetTeacherCourses();
                }
            };

            $('input[name="exam_type"]').on('change', function () {
                toggleExamType($(this).val());
            });

            toggleExamType('{{ $selectedExamType }}');

            if (examContext.teacher_id) {
                $('#teacher_id').val(examContext.teacher_id);
                loadTeacherCourses(examContext.teacher_id, examContext.course_id);
            } else {
                resetTeacherCourses();
            }

            $('#teacher_id').on('change', function () {
                const teacherId = $(this).val();
                resetTeacherCourses();
                loadTeacherCourses(teacherId, null);
            });

            $('#teacher_course_id').on('change', function () {
                $('#hidden_course_id').val($(this).val() || '');
            });
        });
    </script>
@endsection
