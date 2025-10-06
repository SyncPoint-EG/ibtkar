@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.luckwheelitem.edit') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('luck-wheel-items.index') }}">{{ __('dashboard.luckwheelitem.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.luckwheelitem.edit') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-tooltip">{{ __('dashboard.luckwheelitem.edit') }} {{ __('dashboard.luckwheelitem.title') }} #{{ $luckWheelItem->id }}</h4>
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
                                    <div class="card-block">
                                        <div class="card-text">
                                            <p>{{ __('dashboard.luckwheelitem.update_info') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('luck-wheel-items.update', $luckWheelItem->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="form-group">
            <label for="key">{{ __("dashboard.luckwheelitem.fields.key") }}</label>
            <input type="text" id="key" class="form-control @error('key') is-invalid @enderror"
                   name="key" value="{{ isset($luckWheelItem) ? $luckWheelItem->key : old('key') }}"
                   placeholder="{{ __("dashboard.luckwheelitem.fields.key") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.luckwheelitem.fields.key") }}">
            @error('key')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="value">{{ __("dashboard.luckwheelitem.fields.value") }}</label>
            <input type="text" id="value" class="form-control @error('value') is-invalid @enderror"
                   name="value" value="{{ isset($luckWheelItem) ? $luckWheelItem->value : old('value') }}"
                   placeholder="{{ __("dashboard.luckwheelitem.fields.value") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.luckwheelitem.fields.value") }}">
            @error('value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="type">{{ __("dashboard.luckwheelitem.fields.type") }}</label>
            <select id="type" name="type" class="form-control @error('type') is-invalid @enderror">
                <option value="points" {{ (isset($luckWheelItem) && $luckWheelItem->type == 'points') || old('type') == 'points' ? 'selected' : '' }}>Points</option>
                <option value="nothing" {{ (isset($luckWheelItem) && $luckWheelItem->type == 'nothing') || old('type') == 'nothing' ? 'selected' : '' }}>Nothing</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="appearance_percentage">{{ __("dashboard.luckwheelitem.fields.appearance_percentage") }}</label>
            <input type="number" step="1" id="appearance_percentage" class="form-control @error('appearance_percentage') is-invalid @enderror"
                   name="appearance_percentage" value="{{ isset($luckWheelItem) ? $luckWheelItem->appearance_percentage : old('appearance_percentage') }}"
                   data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.luckwheelitem.fields.appearance_percentage") }}">
            @error('appearance_percentage')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('luck-wheel-items.index') }}" class="btn btn-warning mr-1">
                                                    <i class="icon-cross2"></i> {{ __('dashboard.common.cancel') }}
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="icon-check2"></i> {{ __('dashboard.common.update') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
