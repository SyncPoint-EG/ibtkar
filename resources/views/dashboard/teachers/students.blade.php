@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.teacher.students_of') }} {{ $teacher->name }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('teachers.index') }}">{{ __('dashboard.teacher.title') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.teacher.students') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('dashboard.student.list') }}</h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                        <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                                        <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                                        <li><a data-action="close"><i class="icon-cross2"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-body collapse in">
                                <div class="card-block card-dashboard">
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <form method="GET" action="{{ route('teachers.students', $teacher->id) }}">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="stage_id">{{ __('dashboard.stage.title') }}</label>
                                                            <select name="stage_id" id="stage_id" class="form-control">
                                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                                @if(isset($stages))
                                                                    @foreach($stages as $stage)
                                                                        <option value="{{ $stage->id }}" {{ request('stage_id') == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="grade_id">{{ __('dashboard.grade.title') }}</label>
                                                            <select name="grade_id" id="grade_id" class="form-control">
                                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                                @if(isset($grades))
                                                                    @foreach($grades as $grade)
                                                                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="division_id">{{ __('dashboard.division.title') }}</label>
                                                            <select name="division_id" id="division_id" class="form-control">
                                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                                @if(isset($divisions))
                                                                    @foreach($divisions as $division)
                                                                        <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="search">{{ __('dashboard.common.search') }}</label>
                                                            <input type="text" name="search" id="search" class="form-control" placeholder="{{ __('dashboard.student.search_placeholder') }}" value="{{ request('search') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary">{{ __('dashboard.common.filter') }}</button>
                                                        <a href="{{ route('teachers.students', $teacher->id) }}" class="btn btn-secondary">{{ __('dashboard.common.reset') }}</a>
                                                        <a href="{{ route('teachers.students.export', ['teacher' => $teacher->id] + request()->query()) }}" class="btn btn-success">
                                                            <i class="icon-download"></i> {{ __('dashboard.common.export') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead class="thead-inverse">
                                            <tr>
                                                <th>{{ __('dashboard.common.id') }}</th>
                                                <th>{{ __('dashboard.student.fields.name') }}</th>
                                                <th>{{ __('dashboard.student.fields.phone') }}</th>
                                                <th>{{ __('dashboard.student.fields.grade') }}</th>
                                                <th>{{ __('dashboard.common.actions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($students as $student)
                                                <tr>
                                                    <td>{{ $student->id }}</td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>{{ $student->phone }}</td>
                                                    <td>{{ $student->grade->name ?? '' }}</td>
                                                    <td>
                                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm">
                                                            <i class="icon-eye6"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">{{ __('dashboard.student.no_records') }}</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="text-center">
                                        {{ $students->withQueryString()->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
