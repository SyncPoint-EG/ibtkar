@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h2 class="content-header-title">Create New Homework</h2>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('homework.index') }}">Homework</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card px-1">
                            <div class="card-header">
                                <h4 class="card-title">Homework Details</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('homework.store') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="title">Homework Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="lesson_id">Lesson *</label>
                                        <select class="form-control @error('lesson_id') is-invalid @enderror"
                                                id="lesson_id" name="lesson_id" required>
                                            <option value="">Select Lesson</option>
                                            @foreach($lessons as $lesson)
                                                <option value="{{ $lesson->id }}" {{ old('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                                    {{ $lesson->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lesson_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="due_date">Due Date</label>
                                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                                       id="due_date" name="due_date" value="{{ old('due_date') }}">
                                                @error('due_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="is_active">Status</label>
                                                <select class="form-control" id="is_active" name="is_active">
                                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="icon-check2"></i> Create Homework
                                        </button>
                                        <a href="{{ route('homework.index') }}" class="btn btn-secondary">
                                            <i class="icon-cross"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
