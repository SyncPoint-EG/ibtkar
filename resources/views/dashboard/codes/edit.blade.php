@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.code.edit') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('codes.index') }}">{{ __('dashboard.code.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.code.edit') }}
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
                                    <h4 class="card-title" id="basic-layout-tooltip">{{ __('dashboard.code.edit') }} {{ __('dashboard.code.title') }} #{{ $code->id }}</h4>
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
                                            <p>{{ __('dashboard.code.update_info') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('codes.update', $code->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="form-group">
            <label for="code">{{ __("dashboard.code.fields.code") }}</label>
            <input type="text" id="code" class="form-control @error('code') is-invalid @enderror"
                   name="code" value="{{ isset($code) ? $code->code : old('code') }}"
                   placeholder="{{ __("dashboard.code.fields.code") }}" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.code.fields.code") }}">
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="for">{{ __("dashboard.code.fields.for") }}</label>
            <select id="for" name="for" class="form-control @error('for') is-invalid @enderror" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.code.fields.for") }}">
                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.code.fields.for") }}</option>
                @php
                    $forOptions = ['course', 'chapter', 'lesson', 'charge', 'grade_plan'];
                @endphp
                @foreach($forOptions as $option)
                    <option value="{{ $option }}" {{ (isset($code) && $code->for == $option) || old('for') == $option ? 'selected' : '' }}>
                        {{ ucfirst($option) }}
                    </option>
                @endforeach
            </select>
            @error('for')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="teacher_id">{{ __("dashboard.code.fields.teacher") }}</label>
            <select id="teacher_id" name="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror">
                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.code.fields.teacher") }}</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ (isset($code) && $code->teacher_id == $teacher->id) || old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                @endforeach
            </select>
            @error('teacher_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="code_classification">{{ __("dashboard.code.fields.code_classification") }}</label>
            <input type="text" id="code_classification" class="form-control @error('code_classification') is-invalid @enderror"
                   name="code_classification" value="{{ isset($code) ? $code->code_classification : old('code_classification') }}"
                   placeholder="{{ __("dashboard.code.fields.code_classification") }}">
            @error('code_classification')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                <div class="form-group">
            <label for="price">{{ __("dashboard.code.fields.price") }}</label>
            <input type="number" id="price" class="form-control @error('price') is-invalid @enderror"
                   name="price" value="{{ isset($code) ? $code->price : old('price') }}"
                   placeholder="{{ __("dashboard.code.fields.price") }}" step="0.01">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                
                <div class="form-group">
            <label for="expires_at">{{ __("dashboard.code.fields.expires_at") }}</label>
            <input type="date" id="expires_at" class="form-control @error('expires_at') is-invalid @enderror"
                   name="expires_at" value="{{ isset($code) ? $code->expires_at : old('expires_at') }}"
                   data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="{{ __("dashboard.code.fields.expires_at") }}">
            @error('expires_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('codes.index') }}" class="btn btn-warning mr-1">
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
