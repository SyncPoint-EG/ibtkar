@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.reports.payments_report') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.reports.payments_report') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('dashboard.reports.payments_report') }}</h4>
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
                                            <th>{{ __('dashboard.student.fields.name') }}</th>
                                            <th>{{ __('dashboard.student.fields.id') }}</th>
                                            <th>{{ __('dashboard.reports.payment_method') }}</th>
                                            <th>{{ __('dashboard.reports.payment_type') }}</th>
                                            <th>{{ __('dashboard.teacher.fields.name') }}</th>
                                            <th>{{ __('dashboard.reports.teacher_name') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->student?->name }}</td>
                                            <td>{{ $payment->student?->id }}</td>
                                            <td>{{ $payment->payment_method }}</td>
                                            <td>
                                                @if($payment->course_id)
                                                    {{ __('dashboard.course.title') }}
                                                @elseif($payment->chapter_id)
                                                    {{ __('dashboard.chapter.title') }}
                                                @elseif($payment->lesson_id)
                                                    {{ __('dashboard.lesson.title') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->course?->teacher)
                                                    {{ $payment->course->teacher->name }}
                                                @elseif($payment->chapter?->course?->teacher)
                                                    {{ $payment->chapter->course->teacher->name }}
                                                @elseif($payment->lesson?->chapter?->course?->teacher)
                                                    {{ $payment->lesson->chapter->course->teacher->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->course?->teacher)
                                                    {{ $payment->course->teacher->name }}
                                                @elseif($payment->chapter?->course?->teacher)
                                                    {{ $payment->chapter->course->teacher->name }}
                                                @elseif($payment->lesson?->chapter?->course?->teacher)
                                                    {{ $payment->lesson->chapter->course->teacher->name }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">{{ __('dashboard.common.no_records') }}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $payments->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
