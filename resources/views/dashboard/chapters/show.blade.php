@extends('dashboard.layouts.master')

@section('content')
    <!-- Content section -->
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.chapter.view') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('chapters.index') }}">{{ __('dashboard.chapter.list') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.chapter.view') }}</li>
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
                                    <h4 class="card-title">{{ __('dashboard.chapter.title') }} {{ __('dashboard.common.information') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="{{ route('chapters.edit', $chapter->id) }}"
                                                   class="btn btn-sm btn-primary"><i
                                                        class="icon-pencil"></i> {{ __('dashboard.common.edit') }}</a>
                                            </li>
                                            <li><a href="{{ route('chapters.index') }}"
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
                                                            <td>{{ $chapter->id }}</td>
                                                        </tr>

                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.chapter.fields.name") }}
                                                                :</strong> {{ $chapter->name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>{{ __("dashboard.course.title") }}
                                                                :</strong> {{ $chapter->course->name }}
                                                        </div>

                                                        <tr>
                                                            <th>{{ __('dashboard.common.created_at') }}</th>
                                                            <td>{{ $chapter->created_at->format('Y-m-d H:i:s') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('dashboard.common.updated_at') }}</th>
                                                            <td>{{ $chapter->updated_at->format('Y-m-d H:i:s') }}</td>
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
                                            <form action="{{ route('chapters.destroy', $chapter->id) }}" method="POST"
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

                <!-- Lessons section start -->
                <section id="lessons-section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('dashboard.lesson.list') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="{{ route('lessons.create', ['chapter_id' => $chapter->id]) }}"
                                                   class="btn btn-sm btn-success"><i
                                                        class="icon-plus"></i> {{ __('dashboard.lesson.add') }}</a>
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
                                                @if($chapter->lessons && $chapter->lessons->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>{{ __('dashboard.common.id') }}</th>
                                                                <th>{{ __('dashboard.lesson.fields.name') }}</th>
                                                                <th>{{ __('dashboard.lesson.fields.description') }}</th>
                                                                <th>{{ __('dashboard.lesson.fields.video_image') }}</th>
                                                                <th>{{ __('dashboard.common.created_at') }}</th>
                                                                <th>{{ __('dashboard.common.actions') }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($chapter->lessons as $lesson)
                                                                <tr>
                                                                    <td>{{ $lesson->id }}</td>
                                                                    <td>{{ $lesson->name }}</td>
                                                                    <td>{{ Str::limit($lesson->desc, 50) }}</td>
                                                                    <td>
                                                                        @if($lesson->video_image)
                                                                            <img src="{{ asset($lesson->video_image) }}"
                                                                                 alt="{{ $lesson->name }}"
                                                                                 class="img-thumbnail"
                                                                                 style="width: 60px; height: 40px; object-fit: cover;">
                                                                        @else
                                                                            <span class="text-muted">{{ __('dashboard.common.no_image') }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $lesson->created_at->format('Y-m-d H:i:s') }}</td>
                                                                    <td>
                                                                        <a href="{{ route('lessons.show', $lesson->id) }}"
                                                                           class="btn btn-sm btn-primary">
                                                                            <i class="icon-eye"></i> {{ __('dashboard.common.view') }}
                                                                        </a>
                                                                        <a href="{{ route('lessons.edit', $lesson->id) }}"
                                                                           class="btn btn-sm btn-warning">
                                                                            <i class="icon-pencil"></i> {{ __('dashboard.common.edit') }}
                                                                        </a>
                                                                        <form action="{{ route('lessons.destroy', $lesson->id) }}"
                                                                              method="POST"
                                                                              class="delete-form d-inline-block">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button" class="btn btn-sm btn-danger delete-lesson-btn">
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
                                                    <div class="alert alert-info text-center">
                                                        <i class="icon-info"></i>
                                                        <strong>{{ __('dashboard.lesson.no_lessons') }}</strong>
                                                        <br>
                                                        <a href="{{ route('lessons.create', ['chapter_id' => $chapter->id]) }}"
                                                           class="btn btn-sm btn-success mt-2">
                                                            <i class="icon-plus"></i> {{ __('dashboard.lesson.add_first') }}
                                                        </a>
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
                <!-- Lessons section end -->
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
            // Delete chapter confirmation
            $('.delete-btn').on('click', function (e) {
                e.preventDefault();

                // SweetAlert or custom confirmation
                if (confirm('{{ __("dashboard.chapter.delete_confirm") }}')) {
                    $(this).closest('form').submit();
                }
            });

            // Delete lesson confirmation
            $('.delete-lesson-btn').on('click', function (e) {
                e.preventDefault();

                // SweetAlert or custom confirmation
                if (confirm('{{ __("dashboard.lesson.delete_confirm") }}')) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endsection
