@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Student Points Logs</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item active">Student Points Logs</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Students with awarded points</h4>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>Phone</th>
                                            <th>Current Points</th>
                                            <th>Total Awarded Points</th>
                                            <th>Number of Awards</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->phone }}</td>
                                            <td>{{ $student->points }}</td>
                                            <td>{{ $student->point_logs_sum_points ?? 0 }}</td>
                                            <td>{{ $student->point_logs_count ?? 0 }}</td>
                                            <td>
                                                <a href="{{ route('points-logs.show', $student) }}" class="btn btn-sm btn-info">
                                                    View Logs
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No points awarded yet.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $students->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
