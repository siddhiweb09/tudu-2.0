@extends('layouts.frame')

@section('main')

<style>
    .calendar-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        text-align: center;
        margin-bottom: 0.5rem;
        gap: 0.5rem;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.5rem;
    }

    .calendar-day {
        min-height: 6rem;
        background-color: #f8f9fa;
        padding: 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .calendar-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-light);
    }

    .calendar-day.empty {
        background-color: var(--bg-light);
        border-color: transparent;
    }

    .calendar-day.today {
        background-color: #ddecfb;
        border-color: var(--primary-color);
    }

    .day-number {
        font-size: 0.85rem;
        font-weight: 600;
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-left: auto;
        margin-bottom: 0.25rem;
    }

    .calendar-day.today .day-number {
        background-color: var(--primary-color);
        color: white;
    }

    .day-tasks {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        max-height: 4.5rem;
        overflow-y: auto;
        padding-right: 0.25rem;
    }

    .day-tasks::-webkit-scrollbar {
        width: 3px;
    }

    .day-tasks::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.1);
    }

    .calendar-task {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        border-left: 3px solid var(--warning-color);
        background-color: rgba(255, 193, 7, 0.1);
        cursor: pointer;
        transition: all 0.2s;
    }

    .calendar-task.high-priority {
        border-left-color: var(--danger-color);
        background-color: rgba(245, 56, 90, 0.1);
    }

    .calendar-task.medium-priority {
        border-left-color: var(--warning-color);
        background-color: rgba(255, 159, 28, 0.1);
    }

    .calendar-task.low-priority {
        border-left-color: var(--success-color);
        background-color: rgba(1, 121, 111, 0.1);
    }

    .badge {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.2rem 0.4rem;
    }

    .calendar-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .calendar-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .month-year {
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.9rem;
    }

    .nav-btn-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-btn {
        width: 2rem;
        height: 2rem;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .today-btn {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .more-tasks {
        font-size: 0.7rem;
        color: var(--primary-color);
        font-weight: 600;
        margin-top: 0.25rem;
        cursor: pointer;
    }

    /* Mobile optimizations */
    @media (max-width: 767.98px) {
        .calendar-day {
            min-height: 5rem;
            padding: 0.25rem;
        }

        .day-number {
            font-size: 0.75rem;
            width: 1.25rem;
            height: 1.25rem;
        }

        .calendar-task {
            font-size: 0.65rem;
            padding: 0.15rem 0.25rem;
        }

        .calendar-nav {
            gap: 0.5rem;
            padding: 0.75rem;
        }

        .calendar-title {
            font-size: 1rem;
        }

        .month-year {
            padding: 0.2rem 0.6rem;
            font-size: 0.8rem;
        }

        .nav-btn {
            width: 1.75rem;
            height: 1.75rem;
        }

        .today-btn {
            padding: 0.2rem 0.6rem;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 575.98px) {

        .calendar-header,
        .calendar-grid {
            gap: 0.25rem;
        }

        .calendar-day {
            min-height: 4rem;
        }

        .day-tasks {
            max-height: 3rem;
        }
    }
</style>

<div class="container-xl py-4">
    <div class="row justify-content-center mx-0">
        <div class="col-12 px-0">
            <div class="card border-0 shadow-none">
                <div class="card-body p-2 p-md-3">
                    <div class="calendar-nav">
                        <h4 class="calendar-title">
                            <i class="ti ti-calendar"></i>
                            <span class="month-year">Loading...</span>
                        </h4>
                        <div class="nav-btn-group">
                            <button class="nav-btn btn btn-primary prev-month p-0">
                                <i class="ti ti-chevron-left"></i>
                            </button>
                            <button class="today-btn btn btn-outline-primary">Today</button>
                            <button class="nav-btn btn btn-primary next-month p-0">
                                <i class="ti ti-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <div class="calendar-container">
                        <div class="calendar-header">
                            <div class="text-muted small fw-bold">Mon</div>
                            <div class="text-muted small fw-bold">Tue</div>
                            <div class="text-muted small fw-bold">Wed</div>
                            <div class="text-muted small fw-bold">Thu</div>
                            <div class="text-muted small fw-bold">Fri</div>
                            <div class="text-muted small fw-bold">Sat</div>
                            <div class="text-muted small fw-bold">Sun</div>
                        </div>

                        <div class="calendar-grid">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Details Modal -->
<div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Task Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Task details will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary view-task-btn">View Full Details</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('customJs')
<script>
    const authUserId = "{{ Auth::id() }}";
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    $(document).ready(function() {
        loadCalendarTasks();

        // Navigation handlers
        $('.prev-month').click(function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            loadCalendarTasks();
        });

        $('.next-month').click(function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            loadCalendarTasks();
        });

        $('.today-btn').click(function() {
            const today = new Date();
            currentMonth = today.getMonth();
            currentYear = today.getFullYear();
            loadCalendarTasks();
        });

        // View switcher
        $('.view-btn').click(function() {
            $('.view-btn').removeClass('active');
            $(this).addClass('active');
            // Here you would implement view switching logic
        });

        // Set month view as active by default
        $('.view-btn:first').addClass('active');
    });

    function loadCalendarTasks() {
        $.ajax({
            url: `/fetch-user-tasks/${authUserId}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('.calendar-grid').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
            },
            success: function(response) {
                renderCalendar(currentMonth, currentYear, response.all_tasks);
            },
            error: function(xhr) {
                console.error('Error fetching tasks:', xhr.responseText);
                $('.calendar-grid').html(`
                    <div class="col-12 text-center py-4 text-danger">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                        <p class="mb-0">Failed to load tasks. Please try again.</p>
                    </div>
                `);
            }
        });
    }

    function renderCalendar(month, year, tasks) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.

        // Adjust starting day to make Monday first day of week
        const adjustedStartingDay = startingDay === 0 ? 6 : startingDay - 1;

        // Update calendar title
        const monthNames = ["January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"
        ];
        $('.month-year').text(`${monthNames[month]} ${year}`);

        // Generate calendar grid
        let calendarHtml = '';
        const today = new Date();

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < adjustedStartingDay; i++) {
            calendarHtml += `<div class="calendar-day empty"></div>`;
        }

        // Add cells for each day of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const currentDate = new Date(year, month, day);
            const dateStr = formatDateForAPI(currentDate);
            const isToday = currentDate.toDateString() === today.toDateString();

            // Find tasks for this day
            const dayTasks = tasks.filter(task => {
                if (!task.due_date) return false;
                return task.due_date.startsWith(dateStr);
            });

            // Build day cell
            calendarHtml += `<div class="calendar-day ${isToday ? 'today' : ''}">`;
            calendarHtml += `<div class="day-number">${day}</div>`;

            if (dayTasks.length > 0) {
                // Show max 3 tasks per day
                const tasksToShow = dayTasks.slice(0, 3);
                calendarHtml += `<div class="day-tasks">`;

                tasksToShow.forEach(task => {
                    const priorityClass = getPriorityClass(task.priority);
                    calendarHtml += `
                        <div class="calendar-task ${priorityClass}-priority" data-task-id="${task.id}">
                            <span class="badge bg-${priorityClass}">${capitalizeFirstLetter(task.priority)}</span>
                            <span class="task-title">${task.title}</span>
                        </div>
                    `;
                });

                // Show "more tasks" indicator if there are more than 3
                if (dayTasks.length > 3) {
                    calendarHtml += `<div class="more-tasks">+${dayTasks.length - 3} more</div>`;
                }

                calendarHtml += `</div>`;
            }

            calendarHtml += `</div>`;
        }

        // Calculate total cells (7 columns x 6 rows = 42 cells)
        const totalCells = 42;
        const daysAdded = adjustedStartingDay + daysInMonth;
        const emptyCells = totalCells - daysAdded;

        // Add empty cells to fill the grid
        for (let i = 0; i < emptyCells; i++) {
            calendarHtml += `<div class="calendar-day empty"></div>`;
        }

        // Update the calendar grid
        $('.calendar-grid').html(calendarHtml);

        // Add click handlers to tasks
        $('.calendar-task').click(function() {
            const taskId = $(this).data('task-id');
            const allTasks = tasks.filter(t => t.due_date);
            const task = allTasks.find(t => t.id === taskId);
            showTaskDetails(task);
        });

        // Add click handler to "more tasks" indicators
        $('.more-tasks').click(function(e) {
            e.stopPropagation();
            const dayElement = $(this).closest('.calendar-day');
            const dayNumber = dayElement.find('.day-number').text();
            const dayTasks = tasks.filter(task => {
                if (!task.due_date) return false;
                const taskDate = new Date(task.due_date);
                return taskDate.getDate() == dayNumber &&
                    taskDate.getMonth() == month &&
                    taskDate.getFullYear() == year;
            });

            if (dayTasks.length > 0) {
                showDayTasksPopup(dayNumber, month, year, dayTasks);
            }
        });
    }

    function showTaskDetails(task) {
        const dueDate = task.due_date ? new Date(task.due_date).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) : 'No due date';

        const priorityClass = getPriorityClass(task.priority);
        const statusClass = getStatusClass(task.status);

        const html = `
            <div class="task-details">
                <h5>${task.title}</h5>
                <div class="task-meta">
                    <span class="badge bg-${priorityClass}">${capitalizeFirstLetter(task.priority)} Priority</span>
                    <span class="badge bg-${statusClass}">${capitalizeFirstLetter(task.status.replace('_', ' '))}</span>
                    ${task.type ? `<span class="badge bg-secondary">${task.type}</span>` : ''}
                </div>
                
               <div class="mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-calendar-due me-2" style="font-size: 1.1rem;"></i>
                        <div>
                            <p class="mb-0"><strong>Due Date:</strong> ${dueDate}</p>
                        </div>
                    </div>
                    
                    ${task.project_name ? `
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-folders me-2" style="font-size: 1.1rem;"></i>
                        <div>
                            <p class="mb-0"><strong>Project:</strong> ${task.project_name}</p>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${task.description ? `
                    <div class="mb-3">
                        <h6 class="d-flex align-items-center">
                            <i class="ti ti-notes me-2" style="font-size: 1.1rem;"></i>
                            Description
                        </h6>
                        <p class="ps-4">${task.description}</p>
                    </div>
                    ` : ''}
                    
                    ${task.delegation_notes ? `
                    <div class="mb-3">
                        <h6 class="d-flex align-items-center">
                            <i class="ti ti-note me-2" style="font-size: 1.1rem;"></i>
                            Notes
                        </h6>
                        <p class="ps-4">${task.delegation_notes}</p>
                    </div>
                    ` : ''}
                </div>
        `;

        // Update modal content
        $('#taskDetailsModal .modal-body').html(html);
        $('.view-task-btn').attr('href', `/task/${task.id}`);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('taskDetailsModal'));
        modal.show();
    }

    function showDayTasksPopup(day, month, year, tasks) {
        const date = new Date(year, month, day);
        const dateStr = date.toLocaleDateString('en-US', {
            weekday: 'long',
            month: 'long',
            day: 'numeric'
        });

        let html = `
            <div class="day-tasks-popup">
                <h5 class="mb-3">Tasks for ${dateStr}</h5>
                <div class="task-list">
        `;

        tasks.forEach(task => {
            const priorityClass = getPriorityClass(task.priority);
            html += `
                <div class="task-item mb-2 p-2 rounded d-flex justify-content-between align-items-center" style="border-left: 3px solid var(--${priorityClass}-color); background: var(--${priorityClass}-light);">
                    <div>
                        <strong>${task.title}</strong>
                        <div class="d-flex gap-2 mt-1">
                            <span class="badge bg-${priorityClass}">${capitalizeFirstLetter(task.priority)}</span>
                            ${task.project_name ? `<span class="badge bg-secondary">${task.project_name}</span>` : ''}
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-primary view-task" data-task-id="${task.id}">
                        View
                    </button>
                </div>
            `;
        });

        html += `</div></div>`;

        // Create a modal for day tasks
        const modalId = 'dayTasksModal';
        if ($(`#${modalId}`).length) {
            $(`#${modalId}`).remove();
        }

        $('body').append(`
            <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tasks for ${dateStr}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ${html}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();

        // Add click handler for view buttons
        $(`#${modalId} .view-task`).click(function() {
            const taskId = $(this).data('task-id');
            const task = tasks.find(t => t.id === taskId);
            modal.hide();
            showTaskDetails(task);
        });
    }

    function getPriorityClass(priority) {
        priority = priority ? priority.toLowerCase() : 'medium';
        return priority === 'high' ? 'danger' :
            priority === 'medium' ? 'warning' : 'success';
    }

    function getStatusClass(status) {
        status = status ? status.toLowerCase() : 'pending';
        return status === 'completed' ? 'success' :
            status === 'in_progress' ? 'info' : 'secondary';
    }

    function formatDateForAPI(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function capitalizeFirstLetter(string) {
        return string ? string.charAt(0).toUpperCase() + string.slice(1) : '';
    }
</script>
@endsection