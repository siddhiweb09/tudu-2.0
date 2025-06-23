<!-- resources/views/personal-tasks/matrix.blade.php -->

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Eisenhower Matrix</h5>
        <p class="card-subtitle mb-3 text-muted">Prioritize your tasks based on urgency and importance</p>

        <div class="eisenhower-matrix">
            <div class="row matrix-row">
                <!-- Quadrant 1: Urgent & Important -->
                <div class="col-md-6">
                    <div class="matrix-quadrant urgent-important">
                        <div class="quadrant-header">
                            <h6>Urgent & Important</h6>
                            <span class="badge badge-light">
                                {{ $tasks->where('priority', 'high')->filter(function($task) {
                                    return $task->due_date && $task->due_date->diffInDays(now()) <= 3;
                                })->count() }} tasks
                            </span>
                        </div>
                        <div class="quadrant-tasks">
                            @foreach ($tasks as $task)
                                @if ($task->priority == 'high' && $task->due_date && $task->due_date->diffInDays(now()) <= 3)
                                    @include('personal-tasks.partials.matrix-task', ['task' => $task])
                                @endif
                            @endforeach
                            @if ($tasks->where('priority', 'high')->filter(function($task) {
                                return $task->due_date && $task->due_date->diffInDays(now()) <= 3;
                            })->count() == 0)
                                <div class="no-tasks">No tasks in this quadrant</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quadrant 2: Not Urgent & Important -->
                <div class="col-md-6">
                    <div class="matrix-quadrant not-urgent-important">
                        <div class="quadrant-header">
                            <h6>Not Urgent & Important</h6>
                            <span class="badge badge-light">
                                {{ $tasks->where('priority', 'high')->filter(function($task) {
                                    return !$task->due_date || $task->due_date->diffInDays(now()) > 3;
                                })->count() }} tasks
                            </span>
                        </div>
                        <div class="quadrant-tasks">
                            @foreach ($tasks as $task)
                                @if ($task->priority == 'high' && (!$task->due_date || $task->due_date->diffInDays(now()) > 3))
                                    @include('personal-tasks.partials.matrix-task', ['task' => $task])
                                @endif
                            @endforeach
                            @if ($tasks->where('priority', 'high')->filter(function($task) {
                                return !$task->due_date || $task->due_date->diffInDays(now()) > 3;
                            })->count() == 0)
                                <div class="no-tasks">No tasks in this quadrant</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row matrix-row">
                <!-- Quadrant 3: Urgent & Not Important -->
                <div class="col-md-6">
                    <div class="matrix-quadrant urgent-not-important">
                        <div class="quadrant-header">
                            <h6>Urgent & Not Important</h6>
                            <span class="badge badge-light">
                                {{ $tasks->where('priority', '!=', 'high')->filter(function($task) {
                                    return $task->due_date && $task->due_date->diffInDays(now()) <= 3;
                                })->count() }} tasks
                            </span>
                        </div>
                        <div class="quadrant-tasks">
                            @foreach ($tasks as $task)
                                @if ($task->priority != 'high' && $task->due_date && $task->due_date->diffInDays(now()) <= 3)
                                    @include('personal-tasks.partials.matrix-task', ['task' => $task])
                                @endif
                            @endforeach
                            @if ($tasks->where('priority', '!=', 'high')->filter(function($task) {
                                return $task->due_date && $task->due_date->diffInDays(now()) <= 3;
                            })->count() == 0)
                                <div class="no-tasks">No tasks in this quadrant</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quadrant 4: Not Urgent & Not Important -->
                <div class="col-md-6">
                    <div class="matrix-quadrant not-urgent-not-important">
                        <div class="quadrant-header">
                            <h6>Not Urgent & Not Important</h6>
                            <span class="badge badge-light">
                                {{ $tasks->where('priority', '!=', 'high')->filter(function($task) {
                                    return !$task->due_date || $task->due_date->diffInDays(now()) > 3;
                                })->count() }} tasks
                            </span>
                        </div>
                        <div class="quadrant-tasks">
                            @foreach ($tasks as $task)
                                @if ($task->priority != 'high' && (!$task->due_date || $task->due_date->diffInDays(now()) > 3))
                                    @include('personal-tasks.partials.matrix-task', ['task' => $task])
                                @endif
                            @endforeach
                            @if ($tasks->where('priority', '!=', 'high')->filter(function($task) {
                                return !$task->due_date || $task->due_date->diffInDays(now()) > 3;
                            })->count() == 0)
                                <div class="no-tasks">No tasks in this quadrant</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('customJs')
<script>
$(document).ready(function() {
    // Handle task status changes
    $('.task-status').change(function() {
        const taskId = $(this).data('task-id');
        const isCompleted = $(this).is(':checked');
        const status = isCompleted ? 'completed' : 'todo';

        $.ajax({
            url: "{{ route('personal-tasks.update-status', ':id') }}".replace(':id', taskId),
            method: "PUT",
            data: {
                status: status,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error updating task status');
                }
            }
        });
    });

    // Handle task clicks
   
    
    function getStatusClass(status) {
        const classes = {
            'todo': 'secondary',
            'in_progress': 'info',
            'completed': 'success'
        };
        return classes[status] || 'secondary';
    }
    
    function getPriorityClass(priority) {
        const classes = {
            'low': 'success',
            'medium': 'warning',
            'high': 'danger'
        };
        return classes[priority] || 'secondary';
    }
    
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }
});
</script>
@endsection
