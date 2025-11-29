@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.course.management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.course.title') }}
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
                                <h4 class="card-title">{{ __('dashboard.course.list') }}</h4>
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
                                    <form method="GET" action="{{ route('courses.index') }}">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label for="teacher_id">{{ __('dashboard.teacher.title') }}</label>
                                                    <select name="teacher_id" id="teacher_id" class="form-control">
                                                        <option value="">{{ __('dashboard.common.all') }}</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label for="grade_id">{{ __('dashboard.grade.title') }}</label>
                                                    <select name="grade_id" id="grade_id" class="form-control">
                                                        <option value="">{{ __('dashboard.common.all') }}</option>
                                                        @foreach($grades as $grade)
                                                            <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">{{ __('dashboard.common.filter') }}</button>
                                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">{{ __('dashboard.common.reset') }}</a>
                                    </form>
                                    @can('create_course')
                                        <a href="{{ route('courses.create') }}" class="btn btn-primary my-1">
                                            <i class="icon-plus2"></i> {{ __('dashboard.course.add_new') }}
                                        </a>
                                    @endcan
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-inverse">
                                        <tr>
                                            <th>{{ __('dashboard.common.number') }}</th>
                                            <th>{{ __("dashboard.course.fields.name") }}</th>
                                            <th>{{ __("dashboard.course.fields.year") }}</th>
                                            <th>{{ __("dashboard.course.fields.teacher_id") }}</th>
                                            <th>{{ __("dashboard.education_type.title") }}</th>
                                            <th>{{ __("dashboard.stage.title") }}</th>
                                            <th>{{ __("dashboard.grade.title") }}</th>
                                            <th>{{ __("dashboard.division.title") }}</th>
                                            <th>{{ __("dashboard.semister.title") }}</th>
                                            <th>{{ __("dashboard.subject.title") }}</th>
                                            <th>{{ __("dashboard.course.fields.price") }}</th>
                                            <th>{{ __("dashboard.course.fields.is_featured") }}</th>
                                            <th>{{ __('dashboard.common.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($courses as $course)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $course->name }}</td>
                                                <td>{{ $course->year  }}</td>
                                                <td>{{ $course->teacher?->name }}</td>
                                                <td>{{ $course->educationType?->name }}</td>
                                                <td>{{ $course->stage?->name }}</td>
                                                <td>{{ $course->grade?->name }}</td>
                                                <td>{{ $course->division?->name }}</td>
                                                <td>{{ $course->semister?->name }}</td>
                                                <td>{{ $course->subject?->name }}</td>
                                                <td>{{ $course->price }}</td>
                                                <td>
                                                    <input type="checkbox" class="toggle-featured" data-id="{{ $course->id }}" {{ $course->is_featured ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    @can('view_course')
                                                        <a href="{{ route('courses.show', $course->id) }}"
                                                           class="btn btn-info btn-sm">
                                                            <i class="icon-eye6"></i> {{ __('dashboard.common.view') }}
                                                        </a>
                                                    @endcan

                                                    @can('edit_course')
                                                        <a href="{{ route('courses.edit', $course->id) }}"
                                                           class="btn btn-warning btn-sm">
                                                            <i class="icon-pencil3"></i> {{ __('dashboard.common.edit') }}
                                                        </a>
                                                    @endcan

                                                    @can('delete_course')
                                                        <form action="{{ route('courses.destroy', $course->id) }}"
                                                              method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('{{ __('dashboard.course.delete_confirm') }}');">
                                                                <i class="icon-trash4"></i> {{ __('dashboard.common.delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 2 + count(Schema::getColumnListing('courses')) }}"
                                                    class="text-center">{{ __('dashboard.course.no_records') }}</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{$courses->withQueryString()->links()}}

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

@section('page_scripts')
    <script>
        $(function() {
            $('.toggle-featured').on('change', function() {
                var courseId = $(this).data('id');
                var url = "{{ route('courses.toggle-featured', ':id') }}".replace(':id', courseId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // alert(response.message);
                        } else {
                            // alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        // alert('Something went wrong.');
                    }
                });
            });
        });
    </script>
@endsection
