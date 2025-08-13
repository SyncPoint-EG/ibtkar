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
                                        <p><strong>{{ __('Total Marks:') }}</strong> {{ $exam->total_marks }}</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionType = document.getElementById('question_type');
            const trueFalseOptions = document.getElementById('trueFalseOptions');
            const multipleChoiceOptions = document.getElementById('multipleChoiceOptions');
            const essayCorrectAnswer = document.getElementById('essayCorrectAnswer');
            const optionsContainer = document.getElementById('optionsContainer');
            const addOptionBtn = document.getElementById('addOption');

            // Handle question type change
            questionType.addEventListener('change', function() {
                trueFalseOptions.style.display = 'none';
                multipleChoiceOptions.style.display = 'none';
                essayCorrectAnswer.style.display = 'none';

                if (this.value === 'true_false') {
                    trueFalseOptions.style.display = 'block';
                } else if (this.value === 'multiple_choice') {
                    multipleChoiceOptions.style.display = 'block';
                } else if (this.value === 'essay') {
                    essayCorrectAnswer.style.display = 'block';
                }
            });

            // Add option functionality
            addOptionBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const optionCount = optionsContainer.children.length;
                const newOption = document.createElement('div');
                newOption.className = 'form-group option-group';
                newOption.innerHTML = `
            <div class="input-group">
                <span class="input-group-addon">
                    <input type="radio" name="correct_answer" value="${optionCount}">
                </span>
                <input type="text" class="form-control" name="options[]" placeholder="{{ __('Option') }} ${optionCount + 1}">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger remove-option">
                        <i class="icon-trash"></i>
                    </button>
                </span>
            </div>
        `;
                optionsContainer.appendChild(newOption);

                newOption.querySelector('.remove-option').addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    newOption.remove();
                    updateOptionValues();
                });
            });

            function updateOptionValues() {
                const options = optionsContainer.querySelectorAll('.option-group');
                options.forEach((option, index) => {
                    const radio = option.querySelector('input[type="radio"]');
                    const input = option.querySelector('input[type="text"]');
                    radio.value = index;
                    input.placeholder = `{{ __('Option') }} ${index + 1}`;
                });
            }

            // FIXED: Delete and Edit question functionality with proper event handling
            document.addEventListener('click', function(e) {
                // Prevent multiple event handling
                e.stopPropagation();

                // Handle delete button clicks
                if (e.target.classList.contains('delete-question') ||
                    (e.target.parentElement && e.target.parentElement.classList.contains('delete-question'))) {

                    e.preventDefault();

                    const button = e.target.classList.contains('delete-question') ? e.target : e.target.parentElement;
                    const questionId = button.getAttribute('data-question-id');

                    if (questionId && confirm('{{ __("Are you sure you want to delete this question?") }}')) {
                        // Disable the button to prevent multiple clicks
                        button.disabled = true;
                        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                        deleteQuestion(questionId, button);
                    }
                    return false;
                }

                // Handle edit button clicks
                if (e.target.classList.contains('edit-question') ||
                    (e.target.parentElement && e.target.parentElement.classList.contains('edit-question'))) {

                    e.preventDefault();

                    const button = e.target.classList.contains('edit-question') ? e.target : e.target.parentElement;
                    const questionId = button.getAttribute('data-question-id');

                    if (questionId) {
                        editQuestion(questionId);
                    }
                    return false;
                }
            });

            // FIXED: Delete function with proper error handling
            function deleteQuestion(questionId, button) {
                const examId = {{ $exam->id }};

                fetch(`/exams/${examId}/questions/${questionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Remove question from DOM
                            const questionElement = document.querySelector(`[data-question-id="${questionId}"]`);
                            if (questionElement) {
                                questionElement.remove();
                            }

                            // Update questions count
                            updateQuestionsCount();

                            // Renumber questions
                            renumberQuestions();

                            // Show success message
                            showAlert('success', data.message || 'Question deleted successfully');

                            // Update total marks in the display
                            if (data.total_marks !== undefined) {
                                const totalMarksElement = document.querySelector('p:contains("Total Marks:")');
                                if (totalMarksElement) {
                                    totalMarksElement.innerHTML = `<strong>{{ __('Total Marks:') }}</strong> ${data.total_marks}`;
                                }
                            }
                        } else {
                            showAlert('danger', data.message || 'Failed to delete question');
                            // Re-enable button on failure
                            button.disabled = false;
                            button.innerHTML = '<i class="icon-trash"></i>';
                        }
                    })
                    .catch(error => {
                        console.error('Delete Error:', error);
                        showAlert('danger', 'An error occurred while deleting the question');
                        // Re-enable button on error
                        button.disabled = false;
                        button.innerHTML = '<i class="icon-trash"></i>';
                    });
            }

            // Rest of your functions remain the same...
            function editQuestion(questionId) {
                const examId = {{ $exam->id }};

                fetch(`/exams/${examId}/questions/${questionId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            populateEditModal(data.question);
                            $('#editQuestionModal').modal('show');
                        } else {
                            showAlert('danger', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('danger', '{{ __("An error occurred while fetching question data") }}');
                    });
            }

            // Your other functions remain the same...
            function updateQuestionsCount() {
                const count = document.querySelectorAll('.question-item').length;
                document.getElementById('questions-count').textContent = count;

                if (count === 0) {
                    document.getElementById('questions-container').innerHTML = `
                <div class="text-center py-3" id="no-questions-message">
                    <i class="icon-help font-large-2 text-muted"></i>
                    <h4 class="mt-1">{{ __('No questions added yet') }}</h4>
                    <p class="text-muted">{{ __('Start by adding your first question') }}</p>
                </div>
            `;
                }
            }

            function renumberQuestions() {
                const questions = document.querySelectorAll('.question-item');
                questions.forEach((question, index) => {
                    const questionTitle = question.querySelector('.card-subtitle');
                    if (questionTitle) {
                        const titleText = questionTitle.innerHTML;
                        const newTitleText = titleText.replace(/Question \d+/, `Question ${index + 1}`);
                        questionTitle.innerHTML = newTitleText;
                    }
                });
            }

            function showAlert(type, message) {
                // Remove existing alerts first
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());

                const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                ${message}
            </div>
        `;

                const contentBody = document.querySelector('.content-body');
                contentBody.insertAdjacentHTML('afterbegin', alertHtml);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    const alert = contentBody.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }

            // Trigger change event on page load
            questionType.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
