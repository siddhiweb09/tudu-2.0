<!-- Task Detail Modal -->
<div class="modal fade" id="taskDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Task Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="task-header mb-4">
                            <h3 id="detail-title" class="text-primary"></h3>
                            <div id="detail-description" class="lead mb-3"></div>
                        </div>

                        <div class="task-meta card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="meta-item mb-3">
                                            <strong><i class="ti-check-box me-2"></i>Status:</strong>
                                            <span id="detail-status" class="badge ms-2"></span>
                                        </div>
                                        <div class="meta-item mb-3">
                                            <strong><i class="ti-alert me-2"></i>Priority:</strong>
                                            <span id="detail-priority" class="badge ms-2"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="meta-item mb-3">
                                            <strong><i class="ti-calendar me-2"></i>Due Date:</strong>
                                            <span id="detail-due-date" class="ms-2"></span>
                                        </div>
                                        <div class="meta-item mb-3">
                                            <strong><i class="ti-tag me-2"></i>Category:</strong>
                                            <span id="detail-category" class="badge text-white ms-2"></span>
                                        </div>
                                    </div>
                                </div>
                                <div data-habit-section style="display: none;">
                                    <hr>
                                    <div class="meta-item">
                                        <strong><i class="ti-reload me-2"></i>Habit Frequency:</strong>
                                        <span id="detail-habit-frequency" class="ms-2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="notes-section card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="ti-notepad me-2"></i>Notes</h5>
                            </div>
                            <div class="card-body">
                                <div id="detail-notes" class="notes-container"></div>

                                <form id="addNoteForm" class="mt-4">
                                    <input type="hidden" name="task_id" id="task-id">
                                    <div class="mb-3">
                                        <textarea class="form-control" name="note" placeholder="Add a note or reflection..." rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti-plus me-1"></i> Add Note
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="documents-section card mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="ti-file me-2"></i>Documents</h5>
                            </div>
                            <div class="card-body">
                                <div id="detail-documents" class="documents-container"></div>

                                <form id="uploadDocumentForm" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="task_id" id="document-task-id">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="documentFile" class="form-label">Choose file:</label>
                                        <input type="file" class="form-control" id="documentFile" name="document" required>
                                        <div class="form-text">Max 10MB (images, PDF, docs)</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti-upload me-1"></i> Upload Document
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="task-actions sticky-top" style="top: 20px;">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="ti-settings me-2"></i>Task Actions</h5>
                                </div>
                                <div class="card-body">
                                    <button class="btn btn-warning w-100 mb-3 edit-task" id="modalEditTaskBtn">
                                        <i class="ti-pencil me-1"></i> Edit Task
                                    </button>
                                    <button class="btn btn-danger w-100 mb-3 delete-task" id="modalDeleteTaskBtn">
                                        <i class="ti-trash me-1"></i> Delete Task
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editTaskForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_task_id" name="task_id">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Task Title*</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_category" class="form-label">Category</label>
                                <select class="form-select" id="edit_category" name="category">
                                    <option value="">Select a category</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                </select>
                            </div>

                            <div class="mb-3 new-category-group" style="display: none;">
                                <label for="edit_new_category_name" class="form-label">New Category Name</label>
                                <input type="text" class="form-control" id="edit_new_category_name" name="new_category_name">
                                <label for="edit_new_category_color" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="edit_new_category_color" name="new_category_color" value="#3f51b5">
                            </div>

                            <div class="mb-3">
                                <label for="edit_due_date" class="form-label">Due Date</label>
                                <input type="datetime-local" class="form-control" id="edit_due_date" name="due_date">
                            </div>

                            <div class="mb-3">
                                <label for="edit_priority" class="form-label">Priority</label>
                                <select class="form-select" id="edit_priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_time_estimate" class="form-label">Time Estimate (minutes)</label>
                                <input type="number" class="form-control" id="edit_time_estimate" name="time_estimate" min="0" step="5">
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="edit_is_habit" name="is_habit">
                                <label class="form-check-label" for="edit_is_habit">This is a habit/recurring task</label>
                            </div>

                            <div class="mb-3 edit-habit-frequency" style="display: none;">
                                <label for="edit_habit_frequency" class="form-label">Habit Frequency</label>
                                <select class="form-select" id="edit_habit_frequency" name="habit_frequency">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="weekdays">Weekdays Only</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_task" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-5">
            <div class="modal-header bg-animated-gradient text-white py-4">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-white-10 me-3">
                        <i class="ti ti-playlist-add fs-1 text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="assignTaskModalLabel">CREATE NEW PERSONAL TASK</h5>
                        <p class="text-white-80 mb-0 small">Manager personal tasks.</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <!-- Progress Bar -->
                        <div class="pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="step flex-grow-1 text-center position-relative" data-step="1">
                                    <div class="step-number mx-auto rounded-circle d-flex align-items-center justify-content-center fw-semibold bg-primary text-white" style="width: 32px; height: 32px;">1</div>
                                    <div class="step-title mt-2 small fw-medium">Task Info</div>
                                </div>
                                <div class="step flex-grow-1 text-center position-relative" data-step="2">
                                    <div class="step-number mx-auto rounded-circle d-flex align-items-center justify-content-center fw-semibold bg-primary-light text-secondary" style="width: 32px; height: 32px;">2</div>
                                    <div class="step-title mt-2 small fw-medium text-muted">Assignment</div>
                                </div>
                                <div class="step flex-grow-1 text-center position-relative" data-step="3">
                                    <div class="step-number mx-auto rounded-circle d-flex align-items-center justify-content-center fw-semibold bg-primary-light text-secondary" style="width: 32px; height: 32px;">3</div>
                                    <div class="step-title mt-2 small fw-medium text-muted">Attachments</div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Sections -->
                        <form id="taskForm" method="POST" enctype="multipart/form-data" class="pt-3 px-0">
                            @csrf

                            <!-- Step 1: Task Info -->
                            <div class="form-step active" data-step="1">
                                <h2 class="h5 fw-semibold mb-4">Task Information</h2>

                                <div class="form-floating mb-4">
                                    <input name="task_title" type="text" class="form-control floating-input" id="taskTitleInput" placeholder=" " required>
                                    <label for="taskTitleInput" class="floating-label">
                                        <i class="ti ti-heading me-2"></i>Task Title
                                    </label>
                                    <div class="focus-line"></div>
                                </div>

                                <div class="form-floating mb-4">
                                    <textarea name="task_description" class="form-control floating-input" id="taskDescInput" placeholder=" " style="height: 100px" required></textarea>
                                    <label for="taskDescInput" class="floating-label">
                                        <i class="ti ti-file-description me-2"></i>Task Description
                                    </label>
                                    <div class="focus-line"></div>
                                </div>


                                <!-- Priority Pills -->
                                <div class="mb-4">
                                    <label class="d-block text-uppercase small fw-bold text-muted mb-3">
                                        <i class="ti ti-bolt me-2"></i>Priority Level
                                    </label>
                                    <div class="priority-pills">
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check" name="btnradio" id="high" value="high" autocomplete="off" checked>
                                            <label class="btn btn-check-inverse btn-inverse-danger" for="high">
                                                <i class="ti ti-flame me-1"></i>High
                                            </label>

                                            <input type="radio" class="btn-check" name="btnradio" id="medium" value="medium" autocomplete="off">
                                            <label class="btn btn-check-inverse btn-inverse-warning" for="medium">
                                                <i class="ti ti-sun-high me-1"></i>Medium
                                            </label>

                                            <input type="radio" class="btn-check" name="btnradio" id="low" value="low" autocomplete="off">
                                            <label class="btn btn-check-inverse btn-inverse-success" for="low">
                                                <i class="ti ti-leaf me-1"></i>Low
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="next-step btn btn-primary">
                                        Next: Assignment
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Assignment & Repetition -->
                            <div class="form-step hidden" data-step="2">
                                <h2 class="h5 fw-semibold mb-4">Assignment & Repetition</h2>

                                <div class="row mb-4">

                                    <div class="col-md-12 mb-3">
                                        <div class="select-card active-on-hover">
                                            <div class="select-card-header">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <i class="ti ti-clock me-2"></i>Due Date
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check form-switch float-end">
                                                            <input class="form-check-input" type="checkbox" name="is_recurring" role="switch" id="flexSwitchCheckDefault">
                                                            <label class="form-check-label" for="flexSwitchCheckDefault">Recurrence</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="frequency_section_form" class="p-3">
                                                <div class="datetime-picker-container" id="due_date_section_form1">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-transparent border-end-0">
                                                            <i class="ti ti-calendar-time"></i>
                                                        </span>
                                                        <input type="datetime-local" class="form-control border-start-0" id="due_date_form1" name="due_date" placeholder="Select Date & Time">
                                                    </div>
                                                </div>

                                                <div id="frequency_section" class="d-none">
                                                    <select class="form-control border-bottom" id="frequency_form1" name="frequency">
                                                        <option value="">Select Frequency</option>
                                                        <option value="Daily">Daily</option>
                                                        <option value="Weekly">Weekly</option>
                                                        <option value="Monthly">Monthly</option>
                                                        <option value="Yearly">Yearly</option>
                                                        <option value="Periodic">Periodic</option>
                                                        <option value="Custom">Custom</option>
                                                    </select>
                                                </div>

                                                <!-- Recurrence Options (Hidden) -->
                                                <div id="additional_fields_form1" class="p-4 bg-light border-top d-none">
                                                    <div id="weekly_days_form1" class="frequency-option mb-3 d-none">
                                                        <label class="small text-muted">Weekly on:</label>
                                                        <div class="day-picker">
                                                            <input type="checkbox" id="sunday_form1" name="frequency_duration[]" value="Sunday" class="day-checkbox">
                                                            <label for="sunday_form1" class="day-label">S</label>

                                                            <input type="checkbox" id="monday_form1" name="frequency_duration[]" value="Monday" class="day-checkbox">
                                                            <label for="monday_form1" class="day-label">M</label>

                                                            <input type="checkbox" id="tuesday_form1" name="frequency_duration[]" value="Tuesday" class="day-checkbox">
                                                            <label for="tuesday_form1" class="day-label">T</label>

                                                            <input type="checkbox" id="wednesday_form1" name="frequency_duration[]" value="Wednesday" class="day-checkbox">
                                                            <label for="wednesday_form1" class="day-label">W</label>

                                                            <input type="checkbox" id="thursday_form1" name="frequency_duration[]" value="Thursday" class="day-checkbox">
                                                            <label for="thursday_form1" class="day-label">T</label>

                                                            <input type="checkbox" id="friday_form1" name="frequency_duration[]" value="Friday" class="day-checkbox">
                                                            <label for="friday_form1" class="day-label">F</label>

                                                            <input type="checkbox" id="saturday_form1" name="frequency_duration[]" value="Saturday" class="day-checkbox">
                                                            <label for="saturday_form1" class="day-label">S</label>
                                                        </div>
                                                    </div>

                                                    <div id="monthly_date_form1" class="frequency-option d-none">
                                                        <label for="monthly_day_form1">Enter Day of Month:</label>
                                                        <input type="number" class="form-control" id="monthly_day_form1" name="frequency_duration[]" min="1" max="31" placeholder="31">
                                                    </div>

                                                    <div id="yearly_date_form1" class="frequency-option d-none">
                                                        <label for="yearly_date_input_form1">Select Date:</label>
                                                        <input type="date" class="form-control" id="yearly_date_input_form1" name="frequency_duration[]" placeholder="Select Date">
                                                    </div>

                                                    <div id="periodic_frequency_form1" class="frequency-option d-none">
                                                        <label for="periodic_interval_form1">Interval (in frequency_duration):</label>
                                                        <input type="number" class="form-control" id="periodic_interval_form1" name="frequency_duration[]" min="1" placeholder="Interval Count of Days">
                                                    </div>

                                                    <div id="custom_frequency_form1" class="frequency-option d-none">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="custom_frequency_dropdown_form1">Frequency:</label>
                                                                <select class="form-control" id="custom_frequency_dropdown_form1" name="custom_frequency_dropdown">
                                                                    <option value="">Select Frequency</option>
                                                                    <option value="Month">Month</option>
                                                                    <option value="Week">Week</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="occurs_every_dropdown_form1">Occurs Every:</label>
                                                                <input type="number" class="form-control" id="occurs_every_dropdown_form1" name="frequency_duration[]" min="1" placeholder="Interval Count of Week/Month">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <p class="m-0 small mt-3"><b>Note: </b><span class="note"></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="prev-step btn btn-outline-secondary">
                                        Back
                                    </button>
                                    <button type="button" class="next-step btn btn-primary">
                                        Next: Attachments
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Attachments & Links -->
                            <div class="form-step hidden" data-step="3">
                                <h2 class="h5 fw-semibold mb-4">Attachments & Links</h2>



                                <!-- Document Upload Section -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="ti ti-cloud-upload"></i>Documents</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="documentsContainer">
                                            <div class="document-item mb-3">
                                                <div class="input-group">
                                                    <input type="file" name="documents[]" class="form-control document-file" required>
                                                    <button type="button" class="btn btn-outline-danger remove-link">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary" id="addDocument">
                                            <i class="ti ti-plus mr-2"></i> Add Document
                                        </button>
                                    </div>
                                </div>


                                <!-- Reminders Section -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="ti ti-bell mr-2"></i>Reminders</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="remindersContainer">
                                            <div class="reminder-item mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="Email" name="reminders[]" id="flexCheckChecked1" checked>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        Email
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="WhatsApp" name="reminders[]" id="flexCheckChecked2" checked>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        WhatsApp
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="Telegram" name="reminders[]" id="flexCheckChecked3" checked>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        Telegram
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add these hidden inputs at the top of your form -->
                                <input type="hidden" name="priority" id="priorityInput">
                                <input type="hidden" name="reminders_json" id="remindersInput">

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="prev-step btn btn-outline-secondary">
                                        Back
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Create Task
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer d-none"></div>
        </div>
    </div>
</div>