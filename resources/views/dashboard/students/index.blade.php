@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.student.management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.student.title') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('dashboard.common.filters') }}</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block">
                            <form action="{{ route('students.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">{{ __('dashboard.student.fields.name') }}</label>
                                            <input type="text" id="name" name="name" class="form-control" value="{{ $filters['name'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="phone">{{ __('dashboard.student.fields.phone') }}</label>
                                            <input type="text" id="phone" name="phone" class="form-control" value="{{ $filters['phone'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="governorate_id">{{ __('dashboard.governorate.title') }}</label>
                                            <select id="governorate_id" name="governorate_id" class="form-control">
                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                @foreach($governorates as $governorate)
                                                    <option value="{{ $governorate->id }}" {{ isset($filters['governorate_id']) && $filters['governorate_id'] == $governorate->id ? 'selected' : '' }}>{{ $governorate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="center_id">{{ __('dashboard.center.title') }}</label>
                                            <select id="center_id" name="center_id" class="form-control">
                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                @foreach($centers as $center)
                                                    <option value="{{ $center->id }}" {{ isset($filters['center_id']) && $filters['center_id'] == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="stage_id">{{ __('dashboard.stage.title') }}</label>
                                            <select id="stage_id" name="stage_id" class="form-control">
                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                @foreach($stages as $stage)
                                                    <option value="{{ $stage->id }}" {{ isset($filters['stage_id']) && $filters['stage_id'] == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="grade_id">{{ __('dashboard.grade.title') }}</label>
                                            <select id="grade_id" name="grade_id" class="form-control">
                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                @foreach($grades as $grade)
                                                    <option value="{{ $grade->id }}" {{ isset($filters['grade_id']) && $filters['grade_id'] == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="division_id">{{ __('dashboard.division.title') }}</label>
                                            <select id="division_id" name="division_id" class="form-control">
                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                @foreach($divisions as $division)
                                                    <option value="{{ $division->id }}" {{ isset($filters['division_id']) && $filters['division_id'] == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="education_type_id">{{ __('dashboard.education_type.title') }}</label>
                                            <select id="education_type_id" name="education_type_id" class="form-control">
                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                @foreach($educationTypes as $educationType)
                                                    <option value="{{ $educationType->id }}" {{ isset($filters['education_type_id']) && $filters['education_type_id'] == $educationType->id ? 'selected' : '' }}>{{ $educationType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">{{ __('dashboard.student.fields.status') }}</label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                <option value="1" {{ isset($filters['status']) && $filters['status'] == '1' ? 'selected' : '' }}>{{ __('dashboard.common.active') }}</option>
                                                <option value="0" {{ isset($filters['status']) && $filters['status'] == '0' ? 'selected' : '' }}>{{ __('dashboard.common.inactive') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="gender">{{ __('dashboard.student.fields.gender') }}</label>
                                            <select id="gender" name="gender" class="form-control">
                                                <option value="">{{ __('dashboard.common.all') }}</option>
                                                <option value="male" {{ isset($filters['gender']) && $filters['gender'] == 'male' ? 'selected' : '' }}>{{ __('dashboard.common.male') }}</option>
                                                <option value="female" {{ isset($filters['gender']) && $filters['gender'] == 'female' ? 'selected' : '' }}>{{ __('dashboard.common.female') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">{{ __('dashboard.common.filter') }}</button>
                                        <a href="{{ route('students.index') }}" class="btn btn-secondary">{{ __('dashboard.common.reset') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Table head options start -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('dashboard.student.list') }}</h4>
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
                                <div class="card-block card-dashboard">
                                    @can('create_student')
                                        <a href="{{ route('students.create') }}" class="btn btn-primary mb-1">
                                            <i class="icon-plus2"></i> {{ __('dashboard.student.add_new') }}
                                        </a>
                                    @endcan

                                    @can('view_student')
                                        <a href="{{ route('students.export', request()->query()) }}" class="btn btn-success mb-1">
                                            <i class="icon-download"></i> {{ __('dashboard.common.export') }}
                                        </a>
                                    @endcan

                                    @can('create_student')
                                        <button type="button" class="btn btn-info mb-1" data-toggle="modal" data-target="#importModal">
                                            <i class="icon-upload"></i> {{ __('dashboard.common.import') }}
                                        </button>
                                    @endcan

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">{{ __('dashboard.student.import_students') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">{{ __('dashboard.common.choose_file') }}</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <hr>
                    <h6>{{ __('dashboard.common.instructions') }}</h6>
                    <p>{{ __('dashboard.student.import_instructions') }}</p>
                    <ul>
                        <li>{{ __('dashboard.student.import_column_first_name') }}</li>
                        <li>{{ __('dashboard.student.import_column_last_name') }}</li>
                        <li>{{ __('dashboard.student.import_column_phone') }}</li>
                        <li>{{ __('dashboard.student.import_column_guardian_phone') }}</li>
                        <li>{{ __('dashboard.student.import_column_district') }}</li>
                        <li>{{ __('dashboard.student.import_column_center') }}</li>
                        <li>{{ __('dashboard.student.import_column_stage') }}</li>
                        <li>{{ __('dashboard.student.import_column_grade') }}</li>
                        <li>{{ __('dashboard.student.import_column_division') }}</li>
                        <li>{{ __('dashboard.student.import_column_gender') }}</li>
                        <li>{{ __('dashboard.student.import_column_birth_date') }}</li>
                        <li>{{ __('dashboard.student.import_column_status') }}</li>
                    </ul>
                    <button type="submit" class="btn btn-primary">{{ __('dashboard.common.import') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-inverse">
                                        <tr>
                                            <th>{{ __('dashboard.common.number') }}</th>
                                            <th>{{ __("dashboard.student.fields.first_name") }}</th>
                                            <th>{{ __("dashboard.student.fields.last_name") }}</th>
                                            <th>{{ __("dashboard.student.fields.phone") }}</th>
                                            <th>{{ __("dashboard.student.fields.guardian_number") }}</th>
                                            <th>{{ __("dashboard.district.title") }}</th>
                                            <th>{{ __("dashboard.center.title") }}</th>
                                            <th>{{ __("dashboard.stage.title") }}</th>
                                            <th>{{ __("dashboard.grade.title") }}</th>
                                            <th>{{ __("dashboard.division.title") }}</th>
                                            <th>{{ __("dashboard.student.fields.gender") }}</th>
                                            <th>{{ __("dashboard.student.fields.birth_date") }}</th>
                                            <th>{{ __("dashboard.student.fields.status") }}</th>
                                            <th>{{ __("dashboard.student.fields.referral_code") }}</th>
                                            <th>{{ __("dashboard.student.fields.points") }}</th>
                                            <th>{{ __("dashboard.student.fields.purchased_lessons") }}</th>
                                            <th>{{ __('dashboard.common.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $student->first_name }}</td>
                                                <td>{{ $student->last_name }}</td>
                                                <td>{{ $student->phone }}</td>
                                                <td>{{ $student->guardian?->phone }}</td>
                                                <td>{{ $student->district?->name }}</td>
                                                <td>{{ $student->center?->name }}</td>
                                                <td>{{ $student->stage?->name }}</td>
                                                <td>{{ $student->grade?->name }}</td>
                                                <td>{{ $student->division?->name }}</td>
                                                <td>{{ $student->gender }}</td>
                                                <td>{{ $student->birth_date ? $student->birth_date->format('Y-m-d') : '' }}</td>
                                                <td>{{ $student->status ? __('dashboard.common.active') : __('dashboard.common.inactive') }}</td>
                                                <td>{{ $student->referral_code }}</td>
                                                <td>{{ $student->points }}</td>
                                                <td>{{ $student->purchased_lessons_count }}</td>
                                                <td>
                                                    @can('view_student')
                                                        <a href="{{ route('students.show', $student->id) }}"
                                                           class="btn btn-info btn-sm">
                                                            <i class="icon-eye6"></i> {{ __('dashboard.common.view') }}
                                                        </a>
                                                    @endcan

                                                    @can('edit_student')
                                                        <a href="{{ route('students.edit', $student->id) }}"
                                                           class="btn btn-warning btn-sm">
                                                            <i class="icon-pencil3"></i> {{ __('dashboard.common.edit') }}
                                                        </a>
                                                    @endcan

                                                    @can('delete_student')
                                                        <form action="{{ route('students.destroy', $student->id) }}"
                                                              method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('{{ __('dashboard.student.delete_confirm') }}');">
                                                                <i class="icon-trash4"></i> {{ __('dashboard.common.delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 2 + count(Schema::getColumnListing('students')) }}"
                                                    class="text-center">{{ __('dashboard.student.no_records') }}</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{$students->appends(request()->query())->links()}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Table head options end -->
            </div>
        </div>
    </div>
@endsection