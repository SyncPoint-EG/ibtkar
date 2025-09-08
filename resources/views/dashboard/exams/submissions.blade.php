@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Submissions for {{ $exam->title }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">Exams</a></li>
                            <li class="breadcrumb-item active">Submissions</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Submissions</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Score</th>
                                            <th>Submitted At</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($exam->attempts as $attempt)
                                            <tr>
                                                <td>{{ $attempt->student->name }}</td>
                                                <td>{{ $attempt->score }} / {{ $exam->questions->sum('total_marks') }}</td>
                                                <td>{{ $attempt->created_at->format('d-m-Y H:i') }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#answersModal{{ $attempt->id }}">View Answers</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($exam->attempts as $attempt)
        <div class="modal fade" id="answersModal{{ $attempt->id }}" tabindex="-1" role="dialog" aria-labelledby="answersModalLabel{{ $attempt->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="answersModalLabel{{ $attempt->id }}">Answers for {{ $attempt->student->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @foreach($attempt->answers as $answer)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Q: {{ $answer->question->question_text }}</h5>
                                    @if($answer->question->question_type == 'multiple_choice' || $answer->question->question_type == 'true_false')
                                        <p class="card-text">Your answer: {{ $answer->selectedOption->option_text ?? 'Not answered' }}</p>
                                        <p class="card-text">Correct answer: {{ $answer->question->options->where('is_correct', true)->first()->option_text }}</p>
                                    @else
                                        <p class="card-text">Your answer: {{ $answer->essay_answer ?? 'Not answered' }}</p>
                                        <p class="card-text">Correct answer: {{ $answer->question->correct_essay_answer }}</p>
                                    @endif
                                    <p class="card-text">Status: @if($answer->marks_awarded == $answer->question->marks) <span class="badge badge-success">Correct</span> @else <span class="badge badge-danger">Incorrect</span> @endif</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
