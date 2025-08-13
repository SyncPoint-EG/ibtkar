<div class="card mb-2 question-item" data-question-id="{{ $question->id }}">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                <h6 class="card-subtitle mb-1">
                    Question {{ $index + 1 }}
                    <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $question->question_type)) }}</span>
                    <span class="badge badge-secondary">{{ $question->marks }} {{ $question->marks == 1 ? 'Mark' : 'Marks' }}</span>
                </h6>
                <p class="card-text">{{ $question->question_text }}</p>
                @if($question->image)
                    <div class="mt-2">
                        <img src="{{ Storage::url($question->image) }}" width="100px">
                    </div>
                @endif

                @if($question->question_type !== 'essay')
                    <div class="mt-2">
                        <strong>Options:</strong>
                        <ul class="list-unstyled ml-2">
                            @foreach($question->options as $option)
                                <li class="mb-1">
                                    <span class="badge badge-{{ $option->is_correct ? 'success' : 'light' }}">
                                        {{ $option->is_correct ? '✓' : '○' }}
                                    </span>
                                    {{ $option->option_text }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if($question->question_type === 'essay' && $question->correct_essay_answer)
                    <div class="mt-2">
                        <strong>Correct Answer:</strong>
                        <p class="text-muted">{{ $question->correct_essay_answer }}</p>
                    </div>
                @endif
            </div>
            <div class="ml-2">
                <div class="btn-group-vertical">
                    <button type="button" class="btn btn-warning btn-sm edit-question" data-question-id="{{ $question->id }}">
                        <i class="icon-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm delete-question" data-question-id="{{ $question->id }}">
                        <i class="icon-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>