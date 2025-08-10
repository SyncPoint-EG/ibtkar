@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.chapter.create') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('chapters.index') }}">{{ __('dashboard.chapter.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.chapter.create') }}
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
                                        id="basic-layout-tooltip">{{ __('dashboard.chapter.create_new') }}</h4>
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
                                            <p>{{ __('dashboard.chapter.fill_required') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('chapters.store') }}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="name">{{ __("dashboard.chapter.fields.name") }}</label>
                                                    <input type="text" id="name"
                                                           class="form-control @error('name') is-invalid @enderror"
                                                           name="name"
                                                           value="{{ isset($chapter) ? $chapter->name : old('name') }}"
                                                           placeholder="{{ __("dashboard.chapter.fields.name") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.chapter.fields.name") }}">
                                                    @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">{{ __("dashboard.chapter.fields.price") }}</label>
                                                    <input type="number" id="price"
                                                           class="form-control @error('price') is-invalid @enderror"
                                                           name="price"
                                                           value="{{ isset($chapter) ? $chapter->price : old('price') }}"
                                                           step="0.01"
                                                           placeholder="{{ __("dashboard.chapter.fields.price") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.chapter.fields.price") }}">
                                                    @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="course_id">{{ __("dashboard.chapter.fields.course_id") }}</label>
                                                    <select id="course_id" name="course_id"
                                                            class="form-control @error('course_id') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.chapter.fields.course_id") }}"
                                                            @if($selectedCourse) disabled @endif>
                                                        <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.chapter.fields.course_id") }}</option>
                                                        @foreach($courses as $course)
                                                            <option value="{{ $course->id }}"
                                                                    @if($selectedCourse && $selectedCourse->id == $course->id)
                                                                        selected
                                                                    @elseif(isset($chapter) && $chapter->course_id == $course->id)
                                                                        selected
                                                                @endif>
                                                                {{ $course->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{-- Hidden input to send the course_id when select is disabled --}}
                                                    @if($selectedCourse)
                                                        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                                                        <small class="form-text text-muted">
                                                            <i class="icon-info"></i> {{ __("dashboard.chapter.course_preselected") }}
                                                        </small>
                                                    @endif

                                                    @error('course_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            <div class="form-actions">
                                                <a href="{{ route('chapters.index') }}" class="btn btn-warning mr-1">
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
