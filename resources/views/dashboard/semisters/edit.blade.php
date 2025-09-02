@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.semister.edit') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('semisters.index') }}">{{ __('dashboard.semister.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.semister.edit') }}
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
                                    <h4 class="card-title" id="basic-layout-tooltip">{{ __('dashboard.semister.edit') }} {{ __('dashboard.semister.title') }} #{{ $semister->id }}</h4>
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
                                            <p>{{ __('dashboard.semister.update_info') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('semisters.update', $semister->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="name">{{ __("dashboard.semister.fields.name") }}</label>
                                                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                                           name="name" value="{{ isset($semister) ? $semister->name : old('name') }}"
                                                           placeholder="{{ __("dashboard.semister.fields.name") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.semister.fields.name") }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="start_month">{{ __("dashboard.semister.fields.start_month") }}</label>
                                                    <select id="start_month" name="start_month" class="form-control @error('start_month') is-invalid @enderror">
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}" {{ (isset($semister) && $semister->start_month == $i) || old('start_month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                                        @endfor
                                                    </select>
                                                    @error('start_month')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="start_day">{{ __("dashboard.semister.fields.start_day") }}</label>
                                                    <input type="number" id="start_day" class="form-control @error('start_day') is-invalid @enderror"
                                                           name="start_day" value="{{ isset($semister) ? $semister->start_day : old('start_day') }}"
                                                           placeholder="{{ __("dashboard.semister.fields.start_day") }}" min="1" max="31">
                                                    @error('start_day')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="end_month">{{ __("dashboard.semister.fields.end_month") }}</label>
                                                    <select id="end_month" name="end_month" class="form-control @error('end_month') is-invalid @enderror">
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}" {{ (isset($semister) && $semister->end_month == $i) || old('end_month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                                        @endfor
                                                    </select>
                                                    @error('end_month')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="end_day">{{ __("dashboard.semister.fields.end_day") }}</label>
                                                    <input type="number" id="end_day" class="form-control @error('end_day') is-invalid @enderror"
                                                           name="end_day" value="{{ isset($semister) ? $semister->end_day : old('end_day') }}"
                                                           placeholder="{{ __("dashboard.semister.fields.end_day") }}" min="1" max="31">
                                                    @error('end_day')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('semisters.index') }}" class="btn btn-warning mr-1">
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
