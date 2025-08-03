@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.settings.edit_all_settings') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.settings.title') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('dashboard.settings.edit_all_settings') }}</h4>
                    </div>
                    <div class="card-body mx-2">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('settings.bulkUpdate') }}">
                            @csrf
                            @method('PUT')

                            @foreach($settings as $setting)
                                <div class="form-group row align-items-center m-1">
                                    <label class="col-md-3 col-form-label font-weight-bold" for="value-{{ $setting->id }}">
                                        {{ $setting->key }}
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" id="value-{{ $setting->id }}" name="settings[{{ $setting->id }}][value]"
                                               class="form-control @error('settings.' . $setting->id . '.value') is-invalid @enderror"
                                               value="{{ old('settings.' . $setting->id . '.value', $setting->value) }}">
                                        @error('settings.' . $setting->id . '.value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" id="desc-{{ $setting->id }}" name="settings[{{ $setting->id }}][description]"
                                               class="form-control @error('settings.' . $setting->id . '.description') is-invalid @enderror"
                                               value="{{ old('settings.' . $setting->id . '.description', $setting->description) }}"
                                               placeholder="{{ __('dashboard.settings.description_optional') }}">
                                        @error('settings.' . $setting->id . '.description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <hr>
                            @endforeach

                            <div class="form-actions text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon-check2"></i> {{ __('dashboard.common.save_changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
