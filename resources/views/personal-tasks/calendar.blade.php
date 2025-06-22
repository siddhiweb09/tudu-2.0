<!-- resources/views/personal-tasks/calendar.blade.php -->

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title">Task Calendar</h5>
            <div class="btn-group">
                <a href="{{ route('personal-tasks.calendar', ['month' => $month-1 < 1 ? 12 : $month-1, 'year' => $month-1 < 1 ? $year-1 : $year]) }}" 
                   class="btn btn-outline-secondary">
                    <i class="ti-angle-left"></i>
                </a>
                <button class="btn btn-outline-primary disabled">
                    {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                </button>
                <a href="{{ route('personal-tasks.calendar', ['month' => $month+1 > 12 ? 1 : $month+1, 'year' => $month+1 > 12 ? $year+1 : $year]) }}" 
                   class="btn btn-outline-secondary">
                    <i class="ti-angle-right"></i>
                </a>
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
                @php
                    $firstDayOfMonth = \Carbon\Carbon::create($year, $month, 1);
                    $daysInMonth = $firstDayOfMonth->daysInMonth;
                    $firstDayOfWeek = $firstDayOfMonth->dayOfWeekIso;
                    
                    // Fill in blank days for the first week
                    for ($i = 1; $i < $firstDayOfWeek; $i++) {
                        echo '<div class="calendar-day empty"></div>';
                    }
                    
                    // Display each day of the month
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $currentDate = \Carbon\Carbon::create($year, $month, $day);
                        $isToday = $currentDate->isToday();
                        
                        // Filter tasks for this day
                        $dayTasks = $tasks->filter(function($task) use ($currentDate) {
                            return $task->due_date && $task->due_date->isSameDay($currentDate);
                        });
                        
                        echo '<div class="calendar-day'.($isToday ? ' today' : '').'">';
                        echo '<div class="day-number">'.$day.'</div>';
                        
                        if ($dayTasks->count() > 0) {
                            echo '<div class="day-tasks">';
                            foreach ($dayTasks as $task) {
                                $priority_class = '';
                                if ($task->priority == 'high') $priority_class = 'high-priority';
                                elseif ($task->priority == 'medium') $priority_class = 'medium-priority';
                                
                                echo '<div class="calendar-task '.$priority_class.'" data-task-id="'.$task->id.'">';
                                echo '<span class="task-title">'.e($task->title).'</span>';
                                
                                if ($task->status == 'completed') {
                                    echo '<i class="ti-check text-success ml-1"></i>';
                                }
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        echo '</div>';
                        
                        // Start new row after Sunday
                        if (($day + $firstDayOfWeek - 1) % 7 == 0 && $day != $daysInMonth) {
                            echo '</div><div class="calendar-grid">';
                        }
                    }
                    
                    // Fill in remaining blank days in the last week
                    $daysDisplayed = $daysInMonth + $firstDayOfWeek - 1;
                    $remainingDays = 7 - ($daysDisplayed % 7);
                    if ($remainingDays < 7) {
                        for ($i = 0; $i < $remainingDays; $i++) {
                            echo '<div class="calendar-day empty"></div>';
                        }
                    }
                @endphp
            </div>
        </div>
    </div>
</div>

<!-- Task Detail Modal for Calendar -->
<div class="modal fade" id="calendarTaskModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Task Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="calendarTaskDetails">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@section('customJs')
<script>
$(document).ready(function() {
    // Handle task clicks in calendar
    $('.calendar-task').click(function() {
        const taskId = $(this).data('task-id');
        
        $.get("{{ route('personal-tasks.show', ':id') }}".replace(':id', taskId), function(data) {
            let html = `
                <h6>${data.title}</h6>
                <p>${data.description || 'No description'}</p>
                <div class="task-meta">
                    <div><strong>Status:</strong> <span class="badge badge-${getStatusClass(data.status)}">${data.status.replace('_', ' ')}</span></div>
                    <div><strong>Priority:</strong> <span class="badge badge-${getPriorityClass(data.priority)}">${data.priority}</span></div>
                    <div><strong>Due Date:</strong> ${data.due_date ? formatDate(data.due_date) : 'No deadline'}</div>
                </div>
            `;
            
            $('#calendarTaskDetails').html(html);
            $('#calendarTaskModal').modal('show');
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
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
    }
});
</script>
@endsection
