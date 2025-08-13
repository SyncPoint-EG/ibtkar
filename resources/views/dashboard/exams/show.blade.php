{{-- resources/views/dashboard/exams/show.blade.php --}}

@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ $exam->title }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">{{ __('Exams') }}</a></li>
                            <li class="breadcrumb-item active">{{ $exam->title }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row">
                    <!-- Exam Details -->
                    <div class="col-md-8 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Exam Details') }}</h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a href="{{ route('exams.edit', $exam) }}" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i> {{ __('Edit Exam') }}
                                            </a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-body collapse in">
                                <div class="row p-1">
                                    <div class="col-md-6">
                                        <p><strong>{{ __('Lesson:') }}</strong> {{ $exam->lesson->title ?? 'N/A' }}</p>
                                        <p><strong>{{ __('Duration:') }}</strong> {{ $exam->duration_minutes }} {{ __('minutes') }}</p>
                                        <p id="total-marks-display"><strong>{{ __('Total Marks:') }}</strong> {{ $exam->total_marks }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>{{ __('Status:') }}</strong>
                                            @if($exam->is_active)
                                                <span class="tag tag-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="tag tag-danger">{{ __('Inactive') }}</span>
                                            @endif
                                        </p>
                                        <p><strong>{{ __('Questions:') }}</strong> {{ $exam->questions->count() }}</p>
                                        <p><strong>{{ __('Created:') }}</strong> {{ $exam->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>

                                @if($exam->description)
                                    <div class="mt-1 p-1">
                                        <strong>{{ __('Description:') }}</strong>
                                        <p class="text-muted">{{ $exam->description }}</p>
                                    </div>
                                @endif

                                @if($exam->start_date || $exam->end_date)
                                    <div class="mt-1 mx-1">
                                        @if($exam->start_date)
                                            <p><strong>{{ __('Start Date:') }}</strong> {{ $exam->start_date->format('Y-m-d H:i') }}</p>
                                        @endif
                                        @if($exam->end_date)
                                            <p><strong>{{ __('End Date:') }}</strong> {{ $exam->end_date->format('Y-m-d H:i') }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Questions Section -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Questions') }} (<span id="questions-count">{{ $exam->questions->count() }}</span>)</h4>
                            </div>

                            <div class="card-body collapse in mx-1">
                                <div id="questions-container">
                                    @if($exam->questions->count() > 0)
                                        @foreach($exam->questions as $index => $question)
                                            <div class="card mb-2 question-item" data-question-id="{{ $question->id }}">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-subtitle mb-1">
                                                                Question {{ $index + 1 }}
                                                                <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $question->question_type)) }}</span>
                                                                <span class="badge badge-secondary">{{ $question->marks }} {{ $question->marks == 1 ? 'Mark' : 'Marks' }}</span>
                                                            </h6>
                                                            <p class="card-text">{{ $question->question_text }}</p>
                                                            @if($question->image)
                                                                <div class="mt-2">
                                                                    <img src="{{ $question->image }}" width="100px">
                                                                </div>
                                                            @endif
                                                            @if($question->question_type !== 'essay' && $question->options)
                                                                <div class="mt-2">
                                                                    <strong>Options:</strong>
                                                                    <ul class="list-unstyled ml-2">
                                                                        @foreach($question->options as $option)
                                                                            <li class="mb-1">
                                                                                <span class="badge badge-{{ $option->is_correct ? 'success' : 'light' }}">
                                                                                    {{ $option->is_correct ? '✓' : '○' }}
                                                                                </span>
                                                                                {{ $option->option_text }}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                            @if($question->question_type === 'essay' && $question->correct_essay_answer)
                                                                <div class="mt-2">
                                                                    <strong>{{ __('Correct Answer:') }}</strong>
                                                                    <p class="text-muted">{{ $question->correct_essay_answer }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-2">
                                                            <div class="btn-group-vertical">
                                                                <button type="button" class="btn btn-warning btn-sm edit-question" data-question-id="{{ $question->id }}">
                                                                    <i class="icon-pencil"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-danger btn-sm delete-question" data-question-id="{{ $question->id }}">
                                                                    <i class="icon-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-3" id="no-questions-message">
                                            <i class="icon-help font-large-2 text-muted"></i>
                                            <h4 class="mt-1">{{ __('No questions added yet') }}</h4>
                                            <p class="text-muted">{{ __('Start by adding your first question') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Question Form -->
                    <div class="col-md-4 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Add New Question') }}</h4>
                            </div>

                            <div class="card-body collapse in mx-1">
                                <form action="{{ route('exams.add-question', $exam) }}" method="POST" id="questionForm" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label for="question_text">{{ __('Question Text') }} <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('question_text') is-invalid @enderror"
                                                  id="question_text" name="question_text" rows="3" required>{{ old('question_text') }}</textarea>
                                        @error('question_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="question_type">{{ __('Question Type') }} <span class="text-danger">*</span></label>
                                        <select class="form-control @error('question_type') is-invalid @enderror"
                                                id="question_type" name="question_type" required>
                                            <option value="">{{ __('Select Type') }}</option>
                                            <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>{{ __('True/False') }}</option>
                                            <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>{{ __('Multiple Choice') }}</option>
                                            <option value="essay" {{ old('question_type') == 'essay' ? 'selected' : '' }}>{{ __('Essay') }}</option>
                                        </select>
                                        @error('question_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="marks">{{ __('Marks') }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('marks') is-invalid @enderror"
                                               id="marks" name="marks" value="{{ old('marks', 1) }}" min="1" required>
                                        @error('marks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="image">{{ __('Question Image') }}</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                               id="image" name="image">
                                        @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- True/False Options -->
                                    <div id="trueFalseOptions" style="display: none;">
                                        <div class="form-group">
                                            <label>{{ __('Correct Answer') }} <span class="text-danger">*</span></label>
                                            <div class="radio">
                                                <input type="radio" id="true_answer" name="true_false_answer" value="1">
                                                <label for="true_answer">{{ __('True') }}</label>
                                            </div>
                                            <div class="radio">
                                                <input type="radio" id="false_answer" name="true_false_answer" value="0">
                                                <label for="false_answer">{{ __('False') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Multiple Choice Options -->
                                    <div id="multipleChoiceOptions" style="display: none;">
                                        <label>{{ __('Options') }} <span class="text-danger">*</span></label>
                                        <div id="optionsContainer">
                                            <div class="form-group option-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <input type="radio" name="correct_answer" value="0">
                                                    </span>
                                                    <input type="text" class="form-control" name="options[]" placeholder="{{ __('Option 1') }}">
                                                </div>
                                            </div>
                                            <div class="form-group option-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <input type="radio" name="correct_answer" value="1">
                                                    </span>
                                                    <input type="text" class="form-control" name="options[]" placeholder="{{ __('Option 2') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-secondary" id="addOption">
                                            <i class="icon-plus"></i> {{ __('Add Option') }}
                                        </button>
                                    </div>

                                    <!-- Essay Correct Answer -->
                                    <div id="essayCorrectAnswer" style="display: none;">
                                        <div class="form-group">
                                            <label for="correct_essay_answer">{{ __('Correct Answer') }}</label>
                                            <textarea class="form-control @error('correct_essay_answer') is-invalid @enderror"
                                                      id="correct_essay_answer" name="correct_essay_answer" rows="4"
                                                      placeholder="{{ __('Enter the correct answer for this essay question (optional)') }}">{{ old('correct_essay_answer') }}</textarea>
                                            @error('correct_essay_answer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-actions mt-2">
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="icon-plus"></i> {{ __('Add Question') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Question Modal -->
    <div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('Edit Question') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editQuestionForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Form fields will be populated via JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update Question') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('page_scripts')
    <script>
        $(function () {
            // ==== Question type change visibility ====
            $('#question_type').on('change', function () {
                $('#trueFalseOptions, #multipleChoiceOptions, #essayCorrectAnswer').hide();
                if (this.value === 'true_false') {
                    $('#trueFalseOptions').show();
                } else if (this.value === 'multiple_choice') {
                    $('#multipleChoiceOptions').show();
                } else if (this.value === 'essay') {
                    $('#essayCorrectAnswer').show();
                }
            }).trigger('change');

            // ==== Add option in create form ====
            $('#addOption').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let optionCount = $('#optionsContainer .option-group').length;
                let newOption = `
                    <div class="form-group option-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <input type="radio" name="correct_answer" value="${optionCount}">
                            </span>
                            <input type="text" class="form-control" name="options[]" placeholder="{{ __('Option') }} ${optionCount + 1}">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-danger remove-option"><i class="icon-trash"></i></button>
                            </span>
                        </div>
                    </div>
                `;
                $('#optionsContainer').append(newOption);
            });

            // ==== Remove option ====
            $(document).on('click', '.remove-option', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).closest('.option-group').remove();
                updateOptionValues($('#optionsContainer'));
            });

            function updateOptionValues(container) {
                container.find('.option-group').each(function (i) {
                    $(this).find('input[type="radio"]').val(i);
                    $(this).find('input[type="text"]').attr('placeholder', `{{ __('Option') }} ${i + 1}`);
                });
            }

            // ==== Delete question ====
            $(document).on('click', '.delete-question', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let btn = $(this);
                let questionId = btn.data('question-id');
                let examId = {{ $exam->id }};

                if (!confirm('{{ __("Are you sure you want to delete this question?") }}')) {
                    return;
                }

                // Disable button to prevent multiple clicks
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: `/exams/${examId}/questions/${questionId}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            // Remove question from DOM
                            $(`[data-question-id="${questionId}"]`).remove();

                            // Update counts and UI
                            updateQuestionsCount();
                            renumberQuestions();

                            // Update total marks display
                            if (res.total_marks !== undefined) {
                                $('#total-marks-display').html(`<strong>{{ __('Total Marks:') }}</strong> ${res.total_marks}`);
                            }

                            showAlert('success', res.message || 'Question deleted successfully');
                        } else {
                            showAlert('danger', res.message || 'Failed to delete question');
                            // Re-enable button on failure
                            btn.prop('disabled', false).html('<i class="icon-trash"></i>');
                        }
                    },
                    error: function (xhr) {
                        console.error('Delete Error:', xhr.responseText);
                        showAlert('danger', '{{ __("An error occurred while deleting the question") }}');
                        // Re-enable button on error
                        btn.prop('disabled', false).html('<i class="icon-trash"></i>');
                    }
                });
            });

            // ==== Edit question ====
            $(document).on('click', '.edit-question', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let questionId = $(this).data('question-id');
                let examId = {{ $exam->id }};

                $.ajax({
                    url: `/exams/${examId}/questions/${questionId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            populateEditModal(res.question);
                            $('#editQuestionModal').modal('show');
                        } else {
                            showAlert('danger', res.message);
                        }
                    },
                    error: function (xhr) {
                        console.error('Edit Error:', xhr.responseText);
                        showAlert('danger', '{{ __("An error occurred while fetching question data") }}');
                    }
                });
            });

            // ==== Submit edit form ====
            $('#editQuestionForm').on('submit', function (e) {
                e.preventDefault();

                let form = $(this);
                let formData = new FormData(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.success) {
                            $('#editQuestionModal').modal('hide');
                            updateQuestionInDOM(res.question);

                            if (res.total_marks !== undefined) {
                                $('#total-marks-display').html(`<strong>{{ __('Total Marks:') }}</strong> ${res.total_marks}`);
                            }

                            showAlert('success', res.message);
                        } else {
                            showAlert('danger', res.message);
                        }
                    },
                    error: function (xhr) {
                        console.error('Update Error:', xhr.responseText);
                        showAlert('danger', '{{ __("An error occurred while updating the question") }}');
                    }
                });
            });

            // ==== Helper Functions ====

            function updateQuestionsCount() {
                let count = $('.question-item').length;
                $('#questions-count').text(count);

                if (count === 0) {
                    $('#questions-container').html(`
                        <div class="text-center py-3" id="no-questions-message">
                            <i class="icon-help font-large-2 text-muted"></i>
                            <h4 class="mt-1">{{ __('No questions added yet') }}</h4>
                            <p class="text-muted">{{ __('Start by adding your first question') }}</p>
                        </div>
                    `);
                }
            }

            function renumberQuestions() {
                $('.question-item').each(function (i) {
                    let subtitle = $(this).find('.card-subtitle');
                    let currentHtml = subtitle.html();
                    let newHtml = currentHtml.replace(/Question \d+/, `Question ${i + 1}`);
                    subtitle.html(newHtml);
                });
            }

            function updateQuestionInDOM(question) {
                let questionElement = $(`[data-question-id="${question.id}"]`);
                if (questionElement.length) {
                    let index = $('.question-item').index(questionElement);
                    let newHtml = createQuestionHtml(question, index);
                    questionElement.replaceWith(newHtml);
                }
            }

            function createQuestionHtml(question, index) {
                let optionsHtml = '';
                if (question.question_type !== 'essay' && question.options && question.options.length) {
                    optionsHtml = `
                        <div class="mt-2">
                            <strong>Options:</strong>
                            <ul class="list-unstyled ml-2">
                                ${question.options.map(opt => `
                                    <li class="mb-1">
                                        <span class="badge badge-${opt.is_correct ? 'success' : 'light'}">
                                            ${opt.is_correct ? '✓' : '○'}
                                        </span> ${opt.option_text}
                                    </li>`).join('')}
                            </ul>
                        </div>`;
                }

                let imgHtml = question.image ? `<div class="mt-2"><img src="${question.image}" width="100px"></div>` : '';
                let essayAns = question.question_type === 'essay' && question.correct_essay_answer ?
                    `<div class="mt-2"><strong>{{ __('Correct Answer:') }}</strong><p class="text-muted">${question.correct_essay_answer}</p></div>` : '';

                return `
                    <div class="card mb-2 question-item" data-question-id="${question.id}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="card-subtitle mb-1">
                                        Question ${index + 1}
                                        <span class="badge badge-info">${question.question_type.replace('_', ' ')}</span>
                                        <span class="badge badge-secondary">${question.marks} ${question.marks == 1 ? 'Mark' : 'Marks'}</span>
                                    </h6>
                                    <p class="card-text">${question.question_text}</p>
                                    ${imgHtml}${optionsHtml}${essayAns}
                                </div>
                                <div class="ml-2">
                                    <div class="btn-group-vertical">
                                        <button type="button" class="btn btn-warning btn-sm edit-question" data-question-id="${question.id}">
                                            <i class="icon-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-question" data-question-id="${question.id}">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
            }

            function populateEditModal(question) {
                const form = $('#editQuestionForm');
                form.attr('action', `/exams/{{ $exam->id }}/questions/${question.id}`);

                let optionsHtml = '';
                let correctAnswerSection = '';

                // Generate form fields based on question type
                if (question.question_type === 'multiple_choice') {
                    optionsHtml = `
                        <div class="form-group">
                            <label>{{ __('Options') }} <span class="text-danger">*</span></label>
                            <div id="editOptionsContainer">`;

                    if (question.options) {
                        question.options.forEach((option, index) => {
                            optionsHtml += `
                                <div class="form-group option-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <input type="radio" name="correct_answer" value="${index}" ${option.is_correct ? 'checked' : ''}>
                                        </span>
                                        <input type="text" class="form-control" name="options[]" value="${option.option_text}" placeholder="{{ __('Option') }} ${index + 1}">
                                        ${index > 1 ? `<span class="input-group-btn"><button type="button" class="btn btn-danger remove-edit-option"><i class="icon-trash"></i></button></span>` : ''}
                                    </div>
                                </div>`;
                        });
                    }

                    optionsHtml += `
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" id="editAddOption">
                                <i class="icon-plus"></i> {{ __('Add Option') }}
                    </button>
                </div>`;
                } else if (question.question_type === 'true_false') {
                    const trueCorrect = question.options && question.options.find(opt => opt.option_text === 'True' && opt.is_correct);
                    correctAnswerSection = `
                        <div class="form-group">
                            <label>{{ __('Correct Answer') }} <span class="text-danger">*</span></label>
                            <div class="radio">
                                <input type="radio" name="true_false_answer" value="1" ${trueCorrect ? 'checked' : ''}>
                                <label>{{ __('True') }}</label>
                            </div>
                            <div class="radio">
                                <input type="radio" name="true_false_answer" value="0" ${!trueCorrect ? 'checked' : ''}>
                                <label>{{ __('False') }}</label>
                            </div>
                        </div>`;
                } else if (question.question_type === 'essay') {
                    correctAnswerSection = `
                        <div class="form-group">
                            <label>{{ __('Correct Answer') }}</label>
                            <textarea class="form-control" name="correct_essay_answer" rows="4" placeholder="{{ __('Enter the correct answer for this essay question (optional)') }}">${question.correct_essay_answer || ''}</textarea>
                        </div>`;
                }

                const modalBody = form.find('.modal-body');
                modalBody.html(`
                    <div class="form-group">
                        <label>{{ __('Question Text') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="question_text" rows="3" required>${question.question_text}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Question Type') }} <span class="text-danger">*</span></label>
                        <select class="form-control" name="question_type" required>
                            <option value="true_false" ${question.question_type === 'true_false' ? 'selected' : ''}>{{ __('True/False') }}</option>
                            <option value="multiple_choice" ${question.question_type === 'multiple_choice' ? 'selected' : ''}>{{ __('Multiple Choice') }}</option>
                            <option value="essay" ${question.question_type === 'essay' ? 'selected' : ''}>{{ __('Essay') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Marks') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="marks" value="${question.marks}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Question Image') }}</label>
                        <input type="file" class="form-control" name="image">
                        ${question.image ? `<small class="text-muted">{{ __('Current image will be replaced if new image is uploaded') }}</small>` : ''}
                    </div>
                    ${optionsHtml}
                    ${correctAnswerSection}
                `);

                // Add event handlers for edit modal
                $('#editAddOption').off('click').on('click', function() {
                    const container = $('#editOptionsContainer');
                    const optionCount = container.find('.option-group').length;
                    const newOption = `
                        <div class="form-group option-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <input type="radio" name="correct_answer" value="${optionCount}">
                                </span>
                                <input type="text" class="form-control" name="options[]" placeholder="{{ __('Option') }} ${optionCount + 1}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-danger remove-edit-option"><i class="icon-trash"></i></button>
                                </span>
                            </div>
                        </div>`;
                    container.append(newOption);
                });

                $(document).off('click', '.remove-edit-option').on('click', '.remove-edit-option', function() {
                    $(this).closest('.option-group').remove();
                    // Update radio values
                    $('#editOptionsContainer .option-group').each(function(i) {
                        $(this).find('input[type="radio"]').val(i);
                        $(this).find('input[type="text"]').attr('placeholder', `{{ __('Option') }} ${i + 1}`);
                    });
                });
            }

            function showAlert(type, message) {
                // Remove existing alerts
                $('.alert').remove();

                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        ${message}
                    </div>`;

                $('.content-body').prepend(alertHtml);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    $('.alert').fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        });
    </script>

@endsection
