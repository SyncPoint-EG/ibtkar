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
                                        <div class="col-md-6">
                                            <a href="{{ route('teachers.students.export', $teacher->id) }}" class="btn btn-success mb-1">
                                                <i class="icon-download"></i> {{ __('dashboard.common.export') }}
                                            </a>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <form method="GET" action="{{ route('teachers.students', $teacher->id) }}">
                                                <input type="text" name="search" class="form-control" style="width: auto; display: inline-block;" placeholder="{{ __('dashboard.student.search_placeholder') }}" value="{{ request('search') }}">
                                                <button type="submit" class="btn btn-primary">{{ __('dashboard.common.search') }}</button>
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
                                        {{ $students->links() }}
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
