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
                <p class="mb-0 text-muted small">All stats and charts below reflect this date range.</p>
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
                                        <h3 class="font-large-1">{{ number_format($count) }}</h3>
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
                                        <h3 class="font-large-1">
                                            @if(is_numeric($value))
                                                {{ number_format($value, is_int($value) ? 0 : 2) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </h3>
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
                        <h4 class="card-title">Student Registrations</h4>
                        <p class="small text-muted mb-0">Daily new students within the selected range.</p>
                    </div>
                    <div class="card-body">
                        <canvas id="studentRegistrationsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Purchasing Students & Payments</h4>
                        <p class="small text-muted mb-0">How many approvals and total amount per day.</p>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
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
        // Student Registrations
        var ctxStudents = document.getElementById('studentRegistrationsChart').getContext('2d');
        new Chart(ctxStudents, {
            type: 'line',
            data: {
                labels: {!! json_encode($studentRegistrationsChart->keys()) !!},
                datasets: [{
                    label: 'New Students',
                    data: {!! json_encode($studentRegistrationsChart->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.2,
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // Payments Chart (count + amount)
        var ctxPayments = document.getElementById('paymentsChart').getContext('2d');
        new Chart(ctxPayments, {
            type: 'bar',
            data: {
                labels: {!! json_encode($paymentChartLabels) !!},
                datasets: [
                    {
                        type: 'bar',
                        label: 'Approved Payments',
                        data: {!! json_encode($paymentCountSeries) !!},
                        backgroundColor: 'rgba(40, 199, 111, 0.4)',
                        borderColor: 'rgba(40, 199, 111, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        type: 'line',
                        label: 'Amount (EGP)',
                        data: {!! json_encode($paymentAmountSeries) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.3)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 2,
                        yAxisID: 'y1',
                        tension: 0.2,
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        ticks: { stepSize: 1 }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false }
                    }
                },
                plugins: {
                    legend: { display: true }
                }
            }
        });

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
