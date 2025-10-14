@extends('dashboard.layouts.master')

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
                                                        <input type="radio" name="exam_type" value="lesson" class="custom-control-input" {{ $exam->lesson_id ? 'checked' : '' }}>
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description">{{ __('dashboard.exam.types.lesson') }}</span>
                                                    </label>
                                                    <label class="display-inline-block custom-control custom-radio">
                                                        <input type="radio" name="exam_type" value="teacher" class="custom-control-input" {{ $exam->course_id ? 'checked' : '' }}>
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
                                                            <select class="form-control" id="teacher_course_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Section for Lesson -->
                                            <div id="lesson-section">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="stage_id">{{ __('dashboard.stage.title') }}</label>
                                                            <select class="form-control" id="stage_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="grade_id">{{ __('dashboard.grade.title') }}</label>
                                                            <select class="form-control" id="grade_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="division_id">{{ __('dashboard.division.title') }}</label>
                                                            <select class="form-control" id="division_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="lesson_course_id">{{ __('dashboard.course.title') }}</label>
                                                            <select class="form-control" id="lesson_course_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="chapter_id">{{ __('dashboard.chapter.title') }}</label>
                                                            <select class="form-control" id="chapter_id" data-placeholder="{{ __('dashboard.common.select') }}"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="lesson_id">{{ __('dashboard.lesson.title') }}</label>
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
<script>
    $(document).ready(function () {
        const exam = @json($exam);
        const placeholder = '{{ __("dashboard.common.select") }}';

        // Helper to reset and fill a select box
        function populateSelect(selector, data, selectedId = null) {
            const select = $(selector);
            select.empty().append(`<option value="">${placeholder}</option>`);
            $.each(data, function (index, item) {
                const isSelected = item.id == selectedId ? ' selected' : '';
                select.append(`<option value="${item.id}"${isSelected}>${item.name || (item.first_name + ' ' + item.last_name)}</option>`);
            });
            select.prop('disabled', false);
        }

        function resetSelect(selector) {
            $(selector).empty().append(`<option value="">${placeholder}</option>`).prop('disabled', true);
        }

        function initPage() {
            const examType = $('input[name="exam_type"]:checked').val();
            $('#teacher-section').toggle(examType === 'teacher');
            $('#lesson-section').toggle(examType === 'lesson');

            if (examType === 'teacher') {
                if (exam.teacher_id) {
                    $.get(`/api/teachers/${exam.teacher_id}/courses`, function (data) {
                        populateSelect('#teacher_course_id', data, exam.course_id);
                    });
                }
            } else if (examType === 'lesson' && exam.lesson) {
                const course = exam.lesson.chapter.course;
                const stageId = course.stage.id;
                const gradeId = course.grade.id;
                const divisionId = course.division.id;
                const courseId = course.id;
                const chapterId = exam.lesson.chapter.id;
                const lessonId = exam.lesson.id;

                // Chain of calls to populate and select everything
                $.get('{{ route("api.stages") }}', function (stages) {
                    populateSelect('#stage_id', stages, stageId);
                    $.get(`/api/stages/${stageId}/grades`, function (grades) {
                        populateSelect('#grade_id', grades, gradeId);
                        $.get(`/api/grades/${stageId}/${gradeId}/divisions`, function (divisions) {
                            populateSelect('#division_id', divisions, divisionId);
                            $.get('{{ route("api.courses.by-filters") }}', { stage_id: stageId, grade_id: gradeId, division_id: divisionId }, function (courses) {
                                populateSelect('#lesson_course_id', courses, courseId);
                                $.get(`/api/courses/${courseId}/chapters`, function (chapters) {
                                    populateSelect('#chapter_id', chapters, chapterId);
                                    $.get(`/api/chapters/${chapterId}/lessons`, function (lessons) {
                                        populateSelect('#lesson_id', lessons, lessonId);
                                    });
                                });
                            });
                        });
                    });
                });
            } else {
                 // Fallback for lesson type without pre-loaded data
                 $.get('{{ route("api.stages") }}', function (data) {
                    populateSelect('#stage_id', data);
                });
            }
        }

        // --- Event Handlers ---
        $('input[name="exam_type"]').change(function () {
            const selectedType = $(this).val();
            $('#teacher-section').toggle(selectedType === 'teacher');
            $('#lesson-section').toggle(selectedType === 'lesson');
            $('#hidden_course_id').val('');
            $('#hidden_lesson_id').val('');
        });

        $('#teacher_id').change(function () {
            const teacherId = $(this).val();
            resetSelect('#teacher_course_id');
            $('#hidden_course_id').val('');
            if (teacherId) {
                $.get(`/api/teachers/${teacherId}/courses`, function (data) {
                    populateSelect('#teacher_course_id', data);
                });
            }
        });

        $('#teacher_course_id').change(function () {
            $('#hidden_course_id').val($(this).val());
        });

        $('#stage_id, #grade_id, #division_id').change(function() {
            const stageId = $('#stage_id').val();
            const gradeId = $('#grade_id').val();
            const divisionId = $('#division_id').val();

            if ($(this).is('#stage_id')) {
                resetSelect('#grade_id');
                resetSelect('#division_id');
                resetSelect('#lesson_course_id');
                resetSelect('#chapter_id');
                resetSelect('#lesson_id');
                if(stageId) $.get(`/api/stages/${stageId}/grades`, data => populateSelect('#grade_id', data));
            } else if ($(this).is('#grade_id')) {
                resetSelect('#division_id');
                resetSelect('#lesson_course_id');
                resetSelect('#chapter_id');
                resetSelect('#lesson_id');
                if(gradeId) $.get(`/api/grades/${stageId}/${gradeId}/divisions`, data => populateSelect('#division_id', data));
            }

            if (stageId && gradeId && divisionId) {
                 $.get('{{ route("api.courses.by-filters") }}', { stage_id: stageId, grade_id: gradeId, division_id: divisionId }, function (data) {
                    populateSelect('#lesson_course_id', data);
                });
            }
        });

        $('#lesson_course_id').change(function () {
            const courseId = $(this).val();
            resetSelect('#chapter_id');
            resetSelect('#lesson_id');
            if (courseId) $.get(`/api/courses/${courseId}/chapters`, data => populateSelect('#chapter_id', data));
        });

        $('#chapter_id').change(function () {
            const chapterId = $(this).val();
            resetSelect('#lesson_id');
            if (chapterId) $.get(`/api/chapters/${chapterId}/lessons`, data => populateSelect('#lesson_id', data));
        });

        $('#lesson_id').change(function () {
            $('#hidden_lesson_id').val($(this).val());
        });

        // Initialize the page
        initPage();
    });
</script>
@endsection
