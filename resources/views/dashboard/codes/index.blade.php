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
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>
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
                                            <label for="teacher_id" class="mr-1">{{ __("dashboard.code.fields.teacher") }}</label>
                                            <select id="teacher_id" name="teacher_id" class="form-control mr-1">
                                                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.code.fields.teacher") }}</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" {{ isset($filters['teacher_id']) && $filters['teacher_id'] == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="expires_at" class="mr-1">{{ __("dashboard.code.fields.expires_at") }}</label>
                                            <input type="date" id="expires_at" name="expires_at" class="form-control mr-1" value="{{ $filters['expires_at'] ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="for" class="mr-1">{{ __("dashboard.code.fields.for") }}</label>
                                            <select id="for" name="for" class="form-control mr-1">
                                                <option value="">{{ __("dashboard.common.select") }} {{ __("dashboard.code.fields.for") }}</option>
                                                @php
                                                    $forOptions = ['course', 'chapter', 'lesson'];
                                                @endphp
                                                @foreach($forOptions as $option)
                                                    <option value="{{ $option }}" {{ isset($filters['for']) && $filters['for'] == $option ? 'selected' : '' }}>
                                                        {{ ucfirst($option) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="created_at_from" class="mr-1">{{ __("dashboard.common.created_at_from") }}</label>
                                            <input type="datetime-local" id="created_at_from" name="created_at_from" class="form-control mr-1" value="{{ $filters['created_at_from'] ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="created_at_to" class="mr-1">{{ __("dashboard.common.created_at_to") }}</label>
                                            <input type="datetime-local" id="created_at_to" name="created_at_to" class="form-control mr-1" value="{{ $filters['created_at_to'] ?? '' }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-1">{{ __("dashboard.common.search") }}</button>
                                        <a href="{{ route('codes.index') }}" class="btn btn-secondary mr-1">{{ __("dashboard.common.clear") }}</a>
                                    </form>
                                    @can('create_code')
                                        <a href="{{ route('codes.create') }}" class="btn btn-primary mb-1">
                                            <i class="icon-plus2"></i> {{ __('dashboard.code.add_new') }}
                                        </a>
                                    @endcan
                                    <a href="{{ route('codes.export', request()->query()) }}" class="btn btn-success mb-1">
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
                <th>{{ __("dashboard.code.fields.teacher") }}</th>
                <th>{{ __("dashboard.common.created_at") }}</th>
                                            <th>{{ __('dashboard.common.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($codes as $code)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $code->code }}</td>
                <td>{{ $code->for }}</td>
                <td>{{ $code->number_of_uses }}</td>
                <td>{{ $code->expires_at ? $code->expires_at->format('Y-m-d') : '' }}</td>
                <td>{{ $code->teacher->name ?? '' }}</td>
                <td>{{ $code->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    @can('view_code')
                                                        <a href="{{ route('codes.show', $code->id) }}" class="btn btn-info btn-sm">
                                                            <i class="icon-eye6"></i> {{ __('dashboard.common.view') }}
                                                        </a>
                                                    @endcan

                                                    @can('edit_code')
                                                        <a href="{{ route('codes.edit', $code->id) }}" class="btn btn-warning btn-sm">
                                                            <i class="icon-pencil3"></i> {{ __('dashboard.common.edit') }}
                                                        </a>
                                                    @endcan

                                                    @can('delete_code')
                                                        <form action="{{ route('codes.destroy', $code->id) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('dashboard.code.delete_confirm') }}');">
                                                                <i class="icon-trash4"></i> {{ __('dashboard.common.delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 2 + count(Schema::getColumnListing('codes')) }}" class="text-center">{{ __('dashboard.code.no_records') }}</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{$codes->links()}}

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
