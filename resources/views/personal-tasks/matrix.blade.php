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
                                                @include('personal-tasks.partials.matrix-task', ['task'=> $task])
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
                                                @include('personal-tasks.partials.matrix-task', ['task'=> $task])
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
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
@include('personal-tasks.partials.modals')
@endsection

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
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }
    });
</script>
@endsection