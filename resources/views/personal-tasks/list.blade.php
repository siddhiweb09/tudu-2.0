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
                                @if ($task->category)
                                    <span class="badge" style="background-color: {{ $task->category }}">
                                        {{ $task->category }}
                                    </span>
                                @endif
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
                                    <button class="btn btn-info view-task" data-id="{{ $task->id }}">
                                        <i class="ti-eye"></i>
                                    </button>
                                    <button class="btn btn-warning edit-task" data-id="{{ $task->id }}">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger delete-task" data-id="{{ $task->id }}">
                                        <i class="ti-trash"></i>
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