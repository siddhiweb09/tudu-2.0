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
<div class="personal-tasks-container container-xxl p-4">
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
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="card-title">Task List</h5>
                            <div>
                                <button id="downloadCsv" class="btn btn-sm btn-outline-secondary" data-toggle="tooltip" title="Export Data">
                                    <i class="ti ti-download"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="personal_tasks" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Category</th>
                                        <th>Due Date</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $task)
                                    <tr data-task-id="{{ $task->id }}">
                                        <td>
                                            <strong>{{ $task->title }}</strong>
                                            @if ($task->description)
                                            <small class="d-block text-muted">{{ Str::limit($task->description, 50) }}...</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($task->frequency)
                                            <span>
                                                {{ $task->frequency }}
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                            $priority_class = '';
                                            if ($task->priority == 'high') $priority_class = 'danger';
                                            elseif ($task->priority == 'medium') $priority_class = 'warning';
                                            else $priority_class = 'success';
                                            @endphp
                                            <span class="badge badge-{{ $priority_class }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($task->due_date)
                                            {{ $task->due_date->format('M j, Y') }}
                                            @if ($task->due_date->isPast() && $task->status != 'completed')
                                            <span class="badge badge-danger">Overdue</span>
                                            @endif
                                            @else
                                            No deadline
                                            @endif
                                        </td>
                                        <td>
                                            <form class="status-form" data-task-id="{{ $task->id }}" action="{{ route('personal-tasks.update-status', $task) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control status-select">
                                                    <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>To Do</option>
                                                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-info view-task" data-id="{{ $task->task_id }}">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                                <button class="btn btn-warning edit-task" data-id="{{ $task->task_id }}">
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                                <button class="btn btn-danger delete-task" data-id="{{ $task->task_id }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

@endsection