<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-inverse">
        <tr>
            <th class="sortable" data-sort="id">
                {{ __('dashboard.common.id') }}
                <i class="icon-arrow-up-down2 float-right"></i>
            </th>
            <th>{{ __("dashboard.teacher.fields.image") }}</th>
            <th class="sortable" data-sort="name">
                {{ __("dashboard.teacher.fields.name") }}
                <i class="icon-arrow-up-down2 float-right"></i>
            </th>
            <th>{{ __("dashboard.teacher.fields.uuid") }}</th>
            <th>{{ __("dashboard.teacher.fields.grades") }}</th>
            <th>{{ __("dashboard.teacher.fields.subjects") }}</th>
            <th>{{ __("dashboard.teacher.fields.total_students") }}</th>
            <th>{{ __("dashboard.teacher.fields.total_lessons") }}</th>
            <th>{{ __("dashboard.teacher.fields.status") }}</th>
            <th>{{ __("dashboard.teacher.fields.is_featured") }}</th>
            <th>{{ __('dashboard.common.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($teachers as $teacher)
            <tr>
                <td>{{ $teacher->id }}</td>
                <td>
                    <img src="{{ $teacher->image }}" width="50px" height="50px"
                         class="rounded-circle" alt="{{ $teacher->name }}">
                </td>
                <td>{{ $teacher->name }}</td>
                <td>{{$teacher->uuid}}</td>

                {{-- Grades column from subjectTeacherAssignments --}}
                <td>
                    @php
                        $uniqueGrades = $teacher->subjectTeacherAssignments->pluck('grade')->unique('id')->filter();
                        $totalGradesCount = $uniqueGrades->count();
                        $gradesToShow = $uniqueGrades->take(2);
                    @endphp

                    @foreach($gradesToShow as $grade)
                        <span class="badge badge-info">{{ $grade->name }}</span>
                    @endforeach

                    @if($totalGradesCount > 2)
                        <span class="badge badge-secondary">+{{ $totalGradesCount - 2 }}</span>
                    @endif
                </td>

                {{-- Subjects column from subjectTeacherAssignments --}}
                <td>
                    @php
//                        $uniqueSubjects = $teacher->courses->pluck('subject')->unique('id')->filter();
                        $uniqueSubjects = $teacher->subjectTeacherAssignments->pluck('subject')->unique('id')->filter();
                        $totalSubjectsCount = $uniqueSubjects->count();
                        $subjectsToShow = $uniqueSubjects->take(2);
                    @endphp

                    @foreach($subjectsToShow as $subject)
                        <span class="badge badge-primary">{{ $subject->name }}</span>
                    @endforeach

                    @if($totalSubjectsCount > 2)
                        <span class="badge badge-secondary">+{{ $totalSubjectsCount - 2 }}</span>
                    @endif
                </td>

                <td>
                    <span class="badge badge-success">{{ $teacher->students_count ?? 0 }}</span>
                </td>
                <td>
                    <span class="badge badge-warning">{{ $teacher->lessons_count ?? 0 }}</span>
                </td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="status-toggle"
                               data-teacher-id="{{ $teacher->id }}"
                            {{ $teacher->status ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="featured-toggle"
                               data-teacher-id="{{ $teacher->id }}"
                            {{ $teacher->is_featured ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </td>
                <td>
                    @can('view_teacher')
                        <a href="{{ route('teachers.show', $teacher->id) }}"
                           class="btn btn-info btn-sm">
                            <i class="icon-eye6"></i>
                        </a>
                    @endcan

                    @can('edit_teacher')
                        <a href="{{ route('teachers.edit', $teacher->id) }}"
                           class="btn btn-warning btn-sm">
                            <i class="icon-pencil3"></i>
                        </a>
                    @endcan

                    @can('delete_teacher')
                        <button type="button" class="btn btn-danger btn-sm delete-teacher"
                                data-teacher-id="{{ $teacher->id }}"
                                data-teacher-name="{{ $teacher->name }}">
                            <i class="icon-trash4"></i>
                        </button>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">{{ __('dashboard.teacher.no_records') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
