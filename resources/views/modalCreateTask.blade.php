<div class="modal fade" id="assign_task" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
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
                        <form id="taskForm" action="" method="POST" enctype="multipart/form-data" class="pt-3 px-0">
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

                                <div class="task-container">
                                    <!-- Initial Task Field -->
                                    <div class="task-item mb-3" data-task-id="1">
                                        <div class="input-group">
                                            <input type="text" class="form-control task-input me-3" name="tasks[]" placeholder="Enter task" required>
                                            <button type="button" class="btn btn-inverse-primary rounded-circle add-task-btn">
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
                                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                                            <label class="btn btn-check-inverse btn-inverse-danger" for="btnradio1"><i class="ti ti-flame me-1"></i>High</label>

                                            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                                            <label class="btn btn-check-inverse btn-inverse-warning" for="btnradio2"><i class="ti ti-sun-high me-1"></i>Medium</label>

                                            <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                                            <label class="btn btn-check-inverse btn-inverse-success" for="btnradio3"><i class="ti ti-leaf me-1"></i>Low</label>
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

                                    <div class="col-md-6 mb-3">
                                        <div class="select-card active-on-hover">
                                            <div class="select-card-header">
                                                <i class="ti ti-users me-2"></i>Assign To
                                            </div>
                                            <select name="assign_to" class="form-control" required>
                                                <option value="">Select User</option>
                                                <option value="1">John Doe</option>
                                                <option value="2">Jane Smith</option>
                                                <option value="3">Mike Johnson</option>
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
                                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                            <label class="form-check-label" for="flexSwitchCheckDefault">Recurrence</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="datetime-picker-container" id="due_date_section_form1">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-transparent border-end-0">
                                                        <i class="far fa-clock"></i>
                                                    </span>
                                                    <input type="datetime-local" class="form-control border-start-0" id="due_date_form1" name="due_date" placeholder="Select Date & Time">
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

                                <!-- Action Buttons Floating Panel -->
                                <div class="action-panel mb-4">
                                    <div class="action-panel-inner">
                                        <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#recordingVoiceModal" data-form-id="form1" data-tooltip="Voice Note">
                                            <i class="ti ti-microphone-alt"></i>
                                        </button>
                                        <input type="hidden" class="voice-notes-input" name="voice_notes" data-form-id="form1">

                                        <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal" data-form-id="form1" data-tooltip="Upload File">
                                            <i class="ti ti-cloud-upload-alt"></i>
                                        </button>
                                        <input type="hidden" class="uploaded-files-input" name="uploaded_files" data-form-id="form1">

                                        <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#addLinkModal" data-form-id="form1" data-tooltip="Add Link">
                                            <i class="ti ti-link"></i>
                                        </button>
                                        <input type="hidden" class="links-array-input" name="links" data-form-id="form1">

                                        <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#reminderModal" data-form-id="form1" data-tooltip="Set Reminder">
                                            <i class="ti ti-bell"></i>
                                        </button>
                                        <input type="hidden" class="reminder-times-input" name="reminder_times" data-form-id="form1">
                                        <input type="hidden" class="reminder-methods-input" name="reminder_methods" data-form-id="form1">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="prev-step btn btn-outline-secondary">
                                        Back
                                    </button>
                                    <button type="submit" class="btn btn-success">
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