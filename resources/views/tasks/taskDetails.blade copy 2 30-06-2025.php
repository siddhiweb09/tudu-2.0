@extends('layouts.innerFrame')

@section('main')
<div class="bg-white border-bottom">
    <div class="container-xxl p-4">
        <div class="row mb-4 m-0 justify-content-between">
            <div class="col-md-8">
                <!-- Header Section -->
                <h2 class="mb-2">{{$task['task_info']['title']}}</h2>
                @if($task['task_info']['flag'] == 'Main')
                <p class="lead mb-3 text-muted">{{$task['task_info']['task_id']}}</p>
                @else
                <p class="lead mb-3 text-muted">{{$task['task_info']['delegate_task_id']}}</p>
                @endif
                <!-- Status Badges -->
                <div class="d-flex gap-2">
                    <span class="badge badge-light-warning rounded-pill">
                        <i class="bi bi-arrow-repeat me-1"></i>{{$task['task_info']['final_status']}}
                    </span>
                    <span class="badge badge-light-danger rounded-pill">
                        <i class="ti ti-flame me-1"></i>{{$task['task_info']['priority']}}
                    </span>
                </div>
                <!-- Divider -->
            </div>
            <div class="col align-self-center">
                <!-- Deadline Section -->
                <h5 class="text-muted text-end mb-2"><i class="ti ti-calendar-event me-1"></i>Deadline:
                    {{$task['task_info']['due_date']}}
                </h5>
                <p class="text-muted text-end small mb-0"><i class="ti ti-alarm me-1"></i>Started:
                    {{$task['task_info']['created_at']}}
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
                                <h5 class="card-title">{{$task['clubbedInfo']['taskprogressPercentage']}} %</h5>
                            </div>
                            <div class="col-auto p-0">
                                <p class="card-text text-muted"><small>{{$task['clubbedInfo']['taskListCount']}} tasks
                                        ({{$task['clubbedInfo']['taskInCompletedListCount']}}
                                        completed)</small></p>
                            </div>
                        </div>
                        <!-- Progress bar -->
                        <div class="progress mt-2 rounded-pill" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $task['clubbedInfo']['taskprogressPercentage'] }}%;"
                                aria-valuenow="{{ $task['clubbedInfo']['taskprogressPercentage'] }}" aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Assigned by</h6>
                        <h5 class="card-title">{{$task['task_info']['assign_by']}}</h5>
                        <p class="card-text text-muted"><small>Final status:
                                {{$task['task_info']['final_status']}}</small></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Team</h6>
                        <div class="aspect-square-container mb-2">
                            @foreach ($task['clubbedInfo']['teamMembers'] as $teamMember)
                            @if(!empty($teamMember['profile_picture']))
                            <div class="aspect-square-box">
                                <img class="aspect-square" alt="{{ $teamMember['employee_name'] }}"
                                    src="../assets/images/profile_picture/{{$teamMember['profile_picture']}}">
                            </div>
                            @else
                            <div class="aspect-square-box">
                                <img class="aspect-square" alt="{{ $teamMember['employee_name'] }}"
                                    src="../assets/images/profile_picture/user.png">
                            </div>
                            @endif
                            @endforeach
                        </div>
                        <p class="card-text text-muted"><small>{{$task['clubbedInfo']['teamMembersCount']}} team
                                members</small></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Activity</h6>
                        <h5 class="card-title">{{$task['clubbedInfo']['totalActivity']}}</h5>
                        <p class="card-text text-muted"><small>Last updated:
                                {{$task['clubbedInfo']['lastActivity']}}</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="divider"></div>
</div>

<div class="container-xxl p-4">
    <div class="row justify-content-between">
        <div class="col-auto">
            <nav>
                <div class="nav nav-tabs task-details-tab" id="nav-tab" role="tablist">
                    <button class="nav-link" id="nav-overview-tab" data-bs-toggle="tab" data-bs-target="#nav-overview"
                        type="button" role="tab" aria-controls="nav-overview" aria-selected="true"><i
                            class="ti ti-notes me-1"></i> Overview</button>
                    <button class="nav-link active" id="nav-tasks-tab" data-bs-toggle="tab" data-bs-target="#nav-tasks"
                        type="button" role="tab" aria-controls="nav-tasks" aria-selected="false"><i
                            class="ti ti-checkbox me-1"></i>
                        Tasks</button>
                    <button class="nav-link" id="nav-team-tab" data-bs-toggle="tab" data-bs-target="#nav-team"
                        type="button" role="tab" aria-controls="nav-team" aria-selected="false"><i
                            class="ti ti-users-group me-1"></i>
                        Team</button>
                    <button class="nav-link" id="nav-discussion-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-discussion" type="button" role="tab" aria-controls="nav-discussion"
                        aria-selected="false"><i class="ti ti-message me-1"></i> Discussion</button>
                    <button class="nav-link" id="nav-analytics-tab" data-bs-toggle="tab" data-bs-target="#nav-analytics"
                        type="button" role="tab" aria-controls="nav-analytics" aria-selected="false"><i
                            class="ti ti-chart-bar me-1"></i> Analytics</button>
                </div>
            </nav>
        </div>
        @php
        $currentUser = session('employee_code') . '*' . session('employee_name');
        $isAuthorized = $currentUser == $task['task_info']['assign_to']
        || $currentUser == $task['task_info']['assign_by'];

        if ($task['task_info']['flag'] == 'Main') {
        $UpdateTaskId = $task['task_info']['task_id'];
        } else {
        $UpdateTaskId = $task['task_info']['delegate_task_id'];
        }
        @endphp

        <div class="col-auto">
            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                <button type="button"
                    class="btn btn-sm btn-inverse-primary markCompletedBtn"
                    data-task-id="{{ $UpdateTaskId }}"
                    data-assign-by="{{ $task['task_info']['assign_by'] }}"
                    data-current-user="{{ $currentUser }}">
                    Mark as Completed
                </button>
                <a href="/delegate-tasks/{{ $task['task_info']['task_id'] }}"
                    class="btn btn-sm btn-inverse-primary {{ !$isAuthorized ? 'disabled pointer-events-none opacity-50' : '' }}">
                    Delegate Tasks
                </a>
            </div>
        </div>
    </div>

    <div class="tab-content mt-4" id="nav-tabContent">
        <div class="tab-pane fade" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
            <div class="row m-0">
                <div class="col-md-6 col-lg-8 ps-0">
                    <div class="card bg-white">
                        <div class="card-body">
                            <h4 class="card-title mb-3 text-decoration-underline">Task Description</h4>
                            {!! $task['task_info']['description'] !!}
                        </div>
                    </div>
                    <div class="card bg-white mt-4">
                        <div class="card-body">
                            <h4 class="card-title mb-3 text-decoration-underline">Timeline</h4>
                            <div class="timeline p-4 block mb-4">
                                @foreach ($task['clubbedInfo']['activities'] as $activity)
                                @if(!empty($activity['log_description']))
                                <div class="tl-item">
                                    <div class="tl-dot b-primary"></div>
                                    <div class="tl-content">
                                        <h6 class="">{{$activity['log_description']}}</h6>
                                        <div class="tl-date text-muted mt-1">{{$activity['created_at']}} |
                                            {{$activity['added_by']}}
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
                                    $reminders = json_decode($task['task_info']['reminders'], true);
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
                                    <p class="card-title fw-medium text-muted">{{$task['task_info']['frequency']}}</p>
                                </div>
                            </div>
                            <div class="row m-0 mt-4 justify-content-between">
                                @php
                                $frequencies = json_decode($task['task_info']['frequency_duration'], true);
                                $badgeColors = [
                                'bg-primary-light text-primary',
                                'bg-success-light text-success',
                                'bg-warning-light text-warning',
                                'bg-danger-light text-danger',
                                'bg-secondary-light
                                text-secondary',
                                'bg-dark-light text-dark',
                                'bg-dark text-light'
                                ];
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
                                        class="ti ti-star-filled{{ $i <= $task['task_info']['ratings'] ? ' text-warning' : ' text-muted' }} me-1">
                                        </i>
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
                                    @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                                    @php
                                    $hasLinks = false;
                                    foreach ($taskIdData['documents'] as $docs) {
                                    if (!empty($docs['file_name'])) {
                                    $hasLinks = true;
                                    break;
                                    }
                                    }
                                    @endphp

                                    @if($hasLinks)
                                    <p class="card-title mt-3 mb-2 fw-medium">{{ $taskId }}</p>
                                    <div class="border p-3">
                                        @foreach ($taskIdData['documents'] as $docs)
                                        @if(!empty($docs['file_name']))
                                        <div
                                            class="d-flex justify-content-between align-items-center bg-light px-1 py-2 rounded document-box">
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-file-text me-1 text-secondary"></i>
                                                <span class="small">{{ $docs['file_name'] }}</span>
                                            </div>
                                            <a href="../assets/uploads/{{ $docs['file_name'] }}"
                                                class="ti ti-download border-0 bg-transparent ps-3" download></a>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
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
                                    @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                                    @php
                                    $hasLinks = false;
                                    foreach ($taskIdData['links'] as $docs) {
                                    if (!empty($docs['file_name'])) {
                                    $hasLinks = true;
                                    break;
                                    }
                                    }
                                    @endphp

                                    @if($hasLinks)
                                    <p class="card-title mt-3 mb-2 fw-medium">{{ $taskId }}</p>
                                    <div class="border p-3">
                                        @foreach ($taskIdData['links'] as $docs)
                                        @if(!empty($docs['file_name']))
                                        <div class="d-flex align-items-center bg-light px-1 py-2 rounded document-box">
                                            <i class="ti ti-link me-2 text-secondary"></i>
                                            <a href="{{ $docs['file_name'] }}"
                                                class="small text-break text-decoration-underline">{{ $docs['file_name'] }}</a>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
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
                                    @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                                    @php
                                    $hasVoiceNotes = false;
                                    foreach ($taskIdData['voice_notes'] as $docs) {
                                    if (!empty($docs['file_name'])) {
                                    $hasVoiceNotes = true;
                                    break;
                                    }
                                    }
                                    @endphp

                                    @if($hasVoiceNotes)
                                    <p class="card-title mt-3 mb-2 fw-medium">{{ $taskId }}</p>
                                    <div class="border p-3">
                                        @foreach ($taskIdData['voice_notes'] as $docs)
                                        @if(!empty($docs['file_name']))
                                        <div
                                            class="d-flex justify-content-between align-items-center bg-light px-1 py-2 rounded document-box">
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-microphone me-1 text-secondary"></i>
                                                <span class="small">{{ $docs['file_name'] }}</span>
                                            </div>
                                            <a href="../assets/uploads/{{ $docs['file_name'] }}"
                                                class="ti ti-circle-caret-right border-0 bg-transparent ps-3"
                                                download></a>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade active show" id="nav-tasks" role="tabpanel" aria-labelledby="nav-tasks-tab">
            <div class="row row-cols-2 row-cols-md-4 g-4">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title m-0 py-2"><i class="ti ti-checklist"></i> Pending</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                            @if ($taskIdData['each_task_info']['status'] === "Pending")
                            <div class="card mb-3 task-card" data-task-id="{{ $taskId }}" style="cursor: pointer;">
                                <div class="card-body">
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto fw-medium p-0 m-0">
                                            {{ $taskId == $task['task_info']['task_id'] ? 'Main Task' : 'Delegated Task' }}
                                        </p>
                                        <div class="col-auto p-0">
                                            @if($taskIdData['each_task_info']['priority'] === "high")
                                            <span class="badge badge-light-danger rounded">
                                                <i
                                                    class="ti ti-flame me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @elseif($taskIdData['each_task_info']['priority'] === "medium")
                                            <span class="badge badge-light-warning rounded">
                                                <i
                                                    class="ti ti-sun-high me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @else
                                            <span class="badge badge-light-success rounded">
                                                <i
                                                    class="ti ti-leaf me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="card-title">
                                        {{ $taskIdData['each_task_info']['title'] ?? 'Untitled Task' }}
                                    </h5>

                                    {{-- Progress Info --}}
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto m-0 p-0">Progress</p>
                                        <div class="col-auto">{{ $taskIdData['task_progress'] }}%</div>
                                    </div>

                                    <div class="progress mt-1 rounded-pill" style="height: 10px;">
                                        <div class="progress-bar bg-dark" role="progressbar"
                                            style="width: {{ $taskIdData['task_progress'] }}%;"
                                            aria-valuenow="{{ $taskIdData['task_progress'] }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>

                                    {{-- Optional Description or Action --}}
                                    <div class="row mt-2 m-0">
                                        <p class="text-muted text-end small w-auto mb-0 ps-0">
                                            <i class="ti ti-calendar-event me-1"></i>
                                            @php
                                            $createdAt = \Carbon\Carbon::parse($taskIdData['each_task_info']['created_at'] ?? now());
                                            $formattedDate = $createdAt->isToday() ? 'Today' : $createdAt->format('F j');
                                            @endphp
                                            {{ $formattedDate }}
                                        </p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-paperclip me-1"></i>{{ $taskIdData['attachmentsCount'] }}
                                        </p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-messages me-1"></i>{{ $taskIdData['commentsCount'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-checkbox me-1"></i>{{ $taskIdData['completed_task'] }}/{{ $taskIdData['total_task'] }}
                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="aspect-square-container p-0 w-auto">
                                            @php
                                            $assign_by = $taskIdData['each_task_info']['assign_by'];
                                            @endphp

                                            @foreach ($taskIdData['team_members'] as $member)
                                            @php
                                            $employee = $member['employee_code'] . '*' . $member['employee_name'];
                                            @endphp
                                            @if($assign_by !== $employee)
                                            @if(!empty($member['profile_picture']))
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member['employee_name'] }}"
                                                    src="../assets/images/profile_picture/{{$member['profile_picture']}}">
                                            </div>
                                            @else
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member['employee_name'] }}"
                                                    src="../assets/images/profile_picture/user.png">
                                            </div>
                                            @endif
                                            @endif
                                            @endforeach
                                        </div>
                                        <div class="btn-group dropend">
                                            <button type="button" class="w-auto bg-transparent border-0"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow rounded-3 p-1"
                                                aria-labelledby="taskDropdownBtn">
                                                <!-- Edit Task -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#" data-task-id="{{ $taskId }}" data-bs-toggle="modal"
                                                        data-bs-target="#taskDetailModal-{{ $taskId }}">
                                                        <i class="ti ti-edit fs-5"></i> Edit Task
                                                    </a>
                                                </li>

                                                <!-- Mark as Completed -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-circle-check fs-5"></i> Mark as
                                                        Completed
                                                    </a>
                                                </li>

                                                <!-- Change Due Date -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-clock fs-5"></i> Change Due Date
                                                    </a>
                                                </li>

                                                <!-- Change Priority -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-alert-circle fs-5"></i> Change Priority
                                                    </a>
                                                </li>

                                                <!-- Delete Task -->
                                                <li class="border-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2 text-danger"
                                                        href="#">
                                                        <i class="ti ti-trash fs-5"></i> Delete Task
                                                    </a>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('modalEditTask')
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
                            @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                            @if ($taskIdData['each_task_info']['status'] === "In Progress")
                            <div class="card mb-3 task-card" data-task-id="{{ $taskId }}" style="cursor: pointer;">
                                <div class="card-body">
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto fw-medium p-0 m-0">
                                            {{ $taskId == $task['task_info']['task_id'] ? 'Main Task' : 'Delegated Task' }}
                                        </p>
                                        <div class="col-auto p-0">
                                            @if($taskIdData['each_task_info']['priority'] === "high")
                                            <span class="badge badge-light-danger rounded">
                                                <i
                                                    class="ti ti-flame me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @elseif($taskIdData['each_task_info']['priority'] === "medium")
                                            <span class="badge badge-light-warning rounded">
                                                <i
                                                    class="ti ti-sun-high me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @else
                                            <span class="badge badge-light-success rounded">
                                                <i
                                                    class="ti ti-leaf me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="card-title">
                                        {{ $taskIdData['each_task_info']['title'] ?? 'Untitled Task' }}
                                    </h5>

                                    {{-- Progress Info --}}
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto m-0 p-0">Progress</p>
                                        <div class="col-auto">{{ $taskIdData['task_progress'] }}%</div>
                                    </div>

                                    <div class="progress mt-1 rounded-pill" style="height: 10px;">
                                        <div class="progress-bar bg-dark" role="progressbar"
                                            style="width: {{ $taskIdData['task_progress'] }}%;"
                                            aria-valuenow="{{ $taskIdData['task_progress'] }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>

                                    {{-- Optional Description or Action --}}
                                    <div class="row mt-2 m-0">
                                        <p class="text-muted text-end small w-auto mb-0 ps-0">
                                            <i class="ti ti-calendar-event me-1"></i>
                                            @php
                                            $createdAt = \Carbon\Carbon::parse($taskIdData['each_task_info']['created_at'] ?? now());
                                            $formattedDate = $createdAt->isToday() ? 'Today' : $createdAt->format('F j');
                                            @endphp
                                            {{ $formattedDate }}
                                        </p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-paperclip me-1"></i>{{ $taskIdData['attachmentsCount'] }}
                                        </p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-messages me-1"></i>{{ $taskIdData['commentsCount'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-checkbox me-1"></i>{{ $taskIdData['completed_task'] }}/{{ $taskIdData['total_task'] }}
                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="aspect-square-container p-0 w-auto">
                                            @php
                                            $assign_by = $taskIdData['each_task_info']['assign_by'];
                                            @endphp

                                            @foreach ($taskIdData['team_members'] as $member)
                                            @php
                                            $employee = $member['employee_code'] . '*' . $member['employee_name'];
                                            @endphp
                                            @if($assign_by !== $employee)
                                            @if(!empty($member['profile_picture']))
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member['employee_name'] }}"
                                                    src="../assets/images/profile_picture/{{$member['profile_picture']}}">
                                            </div>
                                            @else
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member['employee_name'] }}"
                                                    src="../assets/images/profile_picture/user.png">
                                            </div>
                                            @endif
                                            @endif
                                            @endforeach
                                        </div>
                                        <div class="btn-group dropend">
                                            <button type="button" class="w-auto bg-transparent border-0"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow rounded-3 p-1"
                                                aria-labelledby="taskDropdownBtn">
                                                <!-- Edit Task -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#" data-task-id="{{ $taskId }}" data-bs-toggle="modal"
                                                        data-bs-target="#taskDetailModal-{{ $taskId }}">
                                                        <i class="ti ti-edit fs-5"></i> Edit Task
                                                    </a>
                                                </li>

                                                <!-- Mark as Completed -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-circle-check fs-5"></i> Mark as
                                                        Completed
                                                    </a>
                                                </li>

                                                <!-- Change Due Date -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-clock fs-5"></i> Change Due Date
                                                    </a>
                                                </li>

                                                <!-- Change Priority -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-alert-circle fs-5"></i> Change Priority
                                                    </a>
                                                </li>

                                                <!-- Delete Task -->
                                                <li class="border-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2 text-danger"
                                                        href="#">
                                                        <i class="ti ti-trash fs-5"></i> Delete Task
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('modalEditTask')
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
                            @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                            @if ($taskIdData['each_task_info']['status'] === "In Review")
                            <div class="card mb-3 task-card" data-task-id="{{ $taskId }}" style="cursor: pointer;">
                                <div class="card-body">
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto fw-medium p-0 m-0">
                                            {{ $taskId == $task['task_info']['task_id'] ? 'Main Task' : 'Delegated Task' }}
                                        </p>
                                        <div class="col-auto p-0">
                                            @if($taskIdData['each_task_info']['priority'] === "high")
                                            <span class="badge badge-light-danger rounded">
                                                <i
                                                    class="ti ti-flame me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @elseif($taskIdData['each_task_info']['priority'] === "medium")
                                            <span class="badge badge-light-warning rounded">
                                                <i
                                                    class="ti ti-sun-high me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @else
                                            <span class="badge badge-light-success rounded">
                                                <i
                                                    class="ti ti-leaf me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="card-title">
                                        {{ $taskIdData['each_task_info']['title'] ?? 'Untitled Task' }}
                                    </h5>

                                    {{-- Progress Info --}}
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto m-0 p-0">Progress</p>
                                        <div class="col-auto">{{ $taskIdData['task_progress'] }}%</div>
                                    </div>

                                    <div class="progress mt-1 rounded-pill" style="height: 10px;">
                                        <div class="progress-bar bg-dark" role="progressbar"
                                            style="width: {{ $taskIdData['task_progress'] }}%;"
                                            aria-valuenow="{{ $taskIdData['task_progress'] }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>

                                    {{-- Optional Description or Action --}}
                                    <div class="row mt-2 m-0">
                                        <p class="text-muted text-end small w-auto mb-0 ps-0">
                                            <i class="ti ti-calendar-event me-1"></i>
                                            @php
                                            $createdAt = \Carbon\Carbon::parse($taskIdData['each_task_info']['created_at'] ?? now());
                                            $formattedDate = $createdAt->isToday() ? 'Today' : $createdAt->format('F j');
                                            @endphp
                                            {{ $formattedDate }}
                                        </p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-paperclip me-1"></i>{{ $taskIdData['attachmentsCount'] }}
                                        </p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-messages me-1"></i>{{ $taskIdData['commentsCount'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-checkbox me-1"></i>{{ $taskIdData['completed_task'] }}/{{ $taskIdData['total_task'] }}
                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="aspect-square-container p-0 w-auto">
                                            @php
                                            $assign_by = $taskIdData['each_task_info']['assign_by'];
                                            @endphp

                                            @foreach ($taskIdData['team_members'] as $member)
                                            @php
                                            $employee = $member['employee_code'] . '*' . $member['employee_name'];
                                            @endphp
                                            @if($assign_by !== $employee)
                                            @if(!empty($member['profile_picture']))
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member['employee_name'] }}"
                                                    src="../assets/images/profile_picture/{{$member['profile_picture']}}">
                                            </div>
                                            @else
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member['employee_name'] }}"
                                                    src="../assets/images/profile_picture/user.png">
                                            </div>
                                            @endif
                                            @endif
                                            @endforeach
                                        </div>
                                        <div class="btn-group dropend">
                                            <button type="button" class="w-auto bg-transparent border-0"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow rounded-3 p-1"
                                                aria-labelledby="taskDropdownBtn">
                                                <!-- Edit Task -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#" data-task-id="{{ $taskId }}" data-bs-toggle="modal"
                                                        data-bs-target="#taskDetailModal-{{ $taskId }}">
                                                        <i class="ti ti-edit fs-5"></i> Edit Task
                                                    </a>
                                                </li>

                                                <!-- Mark as Completed -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-circle-check fs-5"></i> Mark as
                                                        Completed
                                                    </a>
                                                </li>

                                                <!-- Change Due Date -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-clock fs-5"></i> Change Due Date
                                                    </a>
                                                </li>

                                                <!-- Change Priority -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-alert-circle fs-5"></i> Change Priority
                                                    </a>
                                                </li>

                                                <!-- Delete Task -->
                                                <li class="border-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2 text-danger"
                                                        href="#">
                                                        <i class="ti ti-trash fs-5"></i> Delete Task
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('modalEditTask')
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
                            @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                            @if ($taskIdData['each_task_info']['status'] === "Completed")
                            <div class="card mb-3 task-card" data-task-id="{{ $taskId }}" style="cursor: pointer;">
                                <div class="card-body">
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto fw-medium p-0 m-0">
                                            {{ $taskId == $task['task_info']['task_id'] ? 'Main Task' : 'Delegated Task' }}
                                        </p>
                                        <div class="col-auto p-0">
                                            @if($taskIdData['each_task_info']['priority'] === "high")
                                            <span class="badge badge-light-danger rounded">
                                                <i
                                                    class="ti ti-flame me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @elseif($taskIdData['each_task_info']['priority'] === "medium")
                                            <span class="badge badge-light-warning rounded">
                                                <i
                                                    class="ti ti-sun-high me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @else
                                            <span class="badge badge-light-success rounded">
                                                <i
                                                    class="ti ti-leaf me-1"></i>{{ $taskIdData['each_task_info']['priority'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="card-title">
                                        {{ $taskIdData['each_task_info']['title'] ?? 'Untitled Task' }}
                                    </h5>

                                    {{-- Progress Info --}}
                                    <div class="row m-0 justify-content-between">
                                        <p class="text-muted w-auto m-0 p-0">Progress</p>
                                        <div class="col-auto">{{ $taskIdData['task_progress'] }}%</div>
                                    </div>

                                    <div class="progress mt-1 rounded-pill" style="height: 10px;">
                                        <div class="progress-bar bg-dark" role="progressbar"
                                            style="width: {{ $taskIdData['task_progress'] }}%;"
                                            aria-valuenow="{{ $taskIdData['task_progress'] }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>

                                    {{-- Optional Description or Action --}}
                                    <div class="row mt-2 m-0">
                                        <p class="text-muted text-end small w-auto mb-0 ps-0">
                                            <i class="ti ti-calendar-event me-1"></i>
                                            @php
                                            $createdAt = \Carbon\Carbon::parse($taskIdData['each_task_info']['created_at'] ?? now());
                                            $formattedDate = $createdAt->isToday() ? 'Today' : $createdAt->format('F j');
                                            @endphp
                                            {{ $formattedDate }}
                                        </p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-paperclip me-1"></i>{{ $taskIdData['attachmentsCount'] }}
                                        </p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-messages me-1"></i>{{ $taskIdData['commentsCount'] }}</p>
                                        <p class="text-muted text-end small w-auto mb-0 ps-0"><i
                                                class="ti ti-checkbox me-1"></i>{{ $taskIdData['completed_task'] }}/{{ $taskIdData['total_task'] }}
                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="aspect-square-container p-0 w-auto">
                                            @php
                                            $assign_by = $taskIdData['each_task_info']['assign_by'];
                                            @endphp

                                            @foreach ($taskIdData['team_members'] as $member)
                                            @php
                                            $employee = $member['employee_code'] . '*' . $member['employee_name'];
                                            @endphp
                                            @if($assign_by !== $employee)
                                            @if(!empty($member['profile_picture']))
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member['employee_name'] }}"
                                                    src="../assets/images/profile_picture/{{$member['profile_picture']}}">
                                            </div>
                                            @else
                                            <div class="aspect-square-box">
                                                <img class="aspect-square" alt="{{ $member['employee_name'] }}"
                                                    src="../assets/images/profile_picture/user.png">
                                            </div>
                                            @endif
                                            @endif
                                            @endforeach
                                        </div>
                                        <div class="btn-group dropend">
                                            <button type="button" class="w-auto bg-transparent border-0"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow rounded-3 p-1"
                                                aria-labelledby="taskDropdownBtn">
                                                <!-- Edit Task -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#" data-task-id="{{ $taskId }}" data-bs-toggle="modal"
                                                        data-bs-target="#taskDetailModal-{{ $taskId }}">
                                                        <i class="ti ti-edit fs-5"></i> Edit Task
                                                    </a>
                                                </li>

                                                <!-- Mark as Completed -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-circle-check fs-5"></i> Mark as
                                                        Completed
                                                    </a>
                                                </li>

                                                <!-- Change Due Date -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-clock fs-5"></i> Change Due Date
                                                    </a>
                                                </li>

                                                <!-- Change Priority -->
                                                <li class="border-0 p-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2"
                                                        href="#">
                                                        <i class="ti ti-alert-circle fs-5"></i> Change Priority
                                                    </a>
                                                </li>

                                                <!-- Delete Task -->
                                                <li class="border-0">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2 text-danger"
                                                        href="#">
                                                        <i class="ti ti-trash fs-5"></i> Delete Task
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('modalEditTask')
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
                            @foreach ($task['groupedByUser'] as $taskId => $userData)
                            <tr>
                                <td>{{$userData['employee_code']}}*{{$userData['employee_name']}}</td>
                                <td>
                                    @php
                                    $employee = $userData['employee_code'] . '*' . $userData['employee_name'];
                                    @endphp
                                    @foreach($userData['tasks'] as $usertask)
                                    @php
                                    $assign_by = $usertask['assign_by'];
                                    @endphp
                                    @if($assign_by !== $employee)
                                    <a href="/task/{{$usertask['task_id']}}">
                                        <span class="badge badge-light-primary rounded">
                                            {{$usertask['task_title']}}
                                        </span>
                                    </a>
                                    @else
                                    <a href="/task/{{$usertask['task_id']}}">
                                        <span class="badge badge-light-danger rounded">
                                            {{$usertask['task_title']}}
                                        </span>
                                    </a>
                                    @endif
                                    @endforeach
                                </td>
                                <td><b>{{ $userData['total_task'] }}</b> ({{ $userData['completed_task'] }}/
                                    completed)</td>
                                <td>{{ $userData['progress'] }}% </td>
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
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3 mb-4">
                <div>
                    <h2 class="h5 fw-bold mb-1">Discussions</h2>
                    <p class="text-muted mb-0">Project discussions and comments</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        + New Discussion
                    </button>
                    <ul class="dropdown-menu" id="newDiscussionDropdown">
                        @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                        @foreach ($taskIdData['comments'] as $comment)
                        <li>
                            <a class="dropdown-item new-discussion-link" href="javascript:void(0);"
                                data-task-id="{{ $comment['task_id'] ?? $taskId }}"
                                data-task-title="{{ $comment['title'] ?? 'Untitled' }}"
                                data-added-by="{{ $comment['added_by'] ?? 'Unknown' }}"
                                data-created-at="{{ isset($comment['created_at']) ? \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() : 'Recently' }}">
                                {{ $comment['title'] ?? 'Untitled Discussion' }}
                            </a>
                        </li>
                        @endforeach
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="d-flex align-items-start comment-box flex-column flex-lg-row">
                <!-- Main Content Column -->
                <div class="tab-content col-lg-8 pe-lg-3" id="v-pills-tabContent">
                    @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                    @if(!empty($taskIdData['comments']))
                    @php
                    $firstComment = collect($taskIdData['comments'])->first();
                    $tabId = 'tab-comment-' . $taskId;
                    @endphp

                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $tabId }}" role="tabpanel"
                        aria-labelledby="{{ $tabId }}-tab" tabindex="0">
                        <div class="card">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <h4 class="card-title mb-3 text-decoration-underline">
                                        {{ $firstComment['title'] ?? $taskIdData['each_task_info']['title'] ?? 'Discussion' }}
                                    </h4>
                                    <small class="text-muted">
                                        Started by {{ $firstComment['added_by'] ?? 'Unknown' }} •
                                        {{ isset($firstComment['created_at']) ? \Carbon\Carbon::parse($firstComment['created_at'])->diffForHumans() : 'Recently' }}
                                    </small>
                                    <hr>
                                </div>

                                <div class="flex-grow-1 overflow-auto" style="max-height: 400px;">
                                    @foreach ($taskIdData['comments'] as $comment)
                                    <div class="d-flex mb-4">
                                        <img src="{{ asset('assets/images/profile_picture/' . ($comment['added_by_picture'] ?? 'user.png')) }}"
                                            alt="{{ $comment['added_by'] ?? 'User' }}" class="rounded-circle me-3"
                                            width="40" height="40">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <strong>{{ $comment['added_by'] ?? 'Unknown' }}</strong>
                                                <span class="mx-2 text-muted">•</span>
                                                <small class="text-muted">
                                                    {{ isset($comment['created_at']) ? \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() : 'Recently' }}
                                                </small>
                                            </div>
                                            <p>{{ $comment['comment'] ?? 'No comment text' }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="d-flex mt-3">
                                    <img src="{{ asset('assets/images/profile_picture/' . ($activeUser->profile_picture ?? 'user.png')) }}"
                                        alt="Your Profile" class="rounded-circle me-3" width="40" height="40">
                                    <form class="flex-grow-1 comment-form" id="comment-form-{{ $taskId }}"
                                        data-task-id="{{ $taskId }}">
                                        @csrf
                                        <textarea class="form-control mb-2" name="comment"
                                            placeholder="Write a reply..." rows="3" required></textarea>
                                        <input type="hidden" name="task_id" value="{{ $taskId }}" />
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Post Reply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <!-- Sidebar Column -->
                <div class="nav flex-column nav-pills col-lg-4 ps-lg-3 mt-4 mt-lg-0" id="v-pills-tab" role="tablist">
                    <div class="card p-3">
                        <h4 class="card-title mb-3 text-decoration-underline">Recent Discussions</h4>
                        @foreach ($task['groupedByTask'] as $taskId => $taskIdData)
                        @if(!empty($taskIdData['comments']))
                        @php
                        $firstComment = collect($taskIdData['comments'])->first();
                        $tabId = 'tab-comment-' . $taskId;
                        $commentCount = count($taskIdData['comments']);
                        @endphp

                        <button class="nav-link bg-light text-start text-muted mb-2 {{ $loop->first ? 'active' : '' }}"
                            id="{{ $tabId }}-tab" data-bs-toggle="pill" data-bs-target="#{{ $tabId }}" type="button"
                            role="tab" aria-controls="{{ $tabId }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            <h6 class="card-title mb-2 text-decoration-underline text-dark">
                                {{ $firstComment['title'] ?? $taskIdData['each_task_info']['title'] ?? 'Untitled Discussion' }}
                            </h6>
                            <div class="d-flex mb-2 small">
                                <p>Started by {{ $firstComment['added_by'] ?? 'Unknown' }}</p>
                                <p class="mx-2">•</p>
                                <p>{{ $firstComment->created_at->diffForHumans() ?? 'Now' }}</p>
                            </div>
                            <div class="d-flex small">
                                <p>{{ $commentCount }} {{ Str::plural('reply', $commentCount) }}</p>
                            </div>
                        </button>
                        @endif
                        @endforeach
                    </div>
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
                            <p class="text-muted">task completion rate</p>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="height: 320px;">
                                <canvas id="myDoughnutChart" width="600" height="300" style="padding: 20px;"></canvas>
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
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="height: 320px;">
                                <canvas id="userTaskChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hidden inputs for chart data -->
            <input type="hidden" id="totalTasks" value="{{ $task['clubbedInfo']['taskListCount'] }}" />
            <input type="hidden" id="completedTasks" value="{{ $task['clubbedInfo']['taskInCompletedListCount'] }}" />
            <input type="hidden" id="inProcessTasks" value="{{ $task['clubbedInfo']['taskInProcessListCount'] }}" />
            <input type="hidden" id="userLabels"
                value='@json(array_column($task[' clubbedInfo']['teamMembers'] ?? [], 'employee_name' ))' />
            <input type="hidden" id="totalTasksData"
                value='@json(array_column($task[' groupedByUser'] ?? [], 'total_task' ))' />
            <input type="hidden" id="completedTasksData"
                value='@json(array_column($task[' groupedByUser'] ?? [], 'completed_task' ))' />
        </div>
    </div>
</div>


@endsection

@section('customJs')
<script>
    const totalTasks = document.getElementById('totalTasks').value;
    const completedTasks = document.getElementById('completedTasks').value;
    const inProcessTasks = document.getElementById('inProcessTasks').value;
    const remainingTasks = totalTasks - completedTasks - inProcessTasks;

    const data = {
        labels: ['Completed', 'Remaining', 'Processing'],
        datasets: [{
            label: 'Task Completion',
            data: [completedTasks, remainingTasks, inProcessTasks],
            backgroundColor: ['#01796f', '#f5385a', '#ff9f1c'],
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

    const userLabels = JSON.parse(document.getElementById('userLabels').value);
    const totalTasksData = JSON.parse(document.getElementById('totalTasksData').value);
    const completedTasksData = JSON.parse(document.getElementById('completedTasksData').value);

    const userChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: userLabels,
            datasets: [{
                    label: 'Total Tasks',
                    data: totalTasksData,
                    backgroundColor: '#4361ee',
                    borderColor: '#4361ee',
                    borderWidth: 1
                },
                {
                    label: 'Completed Tasks',
                    data: completedTasksData,
                    backgroundColor: '#4cc9f0',
                    borderColor: '#4cc9f0',
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

    $(document).ready(function() {

        $('.markCompletedBtn').on('click', function() {
            const taskId = $(this).data('task-id');
            const assignBy = $(this).data('assign-by');
            const currentUser = $(this).data('current-user');

            if (!confirm("Are you sure you want to mark this task as Completed?")) {
                return;
            }

            // ✅ If main task and assign_by === current user, check delegated status first
            if ( assignBy === currentUser && taskId.startsWith('TASK-')) {
                $.ajax({
                    url: "{{ route('checkDelegatedFinalStatus') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        task_id: taskId
                    },
                    success: function(res) {
                        if (res.all_completed) {
                            updateTaskStatus(taskId);
                        } else {
                            alert("All delegated tasks must be completed first.");
                        }
                    },
                    error: function() {
                        alert("Error checking delegated task status.");
                    }
                });
            } else {
                // ✅ Directly update if not the main task creator
                updateTaskStatus(taskId);
            }
        });

        function updateTaskStatus(taskId) {
            $.ajax({
                url: "{{ route('updateTaskStatus') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    task_id: taskId,
                    status: "Completed"
                },
                success: function(response) {
                    if (response.success) {
                        alert("Task marked as Completed.");
                        location.reload();
                    } else {
                        alert("Update failed: " + (response.error || "Unknown error"));
                    }
                },
                error: function() {
                    alert("Something went wrong while updating status.");
                }
            });
        }

        $(".new-discussion-link").on("click", function() {
            const taskId = $(this).data("task-id");
            const taskTitle = $(this).data("task-title");
            const addedBy = $(this).data("added-by");
            const createdAt = $(this).data("created-at");

            const tabId = `tab-comment-${taskId}`;
            const navTabSelector = `#${tabId}-tab`;
            const contentTabSelector = `#${tabId}`;

            // Deactivate currently active tab and pane
            $(".comment-box .nav-link.active").removeClass("active");
            $(".comment-box .tab-pane.active.show").removeClass("active show");

            if ($(navTabSelector).length) {
                // Tab already exists → activate using Bootstrap's API
                const tabTrigger = new bootstrap.Tab($(navTabSelector)[0]);
                tabTrigger.show();
            } else {
                // Create new nav tab
                const navTab = `
                                        <button class="nav-link bg-light text-start text-muted mb-2" id="${tabId}-tab"
                                            data-bs-toggle="pill" data-bs-target="#${tabId}" type="button" role="tab"
                                            aria-controls="${tabId}" aria-selected="false">
                                            <h6 class="card-title mb-3 text-decoration-underline text-dark">${taskTitle}</h6>
                                            <div class="d-flex mb-2 small">
                                                <p>Started by ${addedBy}</p>
                                                <p class="mx-2">•</p>
                                                <p>${createdAt}</p>
                                            </div>
                                            <div class="d-flex small">
                                                <p>0 replies</p>
                                            </div>
                                        </button>
                                    `;
                $("#v-pills-tab .card").append(navTab);

                // Create new content pane (note: initially not active)
                const contentTab = `
                                        <div class="tab-pane fade" id="${tabId}" role="tabpanel" aria-labelledby="${tabId}-tab" tabindex="0">
                                            <div class="card">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="mb-3">
                                                        <h4 class="card-title mb-3 text-decoration-underline">${taskTitle}</h4>
                                                        <small class="text-muted">Started by ${addedBy} • ${createdAt}</small>
                                                        <hr>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-auto" style="max-height: 400px;">
                                                        <p class="text-muted">No comments yet.</p>
                                                    </div>
                                                    <div class="d-flex">
                                                        <img src="/assets/images/profile_picture/{{ $activeUser->profile_picture ?? 'user.png' }}"
                                                            alt="Profile Picture" class="rounded-circle me-3" width="40" height="40">
                                                        <form class="flex-grow-1 comment-form" id="comment-form-${taskId}" data-task-id="${taskId}">
                                                            <textarea class="form-control mb-2" name="comment" placeholder="Write a reply..." rows="3"></textarea>
                                                            <input hidden type="text" name="task_id" value="${taskId}" />
                                                            <div class="text-end">
                                                                <button class="btn btn-primary">Post Reply</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                $("#v-pills-tabContent").append(contentTab);

                // Activate newly created tab using Bootstrap's Tab API
                setTimeout(() => {
                    const tabTrigger = new bootstrap.Tab(document.getElementById(`${tabId}-tab`));
                    tabTrigger.show();
                }, 10); // short delay to ensure DOM is ready
            }
        });

        $(".comment-box .nav-link").on("click", function() {
            const target = $(this).data("bs-target"); // ID of the tab-pane to activate

            // Deactivate all tabs and tab-panes
            $(".comment-box .nav-link.active").removeClass("active");
            $(".comment-box .tab-pane.active.show").removeClass("active show");

            // Activate clicked tab
            $(this).addClass("active");

            // Activate corresponding tab-pane
            $(target).addClass("active show");
        });

    });
</script>
@endsection