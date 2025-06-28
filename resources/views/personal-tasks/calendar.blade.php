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

    .calendar-container {
        width: 100%;
        margin: 0 auto;
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }

    .calendar-day {
        min-height: 100px;
        border: 1px solid #e0e0e0;
        padding: 5px;
        background-color: white;
    }

    .dark-mode .calendar-day {
        background-color: #2a2a2a;
        border-color: #444;
    }

    .calendar-day.empty {
        background-color: #f9f9f9;
    }

    .dark-mode .calendar-day.empty {
        background-color: #1e1e1e;
    }

    .calendar-day.today {
        background-color: #e3f2fd;
        border: 2px solid #2196f3;
    }

    .dark-mode .calendar-day.today {
        background-color: #0d3a63;
        border-color: #1976d2;
    }

    .day-number {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .day-tasks {
        max-height: 80px;
        overflow-y: auto;
    }

    .calendar-task {
        font-size: 12px;
        padding: 2px 5px;
        margin-bottom: 3px;
        border-radius: 3px;
        background-color: #f5f5f5;
        cursor: pointer;
    }

    .dark-mode .calendar-task {
        background-color: #333;
    }

    .calendar-task.high-priority {
        background-color: #ffebee;
        border-left: 3px solid #f44336;
    }

    .calendar-task.medium-priority {
        background-color: #fff8e1;
        border-left: 3px solid #ffc107;
    }

    .dark-mode .calendar-task.high-priority {
        background-color: #4a1c1c;
    }

    .dark-mode .calendar-task.medium-priority {
        background-color: #4a3a1c;
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Task Calendar</h5>
                            <div class="btn-group">
                                <a href="{{ route('personal-tasks.calendar', ['month' => $month-1 < 1 ? 12 : $month-1, 'year' => $month-1 < 1 ? $year-1 : $year]) }}"
                                    class="btn btn-outline-secondary calendar-nav">
                                    <i class="ti-angle-left"></i>
                                </a>
                                <button class="btn btn-outline-primary disabled">
                                    {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                                </button>
                                <a href="{{ route('personal-tasks.calendar', ['month' => $month+1 > 12 ? 1 : $month+1, 'year' => $month+1 > 12 ? $year+1 : $year]) }}"
                                    class="btn btn-outline-secondary calendar-nav">
                                    <i class="ti-angle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="calendar-container">
                            <div class="calendar-header">
                                <div class="calendar-day-header">Mon</div>
                                <div class="calendar-day-header">Tue</div>
                                <div class="calendar-day-header">Wed</div>
                                <div class="calendar-day-header">Thu</div>
                                <div class="calendar-day-header">Fri</div>
                                <div class="calendar-day-header">Sat</div>
                                <div class="calendar-day-header">Sun</div>
                            </div>

                            <div class="calendar-grid">
                                @php
                                $firstDayOfMonth = \Carbon\Carbon::create($year, $month, 1);
                                $daysInMonth = $firstDayOfMonth->daysInMonth;
                                $firstDayOfWeek = $firstDayOfMonth->dayOfWeekIso;

                                // Fill in blank days for the first week
                                for ($i = 1; $i < $firstDayOfWeek; $i++) {
                                    echo '<div class="calendar-day empty"></div>' ;
                                    }

                                    // Display each day of the month
                                    for ($day=1; $day <=$daysInMonth; $day++) {
                                    $currentDate=\Carbon\Carbon::create($year, $month, $day);
                                    $isToday=$currentDate->isToday();

                                    // Filter tasks for this day
                                    $dayTasks = $tasks->filter(function($task) use ($currentDate) {
                                    return $task->due_date && $task->due_date->isSameDay($currentDate);
                                    });

                                    echo '<div class="calendar-day'.($isToday ? ' today' : '').'">';
                                        echo '<div class="day-number">'.$day.'</div>';

                                        if ($dayTasks->count() > 0) {
                                        echo '<div class="day-tasks">';
                                            foreach ($dayTasks as $task) {
                                            $priority_class = '';
                                            if ($task->priority == 'high') $priority_class = 'high-priority';
                                            elseif ($task->priority == 'medium') $priority_class = 'medium-priority';

                                            echo '<div class="calendar-task '.$priority_class.'" data-task-id="'.$task->id.'">';
                                                echo '<span class="task-title">'.e($task->title).'</span>';

                                                if ($task->status == 'completed') {
                                                echo '<i class="ti-check text-success ml-1"></i>';
                                                }
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        echo '</div>';

                                    // Start new row after Sunday
                                    if (($day + $firstDayOfWeek - 1) % 7 == 0 && $day != $daysInMonth) {
                                    echo '</div>
                            <div class="calendar-grid">';
                                }
                                }

                                // Fill in remaining blank days in the last week
                                $daysDisplayed = $daysInMonth + $firstDayOfWeek - 1;
                                $remainingDays = 7 - ($daysDisplayed % 7);
                                if ($remainingDays < 7) {
                                    for ($i=0; $i < $remainingDays; $i++) {
                                    echo '<div class="calendar-day empty"></div>' ;
                                    }
                                    }
                                    @endphp
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