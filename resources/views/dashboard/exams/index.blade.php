{{-- resources/views/dashboard/exams/index.blade.php --}}

@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('Exams Management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Exams') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('All Exams') }}</h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a href="{{ route('exams.create') }}" class="btn btn-primary btn-sm">
                                                <i class="icon-plus"></i> {{ __('Add New Exam') }}
                                            </a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <form action="{{ route('exams.index') }}" method="GET">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="teacher_id">{{ __('dashboard.teacher.title') }}</label>
                                                    <select name="teacher_id" id="teacher_id" class="form-control">
                                                        <option value="">{{ __('dashboard.common.all') }}</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" {{ request()->get('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="grade_id">{{ __('dashboard.grade.title') }}</label>
                                                    <select name="grade_id" id="grade_id" class="form-control">
                                                        <option value="">{{ __('dashboard.common.all') }}</option>
                                                        @foreach($grades as $grade)
                                                            <option value="{{ $grade->id }}" {{ request()->get('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary btn-block">{{ __('dashboard.common.filter') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if($exams->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Lesson') }}</th>
                                                <th>{{ __('Duration') }}</th>
                                                <th>{{ __('Total Marks') }}</th>
                                                <th>{{ __('Questions') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Created At') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($exams as $exam)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $exam->title }}</strong>
                                                        @if($exam->description)
                                                            <br><small class="text-muted">{{ Str::limit($exam->description, 50) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $exam->lesson->title ?? 'N/A' }}</td>
                                                    <td>{{ $exam->duration_minutes }} {{ __('minutes') }}</td>
                                                    <td>{{ $exam->total_marks }}</td>
                                                    <td>{{ $exam->questions->count() }}</td>
                                                    <td>
                                                        @if($exam->is_active)
                                                            <span class="tag tag-success">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="tag tag-danger">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $exam->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('exams.show', $exam) }}" class="btn btn-info btn-sm" title="{{ __('View') }}">
                                                                <i class="icon-eye"></i>
                                                            </a>
                                                            <a href="{{ route('exams.edit', $exam) }}" class="btn btn-warning btn-sm" title="{{ __('Edit') }}">
                                                                <i class="icon-pencil"></i>
                                                            </a>
                                                            <a href="{{ route('exams.submissions', $exam) }}" class="btn btn-success btn-sm" title="{{ __('Submissions') }}">
                                                                <i class="icon-list"></i>
                                                            </a>
                                                            <form action="{{ route('exams.toggle-active', $exam) }}" method="POST" style="display: inline-block;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-{{ $exam->is_active ? 'secondary' : 'success' }} btn-sm"
                                                                        title="{{ $exam->is_active ? __('Deactivate') : __('Activate') }}">
                                                                    <i class="icon-{{ $exam->is_active ? 'pause' : 'play3' }}"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('exams.destroy', $exam) }}" method="POST"
                                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this exam?') }}')"
                                                                  style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" title="{{ __('Delete') }}">
                                                                    <i class="icon-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        {{ $exams->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="icon-file-text2 font-large-2 text-muted"></i>
                                        <h4 class="mt-2">{{ __('No exams found') }}</h4>
                                        <p class="text-muted">{{ __('Start by creating your first exam') }}</p>
                                        <a href="{{ route('exams.create') }}" class="btn btn-primary">
                                            <i class="icon-plus"></i> {{ __('Create Exam') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
