@extends('dashboard.layouts.master')

@section('content')
    <!-- Content section -->
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.course.view') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('dashboard.course.list') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.course.view') }}</li>
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
                                    <h4 class="card-title">{{ __('dashboard.course.title') }} {{ __('dashboard.common.information') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-primary"><i class="icon-pencil"></i> {{ __('dashboard.common.edit') }}</a></li>
                                            <li><a href="{{ route('courses.index') }}" class="btn btn-sm btn-secondary"><i class="icon-arrow-left4"></i> {{ __('dashboard.common.back') }}</a></li>
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
                                                            <td>{{ $course->id }}</td>
                                                        </tr>

                                                        <div class="mb-3">
        <strong>{{ __("dashboard.course.fields.name") }}:</strong> {{ $course->name }}
    </div>
                <div class="mb-3">
        <strong>{{ __("dashboard.course.fields.year") }}:</strong> {{ $course->year }}
    </div>
                <div class="mb-3">
        <strong>{{ __("dashboard.course.fields.teacher_id") }}:</strong> {{ $course->teacher_id }}
    </div>
                <div class="mb-3">
        <strong>{{ __("dashboard.course.fields.education_type_id") }}:</strong> {{ $course->education_type_id }}
    </div>
                <div class="mb-3">
        <strong>{{ __("dashboard.course.fields.stage_id") }}:</strong> {{ $course->stage_id }}
    </div>
                <div class="mb-3">
        <strong>{{ __("dashboard.course.fields.grade_id") }}:</strong> {{ $course->grade_id }}
    </div>
                <div class="mb-3">
        <strong>{{ __("dashboard.course.fields.division_id") }}:</strong> {{ $course->division_id }}
    </div>
                <div class="mb-3">
        <strong>{{ __("dashboard.course.fields.semister_id") }}:</strong> {{ $course->semister_id }}
    </div>
                <div class="mb-3">
        <strong>{{ __("dashboard.course.fields.subject_id") }}:</strong> {{ $course->subject_id }}
    </div>

                                                        <tr>
                                                            <th>{{ __('dashboard.common.created_at') }}</th>
                                                            <td>{{ $course->created_at->format('Y-m-d H:i:s') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('dashboard.common.updated_at') }}</th>
                                                            <td>{{ $course->updated_at->format('Y-m-d H:i:s') }}</td>
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
                                            <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="delete-form d-inline-block">
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
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                // SweetAlert or custom confirmation
                if (confirm('{{ __("dashboard.course.delete_confirm") }}')) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endsection
