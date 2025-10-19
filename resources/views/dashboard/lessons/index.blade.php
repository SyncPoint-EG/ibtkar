@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.lesson.management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.lesson.title') }}
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
                                <h4 class="card-title">{{ __('dashboard.lesson.list') }}</h4>
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
                                    <form method="GET" action="{{ route('lessons.index') }}">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label for="name">{{ __('dashboard.lesson.fields.name') }}</label>
                                                    <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}">
                                                </div>
                                            </div>
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
                                                    <label for="course_id">{{ __('dashboard.course.title') }}</label>
                                                    <select name="course_id" id="course_id" class="form-control">
                                                        <option value="">{{ __('dashboard.common.all') }}</option>
                                                        @foreach($courses as $course)
                                                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                        <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label for="chapter_id">{{ __('dashboard.chapter.title') }}</label>
                                                    <select name="chapter_id" id="chapter_id" class="form-control">
                                                        <option value="">{{ __('dashboard.common.all') }}</option>
                                                        @foreach($chapters as $chapter)
                                                            <option value="{{ $chapter->id }}" {{ request('chapter_id') == $chapter->id ? 'selected' : '' }}>{{ $chapter->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label for="created_at">{{ __('dashboard.common.created_at') }}</label>
                                                    <input type="date" name="created_at" id="created_at" class="form-control" value="{{ request('created_at') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label for="date">{{ __('dashboard.lesson.fields.date') }}</label>
                                                    <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
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
                                        <a href="{{ route('lessons.index') }}" class="btn btn-secondary">{{ __('dashboard.common.reset') }}</a>

                                    </form>

                                </div>
                                <div class="col-lg-6">
                                    @can('create_lesson')
                                        <a href="{{ route('lessons.create') }}" class="btn btn-primary mb-1">
                                            <i class="icon-plus2"></i> {{ __('dashboard.lesson.add_new') }}
                                        </a>
                                    @endcan
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-inverse">
                                        <tr>
                                            <th>{{ __('dashboard.common.number') }}</th>
                                            <th>{{ __("dashboard.lesson.fields.name") }}</th>
                                            <th>{{ __("dashboard.lesson.fields.desc") }}</th>
                                            <th>{{ __("dashboard.lesson.fields.video_link") }}</th>
                                            <th>{{ __("dashboard.lesson.fields.video_image") }}</th>
                                            <th>{{ __("dashboard.chapter.title") }}</th>
                                            <th>{{ __("dashboard.lesson.fields.price") }}</th>
                                            <th>{{ __("dashboard.lesson.fields.is_featured") }}</th>
                                            <th>{{ __("dashboard.lesson.fields.type") }}</th>
                                            <th>{{ __('dashboard.common.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($lessons as $lesson)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $lesson->name }}</td>
                                                <td>{{ $lesson->desc }}</td>
                                                <td><a href="{{ $lesson->video_link }}">{{__('dashboard.lesson.fields.video_link')}}</a></td>
                                                <td><img src="{{ $lesson->video_image }}" width="100px"></td>
                                                <td>{{ $lesson->chapter?->name }}</td>
                                                <td>{{ $lesson->price }}</td>
                                                <td>
                                                    <input type="checkbox" class="featured-toggle" data-lesson-id="{{ $lesson->id }}" {{ $lesson->is_featured ? 'checked' : '' }} />
                                                </td>
                                                <td>{{ __('dashboard.lesson.types.' . $lesson->type) }}</td>
                                                <td>
                                                    @can('view_lesson')
                                                        <a href="{{ route('lessons.show', $lesson->id) }}"
                                                           class="btn btn-info btn-sm">
                                                            <i class="icon-eye6"></i> {{ __('dashboard.common.view') }}
                                                        </a>
                                                    @endcan

                                                    @can('edit_lesson')
                                                        <a href="{{ route('lessons.edit', $lesson->id) }}"
                                                           class="btn btn-warning btn-sm">
                                                            <i class="icon-pencil3"></i> {{ __('dashboard.common.edit') }}
                                                        </a>
                                                    @endcan

                                                    @can('delete_lesson')
                                                        <form action="{{ route('lessons.destroy', $lesson->id) }}"
                                                              method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('{{ __('dashboard.lesson.delete_confirm') }}');">
                                                                <i class="icon-trash4"></i> {{ __('dashboard.common.delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 2 + count(Schema::getColumnListing('lessons')) }}"
                                                    class="text-center">{{ __('dashboard.lesson.no_records') }}</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{$lessons->links()}}

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
        $(document).ready(function() {
            $('.featured-toggle').on('change', function() {
                const lessonId = $(this).data('lesson-id');
                const isChecked = $(this).is(':checked');
                const toggle = $(this);

                $.ajax({
                    url: `/lessons/${lessonId}/toggle-featured`,
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
        });
    </script>
@endsection
