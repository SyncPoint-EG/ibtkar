@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.reports.codes_report') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.reports.codes_report') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('dashboard.reports.codes_report') }}</h4>
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
                                            <th>{{ __('dashboard.code.fields.code') }}</th>
                                            <th>{{ __('dashboard.reports.code_type') }}</th>
                                            <th>{{ __('dashboard.reports.classification') }}</th>
                                            <th>{{ __('dashboard.reports.teacher_name') }}</th>
                                            <th>{{ __('dashboard.teacher.fields.id') }}</th>
                                            <th>{{ __('dashboard.reports.price') }}</th>
                                            <th>{{ __('dashboard.student.fields.name') }}</th>
                                            <th>{{ __('dashboard.student.fields.id') }}</th>
                                            <th>{{ __('dashboard.reports.code_status') }}</th>
                                            <th>{{ __('dashboard.reports.used_for_what') }}</th>
                                            <th>{{ __('dashboard.reports.usage_time') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($codes as $code)
                                        <tr>
                                            <td>{{ $code->code }}</td>
                                            <td>{{ $code->for }}</td>
                                            <td>{{ $code->code_classification }}</td>
                                            <td>{{ $code->teacher?->name }}</td>
                                            <td>{{ $code->teacher?->id }}</td>
                                            <td>{{ $code->price }}</td>
                                            <td>{{ $code->payment?->student?->name }}</td>
                                            <td>{{ $code->payment?->student?->id }}</td>
                                            <td>
                                                @if($code->payment)
                                                    {{ __('dashboard.reports.used') }}
                                                @else
                                                    {{ __('dashboard.reports.unused') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($code->payment?->course_id)
                                                    {{ __('dashboard.course.title') }}
                                                @elseif($code->payment?->chapter_id)
                                                    {{ __('dashboard.chapter.title') }}
                                                @elseif($code->payment?->lesson_id)
                                                    {{ __('dashboard.lesson.title') }}
                                                @endif
                                            </td>
                                            <td>{{ $code->payment?->created_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">{{ __('dashboard.common.no_records') }}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $codes->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
