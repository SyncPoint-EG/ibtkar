@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ $centerExam->title }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('center-exams.index') }}">Center Exams</a></li>
                            <li class="breadcrumb-item active">{{ $centerExam->title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <div class="card p-1">
                            <div class="card-header">
                                <h4 class="card-title">Center Exam Information</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Center:</strong></td>
                                        <td>{{ $centerExam->center->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stage:</strong></td>
                                        <td>{{ $centerExam->stage->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Grade:</strong></td>
                                        <td>{{ $centerExam->grade->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Division:</strong></td>
                                        <td>{{ $centerExam->division->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Marks:</strong></td>
                                        <td id="totalMarks">{{ $centerExam->total_marks }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Passing Marks:</strong></td>
                                        <td>{{ $centerExam->passing_marks }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Duration:</strong></td>
                                        <td>{{ $centerExam->duration_minutes }} minutes</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Start Time:</strong></td>
                                        <td>{{ $centerExam->start_time ? $centerExam->start_time->format('Y-m-d H:i') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>End Time:</strong></td>
                                        <td>{{ $centerExam->end_time ? $centerExam->end_time->format('Y-m-d H:i') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                        <span class="badge badge-{{ $centerExam->is_active ? 'success' : 'danger' }}">
                                            {{ $centerExam->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created At:</strong></td>
                                        <td>{{ $centerExam->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td>{{ $centerExam->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>

                                @if($centerExam->description)
                                    <div class="mt-1">
                                        <strong>Description:</strong>
                                        <p class="text-muted">{{ $centerExam->description }}</p>
                                    </div>
                                @endif

                                <div class="mt-2">
                                    <a href="{{ route('center-exams.edit', $centerExam) }}" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i> Edit Center Exam
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <!-- Add Question Form -->
                        <div class="card mb-2">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="icon-plus"></i> Add New Question
                                    <button type="button" class="btn btn-sm btn-primary float-right" id="toggleQuestionForm">
                                        <i class="icon-plus"></i> Add Question
                                    </button>
                                </h4>
                            </div>
                            <div class="card-body px-1" id="questionFormContainer" style="display: none;">
                                <form id="questionForm" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label for="question_text">Question Text *</label>
                                        <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
                                        <div class="invalid-feedback" id="question_text_error"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="question_type">Question Type *</label>
                                                <select class="form-control" id="question_type" name="question_type" required>
                                                    <option value="">Select Type</option>
                                                    <option value="true_false">True/False</option>
                                                    <option value="multiple_choice">Multiple Choice</option>
                                                    <option value="essay">Essay</option>
                                                </select>
                                                <div class="invalid-feedback" id="question_type_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="marks">Marks *</label>
                                                <input type="number" class="form-control" id="marks" name="marks" value="1" min="1" required>
                                                @error('marks')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Question Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        <div class="invalid-feedback" id="image_error"></div>
                                    </div>

                                    <!-- True/False Options -->
                                    <div id="trueFalseOptions" style="display: none;">
                                        <div class="form-group">
                                            <label>Correct Answer *</label>
                                            <div class="radio">
                                                <input type="radio" id="true_answer" name="correct_answer" value="1">
                                                <label for="true_answer">True</label>
                                            </div>
                                            <div class="radio">
                                                <input type="radio" id="false_answer" name="correct_answer" value="0">
                                                <label for="false_answer">False</label>
                                            </div>
                                            <div class="invalid-feedback" id="correct_answer_error"></div>
                                        </div>
                                    </div>

                                    <!-- Multiple Choice Options -->
                                    <div id="multipleChoiceOptions" style="display: none;">
                                        <div class="form-group">
                                            <label>Options *</label>
                                            <div id="optionsContainer">
                                                <!-- Options will be dynamically added here -->
                                            </div>
                                            <button type="button" id="addOption" class="btn btn-success btn-sm">Add Option</button>
                                            <div class="invalid-feedback" id="options_error"></div>
                                        </div>
                                    </div>

                                    <!-- Essay Correct Answer -->
                                    <div class="form-group" id="essayCorrectAnswer" style="display: none;">
                                        <label for="correct_essay_answer">Correct Answer (for Essay) *</label>
                                        <textarea class="form-control" id="correct_essay_answer" name="correct_essay_answer" rows="5"></textarea>
                                        <div class="invalid-feedback" id="correct_essay_answer_error"></div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary" id="submitQuestion">
                                            <i class="icon-check2"></i> Add Question
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="cancelQuestion">
                                            <i class="icon-cross"></i> Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Questions List -->
                        <div class="card p-1">
                            <div class="card-header">
                                <h4 class="card-title">Questions (<span id="questionsCount">{{ $centerExam->questions->count() }}</span>)</h4>
                            </div>
                            <div class="card-body">
                                <div id="questionsList">
                                    @forelse($centerExam->questions as $index => $question)
                                        @include('dashboard.center_exams.partials.question-item', ['question' => $question, 'index' => $index])
                                    @empty
                                        <div class="text-center py-4" id="noQuestionsMessage">
                                            <i class="icon-file-text2 font-large-2 text-muted"></i>
                                            <p class="text-muted mt-2">No questions added yet.</p>
                                        </div>
                                    @endforelse
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
            document.addEventListener('DOMContentLoaded', function() {
                const questionForm = document.getElementById('questionForm');
                const questionFormContainer = document.getElementById('questionFormContainer');
                const toggleQuestionFormBtn = document.getElementById('toggleQuestionForm');
                const questionType = document.getElementById('question_type');
                const trueFalseOptions = document.getElementById('trueFalseOptions');
                const multipleChoiceOptions = document.getElementById('multipleChoiceOptions');
                const optionsContainer = document.getElementById('optionsContainer');
                const addOptionBtn = document.getElementById('addOption');
                const cancelQuestionBtn = document.getElementById('cancelQuestion');
                const questionsList = document.getElementById('questionsList');
                const noQuestionsMessage = document.getElementById('noQuestionsMessage');

                let optionCount = 0;
                let isEditing = false;
                let editingQuestionId = null;

                // Toggle question form
                toggleQuestionFormBtn.addEventListener('click', function() {
                    if (questionFormContainer.style.display === 'none') {
                        showQuestionForm();
                        this.innerHTML = '<i class="icon-minus"></i> Cancel';
                    } else {
                        hideQuestionForm();
                    }
                });

                cancelQuestionBtn.addEventListener('click', function() {
                    hideQuestionForm();
                });

                function showQuestionForm() {
                    questionFormContainer.style.display = 'block';
                    resetForm();
                }

                function hideQuestionForm() {
                    questionFormContainer.style.display = 'none';
                    toggleQuestionFormBtn.innerHTML = '<i class="icon-plus"></i> Add Question';
                    resetForm();
                    isEditing = false;
                    editingQuestionId = null;
                }

                function resetForm() {
                    questionForm.reset();
                    trueFalseOptions.style.display = 'none';
                    multipleChoiceOptions.style.display = 'none';
                    optionsContainer.innerHTML = '';
                    optionCount = 0;
                    clearErrors();
                }

                function clearErrors() {
                    document.querySelectorAll('.invalid-feedback').forEach(el => {
                        el.textContent = '';
                        el.previousElementSibling.classList.remove('is-invalid');
                    });
                }

                const essayCorrectAnswer = document.getElementById('essayCorrectAnswer');

                // Handle question type change
                questionType.addEventListener('change', function() {
                    trueFalseOptions.style.display = 'none';
                    multipleChoiceOptions.style.display = 'none';
                    essayCorrectAnswer.style.display = 'none'; // Hide by default

                    if (this.value === 'true_false') {
                        trueFalseOptions.style.display = 'block';
                    } else if (this.value === 'multiple_choice') {
                        multipleChoiceOptions.style.display = 'block';
                        if (optionCount === 0) {
                            addInitialOptions();
                        }
                    } else if (this.value === 'essay') {
                        essayCorrectAnswer.style.display = 'block';
                    }
                });

                function addInitialOptions() {
                    for (let i = 0; i < 4; i++) {
                        addOption();
                    }
                }

                // Add new option
                addOptionBtn.addEventListener('click', function() {
                    addOption();
                });

                function addOption() {
                    const optionHtml = `
            <div class="input-group mb-2 option-input">
                <span class="input-group-addon">
                    <input type="radio" name="correct_option" value="${optionCount}">
                </span>
                <input type="text" class="form-control" name="options[]" placeholder="Option ${optionCount + 1}">
                ${optionCount >= 2 ? `
                <span class="input-group-addon">
                    <button type="button" class="btn btn-danger btn-sm remove-option">×</button>
                </span>
                ` : ''}
            </div>
        `;
                    optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
                    optionCount++;
                }

                // Remove option
                optionsContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-option')) {
                        e.target.closest('.option-input').remove();
                        // Update radio button values
                        document.querySelectorAll('input[name="correct_option"]').forEach((radio, index) => {
                            radio.value = index;
                        });
                        optionCount--;
                    }
                });

                // Submit form
                questionForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const url = isEditing
                        ? `/center-exam-questions/${editingQuestionId}`
                        : `/center-exams/{{ $centerExam->id }}/questions`;

                    if (isEditing) {
                        formData.append('_method', 'PATCH');
                    }

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (isEditing) {
                                    updateQuestionInList(data.question);
                                } else {
                                    addQuestionToList(data.question);
                                }
                                hideQuestionForm();
                                updateStats();
                                showAlert('success', data.message);

                                // Hide no questions message if it exists
                                if (noQuestionsMessage) {
                                    noQuestionsMessage.style.display = 'none';
                                }
                            } else {
                                showErrors(data.errors);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('danger', 'An error occurred while processing your request.');
                        });
                });

                function addQuestionToList(question) {
                    const questionIndex = document.querySelectorAll('.question-item').length;
                    const questionHtml = createQuestionHtml(question, questionIndex);
                    questionsList.insertAdjacentHTML('beforeend', questionHtml);
                }

                function updateQuestionInList(question) {
                    const questionElement = document.querySelector(`[data-question-id="${question.id}"]`);
                    if (questionElement) {
                        const questionIndex = Array.from(questionElement.parentNode.children).indexOf(questionElement);
                        const questionHtml = createQuestionHtml(question, questionIndex);
                        questionElement.outerHTML = questionHtml;
                    }
                }

                function createQuestionHtml(question, index) {
                    let optionsHtml = '';
                    if (question.question_type !== 'essay' && question.options) {
                        optionsHtml = `
                <div class="mt-2">
                    <strong>Options:</strong>
                    <ul class="list-unstyled ml-2">
                        ${question.options.map(option => `
                            <li class="mb-1">
                                <span class="badge badge-${option.is_correct ? 'success' : 'light'}">
                                    ${option.is_correct ? '✓' : '○'}
                                </span>
                                ${option.option_text}
                            </li>
                        `).join('')}
                    </ul>
                </div>
            `;
                    }

                    let imageHtml = '';
                    if (question.image) {
                        imageHtml = `
                <div class="mt-2">
                    <img src="/storage/${question.image}" width="100px">
                </div>
            `;
                    }

                    return `
            <div class="card mb-2 question-item" data-question-id="${question.id}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-1">
                                Question ${index + 1}
                                <span class="badge badge-info">${question.question_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                                <span class="badge badge-secondary">${question.marks} ${question.marks == 1 ? 'Mark' : 'Marks'}</span>
                            </h6>
                            <p class="card-text">${question.question_text}</p>
                            ${imageHtml}
                            ${optionsHtml}
                            ${question.question_type === 'essay' && question.correct_essay_answer ? `
                                <div class="mt-2">
                                    <strong>Correct Answer:</strong>
                                    <p class="text-muted">${question.correct_essay_answer}</p>
                                </div>
                            ` : ''}
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
            </div>
        `;
                }

                // Handle edit and delete buttons
                questionsList.addEventListener('click', function(e) {
                    if (e.target.closest('.edit-question')) {
                        const questionId = e.target.closest('.edit-question').dataset.questionId;
                        editQuestion(questionId);
                    } else if (e.target.closest('.delete-question')) {
                        const questionId = e.target.closest('.delete-question').dataset.questionId;
                        deleteQuestion(questionId);
                    }
                });

                function editQuestion(questionId) {
                    fetch(`/center-exam-questions/${questionId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const question = data.question;
                                isEditing = true;
                                editingQuestionId = questionId;
                                showQuestionForm();
                                toggleQuestionFormBtn.innerHTML = '<i class="icon-pencil"></i> Update Question';
                                document.getElementById('submitQuestion').innerHTML = '<i class="icon-check2"></i> Update Question';

                                // Populate form fields
                                document.getElementById('question_text').value = question.question_text;
                                document.getElementById('marks').value = question.marks;
                                questionType.value = question.question_type;

                                // Trigger change event for question type to show/hide options
                                const event = new Event('change');
                                questionType.dispatchEvent(event);

                                if (question.question_type === 'true_false') {
                                    if (question.options[0].is_correct) {
                                        document.getElementById('true_answer').checked = true;
                                    } else {
                                        document.getElementById('false_answer').checked = true;
                                    }
                                } else if (question.question_type === 'multiple_choice') {
                                    optionsContainer.innerHTML = ''; // Clear existing options
                                    question.options.forEach((option, index) => {
                                        addOption(); // Add a new option input
                                        const lastOptionInput = optionsContainer.lastElementChild.querySelector('input[name="options[]"]');
                                        lastOptionInput.value = option.option_text;
                                        if (option.is_correct) {
                                            optionsContainer.lastElementChild.querySelector('input[name="correct_option"]').checked = true;
                                        }
                                    });
                                } else if (question.question_type === 'essay') {
                                    document.getElementById('correct_essay_answer').value = question.correct_essay_answer;
                                }
                            } else {
                                showAlert('danger', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('danger', 'An error occurred while fetching question for edit.');
                        });
                }

                function deleteQuestion(questionId) {
                    if (confirm('Are you sure you want to delete this question?')) {
                        fetch(`/center-exam-questions/${questionId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.querySelector(`[data-question-id="${questionId}"]`).remove();
                                    updateStats();
                                    showAlert('success', data.message);

                                    // Show no questions message if no questions left
                                    if (document.querySelectorAll('.question-item').length === 0 && noQuestionsMessage) {
                                        noQuestionsMessage.style.display = 'block';
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showAlert('danger', 'An error occurred while deleting the question.');
                            });
                    }
                }

                function updateStats() {
                    const questionsCount = document.querySelectorAll('.question-item').length;
                    document.getElementById('questionsCount').textContent = questionsCount;
                    // You might want to update total marks here as well if needed
                }

                function showAlert(type, message) {
                    const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
                    document.querySelector('.content-body').insertAdjacentHTML('afterbegin', alertHtml);

                    // Auto remove after 5 seconds
                    setTimeout(() => {
                        const alert = document.querySelector('.alert');
                        if (alert) {
                            alert.remove();
                        }
                    }, 5000);
                }

                function showErrors(errors) {
                    clearErrors();
                    for (const [field, messages] of Object.entries(errors)) {
                        const errorElement = document.getElementById(`${field}_error`);
                        const inputElement = document.getElementById(field) || document.querySelector(`[name="${field}"]`);

                        if (errorElement && inputElement) {
                            errorElement.textContent = messages[0];
                            inputElement.classList.add('is-invalid');
                        }
                    }
                }
            });
        </script>
    @endsection
