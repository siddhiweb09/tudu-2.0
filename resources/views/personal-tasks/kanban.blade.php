@extends('layouts.personalTaskFrame')

@section('main')

    <style>
        .bg-primary-soft {
            background-color: var(--primary-soft);
        }

        .bg-warning-soft {
            background-color: var(--warning-soft);
        }

        .bg-success-soft {
            background-color: var(--success-soft);
        }

        .bg-danger-soft {
            background-color: var(--danger-soft);
        }

        .border-primary {
            border-left: 4px solid #0d6efd !important;
        }

        .border-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .border-success {
            border-left: 4px solid #198754 !important;
        }

        .border-danger {
            border-left: 4px solid #dc3545 !important;
        }

        .kanban-board {
            padding: 0.5rem;
        }

        .kanban-column {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            min-height: 500px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .kanban-column:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .kanban-column h6 {
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: left;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kanban-cards {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            /* min-height: 400px; */
        }

        .kanban-card {
            background-color: #fff;
            border-left: 4px solid;
            border-radius: 10px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: grab;
            position: relative;
            overflow: hidden;
            user-select: none;
            -webkit-user-drag: element;
        }

        .kanban-card:active {
            cursor: grabbing;
        }

        .kanban-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .kanban-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .kanban-card:hover::after {
            opacity: 1;
        }

        .kanban-card-header {
            font-weight: 600;
            font-size: 0.95rem;
            color: #212529;
            margin-bottom: 0.5rem;
        }

        .kanban-card-body {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }

        .kanban-card-footer {
            font-size: 0.75rem;
        }

        .avatar-group {
            display: flex;
            gap: 6px;
        }

        .avatar-xs {
            width: 24px;
            height: 24px;
            object-fit: cover;
        }

        .kanban-button {
            z-index: 2;
        }

        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(58, 12, 163, 0.5);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(58, 12, 163, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(58, 12, 163, 0);
            }
        }

        /* Drag and drop styling */
        .kanban-card.dragging {
            opacity: 0.8;
            background: #f8f9fa;
            transform: scale(1.02) rotate(2deg);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: none;
        }

        .kanban-column.drop-zone {
            background-color: rgba(13, 110, 253, 0.05);
            border: 2px dashed #0d6efd;
            transition: all 0.2s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .kanban-column {
                min-height: 300px;
            }
        }

        @media (max-width: 768px) {
            .kanban-column {
                margin-bottom: 1.5rem;
            }

            .story-ring {
                width: 56px;
                height: 56px;
            }
        }

        /* Dropdown Button */
        .kanban-dropdown-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            color: #6c757d;
            padding: 0;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            z-index: 5;
            /* Higher than card */
            position: relative;
        }

        .kanban-dropdown-btn:hover {
            background: rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 0, 0, 0.1);
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.15);
            transform: scale(1.1);
            color: #0d6efd;
        }

        /* Dropdown Menu */
        .kanban-dropdown-menu {
            z-index: 10000 !important;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 8px;
            min-width: 95px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(4px);
        }

        /* Dropdown Items */
        .kanban-dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 6px 12px;
            border-radius: 4px;
            background: transparent;
            border: none;
            font-size: 14px;
            text-align: left;
            transition: all 0.2s;
        }

        .kanban-dropdown-item:hover {
            background: rgba(0, 123, 255, 0.08);
        }

        .kanban-icon-btn.text-danger:hover {
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        /* Pulse Animation on Hover */
        @keyframes icon-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.4);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(0, 123, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
            }
        }

        .kanban-icon-btn:hover i {
            animation: icon-pulse 1.5s infinite;
        }

        /* Base Icon Button */
        .kanban-icon-btn {
            --btn-size: 32px;
            --transition-speed: 0.25s;
            --primary-color: #0d6efd;
            --danger-color: #dc3545;

            width: var(--btn-size);
            height: var(--btn-size);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: transparent;
            border: 1px solid transparent;
            transition: all var(--transition-speed) ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        /* Color Variants */
        .kanban-edit-btn {
            color: var(--primary-color);
            border-color: rgba(13, 110, 253, 0.2);
        }

        .kanban-delete-btn {
            color: var(--danger-color);
            border-color: rgba(220, 53, 69, 0.2);
        }

        /* Hover Effects */
        .kanban-icon-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .kanban-icon-btn:hover::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center,
                    rgba(255, 255, 255, 0.8) 0%,
                    rgba(255, 255, 255, 0) 70%);
            animation: ripple var(--transition-speed) linear;
        }

        @keyframes ripple {
            from {
                transform: scale(0);
                opacity: 1;
            }

            to {
                transform: scale(2);
                opacity: 0;
            }
        }

        /* Active/Pressed State */
        .kanban-icon-btn:active {
            transform: translateY(0);
            transition: all 0.1s ease;
        }

        /* Focus State (Accessibility) */
        .kanban-icon-btn:focus-visible {
            outline: 2px solid currentColor;
            outline-offset: 2px;
        }

        /* Icon Styling */
        .kanban-icon-btn i {
            font-size: 16px;
            transition: transform var(--transition-speed) ease;
            position: relative;
            z-index: 1;
        }

        .kanban-icon-btn:hover i {
            transform: scale(1.15);
        }

        /* Specific Button Effects */
        .kanban-edit-btn:hover {
            background: rgba(13, 110, 253, 0.08);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }

        .kanban-delete-btn:hover {
            background: rgba(220, 53, 69, 0.08);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15);
        }

        /* Status update animations */
        .kanban-card.status-updating {
            opacity: 0.7;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.5);
        }

        .kanban-card.status-updated {
            animation: pulse-success 1s ease;
        }

        .kanban-card.status-error {
            animation: shake 0.5s ease;
            box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.5);
        }

        @keyframes pulse-success {
            0% {
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.5);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-5px);
            }

            40%,
            80% {
                transform: translateX(5px);
            }
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
        <div class="container-fluid">

            <div class="card mt-2 border-0 shadow-sm">
                <div class="card-body px-4 py-2">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-0 fw-bold">Kanban Dashboard</h4>
                            <p class="text-muted mb-0">Track your team's progress in real-time</p>
                        </div>
                    </div>

                    <div class="kanban-board">
                        <div class="row g-4">
                            <!-- To Do -->
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="kanban-column bg-primary-soft">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-primary mb-0">
                                            <i class="bi bi-card-checklist me-2"></i>To Do
                                        </h6>
                                        <span id="pending-badge"></span>
                                    </div>
                                    <div class="kanban-cards gap-1" id="todo-column">
                                        <div id="todo-tasks"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- In Progress -->
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="kanban-column bg-warning-soft">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-warning mb-0">
                                            <i class="bi bi-lightning-charge me-2"></i>In Progress
                                        </h6>
                                        <span id="inprogress-badge"></span>
                                    </div>
                                    <div class="kanban-cards" id="inprogress-column">
                                        <div id="inProgress-tasks"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Completed -->
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="kanban-column bg-success-soft">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-success mb-0">
                                            <i class="bi bi-check-circle me-2"></i>Completed
                                        </h6>
                                        <span id="completed-badge"></span>
                                    </div>
                                    <div class="kanban-cards" id="completed-column">
                                        <div id="completed-tasks"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- OverDue -->
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="kanban-column bg-danger-soft">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-danger mb-0">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Overdue
                                        </h6>
                                        <span id="overdue-badge"></span>
                                    </div>
                                    <div class="kanban-cards" id="overdue-column">
                                        <div id="overdue-tasks"></div>
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
        // Initialize when DOM is ready
        $(document).ready(function () {
            fetchTasks(); // Initial load

            // Make this function available globally for reinitialization
            window.initializeDragAndDrop = function () {
                const cards = document.querySelectorAll('.kanban-card');
                const columns = document.querySelectorAll('.kanban-column');

                let draggedCard = null;

                cards.forEach(card => {
                    card.addEventListener('dragstart', function () {
                        draggedCard = this;
                        setTimeout(() => {
                            this.classList.add('dragging');
                        }, 0);
                    });

                    card.addEventListener('dragend', function () {
                        this.classList.remove('dragging');
                        if (draggedCard) {
                            updateTaskStatus(draggedCard);
                        }
                    });
                });

                columns.forEach(column => {
                    column.addEventListener('dragover', function (e) {
                        e.preventDefault();
                        this.classList.add('drop-zone');
                    });

                    column.addEventListener('dragleave', function () {
                        this.classList.remove('drop-zone');
                    });

                    column.addEventListener('drop', function (e) {
                        e.preventDefault();
                        this.classList.remove('drop-zone');

                        if (draggedCard) {
                            // Get the cards container (kanban-cards div)
                            const cardsContainer = this.querySelector('.kanban-cards');
                            const afterElement = getDragAfterElement(cardsContainer, e.clientY);

                            if (afterElement) {
                                cardsContainer.insertBefore(draggedCard, afterElement);
                            } else {
                                cardsContainer.appendChild(draggedCard);
                            }
                        }
                    });
                });
            };

            function getDragAfterElement(container, y) {
                const draggableElements = [...container.querySelectorAll('.kanban-card:not(.dragging)')];
                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    return (offset < 0 && offset > closest.offset) ? {
                        offset: offset,
                        element: child
                    } : closest;
                }, {
                    offset: Number.NEGATIVE_INFINITY
                }).element;
            }


            function updateTaskStatus(card) {
                const dropContainer = card.closest('.kanban-cards');
                if (!dropContainer) {
                    console.error('Could not find drop container');
                    return;
                }

                const taskId = card.dataset.taskId;
                const oldStatus = card.dataset.status; // Get current status from data attribute
                const columnId = dropContainer.id;

                let newStatus;
                switch (columnId) {
                    case 'todo-column': newStatus = 'Pending'; break;
                    case 'inprogress-column': newStatus = 'in-progress'; break;
                    case 'completed-column': newStatus = 'completed'; break;
                    case 'overdue-column': newStatus = 'overdue'; break;
                    default: console.error('Unknown column:', columnId); return;
                }

                // Update visual appearance immediately
                updateCardAppearance(card, newStatus);
                updateBadgeCounts(oldStatus, newStatus);

                card.classList.add('status-updating');

                $.ajax({
                    url: `/update-status/${taskId}`,
                    method: 'PUT',
                    data: { status: newStatus, _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        card.classList.remove('status-updating');
                        card.classList.add('status-updated');
                        setTimeout(() => card.classList.remove('status-updated'), 1000);

                        // Update the data-status attribute
                        card.dataset.status = newStatus;
                    },
                    error: function (xhr) {
                        // Revert visual changes on error
                        updateCardAppearance(card, oldStatus);
                        updateBadgeCounts(newStatus, oldStatus);

                        card.classList.remove('status-updating');
                        card.classList.add('status-error');
                        setTimeout(() => card.classList.remove('status-error'), 2000);

                        console.error('Update failed:', xhr.responseText);
                    }
                });
            }

            function updateCardAppearance(card, newStatus) {
                // Remove all status border classes
                card.classList.remove(
                    'border-primary',
                    'border-warning',
                    'border-success',
                    'border-danger'
                );

                // Add new border class based on status
                switch (newStatus) {
                    case 'Pending': card.classList.add('border-primary'); break;
                    case 'in-progress': card.classList.add('border-warning'); break;
                    case 'completed': card.classList.add('border-success'); break;
                    case 'overdue': card.classList.add('border-danger'); break;
                }

                // Update data-status attribute
                card.dataset.status = newStatus;
            }

            function updateBadgeCounts(oldStatus, newStatus) {
                // Decrement count for old status
                if (oldStatus) {
                    const oldBadge = $(`#${oldStatus.toLowerCase().replace('-', '')}-badge span`);
                    const oldCount = parseInt(oldBadge.text()) || 0;
                    oldBadge.text(Math.max(0, oldCount - 1));
                }

                // Increment count for new status
                const newBadge = $(`#${newStatus.toLowerCase().replace('-', '')}-badge span`);
                const newCount = parseInt(newBadge.text()) || 0;
                newBadge.text(newCount + 1);
            }


            // Fetch and render tasks
            function fetchTasks() {
                $.ajax({
                    url: "{{ route('tasks.fetch') }}",
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            renderTasks(response.tasks);
                            initializeDragAndDrop(); // if needed
                        } else {
                            showErrorMessages();
                        }
                    },
                    error: showErrorMessages
                });
            }

            // Render tasks into kanban columns
            function renderTasks(tasks) {
                let todoHtml = '',
                    inProgressHtml = '',
                    completedHtml = '',
                    overdueHtml = '';
                let pendingCount = 0,
                    inProgressCount = 0,
                    completedCount = 0,
                    overdueCount = 0;

                tasks.forEach(task => {
                    let priorityBadge = '';
                    if (task.priority === 'low') {
                        priorityBadge = `<span class="badge bg-success">Low</span>`;
                    } else if (task.priority === 'medium') {
                        priorityBadge = `<span class="badge bg-warning text-dark">Medium</span>`;
                    } else if (task.priority === 'high') {
                        priorityBadge = `<span class="badge bg-danger">High</span>`;
                    }

                    let borderColourClass = '';
                    if (task.status === 'Pending') {
                        borderColourClass = 'border-primary';
                        pendingCount++;
                    } else if (task.status === 'in-progress') {
                        borderColourClass = 'border-warning';
                        inProgressCount++;
                    } else if (task.status === 'completed') {
                        borderColourClass = 'border-success';
                        completedCount++;
                    } else if (task.status === 'overdue') {
                        borderColourClass = 'border-danger';
                        overdueCount++;
                    }

                    const taskHtml = `<div class="kanban-card ${borderColourClass}" draggable="true" data-task-id="${task.task_id}" data-status="${task.status}">
                                                        <div class="kanban-card-header d-flex justify-content-between">
                                                            <span>${task.title}</span>
                                                            <div class="dropdown" style="position: static;">
                                                                <button type="button" class="kanban-dropdown-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="bi bi-three-dots-vertical"></i>
                                                                </button>
                                                                <div class="kanban-dropdown-menu dropdown-menu dropdown-menu-end p-2">
                                                                    <div class="d-flex gap-2 justify-content-center">
                                                                        <button class="kanban-icon-btn kanban-edit-btn" title="Edit" aria-label="Edit task">
                                                                            <i class="bi bi-pencil-square"></i>
                                                                        </button>
                                                                        <button class="kanban-icon-btn kanban-delete-btn" title="Delete" aria-label="Delete task">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="kanban-card-body">${task.description}</div>
                                                        <div class="kanban-card-footer mt-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>${priorityBadge}</div>
                                                                <span class="badge bg-light text-dark"><i class="bi bi-calendar me-1"></i>Due tomorrow</span>
                                                            </div>
                                                        </div>
                                                    </div>`;

                    if (task.status === 'Pending') {
                        todoHtml += taskHtml;
                    } else if (task.status === 'in-progress') {
                        inProgressHtml += taskHtml;
                    } else if (task.status === 'completed') {
                        completedHtml += taskHtml;
                    } else if (task.status === 'overdue') {
                        overdueHtml += taskHtml;
                    }
                });

                // Inject into DOM
                $('#todo-tasks').html(`<div class="kanban-cards">${todoHtml}</div>`);
                $('#inProgress-tasks').html(`<div class="kanban-cards">${inProgressHtml}</div>`);
                $('#completed-tasks').html(`<div class="kanban-cards">${completedHtml}</div>`);
                $('#overdue-tasks').html(`<div class="kanban-cards">${overdueHtml}</div>`);

                // Update badge counts
                $('#pending-badge').html(`<span class="badge bg-primary rounded-pill">${pendingCount}</span>`);
                $('#inprogress-badge').html(`<span class="badge bg-warning rounded-pill">${inProgressCount}</span>`);
                $('#completed-badge').html(`<span class="badge bg-success rounded-pill">${completedCount}</span>`);
                $('#overdue-badge').html(`<span class="badge bg-danger rounded-pill">${overdueCount}</span>`);
            }

            // Show fallback error messages
            function showErrorMessages() {
                const errorMsg = '<p class="text-danger">Error loading tasks.</p>';
                $('#todo-tasks').html(errorMsg);
                $('#inProgress-tasks').html(errorMsg);
                $('#completed-tasks').html(errorMsg);
                $('#overdue-tasks').html(errorMsg);
            }
        });
    </script>
@endsection