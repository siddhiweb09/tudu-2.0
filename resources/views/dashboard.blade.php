@extends('layouts.frame')

@section('main')

<!-- total task cards -->
<section class="container mb-5 mt-5">
    <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-12 col-md-3">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-file-text text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">Total Projects</h6>
                        <h3 class="fw-bold text-dark mt-1">{{$totalProjects}}</h3>
                        <small class="text-muted">from last month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-12 col-md-3">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-clock text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">In Progress</h6>
                        <h3 class="fw-bold text-dark mt-1">{{$inProcessProjectCount}}</h3>
                        <small class="text-muted">from last month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-12 col-md-3">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-circle-check text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">Completed</h6>
                        <h3 class="fw-bold text-dark mt-1">{{$completedProjectCount}}</h3>
                        <small class="text-muted">from last month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-12 col-md-3">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-info-circle-filled text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">Pending</h6>
                        <h3 class="fw-bold text-dark mt-1">{{$pendingProjectCount}}</h3>
                        <small class="text-muted">from last month</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Project Overview -->
<section class="container mb-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
        <h2 class="h5 fw-semibold text-dark">Project Overview</h2>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                <i class="ti ti-filter" style="font-size: 16px;"></i>
                Filter
            </button>
            <button class="btn btn-outline-secondary btn-sm">View All</button>
        </div>
    </div>

    <div class="row g-3">
        <!-- Card -->
        @foreach($projectNames as $project)
        <div class="col-12 col-md-6 col-xxl-4">
            <div class="card border shadow-sm h-100">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="me-2" style="width: 4px; background-color: #facc15; border-radius: 2px;"></div>
                            <div>
                                <h5 class="card-title d-flex align-items-center mb-1">
                                    {{ $project }}
                                    <i class="ti ti-star-filled ms-2 text-warning" style="font-size: 16px;"></i>
                                </h5>
                                <p class="card-subtitle text-muted small"></p>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark align-self-start"></span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-calendar me-1" style="font-size: 16px;"></i>
                            Deadline:
                        </div>
                        <button class="btn btn-sm btn-light p-1">
                            <i class="ti ti-dots text-secondary" style="font-size: 16px;"></i>
                        </button>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Progress</span><span class="fw-medium">65%</span>
                        </div>
                        <div class="progress" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-primary" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="aspect-square-container mb-2">
                            <div class="aspect-square-box">
                                <img class="aspect-square"
                                    src="../assets/images/profile_picture/user.png">
                            </div>
                            <div class="aspect-square-box">
                                <img class="aspect-square"
                                    src="../assets/images/profile_picture/user.png">
                            </div>
                        </div>
                        <div class="d-flex gap-3 small text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-file-description me-1" style="font-size: 14px;"></i>
                                24 tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="ti ti-chart-bar me-1" style="font-size: 14px;"></i>
                                128 activities
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Project Analytics -->
<section class="container">
    <div class="row g-3 g-xl-4 mb-4">
        <!-- Project Analytics (8 cols) -->
        <div class="col-12 col-xl-8">
            <div class="card h-100 shadow-sm border">
                <div class="card-body pb-2">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-1 fw-semibold">Project Analytics</h4>
                            <div class="text-muted small">Task completion and project progress over time</div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                            This Month
                            <i class="ti ti-chevron-down" style="font-size: 16px;"></i>
                        </button>
                    </div>
                    <!-- Chart Placeholder -->
                    <div style="height: 300px;">
                        <!-- Insert Recharts / Chart.js or any other chart lib -->
                        <div id="project-analytics-chart" style="height: 100%; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Progress (4 cols) -->
        <div class="col-12 col-xl-4">
            <div class="card h-100 shadow-sm border">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="mb-1 fw-semibold">My Progress</h4>
                            <div class="text-muted small">Your task completion rate</div>
                        </div>
                        <button type="button" class="btn btn-light btn-sm p-2">
                            <i class="ti ti-dots" style="font-size: 16px;"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-center mb-4">
                        <div style="width: 100%; height: 256px;">
                            <!-- Insert Pie Chart here -->
                            <div id="my-progress-chart" style="height: 100%; width: 100%;"></div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fw-bold fs-5">63%</div>
                            <div class="text-muted small">Completed</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5">25%</div>
                            <div class="text-muted small">In Progress</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5">12%</div>
                            <div class="text-muted small">Not Started</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- My Tasks -->
<section class="container">
    <div class="row g-3 mb-4">
        <!-- My Tasks (Left Column) -->
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">My Tasks</h4>
                        <a href="/my-tasks" class="btn btn-outline-secondary btn-sm">View All Tasks</a>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-pills nav-fill mb-3 bg-light rounded p-1" id="taskTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="today-tab" data-bs-toggle="tab" data-bs-target="#today" type="button" role="tab">Today</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="tomorrow-tab" data-bs-toggle="tab" data-bs-target="#tomorrow" type="button" role="tab">Tomorrow</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">Upcoming</button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="today" role="tabpanel">
                            <!-- Task Group -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="text-muted fw-medium mb-0">Figma Design System</h6>
                                    <span class="badge bg-light text-dark">3 tasks</span>
                                </div>

                                <!-- Task Card -->
                                <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-start">
                                    <div class="d-flex">
                                        <input class="form-check-input me-3" type="checkbox" id="task-1">
                                        <div>
                                            <label for="task-1" class="fw-semibold">Create component documentation</label>
                                            <div class="small text-muted mt-1 d-flex align-items-center gap-3 flex-wrap">
                                                <span class="d-flex align-items-center text-primary">
                                                    <i class="ti ti-clock me-1"></i> Today, 2:00 PM
                                                </span>
                                                <span class="badge bg-danger text-white">High</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/avatars/alex-morgan.png" class="rounded-circle border" width="28" height="28" alt="Alex">
                                        <img src="/avatars/jessica-chen.png" class="rounded-circle border" width="28" height="28" alt="Jessica">
                                        <button class="btn btn-sm btn-light"><i class="ti ti-three-dots"></i></button>
                                    </div>
                                </div>

                                <!-- Completed Task -->
                                <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-start bg-light-subtle">
                                    <div class="d-flex">
                                        <input class="form-check-input me-3" type="checkbox" id="task-2" checked>
                                        <div>
                                            <label for="task-2" class="text-decoration-line-through text-muted">Design system color palette update</label>
                                            <div class="small text-muted mt-1 d-flex align-items-center gap-3 flex-wrap">
                                                <span class="d-flex align-items-center text-primary">
                                                    <i class="ti ti-clock me-1"></i> Today, 10:00 AM
                                                </span>
                                                <span class="badge bg-warning text-dark">Medium</span>
                                                <span class="text-success d-flex align-items-center">
                                                    <i class="ti ti-check-circle me-1"></i> Completed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/avatars/alex-morgan.png" class="rounded-circle border" width="28" height="28" alt="Alex">
                                        <button class="btn btn-sm btn-light"><i class="ti ti-three-dots"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tomorrow / Upcoming tabs (empty for now) -->
                        <div class="tab-pane fade" id="tomorrow" role="tabpanel"></div>
                        <div class="tab-pane fade" id="upcoming" role="tabpanel"></div>
                    </div>
                </div>

                <!-- Add New Task -->
                <div class="card-footer text-center border-top">
                    <button class="btn btn-outline-primary w-100">
                        <i class="ti ti-plus me-2"></i> Add New Task
                    </button>
                </div>
            </div>
        </div>

        <!-- Team Performance (Right Column) -->
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="mb-0">Team Performance</h4>
                            <small class="text-muted">Weekly task completion rate</small>
                        </div>
                        <button class="btn btn-sm btn-light"><i class="ti ti-three-dots"></i></button>
                    </div>

                    <!-- Chart Placeholder -->
                    <div style="height: 300px;">
                        <!-- Replace this with actual chart using Chart.js or Recharts -->
                        <div class="bg-light border rounded h-100 d-flex align-items-center justify-content-center">
                            <span class="text-muted">[Team Performance Chart]</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Members -->
<section class="container">
    <div class="row g-3 g-xl-4">
        <!-- Left column: Team Members -->
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-body pb-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">Team Members</h5>
                            <small class="text-muted">Performance overview of team members</small>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                            <i class="ti ti-people-fill me-2"></i> View All
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-borderless mb-0">
                            <thead class="border-bottom">
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th class="text-center">Tasks</th>
                                    <th class="text-center">Performance</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Member Row -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/avatars/alex-morgan.png" alt="Alex Morgan" class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div class="fw-semibold">Alex Morgan</div>
                                                <small class="text-muted">alex.morgan@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap text-muted">UI/UX Designer</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary fw-medium me-1">18</span>
                                        <span class="badge bg-warning-subtle text-warning fw-medium me-1">7</span>
                                        <span class="badge bg-success-subtle text-success fw-medium">11</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success d-inline-flex align-items-center">
                                            <i class="ti ti-arrow-up-right me-1"></i> +12% this week
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light">
                                            <i class="ti ti-three-dots-vertical"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Repeat the above block for each member with different data -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right column: Tomorrow Note -->
        <div class="col-12 col-xl-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">Tomorrow Note</h5>
                        <span class="badge bg-light text-dark d-inline-flex align-items-center">
                            <i class="ti ti-lock-fill me-1 small"></i> Private
                        </span>
                    </div>
                    <small class="text-muted">Your personal notes for tomorrow</small>

                    <ul class="list-unstyled mt-3">
                        <li class="d-flex"><span class="text-primary me-2">•</span> Team meeting at 10:00 AM with design department</li>
                        <li class="d-flex"><span class="text-primary me-2">•</span> Review design system updates for Figma components</li>
                        <li class="d-flex"><span class="text-primary me-2">•</span> Finalize project timeline for Keep React development</li>
                        <li class="d-flex"><span class="text-primary me-2">•</span> Prepare presentation for client meeting at 2:00 PM</li>
                        <li class="d-flex"><span class="text-primary me-2">•</span> Follow up with marketing team on campaign assets</li>
                    </ul>
                </div>

                <div class="card-footer d-flex justify-content-between pt-3">
                    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="ti ti-plus me-2"></i> Add Note
                    </button>
                    <button class="btn btn-outline-secondary btn-sm">Edit Notes</button>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script>
    const authUserId = "{{ Auth::id() }}";
    $(document).ready(function() {
            loadUserStat();
    });

    function loadUserStat() {
        $.ajax({
            url: `/fetch-user-tasks/${authUserId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {},
            error: function(xhr) {
                console.error('Error fetching tasks:', xhr.responseText);
            }
        });
    }
</script>

@endsection