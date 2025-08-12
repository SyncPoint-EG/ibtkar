@extends('dashboard.layouts.master')

@section('page_styles')
    <style>
        .spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .sortable {
            cursor: pointer;
            user-select: none;
        }
        .sortable:hover {
            background-color: rgba(0,0,0,0.1);
        }
        #loadingIndicator {
            padding: 2rem;
        }

        .media-body span{
            color: #0a0a0a;
        }
    </style>
@endsection

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.teacher.management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.teacher.title') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Statistics Cards -->
                <div class="row mb-2">
                    <div class="col-xl-3 col-lg-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-block">
                                    <div class="media">
                                        <div class="media-body white text-left">
                                            <h3 class="primary">{{ $stats['total_teachers'] }}</h3>
                                            <span>{{ __('dashboard.teacher.stats.total') }}</span>
                                        </div>
                                        <div class="media-right primary text-right">
                                            <i class="icon-users font-large-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-block">
                                    <div class="media">
                                        <div class="media-body white text-left">
                                            <h3 class="success">{{ $stats['active_teachers'] }}</h3>
                                            <span>{{ __('dashboard.teacher.stats.active') }}</span>
                                        </div>
                                        <div class="media-right success text-right">
                                            <i class="icon-user-check font-large-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-block">
                                    <div class="media">
                                        <div class="media-body white text-left">
                                            <h3 class="warning">{{ $stats['inactive_teachers'] }}</h3>
                                            <span>{{ __('dashboard.teacher.stats.inactive') }}</span>
                                        </div>
                                        <div class="media-right warning text-right">
                                            <i class="icon-user-minus font-large-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-block">
                                    <div class="media">
                                        <div class="media-body white text-left">
                                            <h3 class="info">{{ $stats['average_students_per_teacher'] }}</h3>
                                            <span>{{ __('dashboard.teacher.stats.all_students') }}</span>
                                        </div>
                                        <div class="media-right info text-right">
                                            <i class="icon-graduation font-large-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Table -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('dashboard.teacher.list') }}</h4>
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
                                <!-- Action Buttons and Filters -->
                                <div class="card-block card-dashboard">
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            @can('create_teacher')
                                                <a href="{{ route('teachers.create') }}" class="btn btn-primary mb-1">
                                                    <i class="icon-plus2"></i> {{ __('dashboard.teacher.add_new') }}
                                                </a>
                                            @endcan

                                            <!-- Export Buttons -->
                                            <div class="btn-group mb-1" role="group">
                                                <button type="button" class="btn btn-secondary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icon-download4"></i> {{ __('dashboard.teacher.export') }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('teachers.export', ['format' => 'excel']) }}">
                                                        <i class="icon-file-excel"></i> Excel
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('teachers.export', ['format' => 'pdf']) }}">
                                                        <i class="icon-file-pdf"></i> PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 text-right">
                                            <!-- Per Page Selector -->
                                            <select id="perPageSelect" class="form-control" style="width: auto; display: inline-block;">
                                                <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                                                <option value="25" {{ $per_page == 25 ? 'selected' : '' }}>25</option>
                                                <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
                                                <option value="100" {{ $per_page == 100 ? 'selected' : '' }}>100</option>
                                            </select>
                                            <label for="perPageSelect">{{ __('dashboard.common.per_page') }}</label>
                                        </div>
                                    </div>

                                    <!-- Search and Filters Form -->
                                    <div class="row mb-2" id="searchFilters">
                                        <div class="col-md-2">
                                            <input type="text" id="searchInput" class="form-control"
                                                   placeholder="{{ __('dashboard.teacher.search_placeholder') }}"
                                                   value="{{ $search }}">
                                        </div>
                                        <div class="col-md-2">
                                            <select id="statusFilter" class="form-control">
                                                <option value="">{{ __('dashboard.teacher.all_status') }}</option>
                                                <option value="1" {{ $status === '1' ? 'selected' : '' }}>{{ __('dashboard.teacher.active') }}</option>
                                                <option value="0" {{ $status === '0' ? 'selected' : '' }}>{{ __('dashboard.teacher.inactive') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select id="stageFilter" class="form-control">
                                                <option value="">{{ __('dashboard.teacher.all_stages') }}</option>
                                                @foreach($stages as $stage)
                                                    <option value="{{ $stage->id }}" {{ $stage_id == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select id="subjectFilter" class="form-control">
                                                <option value="">{{ __('dashboard.teacher.all_subjects') }}</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}" {{ $subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select id="gradeFilter" class="form-control">
                                                <option value="">{{ __('dashboard.teacher.all_grades') }}</option>
                                                @foreach($grades as $grade)
                                                    <option value="{{ $grade->id }}" {{ $grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select id="divisionFilter" class="form-control">
                                                <option value="">{{ __('dashboard.teacher.all_divisions') }}</option>
                                                @foreach($divisions as $division)
                                                    <option value="{{ $division->id }}" {{ $division_id == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button type="button" id="clearFilters" class="btn btn-secondary">
                                                <i class="icon-reload"></i> {{ __('dashboard.common.clear') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Loading Indicator -->
                                <div id="loadingIndicator" class="text-center" style="display: none;">
                                    <i class="icon-spinner spinner font-large-2"></i>
                                    <p>{{ __('dashboard.common.loading') }}</p>
                                </div>

                                <!-- Teachers Table Container -->
                                <div id="teachersTableContainer">
                                    @include('dashboard.teachers.partials.table', ['teachers' => $teachers])
                                </div>

                                <!-- Pagination Container -->
                                <div id="paginationContainer">
                                    @include('dashboard.teachers.partials.pagination', ['teachers' => $teachers])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('dashboard.teacher.delete_confirm_title') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('dashboard.common.cancel') }}
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            {{ __('dashboard.common.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            let searchTimeout;
            let currentPage = 1;
            let sortBy = '{{ $sort_by }}';
            let sortDirection = '{{ $sort_direction }}';

            function buildSearchParams(page = 1) {
                return {
                    search: $('#searchInput').val(),
                    status: $('#statusFilter').val(),
                    stage_id: $('#stageFilter').val(),
                    subject_id: $('#subjectFilter').val(),
                    grade_id: $('#gradeFilter').val(),
                    division_id: $('#divisionFilter').val(),
                    sort_by: sortBy,
                    sort_direction: sortDirection,
                    per_page: $('#perPageSelect').val(),
                    page: page
                };
            }

            function searchTeachers(page = 1) {
                const data = buildSearchParams(page);
                $('#loadingIndicator').show();
                $('#teachersTableContainer, #paginationContainer').hide();

                $.ajax({
                    url: '{{ route("teachers.search") }}',
                    method: 'GET',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            $('#teachersTableContainer').html(response.html).show();
                            $('#paginationContainer').html(response.pagination).show();
                            currentPage = response.current_page;
                            bindTableEvents();
                        }
                    },
                    error: function() {
                        toastr.error('{{ __("dashboard.common.search_error") }}');
                    },
                    complete: function() {
                        $('#loadingIndicator').hide();
                    }
                });
            }

            function bindTableEvents() {
                // Status toggle
                $('.status-toggle').off('change').on('change', function() {
                    const teacherId = $(this).data('teacher-id');
                    const isChecked = $(this).is(':checked');
                    const toggle = $(this);

                    $.ajax({
                        url: `/teachers/${teacherId}/toggle-activation`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                                toggle.prop('checked', !isChecked);
                            }
                        },
                        error: function() {
                            toastr.error('{{ __("dashboard.common.error") }}');
                            toggle.prop('checked', !isChecked);
                        }
                    });
                });

                // Featured toggle
                $('.featured-toggle').off('change').on('change', function() {
                    const teacherId = $(this).data('teacher-id');
                    const isChecked = $(this).is(':checked');
                    const toggle = $(this);

                    $.ajax({
                        url: `/teachers/${teacherId}/toggle-featured`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                                toggle.prop('checked', !isChecked);
                            }
                        },
                        error: function() {
                            toastr.error('{{ __("dashboard.common.error") }}');
                            toggle.prop('checked', !isChecked);
                        }
                    });
                });

                // Delete confirmation
                $('.delete-teacher').off('click').on('click', function() {
                    const teacherId = $(this).data('teacher-id');
                    const teacherName = $(this).data('teacher-name');

                    $('#deleteMessage').text(`{{ __('dashboard.teacher.delete_confirm') }} "${teacherName}"?`);
                    $('#deleteForm').attr('action', `/teachers/${teacherId}`);
                    $('#deleteModal').modal('show');
                });

                // Sorting
                $('.sortable').off('click').on('click', function(e) {
                    e.preventDefault();
                    const newSortBy = $(this).data('sort');

                    if (sortBy === newSortBy) {
                        sortDirection = (sortDirection === 'asc') ? 'desc' : 'asc';
                    } else {
                        sortBy = newSortBy;
                        sortDirection = 'asc';
                    }

                    searchTeachers(currentPage);
                });

                // Pagination
                $('#paginationContainer').off('click', '.pagination a').on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    const url = new URL($(this).attr('href'), window.location.origin);
                    const page = url.searchParams.get('page');
                    if (page) {
                        searchTeachers(page);
                    }
                });
            }

            $('#searchInput').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    searchTeachers(1);
                }, 500);
            });

            $('#statusFilter, #stageFilter, #subjectFilter, #gradeFilter, #divisionFilter').on('change', function() {
                searchTeachers(1);
            });

            $('#perPageSelect').on('change', function() {
                searchTeachers(1);
            });

            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                $('#statusFilter').val('');
                $('#stageFilter').val('');
                $('#subjectFilter').val('');
                $('#gradeFilter').val('');
                $('#divisionFilter').val('');
                sortBy = 'id';
                sortDirection = 'asc';
                searchTeachers(1);
            });

            bindTableEvents();
        });
    </script>
@endsection
