@extends('layouts.innerframe')

@section('main')
<div class="bg-white">
    <div class="container-xxl p-4">
        <div class="row mb-5 m-0 justify-content-between">
            <div class="col-md-8">
                <!-- Header Section -->
                <h2 class="mb-2">{{$task->title}}</h2>
                <p class="lead mb-3 text-muted">{{$task->task_id}}</p>

                <!-- Status Badges -->
                <div class="d-flex gap-2">
                    <span class="badge badge-light-warning rounded-pill">
                        <i class="bi bi-arrow-repeat me-1"></i>{{$task->final_status}}
                    </span>
                    <span class="badge badge-light-danger rounded-pill">
                        <i class="ti ti-flame me-1"></i>{{$task->priority}}
                    </span>
                </div>
                <!-- Divider -->
            </div>
            <div class="col align-self-center">
                <!-- Deadline Section -->
                <h5 class="text-muted text-end mb-2">Deadline: Nov 15, 2023</h5>
                <p class="text-muted text-end small mb-0"><i class="bi bi-calendar-event me-1"></i>Started: Oct 1, 2023
                </p>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Progress</h6>
                        <div class="row">
                            <div class="col-auto">
                                <h5 class="card-title">{{$progressPercentage}} %</h5>
                            </div>
                            <div class="col p-0">
                                <p class="card-text text-muted"><small>{{$totalTasks}} tasks ({{$completedTasks}}
                                        completed)</small></p>
                            </div>
                        </div>
                        <!-- Progress bar -->
                        <div class="progress mt-3 rounded-pill" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}"
                                aria-valuemin="0" aria-valuemax="100">
                                {{ $progressPercentage }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Progress</h6>
                        <div class="row">
                            <div class="col-auto">
                                <h5 class="card-title">{{$progressPercentage}} %</h5>
                            </div>
                            <div class="col p-0">
                                <p class="card-text text-muted"><small>{{$totalTasks}} tasks ({{$completedTasks}}
                                        completed)</small></p>
                            </div>
                        </div>
                        <!-- Progress bar -->
                        <div class="progress mt-3 rounded-pill" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}"
                                aria-valuemin="0" aria-valuemax="100">
                                {{ $progressPercentage }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Team</h6>
                        <div class="aspect-square-container mb-2">
                            <span class="aspect-square-box">
                                <img class="aspect-square" alt="Alex Morgan"
                                    src="../assets/images/users/alex-morgan.png"></span>
                            <span class="aspect-square-box">
                                <img class="aspect-square" alt="Jessica Chen"
                                    src="../assets/images/users/alex-morgan.png"></span>
                            <span class="aspect-square-box">
                                <img class="aspect-square" alt="Ryan Park" src="../assets/images/users/alex-morgan.png">
                            </span>
                        </div>

                        
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Progress</h6>
                        <div class="row">
                            <div class="col-auto">
                                <h5 class="card-title">{{$progressPercentage}} %</h5>
                            </div>
                            <div class="col p-0">
                                <p class="card-text text-muted"><small>{{$totalTasks}} tasks ({{$completedTasks}}
                                        completed)</small></p>
                            </div>
                        </div>
                        <!-- Progress bar -->
                        <div class="progress mt-3 rounded-pill" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}"
                                aria-valuemin="0" aria-valuemax="100">
                                {{ $progressPercentage }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="divider"></div>
</div>

<div class="container-xxl p-4">
    <nav>
        <div class="nav nav-tabs task-details-tab" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-overview-tab" data-bs-toggle="tab" data-bs-target="#nav-overview"
                type="button" role="tab" aria-controls="nav-overview" aria-selected="true"><i
                    class="ti ti-notes me-1"></i> Overview</button>
            <button class="nav-link" id="nav-tasks-tab" data-bs-toggle="tab" data-bs-target="#nav-tasks" type="button"
                role="tab" aria-controls="nav-tasks" aria-selected="false"><i class="ti ti-checkbox me-1"></i>
                Tasks</button>
            <button class="nav-link" id="nav-team-tab" data-bs-toggle="tab" data-bs-target="#nav-team" type="button"
                role="tab" aria-controls="nav-team" aria-selected="false"><i class="ti ti-users-group me-1"></i>
                Team</button>
            <button class="nav-link" id="nav-discussion-tab" data-bs-toggle="tab" data-bs-target="#nav-discussion"
                type="button" role="tab" aria-controls="nav-discussion" aria-selected="false"><i
                    class="ti ti-message me-1"></i> Discussion</button>
            <button class="nav-link" id="nav-analytics-tab" data-bs-toggle="tab" data-bs-target="#nav-analytics"
                type="button" role="tab" aria-controls="nav-analytics" aria-selected="false"><i
                    class="ti ti-chart-bar me-1"></i> Analytics</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">...
        </div>
        <div class="tab-pane fade" id="nav-tasks" role="tabpanel" aria-labelledby="nav-tasks-tab">...</div>
        <div class="tab-pane fade" id="nav-team" role="tabpanel" aria-labelledby="nav-team-tab">...</div>
        <div class="tab-pane fade" id="nav-discussion" role="tabpanel" aria-labelledby="nav-discussion-tab">...</div>
        <div class="tab-pane fade" id="nav-analytics" role="tabpanel" aria-labelledby="nav-analytics-tab">...</div>
    </div>
</div>

@endsection

@section('customJs')

@endsection