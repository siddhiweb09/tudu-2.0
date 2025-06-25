@extends('layouts.innerframe')

@section('main')
<div class="bg-white border-bottom">
    <div class="container-xxl p-4">
        <div class="row mb-4 m-0 justify-content-between">
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
                <h5 class="text-muted text-end mb-2"><i class="ti ti-calendar-event me-1"></i>Deadline: {{$task->due_date}}</h5>
                <p class="text-muted text-end small mb-0"><i class="ti ti-alarm me-1"></i>Started: {{$task->created_at}} </p>
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
                        <div class="progress mt-2 rounded-pill" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}"
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Assigned by</h6>
                        <h5 class="card-title">{{$task->assign_by}}</h5>
                        <p class="card-text text-muted"><small>Final status: {{$task->final_status}}</small></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Team</h6>
                        <div class="aspect-square-container mb-2">
                            @foreach ($team as $teamMember)
                            @if(!empty($teamMember->profile_picture))
                            <div class="aspect-square-box">
                                <img class="aspect-square" alt="{{ $teamMember->employee_name }}"
                                    src="../assets/images/profile_picture/{{$teamMember->profile_picture}}">
                            </div>
                            @else
                            <div class="aspect-square-box">
                                <img class="aspect-square" alt="{{ $teamMember->employee_name }}"
                                    src="../assets/images/profile_picture/user.png">
                            </div>
                            @endif
                            @endforeach
                        </div>
                        <p class="card-text text-muted"><small>{{$teamCount}} team members</small></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Activity</h6>
                        <h5 class="card-title">{{$totalActivity}}</h5>
                        <p class="card-text text-muted"><small>Last updated: {{$lastActivity->created_at}}</small></p>
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
    <div class="tab-content mt-4" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
            <div class="row m-0">
                <div class="col-md-6 col-lg-8 ps-0">
                    <div class="card bg-white">
                        <div class="card-body">
                            <h4 class="card-title mb-3 text-decoration-underline">Task Description</h4>
                            {!! $task->description !!}
                        </div>
                    </div>
                    <div class="card bg-white mt-4">
                        <div class="card-body">
                            <h4 class="card-title mb-3 text-decoration-underline">Timeline</h4>
                            <div class="timeline p-4 block mb-4">
                                @foreach ($activities as $activity)
                                @if(!empty($activity->log_description))
                                <div class="tl-item">
                                    <div class="tl-dot b-primary"></div>
                                    <div class="tl-content">
                                        <h6 class="">{{$activity->log_description}}</h6>
                                        <div class="tl-date text-muted mt-1">{{$activity->created_at}} | {{$activity->added_by}}
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="tl-item">
                                    <div class="tl-dot b-primary"></div>
                                    <div class="tl-content">
                                        <h6 class="">No log found.</h6>
                                        <div class="tl-date text-muted mt-1"></div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 pe-0">
                    <div class="card bg-white">
                        <div class="card-body">
                            <h6 class="text-muted">Assigned by</h6>
                            <h5 class="card-title">{{$task->assign_by}}</h5>
                            <p class="card-text text-muted"><small>Final status: {{$task->final_status}}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-tasks" role="tabpanel" aria-labelledby="nav-tasks-tab">
            <div class="row row-cols-2 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title m-0 py-2"><i class="ti ti-checklist"></i> Pending</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($individualStats as $taskStat)
                            @if ($taskStat['status'] === "Pending")
                            <div class="card mb-3 task-card">
                                <div class="card-body">
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto fw-medium p-0 m-0">
                                            {{ $taskStat['task_id'] == $task->task_id ? 'Main Task' : 'Delegated Task' }}
                                        </p>
                                        <div class="col-auto p-0">
                                            @if($taskStat['priority'] === "high")
                                            <span class="badge badge-light-danger rounded">
                                                <i class="ti ti-flame me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @elseif($taskStat['priority'] === "medium")
                                            <span class="badge badge-light-warning rounded">
                                                <i class="ti ti-sun-high me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @else
                                            <span class="badge badge-light-success rounded">
                                                <i class="ti ti-leaf me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="card-title">{{ $taskStat['title'] ?? 'Untitled Task' }}</h5>

                                    {{-- Progress Info --}}
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto m-0 p-0">Progress</p>
                                        <div class="col-auto">{{ $taskStat['progress'] }}%</div>
                                    </div>

                                    <div class="progress mt-1 rounded-pill" style="height: 10px;">
                                        <div class="progress-bar bg-dark" role="progressbar"
                                            style="width: {{ $taskStat['progress'] }}%;"
                                            aria-valuenow="{{ $taskStat['progress'] }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>

                                    {{-- Optional Description or Action --}}
                                    <div class="row mt-2 m-0">
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-calendar-event me-1"></i>{{ $taskStat['assign_at'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-paperclip me-1"></i>{{ $taskStat['totalMedias'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-messages me-1"></i>{{ $taskStat['totalComments'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-checkbox me-1"></i>{{ $taskStat['completed'] }}/{{ $taskStat['total'] }}</p>
                                    </div>

                                    <div class="row mt-2 m-0 justify-content-between">
                                        <div class="aspect-square-container p-0 w-auto">
                                            @foreach ($taskStat['teamMembers'] as $member)
                                            @if(!empty($member->profile_picture))
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member->employee_name }}"
                                                    src="../assets/images/profile_picture/{{$member->profile_picture}}">
                                            </div>
                                            @else
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member->employee_name }}"
                                                    src="../assets/images/profile_picture/user.png">
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        <button class="w-auto bg-transparent border-0"><i class="ti ti-dots-vertical"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title m-0 py-2"><i class="ti ti-checklist"></i> In Progress</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($individualStats as $taskStat)
                            @if ($taskStat['status'] === "In Progress")
                            <div class="card mb-3 task-card">
                                <div class="card-body">
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto fw-medium p-0 m-0">
                                            {{ $taskStat['task_id'] == $task->task_id ? 'Main Task' : 'Delegated Task' }}
                                        </p>
                                        <div class="col-auto p-0">
                                            @if($taskStat['priority'] === "high")
                                            <span class="badge badge-light-danger rounded">
                                                <i class="ti ti-flame me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @elseif($taskStat['priority'] === "medium")
                                            <span class="badge badge-light-warning rounded">
                                                <i class="ti ti-sun-high me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @else
                                            <span class="badge badge-light-success rounded">
                                                <i class="ti ti-leaf me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="card-title">{{ $taskStat['title'] ?? 'Untitled Task' }}</h5>

                                    {{-- Progress Info --}}
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto m-0 p-0">Progress</p>
                                        <div class="col-auto">{{ $taskStat['progress'] }}%</div>
                                    </div>

                                    <div class="progress mt-1 rounded-pill" style="height: 10px;">
                                        <div class="progress-bar bg-dark" role="progressbar"
                                            style="width: {{ $taskStat['progress'] }}%;"
                                            aria-valuenow="{{ $taskStat['progress'] }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>

                                    {{-- Optional Description or Action --}}
                                    <div class="row mt-2 m-0">
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-calendar-event me-1"></i>{{ $taskStat['assign_at'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-paperclip me-1"></i>{{ $taskStat['totalMedias'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-messages me-1"></i>{{ $taskStat['totalComments'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-checkbox me-1"></i>{{ $taskStat['completed'] }}/{{ $taskStat['total'] }}</p>
                                    </div>

                                    <div class="row mt-2 m-0 justify-content-between">
                                        <div class="aspect-square-container p-0 w-auto">
                                            @foreach ($taskStat['teamMembers'] as $member)
                                            @if(!empty($member->profile_picture))
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member->employee_name }}"
                                                    src="../assets/images/profile_picture/{{$member->profile_picture}}">
                                            </div>
                                            @else
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member->employee_name }}"
                                                    src="../assets/images/profile_picture/user.png">
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        <button class="w-auto bg-transparent border-0"><i class="ti ti-dots-vertical"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>

                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title m-0 py-2"><i class="ti ti-checklist"></i> In Review</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($individualStats as $taskStat)
                            @if ($taskStat['status'] === "In Review")
                            <div class="card mb-3 task-card">
                                <div class="card-body">
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto fw-medium p-0 m-0">
                                            {{ $taskStat['task_id'] == $task->task_id ? 'Main Task' : 'Delegated Task' }}
                                        </p>
                                        <div class="col-auto p-0">
                                            @if($taskStat['priority'] === "high")
                                            <span class="badge badge-light-danger rounded">
                                                <i class="ti ti-flame me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @elseif($taskStat['priority'] === "medium")
                                            <span class="badge badge-light-warning rounded">
                                                <i class="ti ti-sun-high me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @else
                                            <span class="badge badge-light-success rounded">
                                                <i class="ti ti-leaf me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="card-title">{{ $taskStat['title'] ?? 'Untitled Task' }}</h5>

                                    {{-- Progress Info --}}
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto m-0 p-0">Progress</p>
                                        <div class="col-auto">{{ $taskStat['progress'] }}%</div>
                                    </div>

                                    <div class="progress mt-1 rounded-pill" style="height: 10px;">
                                        <div class="progress-bar bg-dark" role="progressbar"
                                            style="width: {{ $taskStat['progress'] }}%;"
                                            aria-valuenow="{{ $taskStat['progress'] }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>

                                    {{-- Optional Description or Action --}}
                                    <div class="row mt-2 m-0">
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-calendar-event me-1"></i>{{ $taskStat['assign_at'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-paperclip me-1"></i>{{ $taskStat['totalMedias'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-messages me-1"></i>{{ $taskStat['totalComments'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-checkbox me-1"></i>{{ $taskStat['completed'] }}/{{ $taskStat['total'] }}</p>
                                    </div>

                                    <div class="row mt-2 m-0 justify-content-between">
                                        <div class="aspect-square-container p-0 w-auto">
                                            @foreach ($taskStat['teamMembers'] as $member)
                                            @if(!empty($member->profile_picture))
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member->employee_name }}"
                                                    src="../assets/images/profile_picture/{{$member->profile_picture}}">
                                            </div>
                                            @else
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member->employee_name }}"
                                                    src="../assets/images/profile_picture/user.png">
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        <button class="w-auto bg-transparent border-0"><i class="ti ti-dots-vertical"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>

                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title m-0 py-2"><i class="ti ti-checklist"></i> Completed</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($individualStats as $taskStat)
                            @if ($taskStat['status'] === "Completed")
                            <div class="card mb-3 task-card">
                                <div class="card-body">
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto fw-medium p-0 m-0">
                                            {{ $taskStat['task_id'] == $task->task_id ? 'Main Task' : 'Delegated Task' }}
                                        </p>
                                        <div class="col-auto p-0">
                                            @if($taskStat['priority'] === "high")
                                            <span class="badge badge-light-danger rounded">
                                                <i class="ti ti-flame me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @elseif($taskStat['priority'] === "medium")
                                            <span class="badge badge-light-warning rounded">
                                                <i class="ti ti-sun-high me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @else
                                            <span class="badge badge-light-success rounded">
                                                <i class="ti ti-leaf me-1"></i>{{ $taskStat['priority'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="card-title">{{ $taskStat['title'] ?? 'Untitled Task' }}</h5>

                                    {{-- Progress Info --}}
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto m-0 p-0">Progress</p>
                                        <div class="col-auto">{{ $taskStat['progress'] }}%</div>
                                    </div>

                                    <div class="progress mt-1 rounded-pill" style="height: 10px;">
                                        <div class="progress-bar bg-dark" role="progressbar"
                                            style="width: {{ $taskStat['progress'] }}%;"
                                            aria-valuenow="{{ $taskStat['progress'] }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>

                                    {{-- Optional Description or Action --}}
                                    <div class="row mt-2 m-0">
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-calendar-event me-1"></i>{{ $taskStat['assign_at'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-paperclip me-1"></i>{{ $taskStat['totalMedias'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-messages me-1"></i>{{ $taskStat['totalComments'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i class="ti ti-checkbox me-1"></i>{{ $taskStat['completed'] }}/{{ $taskStat['total'] }}</p>
                                    </div>

                                    <div class="row mt-2 m-0 justify-content-between">
                                        <div class="aspect-square-container p-0 w-auto">
                                            @foreach ($taskStat['teamMembers'] as $member)
                                            @if(!empty($member->profile_picture))
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member->employee_name }}"
                                                    src="../assets/images/profile_picture/{{$member->profile_picture}}">
                                            </div>
                                            @else
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member->employee_name }}"
                                                    src="../assets/images/profile_picture/user.png">
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        <button class="w-auto bg-transparent border-0"><i class="ti ti-dots-vertical"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-team" role="tabpanel" aria-labelledby="nav-team-tab">
            <div class="card bg-white">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Tasks</th>
                                <th scope="col">Sub-Tasks</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($userWiseStats as $userStat)
                            <tr>
                                <td>{{$userStat['employee_code']}}*{{$userStat['employee_name']}}</td>
                                <td>
                                    @foreach($userStat['tasks'] as $usertask)
                                    <a href="/task/{{$usertask['task_id']}}">
                                        <span class="badge badge-outline-primary rounded">
                                            {{$usertask['title']}}
                                        </span>
                                    </a>
                                    @endforeach
                                </td>
                                <td><b>{{ $userStat['total_tasks'] }}</b> ({{ $userStat['completed_tasks'] }}/ completed)</td>
                                <td>{{ $userStat['progress'] }}% </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                        <button type="button" class="btn btn-transparent shadow-none py-0"><i class="ti ti-message"></i></button>
                                        <button type="button" class="btn btn-transparent shadow-none py-0"><i class="ti ti-edit"></i></button>
                                        <button type="button" class="btn btn-transparent shadow-none py-0"><i class="ti ti-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-discussion" role="tabpanel" aria-labelledby="nav-discussion-tab">...</div>
        <div class="tab-pane fade" id="nav-analytics" role="tabpanel" aria-labelledby="nav-analytics-tab">...</div>
    </div>
</div>

@endsection

@section('customJs')

@endsection