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
        margin-bottom: 12px;
        gap: 8px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px;
        max-height: 75vh;
        overflow-y: hidden;
    }

    .calendar-day {
        min-height: 120px;
        border-radius: 12px;
        background-color: #fff;
        padding: 10px;
        transition: all 0.2s ease-in-out;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        position: relative;
    }

    .calendar-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: var(--primary-light);
    }

    .calendar-day.empty {
        background-color: var(--bg-light);
        border-color: var(--bg-light);
        box-shadow: none;
    }

    .calendar-day.empty:hover {
        transform: none;
        box-shadow: none;
        cursor: default;
    }

    .calendar-day.today {
        background-color: var(--primary-light);
        border: 1px solid var(--primary-color);
    }

    .calendar-day.today .day-number {
        color: var(--primary-color);
        font-weight: 700;
    }

    .day-number {
        font-size: 0.95rem;
        margin-bottom: 6px;
        color: var(--text-dark);
        font-weight: 500;
        align-self: flex-end;
        width: 24px;
        height: 24px;
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
        overflow-y: auto;
        flex-grow: 1;
        padding-right: 4px;
    }

    /* Your original task style */
    .calendar-task {
        font-size: 12px;
        border-left: 4px solid #ffc107;
        background-color: #fffbea;
        margin-bottom: 6px;
        padding: 6px 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        border-radius: 4px;
        cursor: pointer;
    }

    .task-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        max-width: 100%;
        font-weight: 500;
    }

    .calendar-day-header {
        padding: 10px;
        color: var(--text-light);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.8rem;
    }

    .calendar-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .calendar-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .nav-btn {
        border: none;
        background: var(--primary-color);
        color: white;
        width: 36px;
        height: 36px;
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
        gap: 8px;
    }

    /* Scrollbar styling */
    .day-tasks::-webkit-scrollbar {
        width: 4px;
    }

    .day-tasks::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .day-tasks::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .day-tasks::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .calendar-day {
            min-height: 100px;
            padding: 8px;
            border-radius: 8px;
        }

        .calendar-grid {
            gap: 8px;
        }

        .calendar-title {
            font-size: 1.2rem;
        }

        .task-title {
            font-size: 11px;
        }
    }

    @media (max-width: 576px) {
        .calendar-day {
            min-height: 80px;
        }

        .day-number {
            font-size: 0.8rem;
            width: 20px;
            height: 20px;
        }

        .calendar-task {
            padding: 4px 6px;
        }
    }
</style>
<div class="container-fluid">
    <div class="row mt-3 justify-content-center">
        <div class="col-xl-10 col-lg-11 col-md-12">
            <!-- Calendar View -->
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body p-4">
                    <div class="calendar-nav">
                        <h4 class="calendar-title">June 2025</h4>
                        <div class="nav-btn-group">
                            <button class="nav-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <button class="nav-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                            <button class="nav-btn" style="background: var(--bg-light); color: var(--primary-color); border: 1px solid var(--border-color);">
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

                        <!-- Calendar body structure -->
                        <div class="calendar-grid">
                            <!-- Week 1 -->
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day">
                                <div class="day-number">1</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">2</div>
                            </div>

                            <!-- Week 2 -->
                            <div class="calendar-day">
                                <div class="day-number">3</div>
                                <div class="day-tasks">
                                    <div class="calendar-task" data-task-id="1">
                                        <span class="badge bg-warning">Medium</span>
                                        <span class="task-title">Team sync meeting</span>
                                    </div>
                                </div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">4</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">5</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">6</div>
                                <div class="day-tasks">
                                    <div class="calendar-task" data-task-id="2">
                                        <span class="badge bg-danger">High</span>
                                        <span class="task-title">Project deadline</span>
                                    </div>
                                </div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">7</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">8</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">9</div>
                            </div>

                            <!-- Week 3 -->
                            <div class="calendar-day">
                                <div class="day-number">10</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">11</div>
                                <div class="day-tasks">
                                    <div class="calendar-task" data-task-id="3">
                                        <span class="badge bg-warning">Medium</span>
                                        <span class="task-title">Client presentation</span>
                                    </div>
                                </div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">12</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">13</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">14</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">15</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">16</div>
                            </div>

                            <!-- Week 4 -->
                            <div class="calendar-day">
                                <div class="day-number">17</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">18</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">19</div>
                                <div class="day-tasks">
                                    <div class="calendar-task" data-task-id="4">
                                        <span class="badge bg-success">Low</span>
                                        <span class="task-title">Monthly review</span>
                                    </div>
                                </div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">20</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">21</div>
                            </div>
                            <div class="calendar-day today">
                                <div class="day-number">22</div>
                                <div class="day-tasks">
                                    <div class="calendar-task" data-task-id="5">
                                        <span class="badge bg-danger">High</span>
                                        <span class="task-title">Important meeting</span>
                                    </div>
                                    <div class="calendar-task" data-task-id="6">
                                        <span class="badge bg-warning">Medium</span>
                                        <span class="task-title">Team lunch</span>
                                    </div>
                                </div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">23</div>
                            </div>

                            <!-- Week 5 -->
                            <div class="calendar-day">
                                <div class="day-number">24</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">25</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">26</div>
                                <div class="day-tasks">
                                    <div class="calendar-task" data-task-id="7">
                                        <span class="badge bg-warning">Medium</span>
                                        <span class="task-title">Product demo</span>
                                    </div>
                                </div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">27</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">28</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">29</div>
                            </div>
                            <div class="calendar-day">
                                <div class="day-number">30</div>
                                <div class="day-tasks">
                                    <div class="calendar-task" data-task-id="8">
                                        <span class="badge bg-warning">Medium</span>
                                        <span class="task-title">Friday Task: Meeting Review</span>
                                    </div>
                                    <div class="calendar-task" data-task-id="9">
                                        <span class="badge bg-warning">Medium</span>
                                        <span class="task-title">Second Task: Demo Prep</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Week 6 -->
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                            <div class="calendar-day empty"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
<script>
    $(document).ready(function() {
        $('.calendar-task').click(function() {
            const taskId = $(this).data('task-id');
            $.get('/get-task-details', {
                task_id: taskId
            }, function(data) {
                let html = `
                <h6>${data.title}</h6>
                <p>${data.description || 'No description'}</p>
                <div class="task-meta">
                    <div><strong>Status:</strong> <span class="badge bg-${getStatusClass(data.status)}">${data.status.replace('_', ' ')}</span></div>
                    <div><strong>Priority:</strong> <span class="badge bg-${getPriorityClass(data.priority)}">${data.priority}</span></div>
                    <div><strong>Due Date:</strong> ${data.due_date ? formatDate(data.due_date) : 'No deadline'}</div>
                </div>
            `;
                $('#calendarTaskDetails').html(html);
                const modal = new bootstrap.Modal(document.getElementById('calendarTaskModal'));
                modal.show();
            });
        });

        function getStatusClass(status) {
            const classes = {
                'todo': 'secondary',
                'in_progress': 'info',
                'completed': 'success'
            };
            return classes[status] || 'secondary';
        }

        function getPriorityClass(priority) {
            const classes = {
                'low': 'success',
                'medium': 'warning',
                'high': 'danger'
            };
            return classes[priority] || 'secondary';
        }

        function formatDate(dateString) {
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }
    });
</script>
@endsection