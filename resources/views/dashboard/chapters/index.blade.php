@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.chapter.management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.chapter.title') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Table head options start -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('dashboard.chapter.list') }}</h4>
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
                                    @can('create_chapter')
                                        <a href="{{ route('chapters.create') }}" class="btn btn-primary m-1">
                                            <i class="icon-plus2"></i> {{ __('dashboard.chapter.add_new') }}
                                        </a>
                                    @endcan

                                    <form method="GET" action="{{ route('chapters.index') }}" class="form">
                                        <div class="row m-1">
                                            <div class="col-md-4">
                                                <input type="text" name="name" class="form-control" placeholder="{{ __('dashboard.chapter.fields.name') }}" value="{{ $filters['name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <select name="course_id" class="form-control">
                                                    <option value="">{{ __('dashboard.course.title') }}</option>
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" {{ isset($filters['course_id']) && $filters['course_id'] == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select name="teacher_id" class="form-control">
                                                    <option value="">{{ __('dashboard.teacher.title') }}</option>
                                                    @foreach($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}" {{ isset($filters['teacher_id']) && $filters['teacher_id'] == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row m-1">

                                            <div class="col-md-4">
                                                <select name="stage_id" class="form-control">
                                                    <option value="">{{ __('dashboard.stage.title') }}</option>
                                                    @foreach($stages as $stage)
                                                        <option value="{{ $stage->id }}" {{ isset($filters['stage_id']) && $filters['stage_id'] == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select name="grade_id" class="form-control">
                                                    <option value="">{{ __('dashboard.grade.title') }}</option>
                                                    @foreach($grades as $grade)
                                                        <option value="{{ $grade->id }}" {{ isset($filters['grade_id']) && $filters['grade_id'] == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select name="division_id" class="form-control">
                                                    <option value="">{{ __('dashboard.division.title') }}</option>
                                                    @foreach($divisions as $division)
                                                        <option value="{{ $division->id }}" {{ isset($filters['division_id']) && $filters['division_id'] == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary m-1">{{ __('dashboard.common.filter') }}</button>
                                        <a href="{{ route('chapters.index') }}" class="btn btn-secondary m-1">{{ __('dashboard.common.reset') }}</a>
                                    </form>
                                </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-inverse">
                                        <tr>
                                            <th>{{ __('dashboard.common.number') }}</th>
                                            <th>{{ __("dashboard.chapter.fields.name") }}</th>
                                            <th>{{ __("dashboard.course.title") }}</th>
                                            <th>{{ __("dashboard.teacher.title") }}</th>
                                            <th>{{ __("dashboard.chapter.fields.price") }}</th>
                                            <th>{{ __('dashboard.common.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($chapters as $chapter)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $chapter->name }}</td>
                                                <td>{{ $chapter->course?->name }}</td>
                                                <td>{{ $chapter->course?->teacher?->name }}</td>
                                                <td>{{ $chapter->price }}</td>
                                                <td>
                                                    @can('view_chapter')
                                                        <a href="{{ route('chapters.show', $chapter->id) }}"
                                                           class="btn btn-info btn-sm">
                                                            <i class="icon-eye6"></i> {{ __('dashboard.common.view') }}
                                                        </a>
                                                    @endcan

                                                    @can('edit_chapter')
                                                        <a href="{{ route('chapters.edit', $chapter->id) }}"
                                                           class="btn btn-warning btn-sm">
                                                            <i class="icon-pencil3"></i> {{ __('dashboard.common.edit') }}
                                                        </a>
                                                    @endcan

                                                    @can('delete_chapter')
                                                        <form action="{{ route('chapters.destroy', $chapter->id) }}"
                                                              method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('{{ __('dashboard.chapter.delete_confirm') }}');">
                                                                <i class="icon-trash4"></i> {{ __('dashboard.common.delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 2 + count(Schema::getColumnListing('chapters')) }}"
                                                    class="text-center">{{ __('dashboard.chapter.no_records') }}</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{$chapters->links()}}

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
