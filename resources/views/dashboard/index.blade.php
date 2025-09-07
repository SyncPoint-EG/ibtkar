@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-1">
            <h2 class="content-header-title">{{ __('dashboard.common.information') }}</h2>
        </div>
        <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
{{--            <div class="breadcrumb-wrapper col-xs-12">--}}
{{--                <ol class="breadcrumb">--}}
{{--                    <li class="breadcrumb-item"><a--}}
{{--                            href="{{ route('dashboard') }}">{{ __('dashboard.common.dashboard') }}</a>--}}
{{--                    </li>--}}
{{--                    <li class="breadcrumb-item active">{{ __('dashboard.grade.title') }}--}}
{{--                    </li>--}}
{{--                </ol>--}}
{{--            </div>--}}
        </div>
    </div>

    <div class="content-body">
    <!-- Date Filter -->
    <section id="date-filter">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Filter by Date</h4>
            </div>
            <div class="card-body p-1">
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Main Stats -->
    <section id="main-stats">
        <div class="row">
            @foreach($mainStats as $title => $count)
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body p-2">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="font-large-1">{{ $count }}</h3>
                                    <span>{{ $title }}</span>
                                </div>
                                <div class="media-right media-middle">
                                    <i class="icon-layers font-large-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Financial Stats -->
    @can('view_financial_stats')
    <section id="financial-stats">
        <h4 class="text-bold-600">Financial Stats</h4>
        <div class="row">
            @foreach($financialStats as $title => $value)
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body p-2">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="font-large-1">{{ is_numeric($value) ? number_format($value, 2) : $value }}</h3>
                                    <span>{{ $title }}</span>
                                </div>
                                <div class="media-right media-middle">
                                    <i class="icon-wallet font-large-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endcan

    <!-- Charts -->
    <section id="charts">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lessons Created</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="lessonsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lesson Views</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="lessonViewsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function(){
        // Lessons Chart
        var ctxLessons = document.getElementById('lessonsChart').getContext('2d');
        var lessonsChart = new Chart(ctxLessons, {
            type: 'line',
            data: {
                labels: {!! json_encode($lessonsChart->keys()) !!},
                datasets: [{
                    label: 'Lessons',
                    data: {!! json_encode($lessonsChart->values()) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Lesson Views Chart
        var ctxLessonViews = document.getElementById('lessonViewsChart').getContext('2d');
        var lessonViewsChart = new Chart(ctxLessonViews, {
            type: 'line',
            data: {
                labels: {!! json_encode($lessonViewsChart->keys()) !!},
                datasets: [{
                    label: 'Views',
                    data: {!! json_encode($lessonViewsChart->values()) !!},
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
