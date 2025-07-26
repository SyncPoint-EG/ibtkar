{{-- resources/views/dashboard/exams/results.blade.php --}}

@extends('dashboard.layouts.app')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('Exam Results') }}</h2>
                    <p class="text-muted">{{ $attempt->exam->title }}</p>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">{{ __('Exams') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Results') }}</li>
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

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('warning') }}
                    </div>
                @endif

                <div class="row">
                    <!-- Results Summary -->
                    <div class="col-md-4 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Exam Summary') }}</h4>
                            </div>

                            <div class="card-body">
                                <div class="text-center mb-2">
                                    @php
                                        $percentage = $attempt->getPercentageScore();
                                        $scoreClass = $percentage >= 80 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger');
                                    @endphp

                                    <div class="score-circle bg-{{ $scoreClass }} text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                         style="width: 120px; height: 120px;">
                                        <div>
                                            <h2 class="mb-0">{{ $percentage }}%</h2>
                                            <small>{{ __('Score') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="exam-details">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ __('Your Score:') }}</span>
                                        <strong>{{ $attempt->score ?? 0 }} / {{ $attempt->total_marks }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ __('Questions:') }}</span>
                                        <strong>{{ $attempt->exam->questions->count() }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ __('Started:') }}</span>
                                        <strong>{{ $attempt->started_at->format('M d, Y H:i') }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ __('Completed:') }}</span>
                                        <strong>{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y H:i') : 'N/A' }}</strong>
                                    </div>

                                    @if($attempt->completed_at)
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>{{ __('Time Taken:') }}</span>
                                            <strong>{{ $attempt->started_at->diffForHumans($attempt->completed_at, true) }}</strong>
                                        </div>
                                    @endif
                                </div>

                                @php
                                    $correctAnswers = $attempt->answers->where('marks_awarded', '>', 0)->count();
                                    $totalQuestions = $attempt->exam->questions->count();
                                    $essayQuestions = $attempt->exam->questions->where('question_type', 'essay')->count();
                                    $gradedQuestions = $totalQuestions - $essayQuestions;
                                @endphp

                                <div class="progress-stats mt-2">
                                    <h6>{{ __('Question Breakdown:') }}</h6>
                                    <div class="d-flex justify-content-between text-success">
                                        <span>{{ __('Correct:') }}</span>
                                        <strong>{{ $correctAnswers }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between text-danger">
                                        <span>{{ __('Incorrect:') }}</span>
                                        <strong>{{ $gradedQuestions - $correctAnswers }}</strong>
                                    </div>
                                    @if($essayQuestions > 0)
                                        <div class="d-flex justify-content-between text-info">
                                            <span>{{ __('Essay (Pending):') }}</span>
                                            <strong>{{ $essayQuestions }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Results -->
                    <div class="col-md-8 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Detailed Results') }}</h4>
                            </div>

                            <div class="card-body">
                                @foreach($attempt->exam->questions as $index => $question)
                                    @php
                                        $userAnswer = $attempt->answers->where('question_id', $question->id)->first();
                                        $isCorrect = $userAnswer && $userAnswer->marks_awarded > 0;
                                        $isPending = $question->question_type === 'essay' && (!$userAnswer || $userAnswer->marks_awarded === null);
                                    @endphp

                                    <div class="card bg-light mb-2">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0">
                                                    {{ __('Question') }} {{ $index + 1 }}
                                                    <span class="tag tag-{{ $question->question_type === 'true_false' ? 'info' : ($question->question_type === 'multiple_choice' ? 'primary' : 'secondary') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                                </span>
                                                </h6>
                                                <div>
                                                    @if($isPending)
                                                        <span class="tag tag-warning">{{ __('Pending Review') }}</span>
                                                    @elseif($isCorrect)
                                                        <span class="tag tag-success">{{ __('Correct') }}</span>
                                                    @else
                                                        <span class="tag tag-danger">{{ __('Incorrect') }}</span>
                                                    @endif
                                                    <span class="tag tag-outline-dark">
                                                    {{ $userAnswer->marks_awarded ?? 0 }} / {{ $question->marks }} {{ __('marks') }}
                                                </span>
                                                </div>
                                            </div>

                                            <p class="question-text mb-2">{{ $question->question_text }}</p>

                                            @if($question->question_type === 'true_false' || $question->question_type === 'multiple_choice')
                                                <div class="options-review">
                                                    @foreach($question->options as $option)
                                                        @php
                                                            $isSelected = false;
                                                            if ($question->question_type === 'true_false') {
                                                                $isSelected = $userAnswer &&
                                                                             $userAnswer->true_false_answer == ($option->option_text === 'True');
                                                            } else {
                                                                $isSelected = $userAnswer &&
                                                                             $userAnswer->selected_option_id == $option->id;
                                                            }
                                                        @endphp

                                                        <div class="option-item p-2 mb-1 rounded
                                                        {{ $option->is_correct ? 'bg-success text-white' : '' }}
                                                        {{ $isSelected && !$option->is_correct ? 'bg-danger text-white' : '' }}
                                                        {{ $isSelected && $option->is_correct ? 'bg-success text-white' : '' }}
                                                        {{ !$isSelected && !$option->is_correct ? 'bg-white' : '' }}">

                                                            <div class="d-flex align-items-center">
                                                                @if($isSelected)
                                                                    <i class="icon-{{ $option->is_correct ? 'check' : 'cross' }} mr-1"></i>
                                                                @elseif($option->is_correct)
                                                                    <i class="icon-check text-success mr-1"></i>
                                                                @else
                                                                    <i class="icon-radio-unchecked mr-1"></i>
                                                                @endif

                                                                @if($question->question_type === 'multiple_choice')
                                                                    <strong>{{ chr(65 + $loop->index) }}.</strong>
                                                                @endif

                                                                <span class="ml-1">{{ $option->option_text }}</span>

                                                                @if($isSelected)
                                                                    <span class="ml-auto">
                                                                    <small>({{ __('Your answer') }})</small>
                                                                </span>
                                                                @endif

                                                                @if($option->is_correct && !$isSelected)
                                                                    <span class="ml-auto">
                                                                    <small>({{ __('Correct answer') }})</small>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            @elseif($question->question_type === 'essay')
                                                <div class="essay-answer">
                                                    <h6>{{ __('Your Answer:') }}</h6>
                                                    @if($userAnswer && $userAnswer->essay_answer)
                                                        <div class="bg-white p-2 rounded border">
                                                            {!! nl2br(e($userAnswer->essay_answer)) !!}
                                                        </div>
                                                    @else
                                                        <div class="bg-light p-2 rounded text-muted">
                                                            {{ __('No answer provided') }}
                                                        </div>
                                                    @endif

                                                    @if($isPending)
                                                        <small class="text-warning mt-1 d-block">
                                                            <i class="icon-clock"></i> {{ __('This answer is pending manual review by your instructor.') }}
                                                        </small>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
