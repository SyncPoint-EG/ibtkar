@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Edit Redemption Rule</h2>
                </div>
            </div>

            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update rule</h4>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block">
                            <form action="{{ route('point-redemptions.update', $pointRedemption) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $pointRedemption->name) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="points_required">Points Required</label>
                                    <input type="number" name="points_required" id="points_required" class="form-control" min="1" value="{{ old('points_required', $pointRedemption->points_required) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="wallet_amount">Wallet Amount (EGP)</label>
                                    <input type="number" step="0.01" name="wallet_amount" id="wallet_amount" class="form-control" min="0" value="{{ old('wallet_amount', $pointRedemption->wallet_amount) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $pointRedemption->is_active) ? 'checked' : '' }}>
                                        Active
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('point-redemptions.index') }}" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
