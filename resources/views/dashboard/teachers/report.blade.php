<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <title>Teacher Report</title>
    <style>
        body {
            font-family: 'XBRiyaz', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>{{ __('dashboard.teacher.report_for') }} {{ $teacher->name }}</h1>
    @if($startDate && $endDate)
        <p>{{ __('dashboard.common.from') }} {{ $startDate }} {{ __('dashboard.common.to') }} {{ $endDate }}</p>
    @endif

    <table>
        <tr>
            <th>{{ __('dashboard.teacher.report.metric') }}</th>
            <th>{{ __('dashboard.teacher.report.value') }}</th>
        </tr>
        <tr>
            <td>{{ __('dashboard.teacher.report.lecture_codes_count') }}</td>
            <td>{{ $lectureCodesCount }}</td>
        </tr>
        <tr>
            <td>{{ __('dashboard.teacher.report.monthly_codes_count') }}</td>
            <td>{{ $monthlyCodesCount }}</td>
        </tr>
        <tr>
            <td>{{ __('dashboard.teacher.report.students_count') }}</td>
            <td>{{ $studentsCount }}</td>
        </tr>
        <tr>
            <td>{{ __('dashboard.teacher.report.lessons_count') }}</td>
            <td>{{ $lessonsCount }}</td>
        </tr>
        <tr>
            <td>{{ __('dashboard.teacher.report.exams_count') }}</td>
            <td>{{ $examsCount }}</td>
        </tr>
    </table>
</body>
</html>
