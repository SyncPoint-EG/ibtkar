@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ trans('dashboard.reward_points.title') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">{{ trans('dashboard.reward_points.title') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ trans('dashboard.reward_points.edit') }}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <form action="{{ route('reward-points.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ trans('dashboard.reward_points.fields.reward') }}</th>
                                                    <th>{{ trans('dashboard.reward_points.fields.points_cost') }}</th>
                                                    <th>{{ trans('dashboard.reward_points.fields.image') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rewardPoints as $rewardPoint)
                                                    <tr>
                                                        <td>{{ $rewardPoint->description }}</td>
                                                        <td>
                                                            <input type="number" name="points_cost[{{ $rewardPoint->id }}]" value="{{ $rewardPoint->points_cost }}" class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="file" name="image[{{ $rewardPoint->id }}]" class="form-control">
                                                            <img src="{{ $rewardPoint->image }}" alt="" width="100">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
