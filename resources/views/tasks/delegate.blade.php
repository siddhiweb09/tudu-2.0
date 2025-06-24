@extends('layouts.innerFrame')

@section('main')
    <div class="bg-white">
        <div class="container-xxl p-4">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-11 col-md-12">

                    <div class="card border-1 shadow-5 mt-3">
                        <form id="taskForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header bg-animated-gradient text-white py-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-white-10 me-3">
                                        <i class="ti ti-playlist-add fs-1 text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title fw-bold mb-0" id="taskTitle"></h5>
                                        <input type="hidden" name="task_title" id="taskTitleInput" value="My Task Title">
                                        <p class="text-white-80 mb-0 small">You are delegating this task, originally
                                            assigned to you by a supervisor, to your team for execution.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Sections -->
                            <form id="form2" method="POST" enctype="multipart/form-data" class="pt-3 px-0">
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
                                        <textarea name="task_description" class="form-control floating-input"
                                            id="taskDescInput" placeholder=" " style="height: 100px" required></textarea>
                                        <label for="taskDescInput" class="floating-label">
                                            <i class="ti ti-file-description me-2"></i>Task Description
                                        </label>
                                        <div class="focus-line"></div>
                                    </div>

                                    <div class="task-container">
                                        <!-- Initial Task Field -->
                                        <div class="task-item mb-3" data-task-id="1">
                                            <div class="input-group">
                                                <input type="text" class="form-control task-input me-3" name="tasks[]"
                                                    placeholder="Enter task" required>
                                                <button type="button"
                                                    class="btn btn-inverse-primary rounded-circle add-task-btn">
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
                                                <input type="radio" class="btn-check" name="btnradio" id="high" value="high"
                                                    autocomplete="off" checked>
                                                <label class="btn btn-check-inverse btn-inverse-danger" for="high">
                                                    <i class="ti ti-flame me-1"></i>High
                                                </label>

                                                <input type="radio" class="btn-check" name="btnradio" id="medium"
                                                    value="medium" autocomplete="off">
                                                <label class="btn btn-check-inverse btn-inverse-warning" for="medium">
                                                    <i class="ti ti-sun-high me-1"></i>Medium
                                                </label>

                                                <input type="radio" class="btn-check" name="btnradio" id="low" value="low"
                                                    autocomplete="off">
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
                                        <div class="col-md-6 mb-3">
                                            <div class="select-card active-on-hover">
                                                <div class="select-card-header">
                                                    <i class="ti ti-tag me-2"></i>Category
                                                </div>
                                                <div class="step flex-grow-1 text-center position-relative" data-step="2">
                                                    <div class="step-number mx-auto rounded-circle d-flex align-items-center justify-content-center fw-semibold bg-primary-light text-secondary"
                                                        style="width: 32px; height: 32px;">2</div>
                                                    <div class="step-title mt-2 small fw-medium text-muted">Assignment</div>
                                                </div>
                                                <div class="step flex-grow-1 text-center position-relative" data-step="3">
                                                    <div class="step-number mx-auto rounded-circle d-flex align-items-center justify-content-center fw-semibold bg-primary-light text-secondary"
                                                        style="width: 32px; height: 32px;">3</div>
                                                    <div class="step-title mt-2 small fw-medium text-muted">Attachments
                                                    </div>
                                                </div>
                                                <div id="frequency_section_form" class="p-3">
                                                    <div class="datetime-picker-container" id="due_date_section_form2">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-transparent border-end-0">
                                                                <i class="ti ti-calendar-time"></i>
                                                            </span>
                                                            <input type="datetime-local" class="form-control border-start-0"
                                                                id="due_date_form2" name="due_date"
                                                                placeholder="Select Date & Time">
                                                        </div>
                                                    </div>
                                                </div>

                                                    <div id="frequency_section" class="d-none">
                                                        <select class="form-control border-bottom" id="frequency_form2"
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
                                                </div>

                                                    <!-- Recurrence Options (Hidden) -->
                                                    <div id="additional_fields_form2"
                                                        class="p-4 bg-light border-top d-none">
                                                        <div id="weekly_days_form2" class="frequency-option mb-3 d-none">
                                                            <label class="small text-muted">Weekly on:</label>
                                                            <div class="day-picker">
                                                                <input type="checkbox" id="sunday_form2"
                                                                    name="frequency_duration[]" value="Sunday"
                                                                    class="day-checkbox">
                                                                <label for="sunday_form2" class="day-label">S</label>

                                                                <input type="checkbox" id="monday_form2"
                                                                    name="frequency_duration[]" value="Monday"
                                                                    class="day-checkbox">
                                                                <label for="monday_form2" class="day-label">M</label>

                                                                <input type="checkbox" id="tuesday_form2"
                                                                    name="frequency_duration[]" value="Tuesday"
                                                                    class="day-checkbox">
                                                                <label for="tuesday_form2" class="day-label">T</label>

                                                                <input type="checkbox" id="wednesday_form2"
                                                                    name="frequency_duration[]" value="Wednesday"
                                                                    class="day-checkbox">
                                                                <label for="wednesday_form2" class="day-label">W</label>

                                                                <input type="checkbox" id="thursday_form2"
                                                                    name="frequency_duration[]" value="Thursday"
                                                                    class="day-checkbox">
                                                                <label for="thursday_form2" class="day-label">T</label>

                                                                <input type="checkbox" id="friday_form2"
                                                                    name="frequency_duration[]" value="Friday"
                                                                    class="day-checkbox">
                                                                <label for="friday_form2" class="day-label">F</label>

                                                                <input type="checkbox" id="saturday_form2"
                                                                    name="frequency_duration[]" value="Saturday"
                                                                    class="day-checkbox">
                                                                <label for="saturday_form2" class="day-label">S</label>
                                                            </div>
                                                            <select id="department" name="category" class="form-control"
                                                                required>
                                                                <option value="">Select Category</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                        <div id="monthly_date_form2" class="frequency-option d-none">
                                                            <label for="monthly_day_form2">Enter Day of Month:</label>
                                                            <input type="number" class="form-control" id="monthly_day_form2"
                                                                name="frequency_duration[]" min="1" max="31"
                                                                placeholder="31">
                                                        </div>
                                                    </div>

                                                        <div id="yearly_date_form2" class="frequency-option d-none">
                                                            <label for="yearly_date_input_form2">Select Date:</label>
                                                            <input type="date" class="form-control"
                                                                id="yearly_date_input_form2" name="frequency_duration[]"
                                                                placeholder="Select Date">
                                                        </div>

                                                        <div id="periodic_frequency_form2" class="frequency-option d-none">
                                                            <label for="periodic_interval_form2">Interval (in
                                                                frequency_duration):</label>
                                                            <input type="number" class="form-control"
                                                                id="periodic_interval_form2" name="frequency_duration[]"
                                                                min="1" placeholder="Interval Count of Days">
                                                        </div>

                                                        <div id="custom_frequency_form2" class="frequency-option d-none">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label
                                                                        for="custom_frequency_dropdown_form2">Frequency:</label>
                                                                    <select class="form-control"
                                                                        id="custom_frequency_dropdown_form2"
                                                                        name="custom_frequency_dropdown">
                                                                        <option value="">Select Frequency</option>
                                                                        <option value="Daily">Daily</option>
                                                                        <option value="Weekly">Weekly</option>
                                                                        <option value="Monthly">Monthly</option>
                                                                        <option value="Yearly">Yearly</option>
                                                                        <option value="Periodic">Periodic</option>
                                                                        <option value="Custom">Custom</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="occurs_every_dropdown_form2">Occurs
                                                                        Every:</label>
                                                                    <input type="number" class="form-control"
                                                                        id="occurs_every_dropdown_form2"
                                                                        name="frequency_duration[]" min="1"
                                                                        placeholder="Interval Count of Week/Month">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <div class="select-card active-on-hover">
                                                            <div class="select-card-header">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <i class="ti ti-eye me-2"></i>Task Visibility
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="px-3">
                                                                <div id="task-visibility"
                                                                    class="d-flex flex-wrap gap-3 my-3">
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
                                                        <h6 class="mb-0"><i class="ti ti-microphone-alt"></i>Voice Notes
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="recordedNotesList" class="mb-3"></div>
                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                id="startButton">
                                                                <i class="ti ti-microphone"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-secondary"
                                                                id="stopButton" disabled>
                                                                <i class="ti ti-player-stop"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                id="cancelButton" disabled>
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
                                                                    <button type="button"
                                                                        class="btn btn-outline-danger remove-link">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            id="addDocument">
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
                                                                    <button type="button"
                                                                        class="btn btn-outline-danger remove-link">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-outline-primary mt-2"
                                                            id="addMoreLinks">
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
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="Email" name="reminders[]"
                                                                        id="flexCheckChecked1" checked>
                                                                    <label class="form-check-label" for="flexCheckChecked">
                                                                        Email
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="WhatsApp" name="reminders[]"
                                                                        id="flexCheckChecked2" checked>
                                                                    <label class="form-check-label" for="flexCheckChecked">
                                                                        WhatsApp
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="Telegram" name="reminders[]"
                                                                        id="flexCheckChecked3" checked>
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

                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="prev-step btn btn-outline-secondary">
                                                        Back
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        Create Task
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        $(document).ready(function () {
            let taskId = "{{ $taskId }}";

            $.ajax({
                url: '/get-task-details/' + taskId,
                method: 'GET',
                success: function (response) {
                    $('#taskTitle').text(response.title);
                    $('#taskTitleInput').val(response.title);
                },
                error: function (xhr) {
                    $('#taskTitle').text('Error loading task');
                    console.error('Error:', xhr.responseText);
                }
            });

            $.ajax({
                url: `/get-task-visibility/${taskId}`,
                method: 'GET',
                success: function (users) {
                    const container = $('#task-visibility');
                    container.empty();

                    if (users.length === 0) {
                        container.append(`<div class="text-muted">No assigned users found.</div>`);
                        return;
                    }

                    // Append the Select All checkbox first
                    const selectAllCheckbox = `
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="select_all_visibility">
                                    <label class="form-check-label fw-medium text-primary" for="select_all_visibility">
                                        Select All
                                    </label>
                                </div>
                            `;
                    container.append(selectAllCheckbox);

                    // Append each user checkbox
                    users.forEach(user => {
                        const checkboxItem = `
                                    <div class="form-check mb-2">
                                        <input class="form-check-input user-checkbox" type="checkbox" name="visible_users[]" value="${user}" id="user_${user.replace(/\s+/g, '_')}">
                                        <label class="form-check-label" for="user_${user.replace(/\s+/g, '_')}">
                                            ${user}
                                        </label>
                                    </div>
                                `;
                        container.append(checkboxItem);
                    });

                    // Select All handler
                    $('#select_all_visibility').on('change', function () {
                        $('.user-checkbox').prop('checked', this.checked);
                    });
                },
                error: function (xhr) {
                    console.error("Error fetching visibility users:", xhr.responseText);
                    $('#task-visibility').html('<div class="text-danger">Failed to load visibility users.</div>');
                }
            });


        });
    </script>
@endsection