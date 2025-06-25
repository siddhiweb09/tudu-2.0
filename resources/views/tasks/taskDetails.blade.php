@extends('layouts.innerframe')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <h5 class="text-muted text-end mb-2"><i class="ti ti-calendar-event me-1"></i>Deadline:
                        {{$task->due_date}}
                    </h5>
                    <p class="text-muted text-end small mb-0"><i class="ti ti-alarm me-1"></i>Started: {{$task->created_at}}
                    </p>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col">
                    <div class="card h-100 bg-light border-0">
                        <div class="card-body">
                            <h6 class="text-muted">Progress</h6>
                            <div class="row m-0 justify-content-between">
                                <div class="col-auto p-0">
                                    <h5 class="card-title">{{$progressPercentage}} %</h5>
                                </div>
                                <div class="col-auto p-0">
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
                <button class="nav-link" id="nav-overview-tab" data-bs-toggle="tab" data-bs-target="#nav-overview"
                    type="button" role="tab" aria-controls="nav-overview" aria-selected="true"><i
                        class="ti ti-notes me-1"></i> Overview</button>
                <button class="nav-link" id="nav-tasks-tab" data-bs-toggle="tab" data-bs-target="#nav-tasks" type="button"
                    role="tab" aria-controls="nav-tasks" aria-selected="false"><i class="ti ti-checkbox me-1"></i>
                    Tasks</button>
                <button class="nav-link" id="nav-team-tab" data-bs-toggle="tab" data-bs-target="#nav-team" type="button"
                    role="tab" aria-controls="nav-team" aria-selected="false"><i class="ti ti-users-group me-1"></i>
                    Team</button>
                <button class="nav-link active" id="nav-discussion-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-discussion" type="button" role="tab" aria-controls="nav-discussion"
                    aria-selected="false"><i class="ti ti-message me-1"></i> Discussion</button>
                <button class="nav-link" id="nav-analytics-tab" data-bs-toggle="tab" data-bs-target="#nav-analytics"
                    type="button" role="tab" aria-controls="nav-analytics" aria-selected="false"><i
                        class="ti ti-chart-bar me-1"></i> Analytics</button>
            </div>
        </nav>
        <div class="tab-content mt-4" id="nav-tabContent">
            <div class="tab-pane fade" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
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
                                                    <div class="tl-date text-muted mt-1">{{$activity->created_at}} |
                                                        {{$activity->added_by}}
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
                                <h4 class="card-title mb-3 text-decoration-underline">Task Details</h4>
                                <div class="row m-0 justify-content-between">
                                    <div class="col-auto p-0">
                                        <p class="card-title fw-bold text-muted">Reminders: </p>
                                    </div>
                                    <div class="col-auto p-0">
                                        @php
                                            $reminders = json_decode($task->reminders, true);
                                        @endphp
                                        @foreach ($reminders as $reminder)
                                            @if($reminder === "Email")
                                                <span class="bg-danger-light px-2 py-1 rounded small text-danger"><i
                                                        class="ti ti-mail-opened me-1"></i>{{ $reminder }}</span>
                                            @elseif($reminder === "WhatsApp")
                                                <span class="bg-success-light px-2 py-1 rounded small text-success"><i
                                                        class="ti ti-brand-whatsapp me-1"></i>{{ $reminder }}</span>
                                            @elseif($reminder === "Telegram")
                                                <span class="bg-primary-light px-2 py-1 rounded small text-primary"><i
                                                        class="ti ti-brand-telegram me-1"></i>{{ $reminder }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row m-0 mt-4 justify-content-between">
                                    <div class="col-auto p-0">
                                        <p class="card-title fw-bold text-muted">Frequency: </p>
                                    </div>
                                    <div class="col-auto p-0">
                                        <p class="card-title fw-medium text-muted">{{$task->frequency}}</p>
                                    </div>
                                </div>
                                <div class="row m-0 mt-4 justify-content-between">
                                    @php
                                        $frequencies = json_decode($task->frequency_duration, true);
                                        $badgeColors = ['bg-primary-light text-primary', 'bg-success-light text-success', 'bg-warning-light text-warning', 'bg-danger-light text-danger', 'bg-secondary-light text-secondary', 'bg-dark-light text-dark', 'bg-dark text-light'];
                                    @endphp

                                    @foreach ($frequencies as $index => $frequency)
                                        @php
                                            $colorClass = $badgeColors[$index % count($badgeColors)];
                                        @endphp
                                        <span
                                            class="{{ $colorClass }} px-2 py-1 rounded small me-1 mb-2 fw-medium w-auto">{{ $frequency }}</span>
                                    @endforeach
                                </div>
                                <div class="row m-0 mt-4 justify-content-between">
                                    <div class="col-auto p-0">
                                        <p class="card-title fw-bold text-muted">Rating: </p>
                                    </div>
                                    <div class="col-auto p-0">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="ti ti-star-filled{{ $i <= $task->ratings ? ' text-warning' : ' text-muted' }} me-1"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-white mt-4">
                            <div class="card-body">
                                <h4 class="card-title mb-3 text-decoration-underline">Attachments</h4>
                                <div class="row m-0 justify-content-between">
                                    <div class="col p-0">
                                        @php
                                            $grouped = $taskMedias->where('category', 'document')->groupBy('task_id');
                                        @endphp

                                        @foreach ($grouped as $taskId => $docs)
                                            <p class="card-title my-3 fw-medium">{{ $taskId }}</p>

                                            <div class="border p-3">
                                                @foreach ($docs as $taskMedia)
                                                    <div
                                                        class="d-flex justify-content-between align-items-center bg-light px-1 py-2 rounded document-box">
                                                        <div class="d-flex align-items-center">
                                                            <i class="ti ti-file-text me-1 text-secondary"></i>
                                                            <span class="small">{{ $taskMedia->file_name }}</span>
                                                        </div>
                                                        <a href="../assets/uploads/{{ $taskMedia->file_name }}"
                                                            class="ti ti-download border-0 bg-transparent ps-3" download></a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-white mt-4">
                            <div class="card-body">
                                <h4 class="card-title mb-3 text-decoration-underline">Links</h4>
                                <div class="row m-0 justify-content-between">
                                    <div class="col p-0">
                                        @php
                                            $grouped = $taskMedias->where('category', 'link')->groupBy('task_id');
                                        @endphp

                                        @foreach ($grouped as $taskId => $docs)
                                            <p class="card-title my-3 fw-medium">{{ $taskId }}</p>

                                            <div class="border p-3">
                                                @foreach ($docs as $taskMedia)
                                                    <div class="d-flex align-items-center bg-light px-1 py-2 rounded document-box">
                                                        <i class="ti ti-link me-2 text-secondary"></i>
                                                        <a href="{{ $taskMedia->file_name }}"
                                                            class="small text-break text-decoration-underline">{{ $taskMedia->file_name }}</a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-white mt-4">
                            <div class="card-body">
                                <h4 class="card-title mb-3 text-decoration-underline">Voice Notes</h4>
                                <div class="row m-0 justify-content-between">
                                    <div class="col p-0">
                                        @php
                                            $grouped = $taskMedias->where('category', 'voice_note')->groupBy('task_id');
                                        @endphp

                                        @foreach ($grouped as $taskId => $docs)
                                            <p class="card-title my-3 fw-medium">{{ $taskId }}</p>

                                            <div class="border p-3">
                                                @foreach ($docs as $taskMedia)
                                                    <div
                                                        class="d-flex justify-content-between align-items-center bg-light px-1 py-2 rounded document-box">
                                                        <div class="d-flex align-items-center">
                                                            <i class="ti ti-speakerphone me-1 text-secondary"></i>
                                                            <span class="small">{{ $taskMedia->file_name }}</span>
                                                        </div>
                                                        <a href="../assets/uploads/{{ $taskMedia->file_name }}"
                                                            class="ti ti-circle-caret-right border-0 bg-transparent ps-3"
                                                            download></a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
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
                                        <div class="card mb-3 task-card" data-task-id="{{ $taskStat['task_id'] }}"
                                            data-bs-toggle="modal" data-bs-target="#taskDetailModal" style="cursor: pointer;">
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
                                                        aria-valuenow="{{ $taskStat['progress'] }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>

                                                {{-- Optional Description or Action --}}
                                                <div class="row mt-2 m-0">
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-calendar-event me-1"></i>{{ $taskStat['assign_at'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-paperclip me-1"></i>{{ $taskStat['totalMedias'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-messages me-1"></i>{{ $taskStat['totalComments'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-checkbox me-1"></i>{{ $taskStat['completed'] }}/{{ $taskStat['total'] }}
                                                    </p>
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
                                                    <button class="w-auto bg-transparent border-0"><i
                                                            class="ti ti-dots-vertical"></i></button>
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
                                                        aria-valuenow="{{ $taskStat['progress'] }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>

                                                {{-- Optional Description or Action --}}
                                                <div class="row mt-2 m-0">
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-calendar-event me-1"></i>{{ $taskStat['assign_at'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-paperclip me-1"></i>{{ $taskStat['totalMedias'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-messages me-1"></i>{{ $taskStat['totalComments'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-checkbox me-1"></i>{{ $taskStat['completed'] }}/{{ $taskStat['total'] }}
                                                    </p>
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
                                                    <button class="w-auto bg-transparent border-0"><i
                                                            class="ti ti-dots-vertical"></i></button>
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
                                                        aria-valuenow="{{ $taskStat['progress'] }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>

                                                {{-- Optional Description or Action --}}
                                                <div class="row mt-2 m-0">
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-calendar-event me-1"></i>{{ $taskStat['assign_at'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-paperclip me-1"></i>{{ $taskStat['totalMedias'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-messages me-1"></i>{{ $taskStat['totalComments'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-checkbox me-1"></i>{{ $taskStat['completed'] }}/{{ $taskStat['total'] }}
                                                    </p>
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
                                                    <button class="w-auto bg-transparent border-0"><i
                                                            class="ti ti-dots-vertical"></i></button>
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
                                                        aria-valuenow="{{ $taskStat['progress'] }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>

                                                {{-- Optional Description or Action --}}
                                                <div class="row mt-2 m-0">
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-calendar-event me-1"></i>{{ $taskStat['assign_at'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-paperclip me-1"></i>{{ $taskStat['totalMedias'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-messages me-1"></i>{{ $taskStat['totalComments'] }}</p>
                                                    <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                            class="ti ti-checkbox me-1"></i>{{ $taskStat['completed'] }}/{{ $taskStat['total'] }}
                                                    </p>
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
                                                    <button class="w-auto bg-transparent border-0"><i
                                                            class="ti ti-dots-vertical"></i></button>
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
                                        <td><b>{{ $userStat['total_tasks'] }}</b> ({{ $userStat['completed_tasks'] }}/
                                            completed)</td>
                                        <td>{{ $userStat['progress'] }}% </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                <button type="button" class="btn btn-transparent shadow-none py-0"><i
                                                        class="ti ti-message"></i></button>
                                                <button type="button" class="btn btn-transparent shadow-none py-0"><i
                                                        class="ti ti-edit"></i></button>
                                                <button type="button" class="btn btn-transparent shadow-none py-0"><i
                                                        class="ti ti-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-discussion" role="tabpanel" aria-labelledby="nav-discussion-tab">
                <div class="tab-pane fade show active" id="discussions" role="tabpanel" aria-labelledby="discussions-tab">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3 mb-4">
                        <div>
                            <h2 class="h5 fw-bold mb-1">Discussions</h2>
                            <p class="text-muted mb-0">Project discussions and comments</p>
                        </div>
                        <button class="btn btn-primary d-inline-flex align-items-center">
                            <i class="ti ti-message-circle me-2"></i> New Discussion
                        </button>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <!-- Fixed Header -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h5 class="card-title mb-1">Design System Components</h5>
                                                <small class="text-muted">Started by Jessica Chen • 3 days ago</small>
                                            </div>
                                            <span class="badge text-bg-success">Active</span>
                                        </div>
                                        <hr>
                                    </div>

                                    <!-- Scrollable Comment Section -->
                                    <div class="flex-grow-1 overflow-auto" style="max-height: 400px;">
                                        <div class="d-flex mb-4">
                                            <img src="/avatars/jessica-chen.png" alt="Jessica Chen"
                                                class="rounded-circle me-3" width="40" height="40">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <strong>Jessica Chen</strong>
                                                    <span class="mx-2 text-muted">•</span>
                                                    <small class="text-muted">3 days ago</small>
                                                </div>
                                                <p>I've updated the design files with the latest component changes. Please
                                                    review when you get a chance.</p>
                                                <p>Also, I'm thinking we should standardize our color palette a bit more.
                                                </p>
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-secondary me-2">
                                                        <i class="bi bi-reply me-1"></i> Reply
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-link-45deg me-1"></i> Copy Link
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex mb-4">
                                            <img src="/avatars/alex-morgan.png" alt="Alex Morgan"
                                                class="rounded-circle me-3" width="40" height="40">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <strong>Alex Morgan</strong>
                                                    <span class="mx-2 text-muted">•</span>
                                                    <small class="text-muted">2 days ago</small>
                                                </div>
                                                <p>Thanks for the update, Jessica! I've reviewed the changes and they look
                                                    great.</p>
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-secondary me-2">
                                                        <i class="bi bi-reply me-1"></i> Reply
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-link-45deg me-1"></i> Copy Link
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <img src="/avatars/alex-morgan.png" alt="Alex Morgan" class="rounded-circle me-3"
                                            width="40" height="40">
                                        <div class="flex-grow-1">
                                            <textarea class="form-control mb-2" placeholder="Write a reply..."
                                                rows="3"></textarea>
                                            <div class="text-end">
                                                <button class="btn btn-primary">Post Reply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Recent Discussions</h5>
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <h6 class="mb-1">Design System Components</h6>
                                            <small class="text-muted">Started by Jessica Chen • 3 days ago</small><br>
                                            <span class="badge bg-success me-2">Active</span>
                                            <small class="text-muted">3 replies</small>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <h6 class="mb-1">API Integration Issues</h6>
                                            <small class="text-muted">Started by David Kim • 5 days ago</small><br>
                                            <span class="badge bg-warning text-dark me-2">Needs Input</span>
                                            <small class="text-muted">7 replies</small>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <h6 class="mb-1">Project Timeline Updates</h6>
                                            <small class="text-muted">Started by Alex Morgan • 1 week ago</small><br>
                                            <span class="badge bg-info text-dark me-2">Resolved</span>
                                            <small class="text-muted">12 replies</small>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Team Activity</h5>
                                    <ul class="list-unstyled">
                                        <li class="d-flex align-items-start mb-3">
                                            <img src="/avatars/jessica-chen.png" alt="Jessica Chen"
                                                class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <p class="mb-1 small"><strong>Jessica Chen</strong> started a new discussion
                                                    <span class="text-primary">Design System Components</span>
                                                </p>
                                                <small class="text-muted">3 days ago</small>
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-start mb-3">
                                            <img src="/avatars/alex-morgan.png" alt="Alex Morgan"
                                                class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <p class="mb-1 small"><strong>Alex Morgan</strong> replied to <span
                                                        class="text-primary">API Integration Issues</span></p>
                                                <small class="text-muted">4 days ago</small>
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <img src="/avatars/ryan-park.png" alt="Ryan Park" class="rounded-circle me-2"
                                                width="32" height="32">
                                            <div>
                                                <p class="mb-1 small"><strong>Ryan Park</strong> closed discussion <span
                                                        class="text-primary">Project Timeline Updates</span></p>
                                                <small class="text-muted">1 week ago</small>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-analytics" role="tabpanel" aria-labelledby="nav-analytics-tab">...</div>
        </div>
    </div>

    <div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="height: 90vh; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <!-- Modern header with gradient and better spacing -->
                <div class="modal-header border-0 primary-gradient-effect">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title fs-5 fw-semibold text-white">Task Details</h5>
                        <button type="button"
                            class="btn btn-sm btn-icon rounded-circle bg-white bg-opacity-20 hover-bg-opacity-30 transition-all text-white"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x fs-4 text-danger fw-bold"></i>
                        </button>
                    </div>
                </div>

                <!-- Modern body with subtle background and better spacing -->
                <div class="modal-body p-4 pt-3" style="
                            overflow-y: auto;
                            background-color: #f8fafc;
                        ">
                    <div id="taskDetailContent">
                        <!-- <div class="d-flex justify-content-center align-items-center h-100">
                                                <div class="text-center">
                                                    <div class="spinner-border text-primary mb-3" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="text-muted">Loading task details...</p>
                                                </div>
                                            </div> -->
                        <div class="row g-4">
                            <!-- Task Title -->
                            <div class="col-12">
                                <label for="taskTitle" class="form-label fw-medium text-muted">TASK TITLE</label>
                                <input type="text" class="form-control border-2 py-2 px-3" id="taskTitle"
                                    value="Redesign landing page" style="border-color: #e2e8f0;">
                            </div>

                            <!-- Subtasks - Modern card style -->
                            <div class="col-12">
                                <label class="form-label fw-medium text-muted">SUBTASKS</label>
                                <div class="border rounded p-3"
                                    style="background-color: white; border-color: #e2e8f0 !important;">
                                    <div class="form-check mb-3 d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="subtask1" checked
                                            style="width: 18px; height: 18px;">
                                        <label class="form-check-label text-dark" for="subtask1">Create wireframes</label>
                                    </div>
                                    <div class="form-check mb-3 d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="subtask2"
                                            style="width: 18px; height: 18px;">
                                        <label class="form-check-label text-dark" for="subtask2">Design mockups</label>
                                    </div>
                                    <div class="form-check mb-3 d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="subtask3"
                                            style="width: 18px; height: 18px;">
                                        <label class="form-check-label text-dark" for="subtask3">Get feedback</label>
                                    </div>
                                    <button class="btn btn-outline-primary w-100 mt-1 py-2" style="border-color: #e2e8f0;">
                                        <i class="ti ti-plus me-2"></i>Add Subtask
                                    </button>
                                </div>
                            </div>

                            <!-- Assignees - Modern badge style -->
                            <div class="col-12">
                                <label class="form-label fw-medium text-muted mb-2">ASSIGNEES</label>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <!-- User Badge 1 -->
                                    <div class="d-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-xs"
                                        style="border: 1px solid #e2e8f0; height: 32px;">
                                        <img src="../assets/images/profile_picture/10491834.jpg" class="rounded-circle me-2"
                                            width="20" height="20" style="object-fit: cover;">
                                        <span class="text-dark fs-6 fw-medium">Sarah Johnson</span>
                                        <button class="btn btn-icon p-0 ms-2" style="width: 16px; height: 16px;">
                                            <i class="ti ti-x text-muted" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>

                                    <!-- User Badge 2 -->
                                    <div class="d-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-xs"
                                        style="border: 1px solid #e2e8f0; height: 32px;">
                                        <img src="../assets/images/profile_picture/10491834.jpg" class="rounded-circle me-2"
                                            width="20" height="20" style="object-fit: cover;">
                                        <span class="text-dark fs-6 fw-medium">David Kim</span>
                                        <button class="btn btn-icon p-0 ms-2" style="width: 16px; height: 16px;">
                                            <i class="ti ti-x text-muted" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>

                                    <!-- Add Button -->
                                    <button
                                        class="btn btn-sm btn-outline-primary d-flex align-items-center rounded-pill px-3"
                                        style="height: 32px;">
                                        <i class="ti ti-plus me-1" style="font-size: 0.75rem;"></i>
                                        <span style="font-size: 0.8125rem;">Add</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Comments - Modern chat bubbles -->
                            <div class="col-12">
                                <label class="form-label fw-medium text-muted">COMMENTS</label>
                                <div class="border rounded p-3"
                                    style="background-color: white; border-color: #e2e8f0 !important;">
                                    <div class="mb-3">
                                        <div class="d-flex mb-3">
                                            <img src="../assets/images/profile_picture/10491834.jpg"
                                                class="rounded-circle me-3" width="40" height="40">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-medium">Sarah Johnson</span>
                                                    <small class="text-muted">2 days ago</small>
                                                </div>
                                                <p class="mb-0 p-2 ps-3 rounded"
                                                    style="background-color: #f1f5f9; border-left: 3px solid #3b82f6;">
                                                    I've started working on this. Will update the progress soon.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <img src="../assets/images/profile_picture/10491834.jpg"
                                                class="rounded-circle me-3" width="40" height="40">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-medium">David Kim</span>
                                                    <small class="text-muted">Yesterday</small>
                                                </div>
                                                <p class="mb-0 p-2 ps-3 rounded"
                                                    style="background-color: #f1f5f9; border-left: 3px solid #10b981;">
                                                    Let me know if you need any help with this task.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-end-0 py-2"
                                            placeholder="Add a comment..." style="border-color: #e2e8f0;">
                                        <button class="btn btn-primary px-3">
                                            <i class="ti ti-send"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Attachments - Modern file cards -->
                            <div class="col-12">
                                <label class="form-label fw-medium text-muted">ATTACHMENTS</label>
                                <div class="border rounded p-3"
                                    style="background-color: white; border-color: #e2e8f0 !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded shadow-sm"
                                        style="background-color: #f8fafc;">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 rounded me-3 d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; background-color: rgba(59, 130, 246, 0.1);">
                                                <i class="ti ti-file text-primary fs-5"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-medium">requirements.pdf</p>
                                                <small class="text-muted">1.2 MB • Added 3 days ago</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-light">
                                            <i class="ti ti-download"></i>
                                        </button>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center p-3 rounded shadow-sm"
                                        style="background-color: #f8fafc;">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 rounded me-3 d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; background-color: rgba(16, 185, 129, 0.1);">
                                                <i class="ti ti-file-analytics text-success fs-5"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-medium">data.xlsx</p>
                                                <small class="text-muted">845 KB • Added yesterday</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-light">
                                            <i class="ti ti-download"></i>
                                        </button>
                                    </div>
                                    <button class="btn btn-outline-primary w-100 mt-2 py-2" style="border-color: #e2e8f0;">
                                        <i class="ti ti-paperclip me-2"></i>Add Attachment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modern footer with better buttons -->
                <div class="modal-footer border-0 px-4 pb-4 pt-3" style="background-color: #f8fafc;">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary px-4 py-2 shadow-sm">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-analytics" role="tabpanel" aria-labelledby="nav-analytics-tab">
            <div class="row g-4">
                <!-- Task Completion Trend Card -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Task Completion Trend</h5>
                            <p class="text-muted">Weekly task completion rate</p>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 320px;">
                                <canvas id="myDoughnutChart" width="600" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Productivity Card -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Team Productivity</h5>
                            <p class="text-muted">Tasks completed by team member</p>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 320px;">
                                <canvas id="userTaskChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const totalTasks = {{$totalTasks}};
const completedTasks = {{$completedTasks}};
const remainingTasks = totalTasks - completedTasks;

    const data = {
        labels: ['Completed', 'Remaining'],
        datasets: [{
            label: 'Task Completion',
            data: [completedTasks, remainingTasks],
            backgroundColor: ['rgb(16, 185, 129)', 'rgb(255, 68, 68)'],
            hoverOffset: 10
        }]
    };

    // ✅ Plugin to show text in center
    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw(chart) {
            const {
                width
            } = chart;
            const {
                height
            } = chart;
            const {
                ctx
            } = chart;

            ctx.restore();
            const fontSize = (height / 150).toFixed(2);
            ctx.font = `${fontSize}em sans-serif`;
            ctx.textBaseline = "middle";

            const text = `${totalTasks}`;
            const textX = Math.round((width - ctx.measureText(text).width) / 2);
            const textY = height / 2;

            ctx.fillStyle = '#111827'; // dark text
            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    };

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        },
        plugins: [centerTextPlugin] // 👈 Plugin added here
    };

    new Chart(
        document.getElementById('myDoughnutChart'),
        config
    );

        const ctx = document.getElementById('userTaskChart').getContext('2d');

    const userLabels = {!! json_encode(array_column($userWiseStats, 'employee_name')) !!};
    const totalTasksData = {!! json_encode(array_column($userWiseStats, 'total_tasks')) !!};
    const completedTasksData = {!! json_encode(array_column($userWiseStats, 'completed_tasks')) !!};

    const userChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: userLabels,
            datasets: [
                {
                    label: 'Total Tasks',
                    data: totalTasksData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Completed Tasks',
                    data: completedTasksData,
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Task Count'
                    }
                }
            }
        }
    });
</script>

@endsection

@section('customJs')

@endsection