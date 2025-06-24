@extends('layouts.personalTaskFrame')

@section('main')

<style>
    .view-container {
        min-height: 500px;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 1.5rem;
    }

    .task-card {
        transition: all 0.2s ease;
        border-left: 4px solid;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .high-priority {
        border-left-color: #dc3545;
    }

    .medium-priority {
        border-left-color: #fd7e14;
    }

    .low-priority {
        border-left-color: #28a745;
    }
</style>
<!-- AI Suggestions -->
<div class="personal-tasks-container p-4">
    <!-- Header with View Controls -->
    <div class=" row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-3 mb-md-0">
                    <h2 class="fw-bold mb-0">
                        <i class="ti ti-checklist me-2"></i>My Personal Tasks
                    </h2>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <!-- View Selector -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button"
                            id="viewDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-layout-grid me-1"></i>
                            <span class="view-label"></span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="viewDropdown">
                            <li>
                                <a class="dropdown-item view-switcher" href="{{ route('personal-tasks.index') }}" data-view="list">
                                    <i class="ti ti-list me-2"></i>List View
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item view-switcher" href="{{ route('personal-tasks.kanban') }}" data-view="kanban">
                                    <i class="ti ti-layout-kanban me-2"></i>Kanban Board
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item view-switcher" href="{{ route('personal-tasks.calendar') }}" data-view="calendar">
                                    <i class="ti ti-calendar me-2"></i>Calendar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item view-switcher" href="{{ route('personal-tasks.matrix') }}" data-view="matrix">
                                    <i class="ti ti-urgent me-2"></i>Priority Matrix
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Add Task Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addTaskModal">
                        <i class="ti ti-plus me-1"></i>Add Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Content Container -->
    <div class="row">
        <div class="col-12">
            <div id="view-container" class="animate__animated animate__fadeIn">
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
                                                    <small class="d-block">Due: {{ $task->due_date->format('Y-m-d') }}</small>
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
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
@include('personal-tasks.partials.modals')
@endsection

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