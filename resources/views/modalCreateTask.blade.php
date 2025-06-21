    <div class="modal fade" id="assign_task" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-5">
                <!-- Animated Gradient Header -->
                <div class="modal-header bg-animated-gradient text-white py-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-white-10 me-3">
                            <i class="fas fa-tasks fa-lg text-white"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="assignTaskModalLabel">CREATE NEW TASK</h5>
                            <p class="text-white-80 mb-0 small">Assign responsibilities to your team</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="form1" class="forms-sample" enctype="multipart/form-data">
                    <div class="modal-body p-5">
                        <input type="hidden" name="task_activity" value="assign">


                        <!-- Floating Label Inputs -->
                        <div class="form-floating mb-4">
                            <input name="task_title" type="text" class="form-control floating-input" id="taskTitleInput" placeholder=" " required>
                            <label for="taskTitleInput" class="floating-label">
                                <i class="fas fa-heading me-2"></i>Task Title
                            </label>
                            <div class="focus-line"></div>
                        </div>

                        <div class="form-floating mb-4">
                            <textarea name="task_description" class="form-control floating-input h-100px" id="taskDescInput" placeholder=" " required></textarea>
                            <label for="taskDescInput" class="floating-label">
                                <i class="fas fa-align-left me-2"></i>Task Description
                            </label>
                            <div class="focus-line"></div>
                        </div>

                        <!-- Dual Select Cards -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="select-card active-on-hover">
                                    <div class="select-card-header">
                                        <i class="fas fa-tag me-2"></i>Category
                                    </div>
                                    <select name="category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <option value="HR">HR</option>
                                        <option value="IT">IT</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Finance">Finance</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="select-card active-on-hover">
                                    <div class="select-card-header">
                                        <i class="fas fa-user-friends me-2"></i>Assign To
                                    </div>
                                    <select name="assign_to" class="form-control" required>
                                        <option value="">Select User</option>
                                        <option value="1">John Doe</option>
                                        <option value="2">Jane Smith</option>
                                        <option value="3">Mike Johnson</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Priority Pills -->
                        <div class="mb-4">
                            <label class="d-block text-uppercase small fw-bold text-muted mb-3">
                                <i class="fas fa-bolt me-2"></i>Priority Level
                            </label>
                            <div class="priority-pills">
                                <input type="radio" name="priority" id="priorityHigh" value="High" class="priority-input" required checked>
                                <label for="priorityHigh" class="priority-pill priority-high">
                                    <i class="fas fa-fire me-2"></i>High
                                    <span class="priority-pill-bg"></span>
                                </label>

                                <input type="radio" name="priority" id="priorityMedium" value="Medium" class="priority-input">
                                <label for="priorityMedium" class="priority-pill priority-medium">
                                    <i class="fas fa-tachometer-alt me-2"></i>Medium
                                    <span class="priority-pill-bg"></span>
                                </label>

                                <input type="radio" name="priority" id="priorityLow" value="Low" class="priority-input">
                                <label for="priorityLow" class="priority-pill priority-low">
                                    <i class="fas fa-leaf me-2"></i>Low
                                    <span class="priority-pill-bg"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons Floating Panel -->
                        <div class="action-panel mb-4">
                            <div class="action-panel-inner">
                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#recordingVoiceModal" data-form-id="form1" data-tooltip="Voice Note">
                                    <i class="fas fa-microphone-alt"></i>
                                </button>
                                <input type="hidden" class="voice-notes-input" name="voice_notes" data-form-id="form1">

                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal" data-form-id="form1" data-tooltip="Upload File">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </button>
                                <input type="hidden" class="uploaded-files-input" name="uploaded_files" data-form-id="form1">

                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#addLinkModal" data-form-id="form1" data-tooltip="Add Link">
                                    <i class="fas fa-link"></i>
                                </button>
                                <input type="hidden" class="links-array-input" name="links" data-form-id="form1">

                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#reminderModal" data-form-id="form1" data-tooltip="Set Reminder">
                                    <i class="fas fa-bell"></i>
                                </button>
                                <input type="hidden" class="reminder-times-input" name="reminder_times" data-form-id="form1">
                                <input type="hidden" class="reminder-methods-input" name="reminder_methods" data-form-id="form1">
                            </div>
                        </div>

                        <!-- Date & Recurrence Section -->
                        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <!-- Due Date -->
                                    <div class="col-md-6 p-4 border-end">
                                        <label class="d-block text-uppercase small fw-bold text-muted mb-3">
                                            <i class="far fa-calendar-alt me-2"></i>Due Date
                                        </label>
                                        <div class="datetime-picker-container" id="due_date_section_form1">
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-end-0">
                                                    <i class="far fa-clock"></i>
                                                </span>
                                                <input type="datetime-local" class="form-control border-start-0" id="due_date_form1" name="due_date" placeholder="Select Date & Time">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Recurrence Toggle -->
                                    <div class="col-md-6 p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <label class="d-block text-uppercase small fw-bold text-muted mb-0">
                                                <i class="fas fa-redo me-2"></i>Recurrence
                                            </label>
                                            <div class="custom-switch-advanced">
                                                <input type="checkbox" class="custom-switch-input" id="repeat_task_form1" name="repeat_task" value="1">
                                                <label for="repeat_task_form1" class="custom-switch-label"></label>
                                            </div>
                                        </div>

                                        <div id="frequency_section_form1" style="display: none;">
                                            <select class="form-control" id="frequency_form1" name="frequency">
                                                <option value="">Select Frequency</option>
                                                <option value="Daily">Daily</option>
                                                <option value="Weekly">Weekly</option>
                                                <option value="Monthly">Monthly</option>
                                                <option value="Yearly">Yearly</option>
                                                <option value="Periodic">Periodic</option>
                                                <option value="Custom">Custom</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recurrence Options (Hidden) -->
                                <div id="additional_fields_form1" class="p-4 bg-light border-top" style="display: none;">
                                    <!-- Daily -->
                                    <div id="daily_time_form1" class="frequency-option" style="display: none;">
                                        <div class="mb-3">
                                            <label class="small text-muted">Daily at:</label>
                                            <input type="time" class="form-control" id="daily_time_input_form1" name="daily_time">
                                        </div>
                                    </div>

                                    <!-- Weekly -->
                                    <div id="weekly_days_form1" class="frequency-option" style="display: none;">
                                        <div class="mb-3">
                                            <label class="small text-muted">Weekly on:</label>
                                            <div class="day-picker">
                                                <input type="checkbox" id="sunday_form1" name="days[]" value="Sunday" class="day-checkbox">
                                                <label for="sunday_form1" class="day-label">S</label>

                                                <input type="checkbox" id="monday_form1" name="days[]" value="Monday" class="day-checkbox">
                                                <label for="monday_form1" class="day-label">M</label>

                                                <input type="checkbox" id="tuesday_form1" name="days[]" value="Tuesday" class="day-checkbox">
                                                <label for="tuesday_form1" class="day-label">T</label>

                                                <input type="checkbox" id="wednesday_form1" name="days[]" value="Wednesday" class="day-checkbox">
                                                <label for="wednesday_form1" class="day-label">W</label>

                                                <input type="checkbox" id="thursday_form1" name="days[]" value="Thursday" class="day-checkbox">
                                                <label for="thursday_form1" class="day-label">T</label>

                                                <input type="checkbox" id="friday_form1" name="days[]" value="Friday" class="day-checkbox">
                                                <label for="friday_form1" class="day-label">F</label>

                                                <input type="checkbox" id="saturday_form1" name="days[]" value="Saturday" class="day-checkbox">
                                                <label for="saturday_form1" class="day-label">S</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small text-muted">At time:</label>
                                            <input type="time" class="form-control" id="weekly_time_input_form1" name="weekly_time">
                                        </div>
                                    </div>

                                    <div id="monthly_date_form1" class="frequency-option" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="monthly_day_form1">Select Day of Month:</label>
                                                <input type="number" class="form-control" id="monthly_day_form1" name="monthly_day" min="1" max="31" placeholder="Select Day">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="monthly_time_input_form1">Select Time:</label>
                                                <input type="time" class="form-control" id="monthly_time_input_form1" name="monthly_time" placeholder="Select Time">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="yearly_date_form1" class="frequency-option" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="yearly_date_input_form1">Select Date:</label>
                                                <input type="date" class="form-control" id="yearly_date_input_form1" name="yearly_date" placeholder="Select Date">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="yearly_time_input_form1">Select Time:</label>
                                                <input type="time" class="form-control" id="yearly_time_input_form1" name="yearly_time" placeholder="Select Time">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="periodic_frequency_form1" class="frequency-option" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="periodic_interval_form1">Interval (in days):</label>
                                                <input type="number" class="form-control" id="periodic_interval_form1" name="periodic_interval" min="1" placeholder="Interval (in days)">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="periodic_time_input_form1">Select Time:</label>
                                                <input type="time" class="form-control" id="periodic_time_input_form1" name="periodic_time" placeholder="Select Time">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="custom_frequency_form1" class="frequency-option" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="custom_frequency_dropdown_form1">Frequency</label>
                                                <select class="form-control" id="custom_frequency_dropdown_form1" name="custom_frequency_dropdown">
                                                    <option value="">Select Frequency</option>
                                                    <option value="Month">Month</option>
                                                    <option value="Week">Week</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="occurs_every_dropdown_form1">Occurs Every</label>
                                                <select class="form-control" id="occurs_every_dropdown_form1" name="occurs_every_dropdown">
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="occurs_on_dropdown_form1">Occurs On</label>
                                                <select class="form-control" id="occurs_on_dropdown_form1" name="occurs_on_dropdown">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer with Glow Effect -->
                    <div class="modal-footer bg-light border-top-0 pt-3 pb-4 px-5">
                        <button type="button" class="btn btn-light btn-float" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-float btn-glow" name="submit">
                            <i class="fas fa-check-circle me-2"></i>Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>