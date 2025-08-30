@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.payment_approval.management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.payment_approval.title') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Statistics start -->
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="p-2 text-xs-center bg-success-gradient media-left media-middle">
                                        <i class="icon-users font-large-2 "></i>
                                    </div>
                                    <div class="p-2 media-body">
                                        <h5 class="success">{{ $statistics['students_paid_count'] }}</h5>
                                        <h5 class="text-bold-400">{{ __('dashboard.payment_approval.stats.students_paid') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="p-2 text-xs-center bg-info-gradient media-left media-middle">
                                        <i class="icon-graduation-cap font-large-2 "></i>
                                    </div>
                                    <div class="p-2 media-body">
                                        <h5 class="info">{{ $statistics['lessons_paid_count'] }}</h5>
                                        <h5 class="text-bold-400">{{ __('dashboard.payment_approval.stats.lessons_paid') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="p-2 text-xs-center bg-warning-gradient media-left media-middle">
                                        <i class="icon-folder-open font-large-2 "></i>
                                    </div>
                                    <div class="p-2 media-body">
                                        <h5 class="warning">{{ $statistics['courses_paid_count'] }}</h5>
                                        <h5 class="text-bold-400">{{ __('dashboard.payment_approval.stats.courses_paid') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="p-2 text-xs-center bg-danger-gradient media-left media-middle">
                                        <i class="icon-bookmark font-large-2 "></i>
                                    </div>
                                    <div class="p-2 media-body">
                                        <h5 class="danger">{{ $statistics['chapters_paid_count'] }}</h5>
                                        <h5 class="text-bold-400">{{ __('dashboard.payment_approval.stats.chapters_paid') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Statistics end -->

                <!-- Table head options start -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('dashboard.payment_approval.list') }}</h4>
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
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-inverse">
                                        <tr>
                                            <th>{{ __('dashboard.payment_approval.fields.id') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.student_id') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.student_name') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.date') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.time') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.payment_method') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.subject_name') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.course') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.lesson') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.academic_level') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.teacher_uuid') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.teacher_name') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.status') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->id }}</td>
                                                <td>{{ $payment->student_id }}</td>
                                                <td>{{ $payment->student?->first_name . ' ' . $payment->student?->last_name }}</td>
                                                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $payment->created_at->format('H:i') }}</td>
                                                <td>{{ $payment->payment_method }}</td>
                                                <td>
                                                    @if($payment->lesson)
                                                        {{ $payment->lesson?->chapter?->course?->subject?->name }}
                                                    @elseif($payment->chapter)
                                                        {{ $payment->chapter?->course?->subject?->name }}
                                                    @elseif($payment->course)
                                                        {{ $payment->course?->subject?->name }}
                                                    @endif
                                                </td>
                                                <td>{{ $payment->course?->name }}</td>
                                                <td>{{ $payment->lesson?->name }}</td>
                                                <td>{{ $payment->student?->stage?->name . ' - ' . $payment->student?->grade?->name . ' - ' . $payment->student?->division?->name }}</td>
                                                <td>
                                                    @if($payment->lesson)
                                                        {{ $payment->lesson?->chapter?->course?->teacher?->uuid }}
                                                    @elseif($payment->chapter)
                                                        {{ $payment->chapter?->course?->teacher?->uuid }}
                                                    @elseif($payment->course)
                                                        {{ $payment->course?->teacher?->uuid }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($payment->lesson)
                                                        {{ $payment->lesson?->chapter?->course?->teacher?->name }}
                                                    @elseif($payment->chapter)
                                                        {{ $payment->chapter?->course?->teacher?->name }}
                                                    @elseif($payment->course)
                                                        {{ $payment->course?->teacher?->name }}
                                                    @endif
                                                </td>
                                                <td>{{ $payment->payment_status }}</td>
                                                <td>
                                                    @if($payment->payment_status === \App\Models\Payment::PAYMENT_STATUS['pending'])
                                                        @can('accept_payment_approval')
                                                            <form
                                                                action="{{ route('payment_approvals.accept', $payment->id) }}"
                                                                method="POST" style="display: inline-block;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm"
                                                                        onclick="return confirm('{{ __('dashboard.payment_approval.accept_confirm') }}');">
                                                                    <i class="icon-check"></i> {{ __('dashboard.common.accept') }}
                                                                </button>
.                                                            </form>
                                                        @endcan

                                                        @can('reject_payment_approval')
                                                            <form
                                                                action="{{ route('payment_approvals.reject', $payment->id) }}"
                                                                method="POST" style="display: inline-block;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('{{ __('dashboard.payment_approval.reject_confirm') }}');">
                                                                    <i class="icon-cross"></i> {{ __('dashboard.common.reject') }}
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    @elseif($payment->payment_status === \App\Models\Payment::PAYMENT_STATUS['approved'])
                                                        <span
                                                            class="badge badge-success">{{ __('dashboard.payment_approval.accepted') }}</span>
                                                    @elseif($payment->payment_status == \App\Models\Payment::PAYMENT_STATUS['rejected'])
                                                        <span
                                                            class="badge badge-danger">{{ __('dashboard.payment_approval.rejected') }}</span>
                                                    @endif

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="13"
                                                    class="text-center">{{ __('dashboard.payment_approval.no_records') }}</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{ $payments->links() }}
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
