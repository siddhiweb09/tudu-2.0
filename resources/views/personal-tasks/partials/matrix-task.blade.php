<div class="matrix-task" data-task-id="{{ $task->id }}">
    <div class="task-checkbox">
        <input type="checkbox" class="task-status"
            data-task-id="{{ $task->id }}"
            {{ $task->status == 'completed' ? 'checked' : '' }}>
    </div>
    <div class="task-content">
        <div class="task-title">{{ $task->title }}</div>
        @if ($task->due_date)
        <div class="task-due">
            <small>
                <i class="ti-calendar"></i>
                {{ $task->due_date->format('M j') }}
                @if ($task->due_date->isPast())
                <span class="text-danger">(overdue)</span>
                @endif
            </small>
        </div>
        @endif
    </div>
    <div class="task-actions">
        <button class="btn btn-info view-task" data-id="{{ $task->task_id }}">
            <i class="ti ti-eye"></i>
        </button>
    </div>
</div>