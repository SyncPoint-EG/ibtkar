@extends('dashboard.layouts.master')

@section('content')
    <!-- Content section -->
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.lesson.view') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('lessons.index') }}">{{ __('dashboard.lesson.list') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.lesson.view') }}</li>
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
                                    <h4 class="card-title">{{ __('dashboard.lesson.title') }} {{ __('dashboard.common.information') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li>
                                                <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="icon-pencil"></i> {{ __('dashboard.common.edit') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('lessons.index') }}" class="btn btn-sm btn-secondary">
                                                    <i class="icon-arrow-left4"></i> {{ __('dashboard.common.back') }}
                                                </a>
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
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <tbody>
                                                        <tr>
                                                            <th width="200">{{ __('dashboard.common.id') }}</th>
                                                            <td>{{ $lesson->id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.lesson.fields.name") }}</th>
                                                            <td>{{ $lesson->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.lesson.fields.desc") }}</th>
                                                            <td>{{ $lesson->desc }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.lesson.fields.video_link") }}</th>
                                                            <td>
                                                                <a href="{{ $lesson->video_link }}">
                                                                    {{__('dashboard.lesson.fields.video_link')}}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.lesson.fields.video_image") }}</th>
                                                            <td>
                                                                <img src="{{ $lesson->video_image }}" width="100px">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.chapter.title") }}</th>
                                                            <td>
                                                                <a href="{{route('chapters.show',$lesson->chapter_id)}}">
                                                                    {{ $lesson->chapter?->name }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.course.title") }}</th>
                                                            <td>
                                                                <a href="{{route('courses.show',$lesson->chapter?->course_id)}}">
                                                                    {{ $lesson->chapter?->course?->name }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.lesson.fields.type") }}</th>
                                                            <td>{{ __('dashboard.lesson.types.' . $lesson->type) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.lesson.all_purchased_students") }}</th>
                                                            <td>{{$totalStudents}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.lesson.students_watched") }}</th>
                                                            <td>{{$watchedStudents}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.lesson.students_watched_percentage") }}</th>
                                                            <td>{{$totalStudents != 0 ? $watchedStudents / $totalStudents * 100 : 0 .' %'}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __("dashboard.lesson.students_not_watched") }}</th>
                                                            <td>{{$totalStudents - $watchedStudents}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('dashboard.common.created_at') }}</th>
                                                            <td>{{ $lesson->created_at->format('Y-m-d H:i:s') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('dashboard.common.updated_at') }}</th>
                                                            <td>{{ $lesson->updated_at->format('Y-m-d H:i:s') }}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
{{--                                            <div class="col-md-6">--}}
{{--                                                {!! $chart->container() !!}--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST"
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

                <!-- Attachments section start -->
                <section id="attachments">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('dashboard.lesson.attachments') }}</h4>
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
                                        @include('dashboard.lessons.partials._attachments_form')
                                        @include('dashboard.lessons.partials._attachments_table')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Attachments section end -->

                <!-- Students section start -->
                <section id="students-section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('dashboard.student.list') }}</h4>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li>
                                                <a href="{{ route('lessons.students.export', $lesson->id) }}" class="btn btn-sm btn-success">
                                                    <i class="icon-download"></i> {{ __('dashboard.common.export_students') }}
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#importStudentsModal">
                                                    <i class="icon-upload"></i> Import Students
                                                </button>
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
                                                <div class="mb-3">
                                                    <form
                                                        action="{{ route('lessons.students.payments.store', $lesson->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="payment_method" value="gift">
                                                        <div class="row">
                                                            <label>Add Student to this lesson</label>
                                                            <select class="form-control col-12 select2" id="select_student" name="student_id"
                                                                    data-placeholder="{{ __('dashboard.student.search_by_name_or_phone') }}">
                                                                <option></option>
                                                            </select>
                                                            <div class="input-group-append m-1">
                                                                <button type="submit"
                                                                        class="btn btn-primary">{{ __('dashboard.common.add_student') }}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                @if($students && $students->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>{{ __('dashboard.student.fields.id') }}</th>
                                                                <th>{{ __('dashboard.student.fields.name') }}</th>
                                                                <th>{{ __('dashboard.student.fields.phone') }}</th>
                                                                <th>{{ __('dashboard.student.fields.guardian_number') }}</th>
                                                                <th>{{ __('dashboard.common.is_watched') }}</th>
                                                                <th>{{ __('dashboard.common.watches') }}</th>
                                                                <th>{{ __('dashboard.common.actions') }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($students as $student)
                                                                @php
                                                                    $payment = App\Models\Payment::where('student_id', $student->id)->where('lesson_id', $lesson->id)->first();
                                                                    $watch = App\Models\Watch::where('student_id', $student->id)->where('lesson_id', $lesson->id)->first();
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $student->id }} </td>
                                                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                                                    <td>{{ $student->phone }} </td>
                                                                    <td>{{ $student->guardian?->phone }} </td>
                                                                    <td>
                                                                        @if($watch)
                                                                            <span class="badge badge-success">{{ __('dashboard.common.yes') }}</span>
                                                                        @else
                                                                            <span class="badge badge-danger">{{ __('dashboard.common.no') }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $student->watches_count }}</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <form
                                                                                action="{{ route('lessons.students.watches.update', [$lesson->id, $student->id]) }}"
                                                                                method="POST" class="mr-1">
                                                                                @csrf
                                                                                <div class="input-group">
                                                                                    <input type="number" name="watches"
                                                                                           class="form-control"
                                                                                           value="{{ $student->watches_count }}" style="width: 70px;">
                                                                                    <div class="input-group-append">
                                                                                        <button type="submit"
                                                                                                class="btn btn-sm btn-primary">{{ __('dashboard.common.update') }}</button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                            @if($payment)
                                                                                <form
                                                                                    action="{{ route('payments.destroy', $payment->id) }}"
                                                                                    method="POST" class="delete-form">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="button" class="btn btn-sm btn-danger delete-payment-btn" title="{{ __('dashboard.common.delete_payment') }}">
                                                                                        <i class="icon-trash"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="icon-info"></i> {{ __('dashboard.student.no_students') }}
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
                <!-- Students section end -->
            </div>
        </div>
<!-- Import Students Modal -->
<div class="modal fade" id="importStudentsModal" tabindex="-1" role="dialog" aria-labelledby="importStudentsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importStudentsModalLabel">Import Students</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('lessons.students.import', $lesson->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        Please make sure the Excel file has a column named 'phone' containing the student phone numbers.
                    </div>
                    <div class="form-group">
                        <label for="file">Excel File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page_scripts')

    <script>
        $(document).ready(function() {
            $('#select_student').select2({
                ajax: {
                    url: '{{ route("students.index") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                        };
                    },
                    processResults: function (data) {
                        // transform the data to the format select2 expects
                        return {
                            results: data.data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
            });
        });
    </script>

@endsection


