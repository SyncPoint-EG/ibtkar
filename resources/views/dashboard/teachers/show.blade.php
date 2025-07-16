@extends('dashboard.layouts.master')

@section('content')
    <!-- Content section -->
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.teacher.view') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('teachers.index') }}">{{ __('dashboard.teacher.list') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.teacher.view') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic example section start -->
                <section id="basic-examples">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('dashboard.teacher.title') }} {{ __('dashboard.common.information') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="{{ route('teachers.edit', $teacher->id) }}"
                                                   class="btn btn-sm btn-primary"><i
                                                        class="icon-pencil"></i> {{ __('dashboard.common.edit') }}</a>
                                            </li>
                                            <li><a href="{{ route('teachers.index') }}"
                                                   class="btn btn-sm btn-secondary"><i
                                                        class="icon-arrow-left4"></i> {{ __('dashboard.common.back') }}
                                                </a></li>
                                            <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <tbody>
                                                        <tr>
                                                            <th width="200">{{ __('dashboard.common.id') }}</th>
                                                            <td>{{ $teacher->id }}</td>
                                                        </tr>

                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.teacher.fields.name") }}
                                                                :</strong> {{ $teacher->name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.teacher.fields.phone") }}
                                                                :</strong> {{ $teacher->phone }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.teacher.fields.other_phone") }}
                                                                :</strong> {{ $teacher->other_phone }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.teacher.fields.bio") }}
                                                                :</strong> {{ $teacher->bio }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.teacher.fields.image") }}
                                                                :</strong> <img src="{{ $teacher->image }}" width="100px">
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.teacher.fields.rate") }}
                                                                :</strong> {{ $teacher->rate }}
                                                        </div>

                                                        <tr>
                                                            <th>{{ __('dashboard.common.created_at') }}</th>
                                                            <td>{{ $teacher->created_at->format('Y-m-d H:i:s') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('dashboard.common.updated_at') }}</th>
                                                            <td>{{ $teacher->updated_at->format('Y-m-d H:i:s') }}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST"
                                                  class="delete-form d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-md delete-btn">
                                                    <i class="icon-trash"></i> {{ __('dashboard.common.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic example section end -->

                <!-- Courses section start -->
                <section id="courses-section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('dashboard.course.list') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="{{ route('courses.create', $teacher->id) }}"
                                                   class="btn btn-sm btn-success"><i
                                                        class="icon-plus"></i> {{ __('dashboard.course.add_new') }}</a>
                                            </li>
                                            <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if($teacher->courses && $teacher->courses->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>{{ __('dashboard.common.id') }}</th>
                                                                <th>{{ __('dashboard.course.fields.name') }}</th>
                                                                <th>{{ __('dashboard.course.fields.year') }}</th>
                                                                <th>{{ __('dashboard.course.fields.education_type') }}</th>
                                                                <th>{{ __('dashboard.course.fields.stage') }}</th>
                                                                <th>{{ __('dashboard.course.fields.grade') }}</th>
                                                                <th>{{ __('dashboard.course.fields.division') }}</th>
                                                                <th>{{ __('dashboard.course.fields.semister') }}</th>
                                                                <th>{{ __('dashboard.course.fields.subject') }}</th>
                                                                <th>{{ __('dashboard.common.created_at') }}</th>
                                                                <th>{{ __('dashboard.common.actions') }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($teacher->courses as $course)
                                                                <tr>
                                                                    <td>{{ $course->id }}</td>
                                                                    <td>{{ $course->name }}</td>
                                                                    <td>{{ $course->year }}</td>
                                                                    <td>{{ $course->educationType->name ?? '-' }}</td>
                                                                    <td>{{ $course->stage->name ?? '-' }}</td>
                                                                    <td>{{ $course->grade->name ?? '-' }}</td>
                                                                    <td>{{ $course->division->name ?? '-' }}</td>
                                                                    <td>{{ $course->semister->name ?? '-' }}</td>
                                                                    <td>{{ $course->subject->name ?? '-' }}</td>
                                                                    <td>{{ $course->created_at->format('Y-m-d H:i:s') }}</td>
                                                                    <td>
                                                                        <a href="{{ route('courses.show', $course->id) }}"
                                                                           class="btn btn-sm btn-primary">
                                                                            <i class="icon-eye"></i> {{ __('dashboard.common.view') }}
                                                                        </a>
                                                                        <a href="{{ route('courses.edit', $course->id) }}"
                                                                           class="btn btn-sm btn-warning">
                                                                            <i class="icon-pencil"></i> {{ __('dashboard.common.edit') }}
                                                                        </a>
                                                                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST"
                                                                              class="delete-form d-inline-block">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button" class="btn btn-sm btn-danger delete-course-btn">
                                                                                <i class="icon-trash"></i> {{ __('dashboard.common.delete') }}
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="icon-info"></i> {{ __('dashboard.course.no_courses') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Courses section end -->
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
            $('.delete-btn').on('click', function (e) {
                e.preventDefault();

                // SweetAlert or custom confirmation
                if (confirm('{{ __("dashboard.teacher.delete_confirm") }}')) {
                    $(this).closest('form').submit();
                }
            });

            $('.delete-course-btn').on('click', function (e) {
                e.preventDefault();

                // SweetAlert or custom confirmation
                if (confirm('{{ __("dashboard.course.delete_confirm") }}')) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endsection
