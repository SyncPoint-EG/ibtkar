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
                                                            <div class="col-md-10">
                                                                <select class="form-control select2" id="select_student" name="student_id"
                                                                        data-placeholder="{{ __('dashboard.student.search_by_name_or_phone') }}">
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="submit"
                                                                        class="btn btn-primary btn-block">{{ __('dashboard.common.add_student') }}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                @if($students && $students->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>{{ __('dashboard.student.fields.name') }}</th>
                                                                <th>{{ __('dashboard.lesson.fields.paid') }}</th>
                                                                <th>{{ __('dashboard.common.watches') }}</th>
                                                                <th>{{ __('dashboard.common.actions') }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($students as $student)
                                                                @php
                                                                    $payment = App\Models\Payment::where('student_id', $student->id)->where('lesson_id', $lesson->id)->first();
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                                                    <td>
                                                                        @if($payment)
                                                                            <span class="badge badge-success">{{ __('dashboard.common.yes') }}</span>
                                                                        @else
                                                                            <span class="badge badge-danger">{{ __('dashboard.common.no') }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $student->watches_count }}</td>
                                                                    <td>
                                                                        <form
                                                                            action="{{ route('lessons.students.watches.update', [$lesson->id, $student->id]) }}"
                                                                            method="POST" class="d-inline-block align-top mr-1">
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
                                                                                method="POST" class="delete-form d-inline-block align-top">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="button" class="btn btn-sm btn-danger delete-payment-btn">
                                                                                    <i class="icon-trash"></i> delete
                                                                                </button>
                                                                            </form>
                                                                        @endif
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
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
            // Lesson delete confirmation
            $('.delete-btn').on('click', function (e) {
                e.preventDefault();
                if (confirm('{{ __("dashboard.lesson.delete_confirm") }}')) {
                    $(this).closest('form.delete-form').submit();
                }
            });

            // Payment delete confirmation
            $('#students-section').on('click', '.delete-payment-btn', function (e) {
                e.preventDefault();
                if (confirm('{{ __("dashboard.payment.delete_confirm") }}')) {
                    $(this).closest('form.delete-form').submit();
                }
            });

            $('#select_student').select2({
                ajax: {
                    url: '{{ route("students.index") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        var results = [];
                        if (data && data.data) {
                            results = data.data.map(function(student) {
                                return {
                                    id: student.id,
                                    text: student.first_name + ' ' + student.last_name,
                                    first_name: student.first_name,
                                    last_name: student.last_name,
                                    phone: student.phone
                                };
                            });
                        }

                        return {
                            results: results,
                            pagination: {
                                more: (params.page * 30) < data.total
                            }
                        };
                    },
                    cache: true
                },
                placeholder: '{{ __("dashboard.student.search_by_name_or_phone") }}',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: function (student) {
                    if (student.loading) {
                        return student.text;
                    }

                    return "<div class='select2-result-repository clearfix'>" +
                        "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'>" + student.first_name + " " + student.last_name + "</div>" +
                        "<div class='select2-result-repository__description'>" + student.phone + "</div>" +
                        "</div></div>";
                },
                templateSelection: function (student) {
                    if (student.first_name && student.last_name) {
                        return student.first_name + " " + student.last_name;
                    }
                    return student.text;
                }
            });
        });
    </script>
@endsection
