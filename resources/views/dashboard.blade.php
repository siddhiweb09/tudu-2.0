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
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-white" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <path d="M10 9H8" />
                                <path d="M16 13H8" />
                                <path d="M16 17H8" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">Total Projects</h6>
                        <h3 class="fw-bold text-dark mt-1">{{ $totalTasks }}</h3>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-white" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">In Progress</h6>
                        <h3 class="fw-bold text-dark mt-1">{{ $inProcess }}</h3>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-white" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M21.801 10A10 10 0 1 1 17 3.335" />
                                <path d="m9 11 3 3L22 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">Completed</h6>
                        <h3 class="fw-bold text-dark mt-1">{{ $completedTasks }}</h3>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-white" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" x2="12" y1="8" y2="12" />
                                <line x1="12" x2="12.01" y1="16" y2="16" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">Pending</h6>
                        <h3 class="fw-bold text-dark mt-1">{{ $pending }}</h3>
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
                <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z" />
                </svg>
                Filter
            </button>
            <button class="btn btn-outline-secondary btn-sm">View All</button>
        </div>
    </div>

    <div class="row g-3">
        <!-- Card -->
        <div class="col-12 col-md-6 col-xxl-4">
            <div class="card border shadow-sm h-100">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="me-2" style="width: 4px; background-color: #facc15; border-radius: 2px;"></div>
                            <div>
                                <h5 class="card-title d-flex align-items-center mb-1">
                                    Figma Design System
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ms-2 text-warning" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                    </svg>
                                </h5>
                                <p class="card-subtitle text-muted small">UI component library for design system</p>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark align-self-start">In Progress</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <div class="d-flex align-items-center">
                            <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg>
                            Deadline: Nov 15, 2023
                        </div>
                        <button class="btn btn-sm btn-light p-1">
                            <svg class="text-secondary" width="16" height="16" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="19" cy="12" r="1" />
                                <circle cx="5" cy="12" r="1" />
                            </svg>
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
                                <svg class="me-1" width="14" height="14" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                    <path d="M10 9H8" />
                                    <path d="M16 13H8" />
                                    <path d="M16 17H8" />
                                </svg>
                                24 tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <svg class="me-1" width="14" height="14" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <line x1="18" x2="18" y1="20" y2="10"></line>
                                    <line x1="12" x2="12" y1="20" y2="4"></line>
                                    <line x1="6" x2="6" y1="20" y2="14"></line>
                                </svg>
                                128 activities
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xxl-4">
            <div class="card border shadow-sm h-100">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="me-2" style="width: 4px; background-color: #facc15; border-radius: 2px;"></div>
                            <div>
                                <h5 class="card-title d-flex align-items-center mb-1">
                                    Figma Design System
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ms-2 text-warning" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                    </svg>
                                </h5>
                                <p class="card-subtitle text-muted small">UI component library for design system</p>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark align-self-start">In Progress</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <div class="d-flex align-items-center">
                            <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg>
                            Deadline: Nov 15, 2023
                        </div>
                        <button class="btn btn-sm btn-light p-1">
                            <svg class="text-secondary" width="16" height="16" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="19" cy="12" r="1" />
                                <circle cx="5" cy="12" r="1" />
                            </svg>
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
                                <svg class="me-1" width="14" height="14" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                    <path d="M10 9H8" />
                                    <path d="M16 13H8" />
                                    <path d="M16 17H8" />
                                </svg>
                                24 tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <svg class="me-1" width="14" height="14" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <line x1="18" x2="18" y1="20" y2="10"></line>
                                    <line x1="12" x2="12" y1="20" y2="4"></line>
                                    <line x1="6" x2="6" y1="20" y2="14"></line>
                                </svg>
                                128 activities
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xxl-4">
            <div class="card border shadow-sm h-100">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="me-2" style="width: 4px; background-color: #facc15; border-radius: 2px;"></div>
                            <div>
                                <h5 class="card-title d-flex align-items-center mb-1">
                                    Figma Design System
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ms-2 text-warning" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                    </svg>
                                </h5>
                                <p class="card-subtitle text-muted small">UI component library for design system</p>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark align-self-start">In Progress</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <div class="d-flex align-items-center">
                            <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg>
                            Deadline: Nov 15, 2023
                        </div>
                        <button class="btn btn-sm btn-light p-1">
                            <svg class="text-secondary" width="16" height="16" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="19" cy="12" r="1" />
                                <circle cx="5" cy="12" r="1" />
                            </svg>
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
                                <svg class="me-1" width="14" height="14" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                    <path d="M10 9H8" />
                                    <path d="M16 13H8" />
                                    <path d="M16 17H8" />
                                </svg>
                                24 tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <svg class="me-1" width="14" height="14" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <line x1="18" x2="18" y1="20" y2="10"></line>
                                    <line x1="12" x2="12" y1="20" y2="4"></line>
                                    <line x1="6" x2="6" y1="20" y2="14"></line>
                                </svg>
                                128 activities
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis">
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="19" cy="12" r="1" />
                                <circle cx="5" cy="12" r="1" />
                            </svg>
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
                                                    <i class="bi bi-clock me-1"></i> Today, 2:00 PM
                                                </span>
                                                <span class="badge bg-danger text-white">High</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/avatars/alex-morgan.png" class="rounded-circle border" width="28" height="28" alt="Alex">
                                        <img src="/avatars/jessica-chen.png" class="rounded-circle border" width="28" height="28" alt="Jessica">
                                        <button class="btn btn-sm btn-light"><i class="bi bi-three-dots"></i></button>
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
                                                    <i class="bi bi-clock me-1"></i> Today, 10:00 AM
                                                </span>
                                                <span class="badge bg-warning text-dark">Medium</span>
                                                <span class="text-success d-flex align-items-center">
                                                    <i class="bi bi-check-circle me-1"></i> Completed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/avatars/alex-morgan.png" class="rounded-circle border" width="28" height="28" alt="Alex">
                                        <button class="btn btn-sm btn-light"><i class="bi bi-three-dots"></i></button>
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
                        <i class="bi bi-plus me-2"></i> Add New Task
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
                        <button class="btn btn-sm btn-light"><i class="bi bi-three-dots"></i></button>
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
                            <i class="bi bi-people-fill me-2"></i> View All
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
                                            <i class="bi bi-arrow-up-right me-1"></i> +12% this week
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light">
                                            <i class="bi bi-three-dots-vertical"></i>
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
                            <i class="bi bi-lock-fill me-1 small"></i> Private
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
                        <i class="bi bi-plus me-2"></i> Add Note
                    </button>
                    <button class="btn btn-outline-secondary btn-sm">Edit Notes</button>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')

@endsection