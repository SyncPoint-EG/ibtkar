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
                                <h4 class="card-title">{{ __('Questions') }} ({{ $exam->questions->count() }})</h4>
                            </div>

                            <div class="card-body collapse in mx-1">
                                @if($exam->questions->count() > 0)
                                    @foreach($exam->questions as $index => $question)
                                        <div class="card bg-light mb-1">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h5>{{ __('Question') }} {{ $index + 1 }}
                                                            <span class="tag tag-primary">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                                            <span class="tag tag-info">{{ $question->marks }} {{ __('marks') }}</span>
                                                        </h5>
                                                        <p class="mb-1">{{ $question->question_text }}</p>

                                                        @if($question->question_type !== 'essay' && $question->options->count() > 0)
                                                            <div class="mt-1">
                                                                <strong>{{ __('Options:') }}</strong>
                                                                <ul class="list-unstyled ml-1">
                                                                    @foreach($question->options as $option)
                                                                        <li class="mb-0">
                                                                            @if($option->is_correct)
                                                                                <i class="icon-check text-success"></i>
                                                                            @else
                                                                                <i class="icon-radio-unchecked text-muted"></i>
                                                                            @endif
                                                                            {{ $option->option_text }}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-1">
                                                        <form action="{{ route('exams.remove-question', [$exam, $question]) }}" method="POST"
                                                              onsubmit="return confirm('{{ __('Are you sure you want to remove this question?') }}')"
                                                              style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="{{ __('Remove Question') }}">
                                                                <i class="icon-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-3">
                                        <i class="icon-help font-large-2 text-muted"></i>
                                        <h4 class="mt-1">{{ __('No questions added yet') }}</h4>
                                        <p class="text-muted">{{ __('Start by adding your first question') }}</p>
                                    </div>
                                @endif
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
                                <form action="{{ route('exams.add-question', $exam) }}" method="POST" id="questionForm">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionType = document.getElementById('question_type');
            const trueFalseOptions = document.getElementById('trueFalseOptions');
            const multipleChoiceOptions = document.getElementById('multipleChoiceOptions');
            const optionsContainer = document.getElementById('optionsContainer');
            const addOptionBtn = document.getElementById('addOption');

            questionType.addEventListener('change', function() {
                trueFalseOptions.style.display = 'none';
                multipleChoiceOptions.style.display = 'none';

                if (this.value === 'true_false') {
                    trueFalseOptions.style.display = 'block';
                } else if (this.value === 'multiple_choice') {
                    multipleChoiceOptions.style.display = 'block';
                }
            });

            addOptionBtn.addEventListener('click', function() {
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

                // Add remove functionality
                newOption.querySelector('.remove-option').addEventListener('click', function() {
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

            // Trigger change event if there's an old value
            if (questionType.value) {
                questionType.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
