@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ trans('dashboard.luck_wheel.management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ trans('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('dashboard.luck_wheel.title') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible mb-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>{{ trans('dashboard.common.success') }}!</strong> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>{{ trans('dashboard.common.error') }}!</strong> {{ session('error') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ trans('dashboard.luck_wheel.edit') }}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <form action="{{ route('luck-wheel.update') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ trans('dashboard.luck_wheel.fields.gift') }}</th>
                                                    <th>{{ trans('dashboard.luck_wheel.fields.appearance_percentage') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($luckWheelItems as $item)
                                                    <tr>
                                                        <td>{{ $item->gift }}</td>
                                                        <td>
                                                            <input type="number" name="appearance_percentage[{{ $item->id }}]" value="{{ $item->appearance_percentage }}" class="form-control">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-primary">{{ trans('dashboard.common.save_changes') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
