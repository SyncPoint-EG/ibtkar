@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{ __('dashboard.code.management') }}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.code.title') }}
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
                                <h4 class="card-title">{{ __('dashboard.code.list') }}</h4>
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
                                    <form action="{{ route('codes.index') }}" method="GET" class="form-inline mb-1">
                                        <div class="form-group">
                                            <label for="teacher_id"
                                                   class="mr-1">{{ __("dashboard.code.fields.teacher") }}</label>
                                            <select id="teacher_id" name="teacher_id" class="form-control mr-1">
                                                <option
                                                    value="">{{ __("dashboard.common.select") }} {{ __("dashboard.code.fields.teacher") }}</option>
                                                @foreach($teachers as $teacher)
                                                    <option
                                                        value="{{ $teacher->id }}" {{ isset($filters['teacher_id']) && $filters['teacher_id'] == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="expires_at"
                                                   class="mr-1">{{ __("dashboard.code.fields.expires_at") }}</label>
                                            <input type="date" id="expires_at" name="expires_at"
                                                   class="form-control mr-1" value="{{ $filters['expires_at'] ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="for" class="mr-1">{{ __("dashboard.code.fields.for") }}</label>
                                            <select id="for" name="for" class="form-control mr-1">
                                                <option
                                                    value="">{{ __("dashboard.common.select") }} {{ __("dashboard.code.fields.for") }}</option>
                                                @php
                                                    $forOptions = ['course', 'chapter', 'lesson', 'charge'];
                                                @endphp
                                                @foreach($forOptions as $option)
                                                    <option
                                                        value="{{ $option }}" {{ isset($filters['for']) && $filters['for'] == $option ? 'selected' : '' }}>
                                                        {{ ucfirst($option) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="created_at_from"
                                                   class="mr-1">{{ __("dashboard.common.created_at_from") }}</label>
                                            <input type="datetime-local" id="created_at_from" name="created_at_from"
                                                   class="form-control mr-1"
                                                   value="{{ $filters['created_at_from'] ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="created_at_to"
                                                   class="mr-1">{{ __("dashboard.common.created_at_to") }}</label>
                                            <input type="datetime-local" id="created_at_to" name="created_at_to"
                                                   class="form-control mr-1"
                                                   value="{{ $filters['created_at_to'] ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="code"
                                                   class="mr-1">{{ __("dashboard.code.fields.code") }}</label>
                                            <input type="text" id="code" name="code"
                                                   class="form-control mr-1" value="{{ $filters['code'] ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="code_classification"
                                                   class="mr-1">{{ __("dashboard.code.fields.code_classification") }}</label>
                                            <select id="code_classification" name="code_classification"
                                                    class="form-control mr-1">
                                                <option
                                                    value="">{{ __("dashboard.common.select") }} {{ __("dashboard.code.fields.code_classification") }}</option>
                                                @foreach($codeClassifications as $classification)
                                                    <option
                                                        value="{{ $classification }}" {{ isset($filters['code_classification']) && $filters['code_classification'] == $classification ? 'selected' : '' }}>
                                                        {{ $classification }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit"
                                                class="btn btn-primary mr-1">{{ __("dashboard.common.search") }}</button>
                                        <a href="{{ route('codes.index') }}"
                                           class="btn btn-secondary mr-1">{{ __("dashboard.common.clear") }}</a>
                                    </form>
                                    @can('edit_code')
                                        <div class="card mt-1">
                                            <div class="card-header">
                                                <h4 class="card-title">Bulk update by classification</h4>
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ route('codes.bulk-update-classification') }}" method="POST" id="bulk-update-form" class="form-inline">
                                                    @csrf
                                                    <div class="form-group mr-1">
                                                        <label for="bulk_code_classification" class="mr-1">Classification</label>
                                                        <select id="bulk_code_classification" name="code_classification" class="form-control" required>
                                                            <option value="">Select classification</option>
                                                            @foreach($codeClassifications as $classification)
                                                                <option value="{{ $classification }}">{{ $classification }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mr-1">
                                                        <label for="bulk_for" class="mr-1">For</label>
                                                        <select id="bulk_for" name="for" class="form-control">
                                                            <option value="">Keep as is</option>
                                                            @foreach(['course', 'chapter', 'lesson', 'charge'] as $option)
                                                                <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mr-1">
                                                        <label for="bulk_teacher_id" class="mr-1">Teacher</label>
                                                        <select id="bulk_teacher_id" name="teacher_id" class="form-control">
                                                            <option value="">Keep as is</option>
                                                            @foreach($teachers as $teacher)
                                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mr-1">
                                                        <label for="bulk_expires_at" class="mr-1">Expires at</label>
                                                        <input type="date" id="bulk_expires_at" name="expires_at" class="form-control">
                                                    </div>
                                                    <div class="form-group mr-1">
                                                        <label for="bulk_price" class="mr-1">Price</label>
                                                        <input type="number" step="0.01" id="bulk_price" name="price" class="form-control" placeholder="Keep as is">
                                                    </div>
                                                    <button type="submit" class="btn btn-warning">Update all</button>
                                                </form>
                                                <p class="mt-1 text-muted small">Select a classification to prefill fields from an existing code. Only non-empty fields are applied.</p>
                                            </div>
                                        </div>
                                    @endcan
                                    @can('create_code')
                                        <a href="{{ route('codes.create') }}" class="btn btn-primary mb-1">
                                            <i class="icon-plus2"></i> {{ __('dashboard.code.add_new') }}
                                        </a>
                                    @endcan
                                    <a href="{{ route('codes.export', request()->query()) }}"
                                       class="btn btn-success mb-1">
                                        <i class="icon-file-excel"></i> {{ __("dashboard.common.export") }}
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-inverse">
                                        <tr>
                                            <th>{{ __('dashboard.common.number') }}</th>
                                            <th>{{ __("dashboard.code.fields.code") }}</th>
                                            <th>{{ __("dashboard.code.fields.for") }}</th>
                                            <th>{{ __("dashboard.code.fields.number_of_uses") }}</th>
                                            <th>{{ __("dashboard.code.fields.expires_at") }}</th>
                                            <th>{{ __("dashboard.code.fields.code_classification") }}</th>
                                            <th>{{ __("dashboard.code.fields.teacher") }}</th>
                                            <th>{{ __("dashboard.student.fields.name") }}</th>
                                            <th>{{ __("dashboard.student.fields.phone") }}</th>
                                            <th>{{ __("dashboard.code.fields.used_in") }}</th>
                                            <th>{{ __("dashboard.code.fields.used_at") }}</th>
                                            <th>{{ __("dashboard.code.fields.price") }}</th>
                                            <th>{{ __("dashboard.common.created_at") }}</th>
                                            <th>{{ __('dashboard.common.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($codes as $code)
                                            <tr>
                                                @php
                                                    $for = $code->for ;
                                                @endphp
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $code->code }}</td>
                                                <td>{{ $code->for }}</td>
                                                <td>{{ $code->number_of_uses }}</td>
                                                <td>{{ $code->expires_at ? $code->expires_at->format('Y-m-d') : '' }}</td>
                                                <td>{{ $code->code_classification ?? 'N/A' }}</td>
                                                <td>{{ $code->teacher->name ?? '' }}</td>
                                                <td>{{ $code->payment->student->name ?? '' }}</td>
                                                <td>{{ $code->payment->student->phone ?? '' }}</td>
                                                <td>{{ $code->payment->$for->name ?? 'N/A' }}</td>
                                                <td>{{ $code->payment->created_at ?? 'N/A' }}</td>
                                                <td>{{ $code->price ?? 'N/A' }}</td>
                                                <td>{{ $code->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    @can('view_code')
                                                        <a href="{{ route('codes.show', $code->id) }}"
                                                           class="btn btn-info btn-sm">
                                                            <i class="icon-eye6"></i> {{ __('dashboard.common.view') }}
                                                        </a>
                                                    @endcan

                                                    @can('edit_code')
                                                        <a href="{{ route('codes.edit', $code->id) }}"
                                                           class="btn btn-warning btn-sm">
                                                            <i class="icon-pencil3"></i> {{ __('dashboard.common.edit') }}
                                                        </a>
                                                    @endcan

                                                    @can('delete_code')
                                                        <form action="{{ route('codes.destroy', $code->id) }}"
                                                              method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('{{ __('dashboard.code.delete_confirm') }}');">
                                                                <i class="icon-trash4"></i> {{ __('dashboard.common.delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 2 + count(Schema::getColumnListing('codes')) }}"
                                                    class="text-center">{{ __('dashboard.code.no_records') }}</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{$codes->withQueryString()->links()}}

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
        document.addEventListener('DOMContentLoaded', function () {
            const classificationSelect = document.getElementById('bulk_code_classification');
            if (!classificationSelect) return;

            classificationSelect.addEventListener('change', function () {
                const value = this.value;
                if (!value) {
                    document.getElementById('bulk_for').value = '';
                    document.getElementById('bulk_teacher_id').value = '';
                    document.getElementById('bulk_expires_at').value = '';
                    document.getElementById('bulk_price').value = '';
                    return;
                }
                fetch("{{ url('codes/classification') }}/" + encodeURIComponent(value) + "/defaults")
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            document.getElementById('bulk_for').value = data.data.for || '';
                            document.getElementById('bulk_teacher_id').value = data.data.teacher_id || '';
                            document.getElementById('bulk_expires_at').value = data.data.expires_at ? data.data.expires_at.substring(0,10) : '';
                            document.getElementById('bulk_price').value = data.data.price ?? '';
                        }
                    })
                    .catch(() => {});
            });
        });
    </script>
@endsection
