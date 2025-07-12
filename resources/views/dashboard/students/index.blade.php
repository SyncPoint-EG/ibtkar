@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.student.management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.student.title') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Table head options start -->
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
                                    @can('create_student')
                                        <a href="{{ route('students.create') }}" class="btn btn-primary mb-1">
                                            <i class="icon-plus2"></i> {{ __('dashboard.student.add_new') }}
                                        </a>
                                    @endcan
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-inverse">
                                        <tr>
                                            <th>{{ __('dashboard.common.number') }}</th>
                                            <th>{{ __("dashboard.student.fields.first_name") }}</th>
                <th>{{ __("dashboard.student.fields.last_name") }}</th>
                <th>{{ __("dashboard.student.fields.phone") }}</th>
                <th>{{ __("dashboard.student.fields.password") }}</th>
                <th>{{ __("dashboard.student.fields.governorate_id") }}</th>
                <th>{{ __("dashboard.student.fields.district_id") }}</th>
                <th>{{ __("dashboard.student.fields.center_id") }}</th>
                <th>{{ __("dashboard.student.fields.stage_id") }}</th>
                <th>{{ __("dashboard.student.fields.grade_id") }}</th>
                <th>{{ __("dashboard.student.fields.division_id") }}</th>
                <th>{{ __("dashboard.student.fields.gender") }}</th>
                <th>{{ __("dashboard.student.fields.birth_date") }}</th>
                <th>{{ __("dashboard.student.fields.status") }}</th>
                                            <th>{{ __('dashboard.common.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $student->first_name }}</td>
                <td>{{ $student->last_name }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ $student->password }}</td>
                <td>{{ $student->governorate_id }}</td>
                <td>{{ $student->district_id }}</td>
                <td>{{ $student->center_id }}</td>
                <td>{{ $student->stage_id }}</td>
                <td>{{ $student->grade_id }}</td>
                <td>{{ $student->division_id }}</td>
                <td>{{ $student->gender }}</td>
                <td>{{ $student->birth_date ? $student->birth_date->format('Y-m-d') : '' }}</td>
                <td>{{ $student->status ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    @can('view_student')
                                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm">
                                                            <i class="icon-eye6"></i> {{ __('dashboard.common.view') }}
                                                        </a>
                                                    @endcan

                                                    @can('edit_student')
                                                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">
                                                            <i class="icon-pencil3"></i> {{ __('dashboard.common.edit') }}
                                                        </a>
                                                    @endcan

                                                    @can('delete_student')
                                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('dashboard.student.delete_confirm') }}');">
                                                                <i class="icon-trash4"></i> {{ __('dashboard.common.delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 2 + count(Schema::getColumnListing('students')) }}" class="text-center">{{ __('dashboard.student.no_records') }}</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{$students->links()}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Table head options end -->
            </div>
        </div>
    </div>
@endsection
