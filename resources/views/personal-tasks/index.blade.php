@extends('layouts.frame')

@section('main')
@include('personal-tasks.partials.ai-suggestions')

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-0">My Personal Tasks</h4>
            </div>
            <div class="d-flex">
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti-layout-grid2"></i> View: {{ ucfirst($view) }}
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('personal-tasks.list') }}">List View</a>
                        <a class="dropdown-item" href="{{ route('personal-tasks.kanban') }}">Kanban Board</a>
                        <a class="dropdown-item" href="{{ route('personal-tasks.calendar') }}">Calendar</a>
                        <a class="dropdown-item" href="{{ route('personal-tasks.matrix') }}">Priority Matrix</a>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="ti-plus"></i> Add Task
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Content -->
<div class="row">
    <div class="col-md-12">
        @if ($view == 'list')
        @include('personal-tasks.list')
        @elseif ($view == 'kanban')
        @include('personal-tasks.kanban')
        @elseif ($view == 'calendar')
        @include('personal-tasks.calendar')
        @elseif ($view == 'matrix')
        @include('personal-tasks.matrix')
        @endif
    </div>
</div>


@include('personal-tasks.partials.modals')
@endsection

@section('customJs')
<script src="assets/js/personal-tasks.js"></script>
@endsection