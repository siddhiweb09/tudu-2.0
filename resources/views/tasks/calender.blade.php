@extends('layouts.frame')

@section('main')
<style>
    .calendar-container {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        margin-bottom: 8px;
        gap: 6px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    .calendar-day {
        min-height: 100px;
        height: 100%;
        border-radius: 8px;
        background-color: #fff;
        padding: 8px;
        transition: all 0.2s ease-in-out;
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        position: relative;
    }

    .calendar-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-light);
    }

    .calendar-day.empty {
        background-color: var(--bg-light);
        border-color: var(--bg-light);
        box-shadow: none;
        min-height: 100px;
    }

    .calendar-day.today {
        background-color: var(--primary-light);
        border: 1px solid var(--primary-color);
    }

    .day-number {
        font-size: 0.85rem;
        margin-bottom: 4px;
        color: var(--text-dark);
        font-weight: 500;
        align-self: flex-end;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .calendar-day.today .day-number {
        background-color: var(--primary-color);
        color: white;
    }

    .day-tasks {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .calendar-task {
        font-size: 11px;
        border-left: 3px solid #ffc107;
        background-color: #fffbea;
        padding: 4px 6px;
        display: flex;
        align-items: center;
        gap: 4px;
        border-radius: 3px;
        cursor: pointer;
        line-height: 1.3;
    }

    .calendar-task.high-priority {
        border-left-color: #ef4444;
        background-color: #fee2e2;
    }

    .calendar-task.medium-priority {
        border-left-color: #f59e0b;
        background-color: #fffbeb;
    }

    .calendar-task.low-priority {
        border-left-color: #10b981;
        background-color: #ecfdf5;
    }

    .task-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        max-width: 100%;
        font-weight: 500;
    }

    .badge {
        font-size: 9px;
        font-weight: 600;
        padding: 2px 5px;
        border-radius: 4px;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .bg-warning {
        background-color: var(--secondary-color);
        color: #000;
    }

    .bg-danger {
        background-color: var(--danger-color);
        color: white;
    }

    .bg-success {
        background-color: var(--success-color);
        color: white;
    }

    .calendar-day-header {
        padding: 8px 4px;
        color: var(--text-light);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }

    .calendar-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .calendar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .nav-btn {
        border: none;
        background: var(--primary-color);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .nav-btn:hover {
        background: #4f46e5;
        transform: scale(1.05);
    }

    .nav-btn-group {
        display: flex;
        gap: 6px;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .calendar-day {
            min-height: 90px;
            padding: 6px;
        }

        .day-number {
            font-size: 0.8rem;
            width: 20px;
            height: 20px;
        }

        .calendar-task {
            font-size: 10px;
            padding: 3px 4px;
        }

        .badge {
            font-size: 8px;
            padding: 1px 3px;
        }
    }

    @media (max-width: 768px) {
        .calendar-day {
            min-height: 80px;
        }

        .calendar-title {
            font-size: 1.1rem;
        }

        .calendar-day-header {
            font-size: 0.7rem;
            padding: 6px 2px;
        }
    }

    @media (max-width: 576px) {
        .calendar-day {
            min-height: 70px;
            padding: 4px;
        }

        .day-number {
            font-size: 0.75rem;
            width: 18px;
            height: 18px;
        }

        .calendar-task {
            font-size: 9px;
        }
    }
</style>

<div class="container-fluid mt-3">
    <div class="row mt-3 justify-content-center">
        <div class="col-xl-10 col-lg-11 col-md-12">
            <!-- Calendar View -->
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body p-4">
                    <div class="calendar-nav">
                        <h4 class="calendar-title">Loading...</h4>
                        <div class="nav-btn-group">
                            <button class="nav-btn prev-month">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <button class="nav-btn next-month">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                            <button class="nav-btn today-btn" style="background: var(--bg-light); color: var(--primary-color); border: 1px solid var(--border-color);">
                                Today
                            </button>
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
                            <!-- Calendar will be dynamically generated here -->
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
            <div class="modal-header">
                <h5 class="modal-title">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                           "August", "September", "October", "November", "December"];
        $('.calendar-title').text(`${monthNames[month]} ${year}`);
        
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
                calendarHtml += `<div class="day-tasks">`;
                dayTasks.forEach(task => {
                    const priorityClass = getPriorityClass(task.priority);
                    calendarHtml += `
                        <div class="calendar-task ${priorityClass}-priority" data-task-id="${task.id}">
                            <span class="badge bg-${priorityClass}">${capitalizeFirstLetter(task.priority)}</span>
                            <span class="task-title">${task.title}</span>
                        </div>
                    `;
                });
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
            const task = tasks.find(t => t.id === taskId);
            showTaskDetails(task);
        });
    }

    function showTaskDetails(task) {
        const dueDate = task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No due date';
        const priorityClass = getPriorityClass(task.priority);
        const statusClass = getStatusClass(task.status);
        
        const html = `
            <div class="task-details">
                <h5>${task.title}</h5>
                <div class="task-meta mb-3">
                    <span class="badge bg-${priorityClass} me-2">${capitalizeFirstLetter(task.priority)}</span>
                    <span class="badge bg-${statusClass} me-2">${capitalizeFirstLetter(task.status.replace('_', ' '))}</span>
                    <span class="badge bg-secondary">${task.type}</span>
                </div>
                <p><strong>Due Date:</strong> ${dueDate}</p>
                ${task.project_name ? `<p><strong>Project:</strong> ${task.project_name}</p>` : ''}
                ${task.description ? `<div class="mt-3"><strong>Description:</strong><p>${task.description}</p></div>` : ''}
                ${task.delegation_notes ? `<div class="mt-3"><strong>Notes:</strong><p>${task.delegation_notes}</p></div>` : ''}
            </div>
        `;
        
        // Update modal content
        $('#taskDetailsModal .modal-body').html(html);
        $('.view-task-btn').attr('href', `/task/${task.id}`);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('taskDetailsModal'));
        modal.show();
    }

    function getPriorityClass(priority) {
        priority = priority ? priority.toLowerCase() : 'medium';
        return priority === 'high' ? 'danger' : 
               priority === 'medium' ? 'warning' : 'success';
    }

    function getStatusClass(status) {
        status = status ? status.toLowerCase() : 'pending';
        return status === 'completed' ? 'success' :
               status === 'in process' ? 'info' : 'secondary';
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