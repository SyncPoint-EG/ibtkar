@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.guardian.edit') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('guardians.index') }}">{{ __('dashboard.guardian.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.guardian.edit') }}
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
                                    <h4 class="card-title" id="basic-layout-tooltip">{{ __('dashboard.guardian.edit') }} {{ __('dashboard.guardian.title') }} #{{ $guardian->id }}</h4>
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
                                            <p>{{ __('dashboard.guardian.update_info') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('guardians.update', $guardian->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="form-group">
            <label for="first_name">{{ __("dashboard.guardian.fields.first_name") }}</label>
            <input type="text" id="first_name" class="form-control @error('first_name') is-invalid @enderror"
                   name="first_name" value="{{ isset($guardian) ? $guardian->first_name : old('first_name') }}"
                   placeholder="{{ __("dashboard.guardian.fields.first_name") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.guardian.fields.first_name") }}">
            @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="last_name">{{ __("dashboard.guardian.fields.last_name") }}</label>
            <input type="text" id="last_name" class="form-control @error('last_name') is-invalid @enderror"
                   name="last_name" value="{{ isset($guardian) ? $guardian->last_name : old('last_name') }}"
                   placeholder="{{ __("dashboard.guardian.fields.last_name") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.guardian.fields.last_name") }}">
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="phone">{{ __("dashboard.guardian.fields.phone") }}</label>
            <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror"
                   name="phone" value="{{ isset($guardian) ? $guardian->phone : old('phone') }}"
                   placeholder="{{ __("dashboard.guardian.fields.phone") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.guardian.fields.phone") }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="student_phone">{{ __("dashboard.guardian.fields.student_phone") }}</label>
            <input type="text" id="student_phone" class="form-control @error('student_phone') is-invalid @enderror"
                   name="student_phone" value="{{ isset($guardian) ? $guardian->student_phone : old('student_phone') }}"
                   placeholder="{{ __("dashboard.guardian.fields.student_phone") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.guardian.fields.student_phone") }}">
            @error('student_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="password">{{ __("dashboard.guardian.fields.password") }}</label>
            <input type="text" id="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" value="{{ isset($guardian) ? $guardian->password : old('password') }}"
                   placeholder="{{ __("dashboard.guardian.fields.password") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.guardian.fields.password") }}">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('guardians.index') }}" class="btn btn-warning mr-1">
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
