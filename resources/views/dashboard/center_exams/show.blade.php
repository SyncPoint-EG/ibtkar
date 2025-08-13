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
                    <div class="col-md-6">
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
                                        <td>{{ $centerExam->total_marks }}</td>
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
                </div>
            </div>
        </div>
    </div>
@endsection
