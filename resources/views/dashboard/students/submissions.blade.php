@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.student.exam_submissions') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('students.index') }}">{{ __('dashboard.student.title') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.student.exam_submissions') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $student->first_name }} {{ $student->last_name }}</h4>
                        <a href="{{ route('students.submissions.export', $student->id) }}" class="btn btn-success my-1">
                            <i class="icon-download"></i> {{ __('dashboard.common.export') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-inverse">
                                <tr>
                                    <th>{{ __('dashboard.exam.title') }}</th>
                                    <th>{{ __('dashboard.lesson.title') }}</th>
                                    <th>{{ __('dashboard.exam.fields.score') }}</th>
                                    <th>{{ __('dashboard.exam.fields.total_score') }}</th>
                                    <th>{{ __('dashboard.common.date') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($examAttempts as $attempt)
                                    <tr>
                                        <td>{{ $attempt->exam->name }}</td>
                                        <td>{{ $attempt->exam->lesson?->name }}</td>
                                        <td>{{ $attempt->score }}</td>
                                        <td>{{ $attempt->exam->total_score }}</td>
                                        <td>{{ $attempt->created_at->format('Y-m-d H:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('dashboard.student.no_exam_submissions') }}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{$examAttempts->withQueryString()->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
