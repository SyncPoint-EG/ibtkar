@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Point Redemptions</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item active">Point Redemptions</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Rules</h4>
                        <div class="heading-elements">
                            <a href="{{ route('point-redemptions.create') }}" class="btn btn-primary btn-sm"><i class="icon-plus"></i> Add rule</a>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Points Required</th>
                                        <th>Wallet Amount (EGP)</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($redemptions as $redemption)
                                        <tr>
                                            <td>{{ $loop->iteration + ($redemptions->currentPage() - 1) * $redemptions->perPage() }}</td>
                                            <td>{{ $redemption->name }}</td>
                                            <td>{{ $redemption->points_required }}</td>
                                            <td>{{ number_format($redemption->wallet_amount, 2) }}</td>
                                            <td>
                                                <span class="tag tag-{{ $redemption->is_active ? 'success' : 'secondary' }}">
                                                    {{ $redemption->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('point-redemptions.edit', $redemption) }}" class="btn btn-sm btn-warning"><i class="icon-pencil"></i></a>
                                                <form action="{{ route('point-redemptions.destroy', $redemption) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this rule?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="icon-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center">No redemption rules yet.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{ $redemptions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
