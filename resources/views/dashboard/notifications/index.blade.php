@extends('dashboard.layouts.master')

@section('page_styles')
    <style>
        #send-notification-form .card-body {
            padding: 1.5rem;
        }

        #send-notification-form .form-group {
            margin-bottom: 1.25rem;
        }
    </style>
@endsection

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-8 col-12 mb-2">
                    <h3 class="content-header-title">{{ __('dashboard.notification.send') }}</h3>
                    <p class="text-muted">{{ __('dashboard.notification.filters_help') }}</p>
                </div>
            </div>
            <div class="content-body">
                <section id="send-notification">
                    <div class="row">
                        <div class="col-12">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="card" id="send-notification-form">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('dashboard.notification.title') }}</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('notifications.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 mb-2">
                                                <div class="form-group">
                                                    <label for="recipient_type">{{ __('dashboard.notification.recipient_label') }}</label>
                                                    <select name="recipient_type" id="recipient_type" class="form-control" required>
                                                        <option value="students" @selected(old('recipient_type', 'students') === 'students')>{{ __('dashboard.notification.recipient_students') }}</option>
                                                        <option value="guardians" @selected(old('recipient_type', 'students') === 'guardians')>{{ __('dashboard.notification.recipient_guardians') }}</option>
                                                        <option value="both" @selected(old('recipient_type', 'students') === 'both')>{{ __('dashboard.notification.recipient_both') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 mb-2">
                                                <div class="form-group">
                                                    <label for="stage_ids">{{ __('dashboard.notification.stages_label') }}</label>
                                                    <select name="stage_ids[]" id="stage_ids" class="form-control select2" multiple data-placeholder="{{ __('dashboard.notification.stages_label') }}">
                                                        @foreach($stages as $stage)
                                                            <option value="{{ $stage->id }}" @selected(collect(old('stage_ids', []))->contains($stage->id))>{{ $stage->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">{{ __('dashboard.notification.filters_leave_blank') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 mb-2">
                                                <div class="form-group">
                                                    <label for="grade_ids">{{ __('dashboard.notification.grades_label') }}</label>
                                                    <select name="grade_ids[]" id="grade_ids" class="form-control select2" multiple data-placeholder="{{ __('dashboard.notification.grades_label') }}">
                                                        @foreach($grades as $grade)
                                                            <option value="{{ $grade->id }}" @selected(collect(old('grade_ids', []))->contains($grade->id))>{{ $grade->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">{{ __('dashboard.notification.filters_leave_blank') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 mb-2">
                                                <div class="form-group">
                                                    <label for="division_ids">{{ __('dashboard.notification.divisions_label') }}</label>
                                                    <select name="division_ids[]" id="division_ids" class="form-control select2" multiple data-placeholder="{{ __('dashboard.notification.divisions_label') }}">
                                                        @foreach($divisions as $division)
                                                            <option value="{{ $division->id }}" @selected(collect(old('division_ids', []))->contains($division->id))>{{ $division->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">{{ __('dashboard.notification.filters_leave_blank') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="title">{{ __('dashboard.notification.title_label') }}</label>
                                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="body">{{ __('dashboard.notification.body_label') }}</label>
                                                    <textarea name="body" id="body" rows="4" class="form-control" required>{{ old('body') }}</textarea>
                                                    <small class="text-muted">{{ __('dashboard.notification.body_hint') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right mt-3">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('dashboard.notification.send') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: function(){
                    return $(this).data('placeholder');
                },
                allowClear: true
            });
        });
    </script>
@endsection
