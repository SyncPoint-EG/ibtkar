@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.reports.students_report') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.reports.students_report') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('dashboard.reports.students_report') }}</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>{{ __('dashboard.student.fields.id') }}</th>
                                            <th>{{ __('dashboard.student.fields.name') }}</th>
                                            <th>{{ __('dashboard.student.fields.gender') }}</th>
                                            <th>{{ __('dashboard.student.fields.phone') }}</th>
                                            <th>{{ __('dashboard.student.fields.wallet') }}</th>
                                            <th>{{ __('dashboard.student.fields.points') }}</th>
                                            <th>{{ __('dashboard.common.created_at') }}</th>
                                            <th>{{ __('dashboard.division.title') }}</th>
                                            <th>{{ __('dashboard.governorate.title') }}</th>
                                            <th>{{ __('dashboard.guardian.fields.phone') }}</th>
                                            <th>{{ __('dashboard.reports.total_views_count') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>{{ $student->id }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->gender }}</td>
                                            <td>{{ $student->phone }}</td>
                                            <td>{{ $student->wallet }}</td>
                                            <td>{{ $student->points }}</td>
                                            <td>{{ $student->created_at }}</td>
                                            <td>{{ $student->division?->name }}</td>
                                            <td>{{ $student->district?->governorate?->name }}</td>
                                            <td>{{ $student->guardian?->phone }}</td>
                                            <td>{{ $student->watches_count }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">{{ __('dashboard.common.no_records') }}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $students->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
