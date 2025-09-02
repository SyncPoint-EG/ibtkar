@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">{{__('dashboard.code.view')}}</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('dashboard.common.dashboard')}}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('codes.index') }}">{{__('dashboard.code.management')}}</a>
                            </li>
                            <li class="breadcrumb-item active">{{__('dashboard.code.view')}}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-tooltip">{{__('dashboard.code.view')}} #{{ $code->id }}</h4>
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
                                    <div class="card-block">
                                        <div class="card-text">
                                            <p>{{__('dashboard.code.update_info')}}</p>
                                        </div>

                                        <div class="form-body">
                                            <div class="mb-3">
                                                <strong>{{__('dashboard.code.fields.code')}}:</strong> {{ $code->code }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>{{__('dashboard.code.fields.for')}}:</strong> {{ $code->for }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>{{__('dashboard.code.fields.number_of_uses')}}:</strong> {{ $code->number_of_uses }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>{{__('dashboard.code.fields.expires_at')}}:</strong> {{ $code->expires_at }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>{{__('dashboard.code.fields.teacher')}}:</strong> {{ $code->teacher->name ?? '' }}
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <a href="{{ route('codes.index') }}" class="btn btn-warning mr-1">
                                                <i class="icon-cross2"></i> {{__('dashboard.common.cancel')}}
                                            </a>
                                            <a href="{{ route('codes.edit', $code->id) }}" class="btn btn-primary">
                                                <i class="icon-check2"></i> {{__('dashboard.common.edit')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection