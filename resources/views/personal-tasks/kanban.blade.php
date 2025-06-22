<div class="card">
    <div class="card-body">
        <h5 class="card-title">Kanban Board</h5>
        <div class="kanban-board">
            <div class="row">
                <div class="col-md-4">
                    <div class="kanban-column">
                        <h6>To Do</h6>
                        <div class="kanban-cards" id="todo-column">
                            @foreach ($tasks as $task)
                                @if ($task->status == 'todo')
                                    <div class="kanban-card" data-task-id="{{ $task->id }}">
                                        <div class="kanban-card-header">
                                            <strong>{{ $task->title }}</strong>
                                        </div>
                                        <div class="kanban-card-body">
                                            @if ($task->category)
                                                <span class="badge" style="background-color: {{ $task->category }}">
                                                    {{ $task->category }}
                                                </span>
                                            @endif
                                            @if ($task->due_date)
                                                <small class="d-block">Due: {{ $task->due_date->format('M j') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kanban-column">
                        <h6>In Progress</h6>
                        <div class="kanban-cards" id="inprogress-column">
                            @foreach ($tasks as $task)
                                @if ($task->status == 'in_progress')
                                    <div class="kanban-card" data-task-id="{{ $task->id }}">
                                        <div class="kanban-card-header">
                                            <strong>{{ $task->title }}</strong>
                                        </div>
                                        <div class="kanban-card-body">
                                            @if ($task->category)
                                                <span class="badge" style="background-color: {{ $task->category }}">
                                                    {{ $task->category }}
                                                </span>
                                            @endif
                                            @if ($task->due_date)
                                                <small class="d-block">Due: {{ $task->due_date->format('M j') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kanban-column">
                        <h6>Completed</h6>
                        <div class="kanban-cards" id="completed-column">
                            @foreach ($tasks as $task)
                                @if ($task->status == 'completed')
                                    <div class="kanban-card" data-task-id="{{ $task->id }}">
                                        <div class="kanban-card-header">
                                            <strong>{{ $task->title }}</strong>
                                        </div>
                                        <div class="kanban-card-body">
                                            @if ($task->category)
                                                <span class="badge" style="background-color: {{ $task->category }}">
                                                    {{ $task->category }}
                                                </span>
                                            @endif
                                            @if ($task->due_date)
                                                <small class="d-block">Due: {{ $task->due_date->format('M j') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('customJs')
<script>
    $(function() {
        // Make kanban cards draggable
        $(".kanban-card").draggable({
            revert: "invalid",
            cursor: "move",
            zIndex: 100
        });
        
        // Make columns droppable
        $(".kanban-cards").droppable({
            accept: ".kanban-card",
            drop: function(event, ui) {
                var taskId = ui.draggable.data("task-id");
                var newStatus = $(this).parent().find("h6").text().toLowerCase().replace(" ", "_");
                
                // Update status via AJAX
                $.ajax({
                    url: "{{ route('personal-tasks.update-status', ':id') }}".replace(':id', taskId),
                    method: "PUT",
                    data: {
                        status: newStatus,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            ui.draggable.appendTo(this);
                        }
                    }.bind(this)
                });
            }
        });
    });
</script>
@endsection
