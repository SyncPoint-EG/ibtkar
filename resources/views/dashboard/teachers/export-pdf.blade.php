<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('dashboard.teacher.title') }} Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
<h2>{{ __('dashboard.teacher.title') }} Export</h2>
<table>
    <thead>
    <tr>
        <th>{{ __('dashboard.common.id') }}</th>
        <th>{{ __('dashboard.teacher.fields.name') }}</th>
        <th>{{ __('dashboard.teacher.fields.grades') }}</th>
        <th>{{ __('dashboard.teacher.fields.subjects') }}</th>
        <th>{{ __('dashboard.teacher.fields.total_students') }}</th>
        <th>{{ __('dashboard.teacher.fields.total_lessons') }}</th>
        <th>{{ __('dashboard.teacher.fields.status') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($teachers as $teacher)
        @php
            $grades = $teacher->courses->pluck('grade')->unique('id')->filter();
            if ($grades->isEmpty()) {
                $grades = $teacher->courses->pluck('grade')->unique('id')->filter();
            }
            $gradeNames = $grades->pluck('name')->implode(', ');

            $subjects = $teacher->courses->pluck('subject')->unique('id')->filter();
            if ($subjects->isEmpty()) {
                $subjects = $teacher->courses->pluck('subject')->unique('id')->filter();
            }
            $subjectNames = $subjects->pluck('name')->implode(', ');

            $statusText = $teacher->status ? __('dashboard.teacher.active') : __('dashboard.teacher.inactive');
        @endphp
        <tr>
            <td>{{ $teacher->id }}</td>
            <td>{{ $teacher->name }}</td>
            <td>{{ $gradeNames }}</td>
            <td>{{ $subjectNames }}</td>
            <td>{{ $teacher->students_count ?? 0 }}</td>
            <td>{{ $teacher->lessons_count ?? 0 }}</td>
            <td>{{ $statusText }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
