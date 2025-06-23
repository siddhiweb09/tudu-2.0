@extends('layouts.frame')

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
<div class="personal-tasks-container">
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
                            <span class="view-label">{{ ucfirst($view) }}</span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="viewDropdown">
                            <li>
                                <a class="dropdown-item view-switcher" href="#" data-view="list">
                                    <i class="ti ti-list me-2"></i>List View
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item view-switcher" href="#" data-view="kanban">
                                    <i class="ti ti-layout-kanban me-2"></i>Kanban Board
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item view-switcher" href="#" data-view="calendar">
                                    <i class="ti ti-calendar me-2"></i>Calendar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item view-switcher" href="#" data-view="matrix">
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
                @include("personal-tasks.{$view}")
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
        // View switcher - handle both direct links and AJAX
        $('.view-switcher').click(function(e) {
            e.preventDefault();
            const view = $(this).data('view');
            window.history.pushState({}, '', `/personal-tasks/${view}`);
            loadView(view);
        });

        // Function to load view content
        function loadView(view) {
            $.ajax({
                url: "{{ route('personal-tasks.index') }}",
                data: {
                    view: view
                },
                success: function(response) {
                    if (typeof response.html !== 'undefined') {
                        $('#view-container').html(response.html);
                    } else {
                        $('#view-container').html($(response).find('#view-container').html());
                    }

                    // Update active view label
                    $('.view-label').text(view.charAt(0).toUpperCase() + view.slice(1));

                    // Add animation
                    $('#view-container').addClass('animate__animated animate__fadeIn');
                    setTimeout(() => {
                        $('#view-container').removeClass('animate__fadeIn');
                    }, 500);
                }
            });
        }

        // Handle browser back/forward
        window.onpopstate = function() {
            const path = window.location.pathname;
            const view = path.split('/').pop();
            if (['list', 'kanban', 'calendar', 'matrix'].includes(view)) {
                loadView(view);
            }
        };

        // Calendar navigation
        $(document).on('click', '.calendar-nav', function(e) {
            e.preventDefault();
            const month = $(this).data('month');
            const year = $(this).data('year');

            $.ajax({
                url: "{{ route('personal-tasks.index') }}",
                data: {
                    view: 'calendar',
                    month: month,
                    year: year
                },
                success: function(response) {
                    if (typeof response.html !== 'undefined') {
                        $('#view-container').html(response.html);
                    } else {
                        $('#view-container').html($(response).find('#view-container').html());
                    }
                }
            });
        });
    });
</script>
@endsection