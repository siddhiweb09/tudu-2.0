@extends('layouts.frame')

@section('main')

<style>
    .kanban-board {
        padding: 1rem;
    }

    .kanban-column {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        min-height: 400px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .kanban-column h6 {
        font-weight: bold;
        margin-bottom: 1rem;
        text-align: center;
        color: #343a40;
    }

    .kanban-cards {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .kanban-card {
        background-color: #fff;
        border-left: 5px solid #0d6efd;
        border-radius: 6px;
        padding: 1rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .kanban-card:hover {
        transform: translateY(-2px);
    }

    .kanban-card-header {
        font-weight: bold;
        font-size: 1rem;
        color: #212529;
    }

    .kanban-card-body {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }
</style>

<div class="container-fluid">
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="card-title mb-4">Kanban Dashboard</h4>
            <div class="kanban-board">
                <div class="row g-3">
                    <!-- To Do -->
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="kanban-column">
                            <h6 class="text-primary">To Do</h6>
                            <div class="kanban-cards" id="todo-column">
                                <div class="kanban-card">
                                    <div class="kanban-card-header">Design Homepage</div>
                                    <div class="kanban-card-body">Create wireframes and get approval from team.</div>
                                </div>
                                <div class="kanban-card">
                                    <div class="kanban-card-header">API Documentation</div>
                                    <div class="kanban-card-body">Document authentication and user modules.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- In Progress -->
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="kanban-column">
                            <h6 class="text-warning">In Progress</h6>
                            <div class="kanban-cards" id="inprogress-column">
                                <div class="kanban-card" style="border-left-color: #ffc107;">
                                    <div class="kanban-card-header">Build Login System</div>
                                    <div class="kanban-card-body">Implement JWT auth and connect with backend.</div>
                                </div>
                                <div class="kanban-card" style="border-left-color: #ffc107;">
                                    <div class="kanban-card-header">UI Testing</div>
                                    <div class="kanban-card-body">Check responsiveness and browser compatibility.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completed -->
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="kanban-column">
                            <h6 class="text-success">Completed</h6>
                            <div class="kanban-cards" id="completed-column">
                                <div class="kanban-card" style="border-left-color: #198754;">
                                    <div class="kanban-card-header">Client Meeting</div>
                                    <div class="kanban-card-body">Final meeting completed and feedback collected.</div>
                                </div>
                                <div class="kanban-card" style="border-left-color: #198754;">
                                    <div class="kanban-card-header">Project Setup</div>
                                    <div class="kanban-card-body">Initial environment and dependencies configured.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OverDue -->
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="kanban-column">
                            <h6 class="text-danger">OverDue</h6>
                            <div class="kanban-cards" id="overdue-column">
                                <div class="kanban-card" style="border-left-color: #dc3545;">
                                    <div class="kanban-card-header">Update User Roles</div>
                                    <div class="kanban-card-body">Pending for over 3 days.</div>
                                </div>
                                <div class="kanban-card" style="border-left-color: #dc3545;">
                                    <div class="kanban-card-header">Bug Fix #42</div>
                                    <div class="kanban-card-body">Reported by QA, fix not yet deployed.</div>
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
    $(document).ready(function() {
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
                $.post("update_personal_task_status.php", {
                    task_id: taskId,
                    status: newStatus
                }, function(response) {
                    if (response.success) {
                        ui.draggable.appendTo(this);
                    }
                }.bind(this));
            }
        });
    });
</script>
@endsection