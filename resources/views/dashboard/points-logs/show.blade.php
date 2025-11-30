@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Points for {{ $student->name }}</h2>
                    <p class="text-muted mb-0">Phone: {{ $student->phone }}</p>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('points-logs.index') }}">Student Points Logs</a></li>
                            <li class="breadcrumb-item active">Details</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0">Total Awarded Points</h4>
                                        <p class="lead mb-0">{{ $totalPoints }}</p>
                                    </div>
                                    <a href="{{ route('points-logs.index') }}" class="btn btn-secondary">Back to list</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Award History</h4>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Action</th>
                                            <th>Points</th>
                                            <th>Given At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                                            <td>{{ $log->action_name }}</td>
                                            <td>{{ $log->points }}</td>
                                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No logs found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $logs->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
