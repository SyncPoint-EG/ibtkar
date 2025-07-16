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
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('courses.index') }}">{{ __('dashboard.course.list') }}</a></li>
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
                                            <li><a href="{{ route('courses.edit', $course->id) }}"
                                                   class="btn btn-sm btn-primary"><i
                                                        class="icon-pencil"></i> {{ __('dashboard.common.edit') }}</a>
                                            </li>
                                            <li><a href="{{ route('courses.index') }}" class="btn btn-sm btn-secondary"><i
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
                                                            <td>{{ $course->id }}</td>
                                                        </tr>

                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.course.fields.name") }}
                                                                :</strong> {{ $course->name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.course.fields.year") }}
                                                                :</strong> {{ $course->year }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.teacher.title") }}
                                                                :</strong> {{ $course->teacher->name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.educationtype.title") }}
                                                                :</strong> {{ $course->educationType->name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.stage.title") }}
                                                                :</strong> {{ $course->stage->name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.grade.title") }}
                                                                :</strong> {{ $course->grade->name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.division.title") }}
                                                                :</strong> {{ $course->division->name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.semister.title") }}
                                                                :</strong> {{ $course->semister->name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.subject.title") }}
                                                                :</strong> {{ $course->subject->name }}
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
                                            <form action="{{ route('courses.destroy', $course->id) }}" method="POST"
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

                <!-- Chapters section start -->
                <section id="chapters-section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('dashboard.chapter.list') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="{{ route('chapters.create', $course->id) }}"
                                                   class="btn btn-sm btn-success"><i
                                                        class="icon-plus"></i> {{ __('dashboard.chapter.add_new') }}</a>
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
                                                @if($course->chapters && $course->chapters->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>{{ __('dashboard.common.id') }}</th>
                                                                <th>{{ __('dashboard.chapter.fields.name') }}</th>
                                                                <th>{{ __('dashboard.common.created_at') }}</th>
                                                                <th>{{ __('dashboard.common.actions') }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($course->chapters as $chapter)
                                                                <tr>
                                                                    <td>{{ $chapter->id }}</td>
                                                                    <td>{{ $chapter->name }}</td>
                                                                    <td>{{ $chapter->created_at->format('Y-m-d H:i:s') }}</td>
                                                                    <td>
                                                                        <a href="{{ route('chapters.show', $chapter->id) }}"
                                                                           class="btn btn-sm btn-primary">
                                                                            <i class="icon-eye"></i> {{ __('dashboard.common.view') }}
                                                                        </a>
                                                                        <a href="{{ route('chapters.edit', $chapter->id) }}"
                                                                           class="btn btn-sm btn-warning">
                                                                            <i class="icon-pencil"></i> {{ __('dashboard.common.edit') }}
                                                                        </a>
                                                                        <form action="{{ route('chapters.destroy', $chapter->id) }}" method="POST"
                                                                              class="delete-form d-inline-block">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button" class="btn btn-sm btn-danger delete-chapter-btn">
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
                                                        <i class="icon-info"></i> {{ __('dashboard.chapter.no_chapters') }}
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
                <!-- Chapters section end -->
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
                if (confirm('{{ __("dashboard.course.delete_confirm") }}')) {
                    $(this).closest('form').submit();
                }
            });

            $('.delete-chapter-btn').on('click', function (e) {
                e.preventDefault();

                // SweetAlert or custom confirmation
                if (confirm('{{ __("dashboard.chapter.delete_confirm") }}')) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endsection
