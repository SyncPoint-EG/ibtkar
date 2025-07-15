@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.subject.edit') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">{{ __('dashboard.subject.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.subject.edit') }}
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
                                    <h4 class="card-title" id="basic-layout-tooltip">{{ __('dashboard.subject.edit') }} {{ __('dashboard.subject.title') }} #{{ $subject->id }}</h4>
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
                                            <p>{{ __('dashboard.subject.update_info') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('subjects.update', $subject->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="form-group">
            <label for="name">{{ __("dashboard.subject.fields.name") }}</label>
            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                   name="name" value="{{ isset($subject) ? $subject->name : old('name') }}"
                   placeholder="{{ __("dashboard.subject.fields.name") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.subject.fields.name") }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="logo">{{ __("dashboard.subject.fields.logo") }}</label>
            <input type="text" id="logo" class="form-control @error('logo') is-invalid @enderror"
                   name="logo" value="{{ isset($subject) ? $subject->logo : old('logo') }}"
                   placeholder="{{ __("dashboard.subject.fields.logo") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.subject.fields.logo") }}">
            @error('logo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="second_logo">{{ __("dashboard.subject.fields.second_logo") }}</label>
            <input type="text" id="second_logo" class="form-control @error('second_logo') is-invalid @enderror"
                   name="second_logo" value="{{ isset($subject) ? $subject->second_logo : old('second_logo') }}"
                   placeholder="{{ __("dashboard.subject.fields.second_logo") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.subject.fields.second_logo") }}">
            @error('second_logo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('subjects.index') }}" class="btn btn-warning mr-1">
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
