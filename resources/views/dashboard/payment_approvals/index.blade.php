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
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.payment_approval.title') }}
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
                                            <th>#</th>
                                            <th>{{ __('dashboard.payment_approval.fields.student_name') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.amount') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.payment_method') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.status') }}</th>
                                            <th>{{ __('dashboard.payment_approval.fields.date') }}</th>
                                            <th>{{ __('dashboard.common.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($payments as $payment)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $payment->student->name ?? 'N/A' }}</td>
                                                <td>{{ $payment->amount }}</td>
                                                <td>{{ $payment->payment_method }}</td>
                                                <td>{{ $payment->payment_status }}</td>
                                                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    @can('accept_payment_approval')
                                                        <form action="{{ route('payment_approvals.accept', $payment->id) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('{{ __('dashboard.payment_approval.accept_confirm') }}');">
                                                                <i class="icon-check"></i> {{ __('dashboard.common.accept') }}
                                                            </button>
                                                        </form>
                                                    @endcan

                                                    @can('reject_payment_approval')
                                                        <form action="{{ route('payment_approvals.reject', $payment->id) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('dashboard.payment_approval.reject_confirm') }}');">
                                                                <i class="icon-cross"></i> {{ __('dashboard.common.reject') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">{{ __('dashboard.payment_approval.no_records') }}</td>
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
