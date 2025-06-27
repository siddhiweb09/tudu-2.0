$(document).ready(function() {
    // Determine task type based on current page
    const path = window.location.pathname;
    let taskType = 'pending';
    
    if (path.includes('in-process')) taskType = 'in-process';
    else if (path.includes('in-review')) taskType = 'in-review';
    else if (path.includes('overdue')) taskType = 'overdue';
    else if (path.includes('all')) taskType = 'all';

    function loadTasks() {
        $.ajax({
            url: `/tasks/${taskType}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                // Show loading state
                $('.kanban-cards').html(`
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-hourglass-split fs-4"></i>
                        <p class="mb-0">Loading tasks...</p>
                    </div>
                `);
            },
            success: function(response) {
                // Clear all columns first
                $('.kanban-cards').html('');
                
                // Process each priority level
                ['High', 'Medium', 'Low'].forEach(priority => {
                    const priorityLower = priority.toLowerCase();
                    const tasks = response.tasksByPriority[priority] || [];
                    const column = $(`#${priorityLower}-column`);
                    
                    // Update count badge
                    $(`#${priorityLower}-count`).text(tasks.length);
                    
                    if (tasks.length === 0) {
                        column.html(`
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-4"></i>
                                <p class="mb-0">No ${priority} priority tasks</p>
                            </div>
                        `);
                    } else {
                        tasks.forEach(task => {
                            const priorityColor = priority === 'High' ? '#1520a6' : 
                                             (priority === 'Medium' ? '#ffc107' : '#198754');
                            
                            // Format frequency/due date
                            let frequencyText = '';
                            if (task.frequency === 'Daily') {
                                frequencyText = 'Daily';
                            } else if (task.frequency === 'Weekly') {
                                const days = task.frequency_duration ? JSON.parse(task.frequency_duration) : [];
                                frequencyText = 'Weekly on: ' + days.join(', ');
                            } else if (task.frequency === 'Monthly') {
                                const day = task.frequency_duration ? JSON.parse(task.frequency_duration)[0] : '1';
                                frequencyText = 'Monthly on day ' + day;
                            } else if (task.frequency === 'Yearly' && task.due_date) {
                                frequencyText = 'Yearly on ' + new Date(task.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                            } else if (task.frequency === 'Periodically') {
                                const days = task.frequency_duration ? JSON.parse(task.frequency_duration)[0] : '1';
                                frequencyText = 'Every ' + days + ' days';
                            } else if (task.due_date) {
                                frequencyText = 'Due ' + new Date(task.due_date).toLocaleDateString('en-US', { 
                                    year: 'numeric', 
                                    month: 'short', 
                                    day: 'numeric' 
                                });
                            }
                            
                            const taskUrl = task.flag === 'Main' ? 
                                          `/task/${task.task_id}` : 
                                          `/task/${task.delegate_task_id}`;
                            
                            column.append(`
                                <a href="${taskUrl}" class="text-decoration-none text-dark">
                                    <div class="kanban-card" draggable="true" style="border-left-color: ${priorityColor};">
                                        <div class="kanban-card-header d-flex justify-content-between">
                                            <span>${task.title}</span>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="kanban-card-footer mt-2">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    ${frequencyText}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            `);
                        });
                    }
                });
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
    loadTasks();
    
    // Refresh button click handler
    $('#refresh-tasks').click(loadTasks);
    
    // Auto-refresh every 60 seconds
    setInterval(loadTasks, 60000);
});