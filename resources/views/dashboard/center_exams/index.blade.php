@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Center Exam Management</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Center Exams</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">All Center Exams</h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <a href="{{ route('center-exams.create') }}" class="btn btn-primary btn-sm">
                                        <i class="icon-plus"></i> Add New Center Exam
                                    </a>
                                </div>
                            </div>
                            <div class="card-body collapse in">
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Center</th>
                                            <th>Stage</th>
                                            <th>Grade</th>
                                            <th>Division</th>
                                            <th>Total Marks</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($centerExams as $exam)
                                            <tr>
                                                <td>{{ $exam->id }}</td>
                                                <td>{{ $exam->title }}</td>
                                                <td>{{ $exam->center->name ?? 'N/A' }}</td>
                                                <td>{{ $exam->stage->name ?? 'N/A' }}</td>
                                                <td>{{ $exam->grade->name ?? 'N/A' }}</td>
                                                <td>{{ $exam->division->name ?? 'N/A' }}</td>
                                                <td>{{ $exam->total_marks }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $exam->is_active ? 'success' : 'danger' }}">
                                                        {{ $exam->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('center-exams.show', $exam) }}" class="btn btn-info btn-sm">
                                                            <i class="icon-eye"></i>
                                                        </a>
                                                        <a href="{{ route('center-exams.edit', $exam) }}" class="btn btn-warning btn-sm">
                                                            <i class="icon-pencil"></i>
                                                        </a>
                                                        <a href="{{ route('center-exams.submissions', $exam) }}" class="btn btn-success btn-sm" title="{{ __('Submissions') }}">
                                                            <i class="icon-list"></i>
                                                        </a>
                                                        <form action="{{ route('center-exams.destroy', $exam) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="icon-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No center exams found</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{ $centerExams->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
