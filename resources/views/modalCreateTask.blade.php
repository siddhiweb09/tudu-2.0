<div class="modal fade" id="add-task" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-5">
            <div class="modal-header bg-animated-gradient text-white py-4">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-white-10 me-3">
                        <i class="ti ti-playlist-add fs-1 text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="assignTaskModalLabel">CREATE NEW TASK</h5>
                        <p class="text-white-80 mb-0 small">Assign responsibilities to your team</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <!-- Progress Bar -->
                        <div class="pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="step flex-grow-1 text-center position-relative" data-step="1">
                                    <div class="step-number mx-auto rounded-circle d-flex align-items-center justify-content-center fw-semibold bg-primary text-white"
                                        style="width: 32px; height: 32px;">1</div>
                                    <div class="step-title mt-2 small fw-medium">Task Info</div>
                                </div>
                                <div class="step flex-grow-1 text-center position-relative" data-step="2">
                                    <div class="step-number mx-auto rounded-circle d-flex align-items-center justify-content-center fw-semibold bg-primary-light text-secondary"
                                        style="width: 32px; height: 32px;">2</div>
                                    <div class="step-title mt-2 small fw-medium text-muted">Assignment</div>
                                </div>
                                <div class="step flex-grow-1 text-center position-relative" data-step="3">
                                    <div class="step-number mx-auto rounded-circle d-flex align-items-center justify-content-center fw-semibold bg-primary-light text-secondary"
                                        style="width: 32px; height: 32px;">3</div>
                                    <div class="step-title mt-2 small fw-medium text-muted">Attachments</div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Sections -->
                        <form id="create-form" method="POST" enctype="multipart/form-data" class="pt-3 px-0">
                            @csrf
                            <!-- Step 1: Task Info -->
                            <div class="form-step active" data-step="1">
                                <h2 class="h5 fw-semibold mb-4">Task Information</h2>

                                <div class="form-floating mb-4">
                                    <input name="task_title" type="text" class="form-control floating-input"
                                        id="taskTitleInput" placeholder=" " required>
                                    <label for="taskTitleInput" class="floating-label">
                                        <i class="ti ti-heading me-2"></i>Task Title
                                    </label>
                                    <div class="focus-line"></div>
                                </div>

                                <div class="form-floating mb-4">
                                    <textarea name="task_description" class="form-control summernote" id="taskDescInput"
                                        placeholder=" " style="height: 100px" required></textarea>
                                    <!-- <label for="taskDescInput" class="floating-label">
                                        <i class="ti ti-file-description me-2"></i>Task Description
                                    </label>
                                    <div class="focus-line"></div> -->
                                </div>

                                <div class="task-container">
                                    <!-- Initial Task Field -->
                                    <div class="task-item mb-3" data-task-id="1">
                                        <div class="input-group">
                                            <input type="text" class="form-control task-input" name="tasks[]"
                                                placeholder="Enter task" required>
                                            <button type="button"
                                                class="btn btn-inverse-primary add-task-btn">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Priority Pills -->
                                <div class="mb-4">
                                    <label class="d-block text-uppercase small fw-bold text-muted mb-3">
                                        <i class="ti ti-bolt me-2"></i>Priority Level
                                    </label>
                                    <div class="priority-pills">
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check" name="btnradio" id="high" value="High"
                                                autocomplete="off" checked>
                                            <label class="btn btn-check-inverse btn-inverse-danger" for="high">
                                                <i class="ti ti-flame me-1"></i>High
                                            </label>

                                            <input type="radio" class="btn-check" name="btnradio" id="medium"
                                                value="Medium" autocomplete="off">
                                            <label class="btn btn-check-inverse btn-inverse-warning" for="medium">
                                                <i class="ti ti-sun-high me-1"></i>Medium
                                            </label>

                                            <input type="radio" class="btn-check" name="btnradio" id="low" value="Low"
                                                autocomplete="off">
                                            <label class="btn btn-check-inverse btn-inverse-success" for="low">
                                                <i class="ti ti-leaf me-1"></i>Low
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="select-card active-on-hover p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <!-- Main label (left) -->
                                            <label class="text-uppercase small fw-bold text-muted mb-0">
                                                <i class="ti ti-user-shield me-2"></i>Projects
                                            </label>

                                            <!-- Right top corner: Main switch -->
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_projects"
                                                    role="switch" id="projectSwitchUncheckDefault">
                                                <label class="form-check-label" for="projectSwitchUncheckDefault">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- project Default Note -->
                                        <div class="mt-3" id="default_note">
                                            <p class="m-0 small mt-3 ps-2"><b>Note: </b><span class="note">Choose
                                                    whether this task belongs to an existing project or if you’d like to
                                                    create a new one.</span></p>
                                        </div>

                                        <!-- Dropdown + second switch -->
                                        <div class="mt-3 d-none" id="project_dropdown">
                                            <div class="row align-items-end">
                                                <div class="col-md-8 pe-3"> <!-- Reduced width and added padding -->
                                                    <div id="projectSelectWrapper">
                                                        <label for="projectSelect" class="form-label">Select
                                                            Project</label>
                                                        <select class="form-select border-all" id="projectSelect"
                                                            name="project_name">
                                                            <option selected disabled>Choose a project</option>
                                                            <option value="1">Project Alpha</option>
                                                            <option value="2">Project Beta</option>
                                                        </select>
                                                    </div>

                                                    <div id="projectInputWrapper" class="d-none">
                                                        <label for="newProjectInput" class="form-label">Enter New
                                                            Project Name</label>
                                                        <input type="text" class="form-control" id="newProjectInput"
                                                            name="new_project_name" placeholder="New project name">
                                                    </div>
                                                </div>

                                                <div class="col-md-4"> <!-- Increased width -->
                                                    <label class="form-label d-block"><span
                                                            class="small text-muted">Want to add a new
                                                            project?</span></label>
                                                    <div class="form-check d-flex justify-content-start"
                                                        style="padding-left:2.5rem !important;">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="createProjectSwitch">
                                                    </div>
                                                </div>
                                            </div>
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
                                    <div class="col-md-6 mb-3">
                                        <div class="select-card active-on-hover">
                                            <div class="select-card-header">
                                                <i class="ti ti-tag me-2"></i>Category
                                            </div>
                                            <select id="department" name="category" class="form-control" required>
                                                <option value="">Select Category</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="select-card active-on-hover">
                                            <div class="select-card-header">
                                                <i class="ti ti-users me-2"></i>Assign To
                                            </div>
                                            <select id="assign_to" name="assign_to" class="form-control" required>
                                                <option value="">Select User</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="select-card active-on-hover">
                                            <div class="select-card-header">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <i class="ti ti-clock me-2"></i>Due Date
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check form-switch float-end">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="is_recurring" role="switch"
                                                                id="flexSwitchCheckDefault">
                                                            <label class="form-check-label"
                                                                for="flexSwitchCheckDefault">Recurrence</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="frequency_section_form" class="p-3">
                                                <div class="datetime-picker-container" id="due_date_section">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-transparent border-end-0">
                                                            <i class="ti ti-calendar-time"></i>
                                                        </span>
                                                        <input type="datetime-local" class="form-control border-start-0"
                                                            id="due_date" name="due_date"
                                                            placeholder="Select Date & Time">
                                                    </div>
                                                </div>

                                                <div id="frequency_section" class="d-none">
                                                    <select class="form-control border-bottom" id="frequency"
                                                        name="frequency">
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
                                                <div id="additional_fields"
                                                    class="p-4 bg-light border-top d-none">
                                                    <div id="weekly_days" class="frequency-option mb-3 d-none">
                                                        <label class="small text-muted">Weekly on:</label>
                                                        <div class="day-picker">
                                                            <input type="checkbox" id="sunday"
                                                                name="frequency_duration[]" value="Sunday"
                                                                class="day-checkbox">
                                                            <label for="sunday" class="day-label">S</label>

                                                            <input type="checkbox" id="monday"
                                                                name="frequency_duration[]" value="Monday"
                                                                class="day-checkbox">
                                                            <label for="monday" class="day-label">M</label>

                                                            <input type="checkbox" id="tuesday"
                                                                name="frequency_duration[]" value="Tuesday"
                                                                class="day-checkbox">
                                                            <label for="tuesday" class="day-label">T</label>

                                                            <input type="checkbox" id="wednesday"
                                                                name="frequency_duration[]" value="Wednesday"
                                                                class="day-checkbox">
                                                            <label for="wednesday" class="day-label">W</label>

                                                            <input type="checkbox" id="thursday"
                                                                name="frequency_duration[]" value="Thursday"
                                                                class="day-checkbox">
                                                            <label for="thursday" class="day-label">T</label>

                                                            <input type="checkbox" id="friday"
                                                                name="frequency_duration[]" value="Friday"
                                                                class="day-checkbox">
                                                            <label for="friday" class="day-label">F</label>

                                                            <input type="checkbox" id="saturday"
                                                                name="frequency_duration[]" value="Saturday"
                                                                class="day-checkbox">
                                                            <label for="saturday" class="day-label">S</label>
                                                        </div>
                                                    </div>

                                                    <div id="monthly_date" class="frequency-option d-none">
                                                        <label for="monthly_day">Enter Day of Month:</label>
                                                        <input type="number" class="form-control" id="monthly_day"
                                                            name="frequency_duration[]" min="1" max="31"
                                                            placeholder="31">
                                                    </div>

                                                    <div id="yearly_date" class="frequency-option d-none">
                                                        <label for="yearly_date_input">Select Date:</label>
                                                        <input type="date" class="form-control"
                                                            id="yearly_date_input" name="frequency_duration[]"
                                                            placeholder="Select Date">
                                                    </div>

                                                    <div id="periodic_frequency" class="frequency-option d-none">
                                                        <label for="periodic_interval">Interval (in
                                                            frequency_duration):</label>
                                                        <input type="number" class="form-control"
                                                            id="periodic_interval" name="frequency_duration[]"
                                                            min="1" placeholder="Interval Count of Days">
                                                    </div>

                                                    <div id="custom_frequency" class="frequency-option d-none">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label
                                                                    for="custom_frequency_dropdown">Frequency:</label>
                                                                <select class="form-control"
                                                                    id="custom_frequency_dropdown"
                                                                    name="custom_frequency_dropdown">
                                                                    <option value="">Select Frequency</option>
                                                                    <option value="Month">Month</option>
                                                                    <option value="Week">Week</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="occurs_every_dropdown">Occurs
                                                                    Every:</label>
                                                                <input type="number" class="form-control"
                                                                    id="occurs_every_dropdown"
                                                                    name="frequency_duration[]" min="1"
                                                                    placeholder="Interval Count of Week/Month">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <p class="m-0 small mt-3"><b>Note: </b><span
                                                            class="note"></span></p>
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

                                <!-- Voice Recording Section -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="ti ti-microphone-alt"></i>Voice Notes</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="recordedNotesList" class="mb-3"></div>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-sm btn-primary" id="startButton">
                                                <i class="ti ti-microphone"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary" id="stopButton"
                                                disabled>
                                                <i class="ti ti-player-stop"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" id="cancelButton"
                                                disabled>
                                                <i class="ti ti-cancel"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" name="voice_notes" id="voiceNotesInput">
                                    </div>
                                </div>

                                <!-- Document Upload Section -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="ti ti-cloud-upload"></i>Documents</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="documentsContainer">
                                            <div class="document-item mb-3">
                                                <div class="input-group">
                                                    <input type="file" name="documents[]"
                                                        class="form-control document-file" required>
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

                                <!-- Links Section -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="ti ti-link mr-2"></i>Links</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="linksContainer">
                                            <div class="link-item mb-2">
                                                <div class="input-group">
                                                    <input type="url" class="form-control link-input"
                                                        placeholder="https://example.com">
                                                    <button type="button" class="btn btn-outline-danger remove-link">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary mt-2" id="addMoreLinks">
                                            <i class="ti ti-plus mr-2"></i> Add Link
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
                                                    <input class="form-check-input" type="checkbox" value="Email"
                                                        name="reminders[]" id="flexCheckChecked1" checked>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        Email
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="WhatsApp"
                                                        name="reminders[]" id="flexCheckChecked2" checked>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        WhatsApp
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="Telegram"
                                                        name="reminders[]" id="flexCheckChecked3" checked>
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
                                <input type="hidden" name="tasks_json" id="tasksInput">
                                <input type="hidden" name="links_json" id="linksInput">
                                <input type="hidden" name="reminders_json" id="remindersInput">
                                <input type="hidden" name="frequency_duration_json" id="frequencyDurationInput">
                                <input type="hidden" name="final_project_name" id="finalProjectName">

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