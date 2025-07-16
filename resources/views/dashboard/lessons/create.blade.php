@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.lesson.create') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('lessons.index') }}">{{ __('dashboard.lesson.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.lesson.create') }}
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
                                        id="basic-layout-tooltip">{{ __('dashboard.lesson.create_new') }}</h4>
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
                                            <p>{{ __('dashboard.lesson.fill_required') }}</p>
                                        </div>
                                        @if ($errors->any())

                                            <div class="alert alert-danger">

                                                <ul>

                                                    @foreach ($errors->all() as $error)

                                                        <li>{{ $error }}</li>

                                                    @endforeach

                                                </ul>

                                            </div>

                                        @endif
                                        <form class="form" method="POST" action="{{ route('lessons.store') }}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="name">{{ __("dashboard.lesson.fields.name") }}</label>
                                                    <input type="text" id="name"
                                                           class="form-control @error('name') is-invalid @enderror"
                                                           name="name"
                                                           value="{{ isset($lesson) ? $lesson->name : old('name') }}"
                                                           placeholder="{{ __("dashboard.lesson.fields.name") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.lesson.fields.name") }}">
                                                    @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="desc">{{ __("dashboard.lesson.fields.desc") }}</label>
                                                    <textarea id="desc" rows="5"
                                                              class="form-control @error('desc') is-invalid @enderror"
                                                              name="desc" data-toggle="tooltip" data-trigger="hover"
                                                              data-placement="top"
                                                              data-title="{{ __("dashboard.lesson.fields.desc") }}">{{ isset($lesson) ? $lesson->desc : old('desc') }}</textarea>
                                                    @error('desc')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="video_link">{{ __("dashboard.lesson.fields.video_link") }}</label>
                                                    <input type="text" id="video_link"
                                                           class="form-control @error('video_link') is-invalid @enderror"
                                                           name="video_link"
                                                           value="{{ isset($lesson) ? $lesson->video_link : old('video_link') }}"
                                                           placeholder="{{ __("dashboard.lesson.fields.video_link") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.lesson.fields.video_link") }}">
                                                    @error('video_link')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="video_image">{{ __("dashboard.lesson.fields.video_image") }}</label>
                                                    <input type="file" id="video_image"
                                                           class="form-control @error('video_image') is-invalid @enderror"
                                                           name="video_image"
                                                           value="{{ isset($lesson) ? $lesson->video_image : old('video_image') }}"
                                                           placeholder="{{ __("dashboard.lesson.fields.video_image") }}"
                                                           data-toggle="tooltip" data-trigger="hover"
                                                           data-placement="top"
                                                           data-title="{{ __("dashboard.lesson.fields.video_image") }}">
                                                    @error('video_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="chapter_id">{{ __("dashboard.lesson.fields.chapter_id") }}</label>
                                                    <select id="chapter_id" name="chapter_id"
                                                            class="form-control @error('chapter_id') is-invalid @enderror"
                                                            data-toggle="tooltip" data-trigger="hover"
                                                            data-placement="top"
                                                            data-title="{{ __("dashboard.lesson.fields.chapter_id") }}"
                                                        {{ isset($selectedChapterId) ? 'disabled' : '' }}>
                                                        <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.lesson.fields.chapter_id") }}</option>
                                                        @foreach($chapters as $chapter)
                                                            <option value="{{ $chapter->id }}"
                                                                {{ (isset($selectedChapterId) && $selectedChapterId == $chapter->id) || (isset($lesson) && $lesson->chapter_id == $chapter->id) ? 'selected' : '' }}>
                                                                {{ $chapter->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{-- Hidden input to maintain the selected chapter when form is disabled --}}
                                                    @if(isset($selectedChapterId))
                                                        <input type="hidden" name="chapter_id" value="{{ $selectedChapterId }}">
                                                        <small class="form-text text-muted">
                                                            <i class="icon-info"></i> {{ __("dashboard.lesson.chapter_preselected") }}
                                                        </small>
                                                    @endif

                                                    @error('chapter_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <a href="{{ route('lessons.index') }}" class="btn btn-warning mr-1">
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
