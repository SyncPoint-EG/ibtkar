@extends('dashboard.layouts.master')@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1"><h2 class="content-header-title">Edit Center
                        Exam: {{ $centerExam->title }}</h2></div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('center-exams.index') }}">Center Exams</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('center-exams.show', $centerExam) }}">{{ $centerExam->title }}</a>
                            </li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card px-1">
                            <div class="card-header"><h4 class="card-title">Edit Center Exam Details</h4></div>
                            <div class="card-body">
                                <form action="{{ route('center-exams.update', $centerExam) }}"
                                      method="POST">                                    @csrf                                    @method('PUT')
                                    <div class="form-group"><label for="title">Exam Title *</label> <input type="text"
                                                                                                           class="form-control @error('title') is-invalid @enderror"
                                                                                                           id="title"
                                                                                                           name="title"
                                                                                                           value="{{ old('title', $centerExam->title) }}"
                                                                                                           required> @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group"><label for="center_id">Center *</label> <select
                                            class="form-control @error('center_id') is-invalid @enderror" id="center_id"
                                            name="center_id" required>
                                            <option value="">Select Center</option> @foreach($centers as $center)
                                                <option
                                                    value="{{ $center->id }}" {{ old('center_id', $centerExam->center_id) == $center->id ? 'selected' : '' }}>                                                    {{ $center->name }}                                                </option>
                                            @endforeach
                                        </select> @error('center_id')
                                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group"><label
                                            for="teacher_id">{{ trans('dashboard.center_exam.fields.teacher') }}
                                            *</label> <select
                                            class="form-control @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id" name="teacher_id" required>
                                            <option
                                                value="">{{ trans('dashboard.center_exam.placeholders.select_teacher') }}</option> @foreach($teachers as $teacher)
                                                <option
                                                    value="{{ $teacher->id }}" {{ old('teacher_id', $centerExam->teacher_id) == $teacher->id ? 'selected' : '' }}>                                                    {{ $teacher->name }}                                                </option>
                                            @endforeach
                                        </select> @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group"><label for="stage_id">Stage *</label> <select
                                            class="form-control @error('stage_id') is-invalid @enderror" id="stage_id"
                                            name="stage_id" required>
                                            <option value="">Select Stage</option> @foreach($stages as $stage)
                                                <option
                                                    value="{{ $stage->id }}" {{ old('stage_id', $centerExam->stage_id) == $stage->id ? 'selected' : '' }}>                                                    {{ $stage->name }}                                                </option>
                                            @endforeach
                                        </select> @error('stage_id')
                                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group"><label for="grade_id">Grade *</label> <select
                                            class="form-control @error('grade_id') is-invalid @enderror" id="grade_id"
                                            name="grade_id" required>
                                            <option value="">Select Grade</option> @foreach($grades as $grade)
                                                <option
                                                    value="{{ $grade->id }}" {{ old('grade_id', $centerExam->grade_id) == $grade->id ? 'selected' : '' }}>                                                    {{ $grade->name }}                                                </option>
                                            @endforeach
                                        </select> @error('grade_id')
                                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group"><label for="division_id">Division (Optional)</label> <select
                                            class="form-control @error('division_id') is-invalid @enderror"
                                            id="division_id" name="division_id">
                                            <option value="">Select Division</option> @foreach($divisions as $division)
                                                <option
                                                    value="{{ $division->id }}" {{ old('division_id', $centerExam->division_id) == $division->id ? 'selected' : '' }}>                                                    {{ $division->name }}                                                </option>
                                            @endforeach
                                        </select> @error('division_id')
                                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group"><label for="description">Description</label> <textarea
                                            class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description"
                                            rows="4">{{ old('description', $centerExam->description) }}</textarea> @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"><label for="total_marks">Total Marks *</label>
                                                <input type="number"
                                                       class="form-control @error('total_marks') is-invalid @enderror"
                                                       id="total_marks" name="total_marks"
                                                       value="{{ old('total_marks', $centerExam->total_marks) }}"
                                                       required> @error('total_marks')
                                                <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label for="passing_marks">Passing Marks *</label>
                                                <input type="number"
                                                       class="form-control @error('passing_marks') is-invalid @enderror"
                                                       id="passing_marks" name="passing_marks"
                                                       value="{{ old('passing_marks', $centerExam->passing_marks) }}"
                                                       required> @error('passing_marks')
                                                <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label for="duration_minutes">Duration (minutes)
                                                    *</label> <input type="number"
                                                                     class="form-control @error('duration_minutes') is-invalid @enderror"
                                                                     id="duration_minutes" name="duration_minutes"
                                                                     value="{{ old('duration_minutes', $centerExam->duration_minutes) }}"
                                                                     required> @error('duration_minutes')
                                                <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group"><label for="start_time">Start Time</label> <input
                                                    type="datetime-local"
                                                    class="form-control @error('start_time') is-invalid @enderror"
                                                    id="start_time" name="start_time"
                                                    value="{{ old('start_time', $centerExam->start_time ? $centerExam->start_time->format('Y-m-d\TH:i') : '') }}"> @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"><label for="end_time">End Time</label> <input
                                                    type="datetime-local"
                                                    class="form-control @error('end_time') is-invalid @enderror"
                                                    id="end_time" name="end_time"
                                                    value="{{ old('end_time', $centerExam->end_time ? $centerExam->end_time->format('Y-m-d\TH:i') : '') }}"> @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group"><label for="is_active">Status</label> <select
                                            class="form-control" id="is_active" name="is_active">
                                            <option
                                                value="1" {{ old('is_active', $centerExam->is_active) == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option
                                                value="0" {{ old('is_active', $centerExam->is_active) == 0 ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select></div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary"><i class="icon-check2"></i> Update
                                            Center Exam
                                        </button>
                                        <a href="{{ route('center-exams.show', $centerExam) }}"
                                           class="btn btn-secondary"> <i class="icon-cross"></i> Cancel </a></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Current Information</h4></div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Current Center:</strong></td>
                                        <td>{{ $centerExam->center->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Current Stage:</strong></td>
                                        <td>{{ $centerExam->stage->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Current Grade:</strong></td>
                                        <td>{{ $centerExam->grade->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Current Division:</strong></td>
                                        <td>{{ $centerExam->division->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Marks:</strong></td>
                                        <td>{{ $centerExam->total_marks }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Passing Marks:</strong></td>
                                        <td>{{ $centerExam->passing_marks }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Duration:</strong></td>
                                        <td>{{ $centerExam->duration_minutes }} minutes</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Current Status:</strong></td>
                                        <td><span
                                                class="badge badge-{{ $centerExam->is_active ? 'success' : 'danger' }}">                                            {{ $centerExam->is_active ? 'Active' : 'Inactive' }}                                        </span>
                                        </td>
                                    </tr> @if($centerExam->start_time)
                                        <tr>
                                            <td><strong>Start Time:</strong></td>
                                            <td>{{ $centerExam->start_time->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endif                                    @if($centerExam->end_time)
                                        <tr>
                                            <td><strong>End Time:</strong></td>
                                            <td>{{ $centerExam->end_time->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $centerExam->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td>{{ $centerExam->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table> @if($centerExam->description)
                                    <div class="mt-1"><strong>Current Description:</strong>
                                        <p class="text-muted">{{ $centerExam->description }}</p></div>
                                @endif
                                <div class="mt-2"><a href="{{ route('center-exams.show', $centerExam) }}"
                                                     class="btn btn-info btn-sm btn-block"> <i class="icon-eye"></i>
                                        View Center Exam Details </a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
