@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.grade_plan.create') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('grade-plans.index') }}">{{ __('dashboard.grade_plan.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.grade_plan.create') }}</li>
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
                                    <h4 class="card-title" id="basic-layout-tooltip">{{ __('dashboard.grade_plan.create_new') }}</h4>
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
                                            <p>{{ __('dashboard.grade_plan.fill_required') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('grade-plans.store') }}">
                                            @csrf
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="stage_id">{{ __('dashboard.stage.title') }}</label>
                                                    <select id="stage_id" name="stage_id" class="form-control @error('stage_id') is-invalid @enderror">
                                                        <option value="">{{ __('dashboard.common.select') }} {{ __('dashboard.stage.title') }}</option>
                                                        @foreach($stages as $stage)
                                                            <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('stage_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="grade_id">{{ __('dashboard.grade.title') }}</label>
                                                    <select id="grade_id" name="grade_id" class="form-control @error('grade_id') is-invalid @enderror">
                                                        <option value="">{{ __('dashboard.common.select') }} {{ __('dashboard.grade.title') }}</option>
                                                        @foreach($grades as $grade)
                                                            <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('grade_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="general_plan_price">{{ __('dashboard.grade_plan.fields.general_plan_price') }}</label>
                                                    <input type="number" step="0.01" id="general_plan_price" class="form-control @error('general_plan_price') is-invalid @enderror"
                                                           name="general_plan_price" value="{{ old('general_plan_price') }}"
                                                           placeholder="{{ __('dashboard.grade_plan.fields.general_plan_price') }}">
                                                    @error('general_plan_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="is_active">{{ __('dashboard.common.status') }}</label>
                                                    <select id="is_active" name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>{{ __('dashboard.common.active') }}</option>
                                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>{{ __('dashboard.common.inactive') }}</option>
                                                    </select>
                                                    @error('is_active')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('grade-plans.index') }}" class="btn btn-warning mr-1">
                                                    <i class="icon-cross2"></i> {{ __('dashboard.common.cancel') }}
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="icon-check2"></i> {{ __('dashboard.common.save') }}
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
