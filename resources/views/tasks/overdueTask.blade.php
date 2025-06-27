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
        border-left: 4px solid #1520a6;
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
        opacity: 0.5;
        background: #f8f9fa;
        transform: scale(1.02) rotate(2deg);
    }

    .kanban-column.drop-zone {
        background-color: rgba(13, 110, 253, 0.05);
        border: 2px dashed #1520a6;
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
    }
</style>

<!-- Blade View -->
<div class="container-xxl py-4">
    <!-- User Status Ring Section -->
    <div class="mt-2 px-4 py-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-0 fw-bold">Overdue Tasks </h4>
                <p class="text-muted mb-0">Track your overdue tasks.</p>
            </div>
        </div>
        <div class="kanban-board">
            <div class="row g-4">
                @foreach(['High', 'Medium', 'Low'] as $priority)
                @php
                $priorityClasses = [
                'High' => ['bg-class' => 'bg-primary-soft', 'text-class' => 'text-primary', 'icon' => 'bi-card-checklist'],
                'Medium' => ['bg-class' => 'bg-warning-soft', 'text-class' => 'text-warning', 'icon' => 'bi-lightning-charge'],
                'Low' => ['bg-class' => 'bg-success-soft', 'text-class' => 'text-success', 'icon' => 'bi-check-circle']
                ];
                @endphp

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="kanban-column {{ $priorityClasses[$priority]['bg-class'] }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="{{ $priorityClasses[$priority]['text-class'] }} mb-0">
                                <i class="bi {{ $priorityClasses[$priority]['icon'] }} me-2"></i>{{ $priority }}
                            </h6>
                            <span class="badge {{ strtolower($priorityClasses[$priority]['text-class']) }} rounded-pill"
                                id="{{ strtolower($priority) }}-count">
                                0
                            </span>
                        </div>
                        <div class="kanban-cards" id="{{ strtolower($priority) }}-column">
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-hourglass-split fs-4"></i>
                                <p class="mb-0">Loading tasks...</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@section('customJs')
<script src="assets/js/fetch-tasks.js"></script>
<script>

</script>
@endsection