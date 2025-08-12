@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.teacher.create') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">{{ __('dashboard.teacher.management') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.teacher.create') }}</li>
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
                                    <h4 class="card-title" id="basic-layout-tooltip">{{ __('dashboard.teacher.create_new') }}</h4>
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
                                            <p>{{ __('dashboard.teacher.fill_required') }}</p>
                                        </div>

                                        <form class="form" method="POST" action="{{ route('teachers.store') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">

                                                {{-- Basic Info --}}
                                                <div class="form-group">
                                                    <label for="name">{{ __("dashboard.teacher.fields.name") }}</label>
                                                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                                           name="name" value="{{ old('name') }}"
                                                           placeholder="{{ __("dashboard.teacher.fields.name") }}">
                                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">{{ __("dashboard.teacher.fields.phone") }}</label>
                                                    <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                                           name="phone" value="{{ old('phone') }}"
                                                           placeholder="{{ __("dashboard.teacher.fields.phone") }}">
                                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="other_phone">{{ __("dashboard.teacher.fields.other_phone") }}</label>
                                                    <input type="text" id="other_phone" class="form-control @error('other_phone') is-invalid @enderror"
                                                           name="other_phone" value="{{ old('other_phone') }}"
                                                           placeholder="{{ __("dashboard.teacher.fields.other_phone") }}">
                                                    @error('other_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="bio">{{ __("dashboard.teacher.fields.bio") }}</label>
                                                    <textarea id="bio" rows="5" class="form-control @error('bio') is-invalid @enderror"
                                                              name="bio">{{ old('bio') }}</textarea>
                                                    @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="image">{{ __("dashboard.teacher.fields.image") }}</label>
                                                    <input type="file" id="image" class="form-control @error('image') is-invalid @enderror"
                                                           name="image">
                                                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="rate">{{ __("dashboard.teacher.fields.rate") }}</label>
                                                    <input type="number" step="0.01" id="rate" class="form-control @error('rate') is-invalid @enderror"
                                                           name="rate" value="{{ old('rate') }}">
                                                    @error('rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="is_featured">{{ __("dashboard.teacher.fields.is_featured") }}</label>
                                                    <input type="checkbox" id="is_featured"
                                                           name="is_featured" value="1"
                                                        {{ old('is_featured') ? 'checked' : '' }}>
                                                    @error('is_featured')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">{{ __("dashboard.student.fields.password") }}</label>
                                                    <input type="text" id="password" class="form-control @error('password') is-invalid @enderror"
                                                           name="password" value="{{ old('password') }}"
                                                           placeholder="{{ __("dashboard.student.fields.password") }}">
                                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="password_confirmation">{{ __("dashboard.common.password_confirmation") }}</label>
                                                    <input type="text" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                                                           name="password_confirmation" value="{{ old('password_confirmation') }}"
                                                           placeholder="{{ __("dashboard.common.password_confirmation") }}">
                                                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
{{--                                                --}}{{-- Subject Assignments --}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label>{{ __("dashboard.teacher.subject_assignments") }}</label>--}}

{{--                                                    <div id="assignments-container">--}}
{{--                                                        --}}{{-- Assignment rows will be inserted here --}}
{{--                                                        @if(old('assignments'))--}}
{{--                                                            @foreach(old('assignments') as $idx => $assignment)--}}
{{--                                                                <div class="assignment-row mb-2 border rounded p-2 bg-light position-relative">--}}
{{--                                                                    <button type="button" class="btn btn-danger btn-sm remove-assignment position-absolute" style="top:5px;right:5px;">--}}
{{--                                                                        <i class="icon-trash"></i>--}}
{{--                                                                    </button>--}}
{{--                                                                    <div class="row">--}}
{{--                                                                        <div class="col-md-3">--}}
{{--                                                                            <select name="assignments[{{ $idx }}][subject_id]" class="form-control" required>--}}
{{--                                                                                <option value="">{{ __("dashboard.teacher.fields.subjects") }}</option>--}}
{{--                                                                                @foreach($subjects as $subject)--}}
{{--                                                                                    <option value="{{ $subject->id }}" {{ $assignment['subject_id'] == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>--}}
{{--                                                                                @endforeach--}}
{{--                                                                            </select>--}}
{{--                                                                        </div>--}}
{{--                                                                        <div class="col-md-3">--}}
{{--                                                                            <select name="assignments[{{ $idx }}][stage_id]" class="form-control" required>--}}
{{--                                                                                <option value="">{{ __("dashboard.teacher.fields.stages") }}</option>--}}
{{--                                                                                @foreach($stages as $stage)--}}
{{--                                                                                    <option value="{{ $stage->id }}" {{ $assignment['stage_id'] == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>--}}
{{--                                                                                @endforeach--}}
{{--                                                                            </select>--}}
{{--                                                                        </div>--}}
{{--                                                                        <div class="col-md-3">--}}
{{--                                                                            <select name="assignments[{{ $idx }}][grade_id]" class="form-control" required>--}}
{{--                                                                                <option value="">{{ __("dashboard.teacher.fields.grades") }}</option>--}}
{{--                                                                                @foreach($grades as $grade)--}}
{{--                                                                                    <option value="{{ $grade->id }}" {{ $assignment['grade_id'] == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>--}}
{{--                                                                                @endforeach--}}
{{--                                                                            </select>--}}
{{--                                                                        </div>--}}
{{--                                                                        <div class="col-md-3">--}}
{{--                                                                            <select name="assignments[{{ $idx }}][division_id]" class="form-control">--}}
{{--                                                                                <option value="">{{ __("dashboard.teacher.fields.divisions") }} ({{ __('dashboard.common.optional') }})</option>--}}
{{--                                                                                @foreach($divisions as $division)--}}
{{--                                                                                    <option value="{{ $division->id }}" {{ (isset($assignment['division_id']) && $assignment['division_id'] == $division->id) ? 'selected' : '' }}>{{ $division->name }}</option>--}}
{{--                                                                                @endforeach--}}
{{--                                                                            </select>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            @endforeach--}}
{{--                                                        @endif--}}
{{--                                                    </div>--}}
{{--                                                    <button type="button" class="btn btn-info mt-1" id="add-assignment">--}}
{{--                                                        <i class="icon-plus2"></i> {{ __("dashboard.teacher.add_assignment") }}--}}
{{--                                                    </button>--}}

{{--                                                    @error('assignments')--}}
{{--                                                    <div class="text-danger">{{ $message }}</div>--}}
{{--                                                    @enderror--}}
{{--                                                    @error('assignments.*.subject_id')--}}
{{--                                                    <div class="text-danger">{{ $message }}</div>--}}
{{--                                                    @enderror--}}
{{--                                                    @error('assignments.*.stage_id')--}}
{{--                                                    <div class="text-danger">{{ $message }}</div>--}}
{{--                                                    @enderror--}}
{{--                                                    @error('assignments.*.grade_id')--}}
{{--                                                    <div class="text-danger">{{ $message }}</div>--}}
{{--                                                    @enderror--}}
{{--                                                </div>--}}

                                            </div>
                                            <div class="form-actions">
                                                <a href="{{ route('teachers.index') }}" class="btn btn-warning mr-1">
                                                    <i class="icon-cross2"></i> {{ __('dashboard.common.cancel') }}
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="icon-check2"></i> {{ __('dashboard.common.save') }}
                                                </button>
                                            </div>
                                        </form>

                                        {{-- Hidden template for assignment row --}}
                                        <div id="assignment-template" style="display:none;">
                                            <div class="assignment-row mb-2 border rounded p-2 bg-light position-relative">
                                                <button type="button" class="btn btn-danger btn-sm remove-assignment position-absolute" style="top:5px;right:5px;">
                                                    <i class="icon-trash"></i>
                                                </button>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <select name="assignments[__INDEX__][subject_id]" class="form-control" required>
                                                            <option value="">{{ __("dashboard.teacher.fields.subjects") }}</option>
                                                            @foreach($subjects as $subject)
                                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="assignments[__INDEX__][stage_id]" class="form-control" required>
                                                            <option value="">{{ __("dashboard.teacher.fields.stages") }}</option>
                                                            @foreach($stages as $stage)
                                                                <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="assignments[__INDEX__][grade_id]" class="form-control" required>
                                                            <option value="">{{ __("dashboard.teacher.fields.grades") }}</option>
                                                            @foreach($grades as $grade)
                                                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="assignments[__INDEX__][division_id]" class="form-control">
                                                            <option value="">{{ __("dashboard.teacher.fields.divisions") }} ({{ __('dashboard.common.optional') }})</option>
                                                            @foreach($divisions as $division)
                                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- End template --}}

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

@section('page_scripts')
    <script>
        $(function() {
            let assignmentIndex = {{ old('assignments') ? count(old('assignments')) : 0 }};

            function addAssignmentRow() {
                let tpl = $('#assignment-template').html().replace(/__INDEX__/g, assignmentIndex);
                $('#assignments-container').append(tpl);
                assignmentIndex++;
            }

            $('#add-assignment').on('click', function() {
                addAssignmentRow();
            });

            $('#assignments-container').on('click', '.remove-assignment', function() {
                $(this).closest('.assignment-row').remove();
            });

            // Add one row if the page loaded with no old input
            @if(!old('assignments'))
            addAssignmentRow();
            @endif
        });
    </script>
@endsection
