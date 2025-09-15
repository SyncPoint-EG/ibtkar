<form action="{{ route('lessons.attachments.store', $lesson->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-body">
        <h4 class="form-section"><i class="icon-file-add"></i> {{ __('dashboard.lesson.add_attachment') }}</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">{{ __('dashboard.lesson.attachment_name') }}</label>
                    <input type="text" id="name" class="form-control" name="name" value="{{ old('name') }}">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="file">{{ __('dashboard.lesson.file') }}</label>
                    <input type="file" id="file" class="form-control" name="file">
                    @error('file')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="is_featured">{{ __("dashboard.lesson_attachment.fields.is_featured") }}</label>
                    <input type="checkbox" id="is_featured"
                           name="is_featured" value="1"
                        {{ old('is_featured') ? 'checked' : '' }}>
                    @error('is_featured')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="icon-check2"></i> {{ __('dashboard.lesson.upload_attachment') }}
        </button>
    </div>
</form>