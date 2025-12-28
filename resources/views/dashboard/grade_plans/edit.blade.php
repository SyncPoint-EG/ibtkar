@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.grade_plan.edit') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('grade-plans.index') }}">{{ __('dashboard.grade_plan.management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.grade_plan.edit') }}</li>
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
                                    <h4 class="card-title" id="basic-layout-tooltip">{{ __('dashboard.grade_plan.update_info') }}</h4>
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

                                        <form class="form" method="POST" action="{{ route('grade-plans.update', $gradePlan->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="stage_id">{{ __('dashboard.stage.title') }}</label>
                                                    <select id="stage_id" name="stage_id" class="form-control @error('stage_id') is-invalid @enderror" data-grades-url="{{ url('grades/by-stage') }}">
                                                        <option value="">{{ __('dashboard.common.select') }} {{ __('dashboard.stage.title') }}</option>
                                                        @foreach($stages as $stage)
                                                            <option value="{{ $stage->id }}" {{ old('stage_id', $gradePlan->stage_id) == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('stage_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="grade_id">{{ __('dashboard.grade.title') }}</label>
                                                    <select id="grade_id" name="grade_id" class="form-control @error('grade_id') is-invalid @enderror" data-selected-grade="{{ old('grade_id', $gradePlan->grade_id) }}">
                                                        <option value="">{{ __('dashboard.common.select') }} {{ __('dashboard.grade.title') }}</option>
                                                    </select>
                                                    @error('grade_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="general_plan_price">{{ __('dashboard.grade_plan.fields.general_plan_price') }}</label>
                                                    <input type="number" step="0.01" id="general_plan_price" class="form-control @error('general_plan_price') is-invalid @enderror"
                                                           name="general_plan_price" value="{{ old('general_plan_price', $gradePlan->general_plan_price) }}"
                                                           placeholder="{{ __('dashboard.grade_plan.fields.general_plan_price') }}">
                                                    @error('general_plan_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="is_active">{{ __('dashboard.common.status') }}</label>
                                                    <select id="is_active" name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                                                        <option value="1" {{ old('is_active', $gradePlan->is_active) == 1 ? 'selected' : '' }}>{{ __('dashboard.common.active') }}</option>
                                                        <option value="0" {{ old('is_active', $gradePlan->is_active) == 0 ? 'selected' : '' }}>{{ __('dashboard.common.inactive') }}</option>
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

@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stageSelect = document.getElementById('stage_id');
            const gradeSelect = document.getElementById('grade_id');
            const baseUrl = stageSelect?.dataset.gradesUrl;
            const selectLabel = @json(__('dashboard.common.select').' '.__('dashboard.grade.title'));

            const renderGrades = (grades, selectedId = null) => {
                gradeSelect.innerHTML = `<option value="">${selectLabel}</option>`;
                grades.forEach(grade => {
                    const option = document.createElement('option');
                    option.value = grade.id;
                    option.textContent = grade.name;
                    if (selectedId && Number(selectedId) === Number(grade.id)) {
                        option.selected = true;
                    }
                    gradeSelect.appendChild(option);
                });
            };

            const fetchGrades = (stageId, selectedId = null) => {
                if (!baseUrl || !stageId) {
                    renderGrades([], selectedId);
                    return;
                }
                fetch(`${baseUrl}/${stageId}`)
                    .then(response => response.json())
                    .then(({data}) => renderGrades(data || [], selectedId))
                    .catch(() => renderGrades([], selectedId));
            };

            stageSelect?.addEventListener('change', (event) => {
                fetchGrades(event.target.value);
            });

            if (stageSelect?.value) {
                fetchGrades(stageSelect.value, gradeSelect.dataset.selectedGrade);
            }
        });
    </script>
@endsection
@endsection
