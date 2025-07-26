{{-- resources/views/dashboard/exams/take.blade.php --}}

@extends('dashboard.layouts.app')

@section('page_scripts')
    <script>
        // Timer functionality
        let timeRemaining = {{ $timeRemaining * 60 }}; // Convert to seconds
        let timerInterval;

        function startTimer() {
            timerInterval = setInterval(function() {
                timeRemaining--;
                updateTimerDisplay();

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    alert('Time is up! Your exam will be submitted automatically.');
                    document.getElementById('examForm').submit();
                }

                // Auto-save every 30 seconds
                if (timeRemaining % 30 === 0) {
                    autoSaveAnswers();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const hours = Math.floor(timeRemaining / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = timeRemaining % 60;

            const display = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            document.getElementById('timer').textContent = display;

            // Change color when less than 5 minutes remaining
            const timerElement = document.getElementById('timer');
            if (timeRemaining <= 300) { // 5 minutes
                timerElement.className = 'text-danger font-weight-bold';
            } else if (timeRemaining <= 600) { // 10 minutes
                timerElement.className = 'text-warning font-weight-bold';
            }
        }

        function autoSaveAnswers() {
            const formData = new FormData(document.getElementById('examForm'));

            fetch('{{ route("exams.save-answer", $exam) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).catch(error => console.error('Auto-save failed:', error));
        }

        // Start timer when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startTimer();
            updateTimerDisplay();

            // Prevent accidental page refresh
            window.addEventListener('beforeunload', function(e) {
                e.preventDefault();
                e.returnValue = '';
            });

            // Remove the beforeunload event when submitting
            document.getElementById('examForm').addEventListener('submit', function() {
                window.removeEventListener('beforeunload', function() {});
            });
        });
    </script>
@endsection

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-8 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ $exam->title }}</h2>
                    <p class="text-muted">{{ $exam->lesson->title ?? '' }}</p>
                </div>
                <div class="content-header-right col-md-4 col-xs-12">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-0">{{ __('Time Remaining') }}</h4>
                            <h2 id="timer" class="mb-0">00:00:00</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('warning') }}
                    </div>
                @endif

                <form id="examForm" action="{{ route('exams.submit', $exam) }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            @foreach($exam->questions as $index => $question)
                                <div class="card mb-2">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            {{ __('Question') }} {{ $index + 1 }}
                                            <span class="tag tag-primary">{{ $question->marks }} {{ __('marks') }}</span>
                                            <span class="tag tag-info">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                        </h5>
                                    </div>

                                    <div class="card-body">
                                        <p class="question-text">{{ $question->question_text }}</p>

                                        @php
                                            $existingAnswer = $existingAnswers->get($question->id);
                                        @endphp

                                        @if($question->question_type === 'true_false')
                                            <div class="form-group">
                                                @foreach($question->options as $option)
                                                    <div class="radio">
                                                        <input type="radio"
                                                               id="q{{ $question->id }}_{{ $option->id }}"
                                                               name="question_{{ $question->id }}"
                                                               value="{{ $option->option_text === 'True' ? 1 : 0 }}"
                                                            {{ $existingAnswer && $existingAnswer->true_false_answer == ($option->option_text === 'True') ? 'checked' : '' }}>
                                                        <label for="q{{ $question->id }}_{{ $option->id }}">
                                                            {{ $option->option_text }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        @elseif($question->question_type === 'multiple_choice')
                                            <div class="form-group">
                                                @foreach($question->options as $option)
                                                    <div class="radio">
                                                        <input type="radio"
                                                               id="q{{ $question->id }}_{{ $option->id }}"
                                                               name="question_{{ $question->id }}"
                                                               value="{{ $option->id }}"
                                                            {{ $existingAnswer && $existingAnswer->selected_option_id == $option->id ? 'checked' : '' }}>
                                                        <label for="q{{ $question->id }}_{{ $option->id }}">
                                                            {{ chr(65 + $loop->index) }}. {{ $option->option_text }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        @elseif($question->question_type === 'essay')
                                            <div class="form-group">
                                            <textarea class="form-control"
                                                      name="question_{{ $question->id }}"
                                                      rows="6"
                                                      placeholder="{{ __('Write your answer here...') }}">{{ $existingAnswer ? $existingAnswer->essay_answer : '' }}</textarea>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <div class="card sticky-top">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Exam Progress') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="exam-info mb-2">
                                        <p><strong>{{ __('Total Questions:') }}</strong> {{ $exam->questions->count() }}</p>
                                        <p><strong>{{ __('Total Marks:') }}</strong> {{ $exam->total_marks }}</p>
                                        <p><strong>{{ __('Duration:') }}</strong> {{ $exam->duration_minutes }} {{ __('minutes') }}</p>
                                    </div>

                                    <div class="question-nav mb-2">
                                        <h6>{{ __('Questions:') }}</h6>
                                        <div class="d-flex flex-wrap">
                                            @foreach($exam->questions as $index => $question)
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary m-1 question-nav-btn"
                                                        data-question="{{ $index + 1 }}"
                                                        onclick="scrollToQuestion({{ $index + 1 }})">
                                                    {{ $index + 1 }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="exam-actions">
                                        <button type="button" class="btn btn-warning btn-block mb-1" onclick="autoSaveAnswers()">
                                            <i class="icon-save"></i> {{ __('Save Progress') }}
                                        </button>

                                        <button type="submit" class="btn btn-success btn-block"
                                                onclick="return confirm('{{ __('Are you sure you want to submit your exam? You cannot change your answers after submission.') }}')">
                                            <i class="icon-check"></i> {{ __('Submit Exam') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function scrollToQuestion(questionNumber) {
            const element = document.querySelector('.card:nth-of-type(' + questionNumber + ')');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Update question navigation buttons based on answered questions
        function updateQuestionNavigation() {
            document.querySelectorAll('.question-nav-btn').forEach((btn, index) => {
                const questionId = {{ json_encode($exam->questions->pluck('id')) }}[index];
                const inputs = document.querySelectorAll(`input[name="question_${questionId}"], textarea[name="question_${questionId}"]`);

                let answered = false;
                inputs.forEach(input => {
                    if ((input.type === 'radio' && input.checked) ||
                        (input.type === 'textarea' && input.value.trim() !== '')) {
                        answered = true;
                    }
                });

                if (answered) {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-success');
                } else {
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }
            });
        }

        // Listen for changes in answers
        document.addEventListener('change', updateQuestionNavigation);
        document.addEventListener('input', updateQuestionNavigation);

        // Initial update
        document.addEventListener('DOMContentLoaded', updateQuestionNavigation);
    </script>
@endsection
