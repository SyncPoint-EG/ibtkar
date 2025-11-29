@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Homework Management</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Homework</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">All Homework</h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <a href="{{ route('homework.create') }}" class="btn btn-primary btn-sm">
                                        <i class="icon-plus"></i> Add New Homework
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
                                            <th>Lesson</th>
                                            <th>Due Date</th>
                                            <th>Total Marks</th>
                                            <th>Questions</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($homework as $hw)
                                            <tr>
                                                <td>{{ $hw->id }}</td>
                                                <td>{{ $hw->title }}</td>
                                                <td>{{ $hw->lesson->title ?? 'N/A' }}</td>
                                                <td>{{ $hw->due_date ? $hw->due_date->format('Y-m-d') : 'No due date' }}</td>
                                                <td>{{ $hw->total_marks }}</td>
                                                <td>{{ $hw->questions->count() }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $hw->is_active ? 'success' : 'danger' }}">
                                                        {{ $hw->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('homework.show', $hw) }}" class="btn btn-info btn-sm">
                                                            <i class="icon-eye"></i>
                                                        </a>
                                                        <a href="{{ route('homework.edit', $hw) }}" class="btn btn-warning btn-sm">
                                                            <i class="icon-pencil"></i>
                                                        </a>
                                                        <a href="{{ route('homework.submissions', $hw) }}" class="btn btn-success btn-sm" title="{{ __('Submissions') }}">
                                                            <i class="icon-list"></i>
                                                        </a>
                                                        <form action="{{ route('homework.toggle-status', $hw) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-{{ $hw->is_active ? 'secondary' : 'success' }} btn-sm">
                                                                <i class="icon-{{ $hw->is_active ? 'pause' : 'play3' }}"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('homework.destroy', $hw) }}" method="POST" style="display: inline;">
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
                                                <td colspan="8" class="text-center">No homework found</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{ $homework->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
