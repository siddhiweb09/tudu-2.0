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
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="viewDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-layout-grid me-1"></i>
                                <span class="view-label"></span>
                            </button>
                            <ul class="dropdown-menu " aria-labelledby="viewDropdown">
                                <li>
                                    <a class="dropdown-item view-switcher" href="{{ route('personal-tasks.index') }}"
                                        data-view="list">
                                        <i class="ti ti-list me-2"></i>List View
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item view-switcher" href="{{ route('personal-tasks.kanban') }}"
                                        data-view="kanban">
                                        <i class="ti ti-layout-kanban me-2"></i>Kanban Board
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item view-switcher" href="{{ route('personal-tasks.calendar') }}"
                                        data-view="calendar">
                                        <i class="ti ti-calendar me-2"></i>Calendar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item view-switcher" href="{{ route('personal-tasks.matrix') }}"
                                        data-view="matrix">
                                        <i class="ti ti-urgent me-2"></i>Priority Matrix
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Add Task Button -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class="ti ti-plus me-1"></i>Add Task
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Content Container -->
        <div class="container row">
            <div class="col-12">
                <div id="view-container" class="animate__animated animate__fadeIn">
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                                <h5 class="card-title mb-0">Task List</h5>

                                <div class="d-flex flex-wrap align-items-center gap-2">

                                    <!-- Export CSV Button -->
                                    <button id="downloadCsv" class="btn btn-primary" data-bs-toggle="tooltip"
                                        title="Export CSV">
                                        <i class="ti ti-download fs-6"></i>
                                    </button>

                                    <!-- Filter Button -->
                                    <button class="btn btn-primary" data-bs-toggle="tooltip" title="Filter Tasks">
                                        <i class="ti ti-filter fs-6"></i>
                                    </button>

                                    <!-- Search Box -->
                                    <div class="position-relative" style="max-width: 250px;">
                                        <i
                                            class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                        <input type="search" class="form-control ps-5 py-2 rounded"
                                            placeholder="Search projects..." />
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="personal_tasks" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Task</th>
                                            <th>Status</th>
                                            <th>Frequency</th>
                                            <th>Due Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $task)
                                            <tr data-task-id="{{ $task->id }}" data-id="{{ $task->task_id }}">
                                                <td>
                                                    @php
                                                        $priority_class = '';
                                                        if ($task->priority == 'high') {
                                                            $priority_class = 'danger';
                                                        } elseif ($task->priority == 'medium') {
                                                            $priority_class = 'warning';
                                                        } else {
                                                            $priority_class = 'success';
                                                        }
                                                    @endphp
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3 bg-{{ $priority_class }}"
                                                            style="width: 4px; height: 32px; border-radius: 0.375rem;">
                                                        </div>
                                                        <div>
                                                            <strong>{{ $task->title }}</strong>
                                                            @if ($task->description)
                                                                <small class="d-block text-muted">{{$task->description}}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="dropdown status-dropdown">
                                                        @php
                                                            $statusOptions = [
                                                                'Pending' => ['label' => 'To Do', 'color' => 'primary', 'icon' => 'ti ti-list-check'],
                                                                'in-progress' => ['label' => 'In Progress', 'color' => 'warning', 'icon' => 'ti ti-loader'],
                                                                'completed' => ['label' => 'Completed', 'color' => 'success', 'icon' => 'ti ti-circle-check'],
                                                                'overdue' => ['label' => 'Overdue', 'color' => 'danger', 'icon' => 'ti ti-exclamation-circle'],
                                                            ];
                                                        @endphp

                                                        <button
                                                            class="btn btn-sm btn-{{ $statusOptions[$task->status]['color'] }} dropdown-toggle px-3 rounded-pill"
                                                            type="button" id="statusDropdown-{{ $task->id }}"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="{{ $statusOptions[$task->status]['icon'] }} me-1"></i>
                                                            {{ $statusOptions[$task->status]['label'] }}
                                                        </button>

                                                        <ul class="dropdown-menu"
                                                            aria-labelledby="statusDropdown-{{ $task->id }}">
                                                            @foreach ($statusOptions as $key => $option)
                                                                <li class="border-0">
                                                                    <button type="button"
                                                                        class="dropdown-item status-update-option {{ $task->status === $key ? 'active' : '' }}"
                                                                        data-id="{{ $task->id }}" data-status="{{ $key }}">
                                                                        <i
                                                                            class="{{ $option['icon'] }} fs-5 me-2 text-{{ $option['color'] }}"></i>
                                                                        {{ $option['label'] }}
                                                                    </button>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-capitalize fw-bold small">
                                                        {{ $task->frequency ?? '-' }}
                                                    </div>
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
                                                    <div class="btn-group gap-2">
                                                        <button
                                                            class="btn btn-outline-primary view-task rounded-pill personal-btn-icon"
                                                            data-id="{{ $task->task_id }}">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                        <button
                                                            class="btn btn-outline-danger delete-task rounded-pill personal-btn-icon"
                                                            data-id="{{ $task->task_id }}">
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
    <script>
        $(document).ready(function () {

            $(document).on('click', '.status-update-option', function (e) {
                e.preventDefault();

                const taskId = $(this).data('id');
                const newStatus = $(this).data('status');

                $.ajax({
                    url: `/personal-tasks-update-status/${taskId}`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        status: newStatus
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Updated!',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Optionally reload or update the UI dynamically
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            Swal.fire('Error', response.message || 'Update failed', 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                });
            });

            $(document).on('click', '.delete-task', function (e) {
                e.preventDefault();
                const taskId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This task will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/personal-tasks-delete/${taskId}`,
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message || 'Task deleted successfully.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });

                                    setTimeout(() => location.reload(), 1200);
                                } else {
                                    Swal.fire('Error', response.message || 'Could not delete task.', 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error', 'Something went wrong.', 'error');
                            }
                        });
                    }
                });
            });
        });

    </script>

@endsection