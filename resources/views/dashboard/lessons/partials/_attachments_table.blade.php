<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>{{ __('dashboard.common.id') }}</th>
            <th>{{ __('dashboard.lesson.attachment_name') }}</th>
            <th>{{ __('dashboard.lesson.attachment_type') }}</th>
            <th>{{ __('dashboard.common.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($lesson->attachments as $attachment)
            <tr>
                <td>{{ $attachment->id }}</td>
                <td><a href="{{ asset('storage/' . $attachment->path) }}" target="_blank">{{ $attachment->name }}</a></td>
                <td>{{ $attachment->type }}</td>
                <td>
                    <form action="{{ route('lessons.attachments.destroy', $attachment->id) }}" method="POST"
                          class="delete-form d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm delete-btn">
                            <i class="icon-trash"></i> {{ __('dashboard.common.delete') }}
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">{{ __('dashboard.lesson.no_attachments') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@section('page_scripts')
    @parent
    <script>
        $(document).ready(function () {
            $('.delete-btn').on('click', function (e) {
                e.preventDefault();

                if (confirm('{{ __("dashboard.lesson.delete_attachment_confirm") }}')) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endsection