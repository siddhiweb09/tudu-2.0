@extends('layouts.frame')

@section('main')

<!-- total task cards -->
<section class="container mb-5 mt-5">
    <div class="row g-4" id="metrics-cards">
        <!-- These will be populated dynamically -->
        <!-- Card 1 - Total Projects -->
        <div class="col-12 col-md-3">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-file-text text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">Total Projects</h6>
                        <h3 class="fw-bold text-dark mt-1" id="total-projects">0</h3>
                        <small class="text-muted">from last month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 - In Progress -->
        <div class="col-12 col-md-3">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-clock text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">In Progress</h6>
                        <h3 class="fw-bold text-dark mt-1" id="in-progress">0</h3>
                        <small class="text-muted">from last month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3 - Completed -->
        <div class="col-12 col-md-3">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-circle-check text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">Completed</h6>
                        <h3 class="fw-bold text-dark mt-1" id="completed-tasks">0</h3>
                        <small class="text-muted">from last month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4 - Pending -->
        <div class="col-12 col-md-3">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-info-circle text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted mb-0">Pending</h6>
                        <h3 class="fw-bold text-dark mt-1" id="pending-tasks">0</h3>
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

    <div class="row g-3" id="project-overview">
        <!-- Projects will be loaded here dynamically -->
        <div class="col-12 text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
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
                            <i class="ti ti-chevron-down" style="font-size: 16px;"></i>
                        </button>
                    </div>
                    <!-- Chart Placeholder -->
                    <div style="height: 300px;">
                        <canvas id="project-analytics-chart"></canvas>
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
                            <canvas id="my-progress-chart"></canvas>
                        </div>
                    </div>

                    <div class="row text-center" id="progress-stats">
                        <div class="col-4">
                            <div class="fw-bold fs-5" id="completed-percent">0%</div>
                            <div class="text-muted small">Completed</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5" id="in-progress-percent">0%</div>
                            <div class="text-muted small">In Progress</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5" id="pending-percent">0%</div>
                            <div class="text-muted small">Pending</div>
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
                    <div class="tab-content" id="tasks-content">
                        <div class="tab-pane fade show active" id="today" role="tabpanel">
                            <!-- Loading indicator -->
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
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
                        <canvas id="team-performance-chart"></canvas>
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
                            <tbody id="team-members-table">
                                <!-- Team members will be loaded here -->
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
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

                    <ul class="list-unstyled mt-3" id="tomorrow-notes">
                        <li class="d-flex"><span class="text-primary me-2">•</span> Team meeting at 10:00 AM with design department</li>
                        <li class="d-flex"><span class="text-primary me-2">•</span> Review design system updates for Figma components</li>
                        <li class="d-flex"><span class="text-primary me-2">•</span> Finalize project timeline for Keep React development</li>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const authUserId = "{{ Auth::id() }}";
    $(document).ready(function() {
        loadUserStat();

        // Initialize charts with empty data
        initCharts();
    });

    function loadUserStat() {
        // Show loading states
        $('#project-overview').html('<div class="col-12 text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#tasks-content .tab-pane').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#team-members-table').html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');

        $.ajax({
            url: `/fetch-user-tasks/${authUserId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Update metrics cards
                updateMetricsCards(response.metrics, response.breakdown);

                // Update project overview
                updateProjectOverview(response);

                // Update task lists
                updateTaskLists(response.all_tasks);

                // Update charts with real data
                updateCharts(response);

                // Update team members
                updateTeamMembers(response);
            },
            error: function(xhr) {
                console.error('Error fetching tasks:', xhr.responseText);
                // Show error states
                $('#project-overview').html('<div class="col-12 text-center py-4 text-danger">Failed to load projects. Please try again.</div>');
                $('#tasks-content .tab-pane').html('<div class="text-center py-4 text-danger">Failed to load tasks. Please try again.</div>');
                $('#team-members-table').html('<tr><td colspan="5" class="text-center py-4 text-danger">Failed to load team members.</td></tr>');
            }
        });
    }

    function initCharts() {
        // Initialize charts with empty data
        const projectAnalyticsCtx = document.getElementById('project-analytics-chart');
        if (projectAnalyticsCtx) {
            new Chart(projectAnalyticsCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Task Completion',
                        data: [],
                        borderColor: '#3b82f6',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        const myProgressCtx = document.getElementById('my-progress-chart');
        if (myProgressCtx) {
            new Chart(myProgressCtx, {
                type: 'pie',
                data: {
                    labels: ['Completed', 'In Progress', 'Pending'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: [
                            '#10b981',
                            '#3b82f6',
                            '#64748b'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        const teamPerformanceCtx = document.getElementById('team-performance-chart');
        if (teamPerformanceCtx) {
            new Chart(teamPerformanceCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Tasks Completed',
                        data: [],
                        backgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }

    function updateMetricsCards(metrics, breakdown) {
        console.log('Metrics:', metrics);
        console.log('Breakdown:', breakdown);

        // Total Projects Card - count the keys in by_project
        const projectCount = breakdown.by_project ? Object.keys(breakdown.by_project).length : 0;
        $('#total-projects').text(projectCount);

        // In Progress Card - check for 'in progress' status (note the space)
        const inProgressCount = breakdown.by_status?.['in progress'] || 0;
        $('#in-progress').text(inProgressCount);

        // Completed Card
        const completedCount = breakdown.by_status?.completed || 0;
        $('#completed-tasks').text(completedCount);

        // Pending Card
        const pendingCount = breakdown.by_status?.pending || 0;
        $('#pending-tasks').text(pendingCount);

        // Progress percentages
        const totalTasks = metrics.total_tasks || 1; // Avoid division by zero
        $('#completed-percent').text(Math.round((completedCount / totalTasks) * 100) + '%');
        $('#in-progress-percent').text(Math.round((inProgressCount / totalTasks) * 100) + '%');
        $('#pending-percent').text(Math.round((pendingCount / totalTasks) * 100) + '%');
    }

    async function updateProjectOverview(response) {
        const projectOverviewContainer = $('#project-overview');
        projectOverviewContainer.empty();

        if (!response.all_tasks || response.all_tasks.length === 0) {
            projectOverviewContainer.html('<div class="col-12 text-center py-4 text-muted">No projects found</div>');
            return;
        }

        // Group tasks by project - only include tasks with project names
        const projects = {};
        response.all_tasks.forEach(task => {
            // Skip tasks without project names
            if (!task.project_name || task.project_name.trim() === '') {
                return;
            }

            // Extract employee code from assign_to if it exists
            if (task.assign_to && task.assign_to.includes('*')) {
                task.assign_to_code = task.assign_to.split('*')[0].trim();
            }

            const projectName = task.project_name;
            if (!projects[projectName]) {
                projects[projectName] = {
                    name: projectName,
                    tasks: [],
                    status: task.status,
                    priority: task.priority,
                    dueDate: task.due_date
                };
            }
            projects[projectName].tasks.push(task);
        });

        // If no projects with names were found
        if (Object.keys(projects).length === 0) {
            projectOverviewContainer.html('<div class="col-12 text-center py-4 text-muted">No projects with assigned names found</div>');
            return;
        }

        // Create project cards - using for...of to handle async properly
        for (const project of Object.values(projects)) {
            const completedTasks = project.tasks.filter(t => t.status.toLowerCase() === 'completed').length;
            const progressPercentage = Math.round((completedTasks / project.tasks.length) * 100);

            // Get avatars (await here since we're in an async function)
            const avatars = await getAssigneeAvatars(project.tasks);


            const projectCard = `
            <div class="col-12 col-md-6 col-xxl-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-body pb-2">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <div class="me-2" style="width: 4px; background-color: ${getPriorityColor(project.priority)}; border-radius: 2px;"></div>
                                <div>
                                    <h5 class="card-title d-flex align-items-center mb-1">
                                       ${project.name}
                                    </h5>
                                    <p class="card-subtitle text-muted small">${project.tasks.length} tasks</p>
                                </div>
                            </div>
                            <span class="badge ${getStatusClass(project.status)} align-self-start">${project.status}</span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-calendar me-1" style="font-size: 16px;"></i>
                                Deadline: ${project.dueDate ? formatDate(project.dueDate) : 'No deadline'}
                            </div>
                            <button class="btn btn-sm btn-light p-1">
                                <i class="ti ti-dots text-secondary" style="font-size: 16px;"></i>
                            </button>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Progress</span><span class="fw-medium">${progressPercentage}%</span>
                            </div>
                            <div class="progress" role="progressbar" aria-valuenow="${progressPercentage}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: ${progressPercentage}%"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="aspect-square-container mb-2">
                                ${avatars}
                            </div>
                            <div class="d-flex gap-3 small text-muted">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-file-description me-1" style="font-size: 14px;"></i>
                                    ${project.tasks.length} tasks
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-chart-bar me-1" style="font-size: 14px;"></i>
                                    ${completedTasks} completed
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            projectOverviewContainer.append(projectCard);
        }
    }

    function updateTaskLists(tasks) {
        // Group tasks by status and due date
        const todayTasks = [];
        const tomorrowTasks = [];
        const upcomingTasks = [];

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        tasks.forEach(task => {
            const dueDate = task.due_date ? new Date(task.due_date) : null;

            if (!dueDate) {
                upcomingTasks.push(task);
            } else {
                const timeDiff = dueDate - today;
                const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

                if (daysDiff === 0) {
                    todayTasks.push(task);
                } else if (daysDiff === 1) {
                    tomorrowTasks.push(task);
                } else {
                    upcomingTasks.push(task);
                }
            }
        });

        // Update Today tab
        updateTaskTab('#today', todayTasks);

        // Update Tomorrow tab
        updateTaskTab('#tomorrow', tomorrowTasks);

        // Update Upcoming tab
        updateTaskTab('#upcoming', upcomingTasks);
    }

    function updateTaskTab(tabId, tasks) {
        const tabContent = $(tabId);
        tabContent.empty();

        if (tasks.length === 0) {
            tabContent.append('<p class="text-muted text-center py-4">No tasks found</p>');
            return;
        }

        // Group tasks by project
        const tasksByProject = {};
        tasks.forEach(task => {
            const projectName = task.project_name || 'Unassigned';
            if (!tasksByProject[projectName]) {
                tasksByProject[projectName] = [];
            }
            tasksByProject[projectName].push(task);
        });

        // Create task groups
        Object.entries(tasksByProject).forEach(([projectName, projectTasks]) => {
            const taskGroup = `
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="text-muted fw-medium mb-0">${projectName}</h6>
                        <span class="badge bg-light text-dark">${projectTasks.length} tasks</span>
                    </div>
            `;

            tabContent.append(taskGroup);

            // Add individual tasks
            projectTasks.forEach((task, index) => {
                const isCompleted = task.status.toLowerCase() === 'completed';
                const taskCard = `
                    <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-start ${isCompleted ? 'bg-light-subtle' : ''}">
                        <div class="d-flex">
                            <input class="form-check-input me-3" type="checkbox" id="task-${task.id}" ${isCompleted ? 'checked' : ''}>
                            <div>
                                <label for="task-${task.id}" class="${isCompleted ? 'text-decoration-line-through text-muted' : 'fw-semibold'}">${task.title}</label>
                                <div class="small text-muted mt-1 d-flex align-items-center gap-3 flex-wrap">
                                    <span class="d-flex align-items-center text-primary">
                                        <i class="ti ti-clock me-1"></i>
                                        ${task.due_date ? formatDateTime(task.due_date) : 'No due date'}
                                    </span>
                                    <span class="badge ${getPriorityBadgeClass(task.priority)}">${capitalizeFirstLetter(task.priority)}</span>
                                    ${isCompleted ? '<span class="text-success d-flex align-items-center"><i class="ti ti-check-circle me-1"></i> Completed</span>' : ''}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            ${getAssigneeAvatar(task.assign_to)}
                            <button class="btn btn-sm btn-light"><i class="ti ti-three-dots"></i></button>
                        </div>
                    </div>
                `;

                tabContent.append(taskCard);
            });

            tabContent.append('</div>');
        });
    }

    function updateCharts(response) {
        // Update Project Analytics Chart (Line Chart)
        const projectAnalyticsChart = Chart.getChart("project-analytics-chart");
        if (projectAnalyticsChart) {
            // Example data - replace with your actual time-based data
            projectAnalyticsChart.data.labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            projectAnalyticsChart.data.datasets[0].data = [12, 19, 8, 15];
            projectAnalyticsChart.update();
        }

        // Update My Progress Chart (Pie Chart)
        const myProgressChart = Chart.getChart("my-progress-chart");
        if (myProgressChart) {
            myProgressChart.data.datasets[0].data = [
                response.metrics.completed_tasks || 0,
                response.metrics.breakdown?.by_status?.['in progress'] || 0,
                response.metrics.breakdown?.by_status?.pending || 0
            ];
            myProgressChart.update();
        }

        // Update Team Performance Chart (Bar Chart)
        const teamPerformanceChart = Chart.getChart("team-performance-chart");
        if (teamPerformanceChart) {
            // Example data - replace with your actual team data
            teamPerformanceChart.data.labels = ['Alex', 'Jamie', 'Taylor', 'Morgan'];
            teamPerformanceChart.data.datasets[0].data = [12, 8, 15, 10];
            teamPerformanceChart.update();
        }
    }

    function updateTeamMembers(response) {
        const teamMembersTable = $('#team-members-table');
        teamMembersTable.empty();

        // Example data - replace with your actual team data
        const teamMembers = [{
                name: "Alex Morgan",
                email: "alex.morgan@example.com",
                role: "UI/UX Designer",
                tasks: {
                    total: 18,
                    inProgress: 7,
                    completed: 11
                },
                performance: "+12%"
            },
            {
                name: "Jamie Smith",
                email: "jamie.smith@example.com",
                role: "Frontend Developer",
                tasks: {
                    total: 15,
                    inProgress: 5,
                    completed: 10
                },
                performance: "+8%"
            },
            {
                name: "Taylor Johnson",
                email: "taylor.johnson@example.com",
                role: "Backend Developer",
                tasks: {
                    total: 22,
                    inProgress: 12,
                    completed: 10
                },
                performance: "+5%"
            }
        ];

        if (teamMembers.length === 0) {
            teamMembersTable.html('<tr><td colspan="5" class="text-center py-4 text-muted">No team members found</td></tr>');
            return;
        }

        teamMembers.forEach(member => {
            const row = `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="/assets/images/profile_picture/${member.profile_picture ? member.profile_picture : 'user.png'}"  alt="${member.name}" class="rounded-circle me-2" width="32" height="32">
                            <div>
                                <div class="fw-semibold">${member.name}</div>
                                <small class="text-muted">${member.email}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-nowrap text-muted">${member.role}</td>
                    <td class="text-center">
                        <span class="badge bg-primary-subtle text-primary fw-medium me-1">${member.tasks.total}</span>
                        <span class="badge bg-warning-subtle text-warning fw-medium me-1">${member.tasks.inProgress}</span>
                        <span class="badge bg-success-subtle text-success fw-medium">${member.tasks.completed}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-success-subtle text-success d-inline-flex align-items-center">
                            <i class="ti ti-arrow-up-right me-1"></i> ${member.performance}
                        </span>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-light">
                            <i class="ti ti-three-dots-vertical"></i>
                        </button>
                    </td>
                </tr>
            `;

            teamMembersTable.append(row);
        });
    }

    // Helper functions
    function getPriorityColor(priority) {
        if (!priority) return '#60a5fa'; // default blue

        const colors = {
            'high': '#f87171', // red
            'medium': '#fbbf24', // yellow
            'low': '#4ade80' // green
        };
        return colors[priority.toLowerCase()] || '#60a5fa'; // default blue
    }

    function getStatusClass(status) {
        if (!status) return 'bg-secondary text-white';

        const classes = {
            'pending': 'bg-warning text-dark',
            'in progress': 'bg-info text-dark',
            'completed': 'bg-success text-white'
        };
        return classes[status.toLowerCase()] || 'bg-secondary text-white';
    }

    function getPriorityBadgeClass(priority) {
        if (!priority) return 'bg-secondary text-white';

        const classes = {
            'high': 'bg-danger text-white',
            'medium': 'bg-warning text-dark',
            'low': 'bg-success text-white'
        };
        return classes[priority.toLowerCase()] || 'bg-secondary text-white';
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    function formatDateTime(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function capitalizeFirstLetter(string) {
        if (!string) return '';
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }


    // async function updateProjectOverview(response) {
    //     const projectOverviewContainer = $('#project-overview');
    //     projectOverviewContainer.empty();

    //     if (!response.all_tasks?.length) {
    //         projectOverviewContainer.html('<div class="col-12 text-center py-4 text-muted">No projects found</div>');
    //         return;
    //     }

    //     // Group tasks by project
    //     const projects = {};
    //     response.all_tasks.forEach(task => {
    //         if (!task.project_name?.trim()) return;

    //         if (!projects[task.project_name]) {
    //             projects[task.project_name] = {
    //                 name: task.project_name,
    //                 tasks: [],
    //                 status: task.status,
    //                 priority: task.priority,
    //                 dueDate: task.due_date
    //             };
    //         }
    //         projects[task.project_name].tasks.push(task);
    //     });

    //     if (!Object.keys(projects).length) {
    //         projectOverviewContainer.html('<div class="col-12 text-center py-4 text-muted">No projects with names found</div>');
    //         return;
    //     }

    //     // Create project cards
    //     for (const project of Object.values(projects)) {
    //         const completedTasks = project.tasks.filter(t => t.status.toLowerCase() === 'completed').length;
    //         const progressPercentage = Math.round((completedTasks / project.tasks.length) * 100) || 0;

    //         const projectCard = `
    //         <div class="col-12 col-md-6 col-xxl-4">
    //             <div class="card border shadow-sm h-100">
    //                 <!-- Other card content... -->
    //                 <div class="aspect-square-container mb-2">
    //                     ${await getAssigneeAvatars(project.tasks)}
    //                 </div>
    //                 <!-- Rest of card content... -->
    //             </div>
    //         </div>
    //     `;
    //         projectOverviewContainer.append(projectCard);
    //     }
    // }

    async function getAssigneeAvatars(tasks) {
        try {
            // Extract unique employee codes from assign_to fields
            const employeeCodes = [];
            const uniqueCodes = new Set();

            tasks.forEach(task => {
                if (task.assign_to && task.assign_to.includes('*')) {
                    const code = task.assign_to.split('*')[0].trim();
                    if (code && !uniqueCodes.has(code)) {
                        uniqueCodes.add(code);
                        employeeCodes.push(code);
                    }
                }
            });

            if (employeeCodes.length === 0) {
                return ''; // No assignees found
            }

            console.log('Fetching avatars for codes:', employeeCodes);

            // Fetch profile pictures in bulk
            const response = await fetch('/api/users/profile-pictures', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    codes: employeeCodes.slice(0, 3) // Only first 3 to limit requests
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const profilePictures = await response.json();
            console.log('Received profile pictures:', profilePictures);

            // Generate avatar HTML
            return employeeCodes.slice(0, 3).map(code => {
                const pictureUrl = profilePictures[code] || '/assets/images/profile_picture/user.png';
                return `
                <div class="aspect-square-box">
                    <img class="aspect-square rounded-circle border" 
                         src="${pictureUrl}" 
                         alt="Assignee ${code}"
                         onerror="this.onerror=null;this.src='/assets/images/profile_picture/user.png'"
                         width="32"
                         height="32"
                         loading="lazy"
                         data-toggle="tooltip" 
                         data-placement="top"
                         title="${getAssigneeName(code, tasks)}">
                </div>
            `;
            }).join('');

        } catch (error) {
            console.error('Error in getAssigneeAvatars:', error);
            return ''; // Return empty string if anything fails
        }
    }

    // Helper function to get assignee name
    function getAssigneeName(code, tasks) {
        const taskWithCode = tasks.find(t => t.assign_to && t.assign_to.startsWith(code + '*'));
        return taskWithCode ? taskWithCode.assign_to.split('*')[1] : 'Assignee';
    }
</script>

    function loadTasksStat() {
        $.ajax({
            url: `/fetch-user-tasks/${authUserId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Clear all columns first

            },
            error: function(xhr) {
                console.error('Error fetching tasks:', xhr.responseText);
                $('.kanban-cards').html(`
                    <div class="text-center py-4 text-danger">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                        <p class="mb-0">Failed to load tasks. Please try again.</p>
                    </div>
                `);
            }
        });
    }

    // Load tasks on page load
    loadTasksStat();
</script>
@endsection