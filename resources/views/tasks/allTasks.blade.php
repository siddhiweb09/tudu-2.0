@extends('layouts.frame')

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
            min-height: 400px;
        }

        .kanban-card {
            background-color: #fff;
            border-left: 4px solid #0d6efd;
            border-radius: 10px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: grab;
            position: relative;
            overflow: hidden;
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

        .story-ring {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            padding: 2px;
            background: linear-gradient(45deg, #f3f3f3, #e0e0e0);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }

        .story-ring img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            display: block;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .story-ring.notify {
            border: 2px solid #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
            animation: pulse 2s infinite;
        }

        .user-avatar-link {
            min-width: 80px;
            text-align: center;
            color: inherit;
            transition: transform 0.2s ease;
            position: relative;
        }

        .user-avatar-link:hover {
            transform: translateY(-5px);
        }

        .user-avatar-link:hover .story-ring img {
            transform: scale(1.05);
        }

        .user-status-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .bg-secondary {
            background-color: #6c757d !important;
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

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
            }
        }

        /* Drag and drop styling */
        .kanban-card.dragging {
            opacity: 0.5;
            background: #f8f9fa;
            transform: scale(1.02) rotate(2deg);
        }

        .kanban-column.drop-zone {
            background-color: rgba(13, 110, 253, 0.05);
            border: 2px dashed #0d6efd;
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
    </style>

    <!-- Blade View -->
    <div class="container-fluid">
        <!-- User Status Ring Section -->
        <div class="d-flex overflow-auto gap-4 py-3 px-3 mb-4 border-bottom">
            @php
                $users = [
                    ['name' => 'Alice', 'image' => '10491834.jpg', 'section' => 'todo-column', 'notify' => true, 'status' => 'active'],
                    ['name' => 'Bob', 'image' => '7309691.jpg', 'section' => 'inprogress-column', 'notify' => true, 'status' => 'busy'],
                    ['name' => 'Clara', 'image' => '10491834.jpg', 'section' => 'completed-column', 'notify' => false, 'status' => 'away'],
                    ['name' => 'Dave', 'image' => '7309691.jpg', 'section' => 'overdue-column', 'notify' => false, 'status' => 'offline'],
                    ['name' => 'Eve', 'image' => '10491834.jpg', 'section' => 'todo-column', 'notify' => true, 'status' => 'active'],
                    ['name' => 'Frank', 'image' => '7309691.jpg', 'section' => 'inprogress-column', 'notify' => false, 'status' => 'active'],
                ];
            @endphp

            @foreach ($users as $user)
                <a href="#{{ $user['section'] }}" class="text-decoration-none text-center user-avatar-link position-relative">
                    <div class="story-ring {{ $user['notify'] ? 'notify' : '' }} position-relative">
                        <img src="{{ asset('assets/images/profile_picture/' . $user['image']) }}" alt="{{ $user['name'] }}"
                            class="user-avatar">
                        <span
                            class="user-status-badge bg-{{ $user['status'] == 'active' ? 'success' : ($user['status'] == 'busy' ? 'danger' : ($user['status'] == 'away' ? 'warning' : 'secondary')) }}"></span>
                    </div>
                    <small class="d-block mt-2 text-dark fw-semibold">{{ $user['name'] }}</small>
                    <span class="badge bg-primary rounded-pill position-absolute top-0 end-0 mt-0 me-1">3</span>
                </a>
            @endforeach
        </div>

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
                                    <span class="badge bg-primary rounded-pill">4</span>
                                </div>
                                <div class="kanban-cards" id="todo-column">
                                    <div class="kanban-card" draggable="true">
                                        <div class="kanban-card-header d-flex justify-content-between">
                                            <span>Design Homepage</span>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Move</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="kanban-card-body">Create wireframes and get approval from team.</div>
                                        <div class="kanban-card-footer mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="avatar-group">
                                                    <img src="{{ asset('assets/images/profile_picture/10491834.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Alice">
                                                    <img src="{{ asset('assets/images/profile_picture/7309691.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Bob">
                                                </div>
                                                <span class="badge bg-light text-dark"><i
                                                        class="bi bi-calendar me-1"></i>Due tomorrow</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="kanban-card" draggable="true">
                                        <div class="kanban-card-header d-flex justify-content-between">
                                            <span>API Documentation</span>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Move</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="kanban-card-body">Document authentication and user modules.</div>
                                        <div class="kanban-card-footer mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="avatar-group">
                                                    <img src="{{ asset('assets/images/profile_picture/7309691.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Bob">
                                                </div>
                                                <span class="badge bg-light text-dark"><i
                                                        class="bi bi-calendar me-1"></i>Due in 3 days</span>
                                            </div>
                                        </div>
                                    </div>

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
                                    <span class="badge bg-warning rounded-pill">3</span>
                                </div>
                                <div class="kanban-cards" id="inprogress-column">
                                    <div class="kanban-card" draggable="true" style="border-left-color: #ffc107;">
                                        <div class="kanban-card-header d-flex justify-content-between">
                                            <span>Build Login System</span>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Move</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="kanban-card-body">Implement JWT auth and connect with backend.</div>
                                        <div class="kanban-card-footer mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="avatar-group">
                                                    <img src="{{ asset('assets/images/profile_picture/7309691.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Bob">
                                                    <img src="{{ asset('assets/images/profile_picture/10491834.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Alice">
                                                </div>
                                                <span class="badge bg-light text-dark"><i
                                                        class="bi bi-calendar me-1"></i>Due today</span>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 65%;"
                                                aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    <div class="kanban-card" draggable="true" style="border-left-color: #ffc107;">
                                        <div class="kanban-card-header d-flex justify-content-between">
                                            <span>UI Testing</span>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Move</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="kanban-card-body">Check responsiveness and browser compatibility.</div>
                                        <div class="kanban-card-footer mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="avatar-group">
                                                    <img src="{{ asset('assets/images/profile_picture/10491834.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Alice">
                                                </div>
                                                <span class="badge bg-light text-dark"><i
                                                        class="bi bi-calendar me-1"></i>Due in 2 days</span>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 30%;"
                                                aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
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
                                    <span class="badge bg-success rounded-pill">2</span>
                                </div>
                                <div class="kanban-cards" id="completed-column">
                                    <div class="kanban-card" draggable="true" style="border-left-color: #198754;">
                                        <div class="kanban-card-header d-flex justify-content-between">
                                            <span>Client Meeting</span>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Move</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="kanban-card-body">Final meeting completed and feedback collected.</div>
                                        <div class="kanban-card-footer mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="avatar-group">
                                                    <img src="{{ asset('assets/images/profile_picture/10491834.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Clara">
                                                </div>
                                                <span class="badge bg-light text-dark"><i
                                                        class="bi bi-check-circle me-1"></i>Completed</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="kanban-card" draggable="true" style="border-left-color: #198754;">
                                        <div class="kanban-card-header d-flex justify-content-between">
                                            <span>Project Setup</span>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Move</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="kanban-card-body">Initial environment and dependencies configured.</div>
                                        <div class="kanban-card-footer mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="avatar-group">
                                                    <img src="{{ asset('assets/images/profile_picture/7309691.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Dave">
                                                </div>
                                                <span class="badge bg-light text-dark"><i
                                                        class="bi bi-check-circle me-1"></i>Completed</span>
                                            </div>
                                        </div>
                                    </div>

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
                                    <span class="badge bg-danger rounded-pill">2</span>
                                </div>
                                <div class="kanban-cards" id="overdue-column">
                                    <div class="kanban-card" draggable="true" style="border-left-color: #dc3545;">
                                        <div class="kanban-card-header d-flex justify-content-between">
                                            <span>Update User Roles</span>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Move</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="kanban-card-body">Pending for over 3 days.</div>
                                        <div class="kanban-card-footer mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="avatar-group">
                                                    <img src="{{ asset('assets/images/profile_picture/7309691.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Dave">
                                                </div>
                                                <span class="badge bg-danger text-white"><i
                                                        class="bi bi-clock me-1"></i>Overdue</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="kanban-card" draggable="true" style="border-left-color: #dc3545;">
                                        <div class="kanban-card-header d-flex justify-content-between">
                                            <span>Bug Fix #42</span>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Move</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="kanban-card-body">Reported by QA, fix not yet deployed.</div>
                                        <div class="kanban-card-footer mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="avatar-group">
                                                    <img src="{{ asset('assets/images/profile_picture/10491834.jpg') }}"
                                                        class="avatar-xs rounded-circle" alt="Clara">
                                                </div>
                                                <span class="badge bg-danger text-white"><i
                                                        class="bi bi-clock me-1"></i>Overdue</span>
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

@endsection

@section('customJs')
    <script>
        $(document).ready(function () {
            // Make kanban cards draggable
            $(".kanban-card").draggable({
                revert: "invalid",
                cursor: "move",
                zIndex: 100
            });

            // Make columns droppable
            $(".kanban-cards").droppable({
                accept: ".kanban-card",
                drop: function (event, ui) {
                    var taskId = ui.draggable.data("task-id");
                    var newStatus = $(this).parent().find("h6").text().toLowerCase().replace(" ", "_");

                    // Update status via AJAX
                    $.post("update_personal_task_status.php", {
                        task_id: taskId,
                        status: newStatus
                    }, function (response) {
                        if (response.success) {
                            ui.draggable.appendTo(this);
                        }
                    }.bind(this));
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            // Make cards draggable
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
                    draggedCard = null;
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

            function getDragAfterElement(container, y) {
                const draggableElements = [...container.querySelectorAll('.kanban-card:not(.dragging)')];

                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;

                    if (offset < 0 && offset > closest.offset) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY }).element;
            }

            // Add animation to user avatars on hover
            const userLinks = document.querySelectorAll('.user-avatar-link');
            userLinks.forEach(link => {
                link.addEventListener('mouseenter', function () {
                    const ring = this.querySelector('.story-ring');
                    ring.style.transform = 'scale(1.1)';
                });

                link.addEventListener('mouseleave', function () {
                    const ring = this.querySelector('.story-ring');
                    ring.style.transform = 'scale(1)';
                });
            });
        });

    </script>
@endsection