@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.user.edit_user') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('users.index') }}">{{ __('dashboard.user.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.user.edit') }}
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
                                    <h4 class="card-title"
                                        id="basic-layout-tooltip">{{ __('dashboard.user.edit_user') }}</h4>
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
                                            <p>{{ __('dashboard.user.update_info') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('users.update', $user->id) }}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label
                                                        for="name">{{ __("dashboard.user.fields.name") }}</label>
                                                    <input type="text" id="name"
                                                           class="form-control @error('name') is-invalid @enderror"
                                                           name="name"
                                                           value="{{ isset($user) ? $user->name : old('name') }}"
                                                           placeholder="{{ __("dashboard.user.fields.name") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.user.fields.name") }}">
                                                    @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <!-- Role Selection Field -->
                                                <div class="form-group">
                                                    <label for="role">{{ __("dashboard.user.fields.role") }}</label>
                                                    <select id="role" name="role"
                                                            class="form-control @error('role') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.user.fields.role") }}">
                                                        <option value="">{{ __("dashboard.user.select_role") }}</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ (isset($user) && $user->hasRole($role->name)) || old('role') == $role->id ? 'selected' : '' }}>
                                                                {{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('role')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="email">{{ __("dashboard.user.fields.email") }}</label>
                                                    <input type="text" id="email"
                                                           class="form-control @error('email') is-invalid @enderror"
                                                           name="email"
                                                           value="{{ isset($user) ? $user->email : old('email') }}"
                                                           placeholder="{{ __("dashboard.user.fields.email") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.user.fields.email") }}">
                                                    @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">{{ __("dashboard.user.fields.phone") }}</label>
                                                    <input type="text" id="phone"
                                                           class="form-control @error('phone') is-invalid @enderror"
                                                           name="phone"
                                                           value="{{ isset($user) ? $user->phone : old('phone') }}"
                                                           placeholder="{{ __("dashboard.user.fields.phone") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.user.fields.phone") }}">
                                                    @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label
                                                        for="is_active">{{ __("dashboard.user.fields.is_active") }}</label>
                                                    <select id="is_active" name="is_active"
                                                            class="form-control @error('is_active') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.user.fields.is_active") }}">
                                                        <option
                                                            value="0" {{ isset($user) && !$user->is_active ? 'selected' : '' }}>{{ __("dashboard.common.no") }}</option>
                                                        <option
                                                            value="1" {{ isset($user) && $user->is_active ? 'selected' : '' }}>{{ __("dashboard.common.yes") }}</option>
                                                    </select>
                                                    @error('is_active')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
{{--                                                <div class="form-group">--}}
{{--                                                    <label for="image">{{ __("dashboard.user.fields.image") }}</label>--}}
{{--                                                    <input type="text" id="image"--}}
{{--                                                           class="form-control @error('image') is-invalid @enderror"--}}
{{--                                                           name="image"--}}
{{--                                                           value="{{ isset($user) ? $user->image : old('image') }}"--}}
{{--                                                           placeholder="{{ __("dashboard.user.fields.image") }}"--}}
{{--                                                           data-toggle="tooltip" data-trigger="hover"--}}
{{--                                                           data-placement="top"--}}
{{--                                                           data-title="{{ __("dashboard.user.fields.image") }}">--}}
{{--                                                    @error('image')--}}
{{--                                                    <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                                    @enderror--}}
{{--                                                </div>--}}

                                                <div class="form-group">
                                                    <label
                                                        for="password">{{ __("dashboard.user.fields.password") }}</label>
                                                    <input type="text" id="password"
                                                           class="form-control @error('password') is-invalid @enderror"
                                                           name="password"
                                                           placeholder="{{ __("dashboard.user.fields.password") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.user.fields.password") }}">
                                                    @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="password">{{ __("dashboard.common.password_confirmation") }}</label>
                                                    <input type="text" id="password"
                                                           class="form-control @error('password') is-invalid @enderror"
                                                           name="password_confirmation"
                                                           placeholder="{{ __("dashboard.common.password_confirmation") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.user.fields.password") }}">
                                                    @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('users.index') }}" class="btn btn-warning mr-1">
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
