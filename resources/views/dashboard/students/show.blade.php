@extends('dashboard.layouts.master')

@section('content')
    <!-- Content section -->
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.student.view') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('students.index') }}">{{ __('dashboard.student.list') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.student.view') }}</li>
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
                                    <h4 class="card-title">{{ __('dashboard.student.title') }} {{ __('dashboard.common.information') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="{{ route('students.edit', $student->id) }}"
                                                   class="btn btn-sm btn-primary"><i
                                                        class="icon-pencil"></i> {{ __('dashboard.common.edit') }}</a>
                                            </li>
                                            <li><a href="{{ route('students.index') }}"
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
                                                            <td>{{ $student->id }}</td>
                                                        </tr>

                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.first_name") }}
                                                                :</strong> {{ $student->first_name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.last_name") }}
                                                                :</strong> {{ $student->last_name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.phone") }}
                                                                :</strong> {{ $student->phone }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.governorate_id") }}
                                                                :</strong> {{ $student->governorate_id }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.district_id") }}
                                                                :</strong> {{ $student->district_id }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.center_id") }}
                                                                :</strong> {{ $student->center_id }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.stage_id") }}
                                                                :</strong> {{ $student->stage_id }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.grade_id") }}
                                                                :</strong> {{ $student->grade_id }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.division_id") }}
                                                                :</strong> {{ $student->division_id }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.gender") }}
                                                                :</strong> {{ $student->gender }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.birth_date") }}
                                                                :</strong> {{ $student->birth_date }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.student.fields.status") }}
                                                                :</strong> {{ $student->status }}
                                                        </div>

                                                        <tr>
                                                            <th>{{ __('dashboard.common.created_at') }}</th>
                                                            <td>{{ $student->created_at->format('Y-m-d H:i:s') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('dashboard.common.updated_at') }}</th>
                                                            <td>{{ $student->updated_at->format('Y-m-d H:i:s') }}</td>
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
                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
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

                <!-- Purchased Lessons Section -->
                <section id="purchased-lessons">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('dashboard.student.purchased_lessons') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="{{ route('students.export.lessons', $student->id) }}"
                                                   class="btn btn-sm btn-success"><i
                                                        class="icon-download"></i> {{ __('dashboard.common.export') }}
                                                </a></li>
                                            <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <form method="GET" action="{{ route('students.show', $student->id) }}">
                                                    <input type="text" name="search" class="form-control"
                                                           style="width: auto; display: inline-block;"
                                                           placeholder="{{ __('dashboard.lesson.search_placeholder') }}"
                                                           value="{{ request('search') }}">
                                                    <button type="submit"
                                                            class="btn btn-primary">{{ __('dashboard.common.search') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>{{ __('dashboard.lesson.fields.name') }}</th>
                                                    <th>{{ __('dashboard.teacher.title') }}</th>
                                                    <th>{{ __('dashboard.course.title') }}</th>
                                                    <th>{{ __('dashboard.lesson.fields.price') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($purchasedLessons as $lesson)
                                                    <tr>
                                                        <td>{{ $lesson->name }}</td>
                                                        <td>{{ $lesson->chapter->course->teacher->name ?? '' }}</td>
                                                        <td>{{ $lesson->chapter->course->name ?? '' }}</td>
                                                        <td>{{ $lesson->price }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4"
                                                            class="text-center">{{ __('dashboard.lesson.no_records') }}</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center">
                                            {{ $purchasedLessons->withQueryString()->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Purchased Lessons Section end -->
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
                if (confirm('{{ __("dashboard.student.delete_confirm") }}')) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endsection
