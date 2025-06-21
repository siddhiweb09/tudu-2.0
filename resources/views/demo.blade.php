<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css" />
   
</head>

<body>
    <div class="demo-container">
        <h1 class="mb-4">Task Management Demo</h1>
        <p class="mb-5">Click the button below to open the task creation modal</p>

        <!-- Button to trigger modal -->
        <button type="button" class="open-modal-btn" data-bs-toggle="modal" data-bs-target="#assign_task">
            <i class="fas fa-plus-circle me-2"></i> Create New Task
        </button>
    </div>

    <!-- Task Creation Modal -->
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

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Add this to your DOMContentLoaded event listener
        document.getElementById('form1').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Get the voice notes and files from hidden inputs
            const voiceNotes = document.querySelector('.voice-notes-input').value;
            const uploadedFiles = document.querySelector('.uploaded-files-input').value;

            // Append them to the form data
            formData.append('voice_notes', voiceNotes);
            formData.append('uploaded_files', uploadedFiles);

            // In a real application, you would send this to your server
            console.log('Form data:', Object.fromEntries(formData));
            alert('Task creation would be submitted here in a real application');

            // Example of what you might do:
            // fetch('dbFiles/assign_task.php', {
            //     method: 'POST',
            //     body: formData
            // })
            // .then(response => response.json())
            // .then(data => {
            //     if (data.success) {
            //         window.location.href = 'all_tasks.php';
            //     } else {
            //         alert('Error: ' + data.message);
            //     }
            // })
            // .catch(error => {
            //     console.error('Error:', error);
            // });
        });

        // Toggle recurrence options
        document.getElementById('repeat_task_form1').addEventListener('change', function() {
            const frequencySection = document.getElementById('frequency_section_form1');
            const additionalFields = document.getElementById('additional_fields_form1');

            if (this.checked) {
                frequencySection.style.display = 'block';
                additionalFields.style.display = 'block';
            } else {
                frequencySection.style.display = 'none';
                additionalFields.style.display = 'none';
            }
        });

        // Show/hide frequency options based on selection
        document.getElementById('frequency_form1')?.addEventListener('change', function() {
            const frequencyOptions = document.querySelectorAll('.frequency-option');
            frequencyOptions.forEach(option => {
                option.style.display = 'none';
            });

            if (this.value) {
                const selectedOption = document.getElementById(`${this.value.toLowerCase()}_time_form1`);
                if (selectedOption) {
                    selectedOption.style.display = 'block';
                }
            }
        });

        // Global state management for all forms
        const formStates = {};

        // Initialize form state
        function initFormState(formId) {
            if (!formStates[formId]) {
                formStates[formId] = {
                    recordings: [],
                    documents: [],
                    links: [],
                    reminders: {
                        times: [],
                        methods: [],
                    },
                };
            }
            return formStates[formId];
        }

        // Get current form ID from modal
        function getCurrentFormId(modalId) {
            const modal = document.getElementById(modalId);
            return modal ? modal.dataset.currentForm : null;
        }

        // Global variables for recording
        let mediaRecorder;
        let audioChunks = [];
        let isRecording = false;
        let currentFormId = null;
        let allRecordings = {};

        // Initialize when DOM is ready
        document.addEventListener("DOMContentLoaded", function() {
            // Set up event listeners for all recording buttons
            document
                .querySelectorAll('[data-target="#recordingVoiceModal"]')
                .forEach((button) => {
                    button.addEventListener("click", function() {
                        currentFormId = this.dataset.formId || "default";
                        initRecordingState(currentFormId);
                    });
                });

            // Recording control buttons
            document
                .getElementById("startButton")
                .addEventListener("click", startRecording);
            document
                .getElementById("stopButton")
                .addEventListener("click", stopRecording);
            document
                .getElementById("cancelButton")
                .addEventListener("click", cancelRecording);
            document
                .getElementById("saveRecording")
                .addEventListener("click", saveRecording);

            // Reset modal when closed
            $("#recordingVoiceModal").on("hidden.bs.modal", function() {
                cleanupRecording();
            });
        });

        // Initialize recording state for a form
        function initRecordingState(formId) {
            if (!allRecordings[formId]) {
                allRecordings[formId] = [];
            }

            // Reset UI
            document.getElementById("recordedNotesList").innerHTML = "";
            document.getElementById("startButton").disabled = false;
            document.getElementById("stopButton").disabled = true;
            document.getElementById("cancelButton").disabled = true;
            document.getElementById("saveRecording").disabled =
                allRecordings[formId].length === 0;

            // Display existing recordings
            allRecordings[formId].forEach((recording, index) => {
                addRecordingPreview(recording, index, formId);
            });
        }

        // Start recording function
        function startRecording() {
            if (!currentFormId) return;

            // Reset chunks
            audioChunks = [];
            isRecording = true;

            navigator.mediaDevices
                .getUserMedia({
                    audio: true,
                })
                .then((stream) => {
                    // Create media recorder
                    mediaRecorder = new MediaRecorder(stream);

                    // Setup data handler
                    mediaRecorder.ondataavailable = (event) => {
                        audioChunks.push(event.data);
                    };

                    // Setup stop handler
                    mediaRecorder.onstop = () => {
                        if (isRecording && audioChunks.length > 0) {
                            const audioBlob = new Blob(audioChunks, {
                                type: "audio/wav",
                            });
                            const reader = new FileReader();

                            reader.onloadend = function() {
                                const base64String = reader.result.split(",")[1];
                                allRecordings[currentFormId].push(base64String);
                                addRecordingPreview(
                                    base64String,
                                    allRecordings[currentFormId].length - 1,
                                    currentFormId
                                );
                                document.getElementById("saveRecording").disabled = false;
                            };

                            reader.readAsDataURL(audioBlob);
                        }
                        isRecording = false;

                        // Stop all tracks in the stream
                        stream.getTracks().forEach((track) => track.stop());
                    };

                    // Start recording
                    mediaRecorder.start();

                    // Update UI
                    document.getElementById("stopButton").disabled = false;
                    document.getElementById("cancelButton").disabled = false;
                    document.getElementById("startButton").disabled = true;
                })
                .catch((error) => {
                    console.error("Error accessing microphone:", error);
                    alert("Could not access microphone. Please check permissions.");
                    isRecording = false;
                });
        }

        function stopRecording() {
            if (mediaRecorder && isRecording) {
                mediaRecorder.stop();
                document.getElementById("startButton").disabled = false;
                document.getElementById("stopButton").disabled = true;
                document.getElementById("cancelButton").disabled = true;
            }
        }

        function cancelRecording() {
            if (mediaRecorder && isRecording) {
                isRecording = false;
                mediaRecorder.stop();
                audioChunks = [];

                // Update UI
                document.getElementById("startButton").disabled = false;
                document.getElementById("stopButton").disabled = true;
                document.getElementById("cancelButton").disabled = true;
            }
        }

        function addRecordingPreview(base64String, index, formId) {
            const recordedNotesList = document.getElementById("recordedNotesList");

            const noteDiv = document.createElement("div");
            noteDiv.className = "recording-item d-flex align-items-center mb-2";
            noteDiv.dataset.index = index;
            noteDiv.dataset.formId = formId;

            const audioElement = document.createElement("audio");
            audioElement.controls = true;
            audioElement.src = `data:audio/wav;base64,${base64String}`;
            audioElement.className = "flex-grow-1";

            const removeButton = document.createElement("button");
            removeButton.className = "btn btn-danger btn-sm ml-2";
            removeButton.innerHTML = '<i class="fas fa-trash"></i>';
            removeButton.onclick = function() {
                removeRecording(index, formId);
            };

            noteDiv.appendChild(audioElement);
            noteDiv.appendChild(removeButton);
            recordedNotesList.appendChild(noteDiv);
        }

        function removeRecording(index, formId) {
            if (allRecordings[formId] && allRecordings[formId][index]) {
                allRecordings[formId].splice(index, 1);

                // Re-render remaining recordings
                const recordedNotesList = document.getElementById("recordedNotesList");
                recordedNotesList.innerHTML = "";

                allRecordings[formId].forEach((rec, idx) => {
                    addRecordingPreview(rec, idx, formId);
                });

                // Update save button state
                document.getElementById("saveRecording").disabled =
                    allRecordings[formId].length === 0;
            }
        }

        // Update the saveRecording function
        function saveRecording() {
            if (currentFormId && allRecordings[currentFormId]) {
                const input = document.querySelector(
                    `.voice-notes-input[data-form-id="${currentFormId}"]`
                );
                if (input) {
                    input.value = JSON.stringify(allRecordings[currentFormId]);
                }
            }
            $("#recordingVoiceModal").modal("hide");
        }

        function cleanupRecording() {
            if (isRecording && mediaRecorder) {
                mediaRecorder.stop();
                isRecording = false;
            }

            currentFormId = null;
        }
        // Document upload functionality
        document
            .getElementById("addDocument")
            .addEventListener("click", addDocumentField);
        document
            .getElementById("saveDocuments")
            .addEventListener("click", saveDocuments);

        function addDocumentField() {
            const container = document.getElementById("documentsContainer");
            const newDoc = document.createElement("div");
            newDoc.className = "form-group document mt-3";
            newDoc.innerHTML = `
        <div class="row">
            <div class="col-lg-9">
                <input type="file" class="form-control-file document_file" required>
            </div>
            <div class="col-lg-3">
                <input type="text" class="form-control document_description" placeholder="Description">
            </div>
        </div>
        <button type="button" class="btn btn-danger btn-sm mt-2 remove-document">
            <i class="fas fa-minus"></i> Remove
        </button>
    `;
            container.appendChild(newDoc);

            // Add remove event
            newDoc
                .querySelector(".remove-document")
                .addEventListener("click", function() {
                    container.removeChild(newDoc);
                });
        }

        function saveDocuments() {
            const formId = getCurrentFormId("uploadDocumentModal");
            if (!formId) return;

            const docs = Array.from(
                document.querySelectorAll("#documentsContainer .document")
            );
            const uploadedFiles = [];

            docs.forEach((doc) => {
                const fileInput = doc.querySelector(".document_file");
                if (fileInput.files.length > 0) {
                    uploadedFiles.push(fileInput.files[0].name);
                }
            });

            // Update the hidden input
            const hiddenInput = document.querySelector(
                `.uploaded-files-input[data-form-id="${formId}"]`
            );
            if (hiddenInput) {
                hiddenInput.value = JSON.stringify(uploadedFiles);
            }

            // Clear the container and close modal
            document.getElementById("documentsContainer").innerHTML = `
        <div class="form-group document">
            <div class="row">
                <div class="col-lg-9">
                    <input type="file" class="form-control-file document_file" required>
                </div>
                <div class="col-lg-3">
                    <input type="text" class="form-control document_description" placeholder="Description">
                </div>
            </div>
        </div>
    `;
            $("#uploadDocumentModal").modal("hide");
        }
        // Links functionality
        document
            .getElementById("addMoreLinks")
            .addEventListener("click", addLinkField);
        document.getElementById("saveLinks").addEventListener("click", saveLinks);

        function addLinkField() {
            const container = document.getElementById("linksContainer");
            const newLink = document.createElement("div");
            newLink.className = "form-group link-group mt-2";
            newLink.innerHTML = `
        <input type="url" class="form-control link-input mb-2" placeholder="https://example.com">
        <button type="button" class="btn btn-danger btn-sm remove-link">
            <i class="fas fa-minus"></i> Remove
        </button>
    `;
            container.appendChild(newLink);

            newLink
                .querySelector(".remove-link")
                .addEventListener("click", function() {
                    container.removeChild(newLink);
                });
        }

        function saveLinks() {
            const formId = getCurrentFormId("addLinkModal");
            if (!formId) return;

            const links = Array.from(document.querySelectorAll(".link-input"))
                .map((input) => input.value.trim())
                .filter((link) => link !== "");

            formStates[formId].links = links;
            updateFormInputs(formId);
            $("#addLinkModal").modal("hide");
        }

        // Reminders functionality
        document
            .getElementById("addReminder")
            .addEventListener("click", addReminderField);
        document
            .getElementById("saveReminders")
            .addEventListener("click", saveReminders);

        function addReminderField() {
            const container = document.getElementById("remindersContainer");
            const newReminder = document.createElement("div");
            newReminder.className = "form-group reminder mt-3";
            newReminder.innerHTML = `
        <div class="row">
            <div class="col-lg-6">
                <input type="time" class="form-control reminder_time">
            </div>
            <div class="col-lg-6">
                <select class="form-control reminder_method">
                    <option value="">Select Method</option>
                    <option value="email">Email</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-danger btn-sm mt-2 remove-reminder">
            <i class="fas fa-minus"></i> Remove
        </button>
    `;
            container.appendChild(newReminder);

            newReminder
                .querySelector(".remove-reminder")
                .addEventListener("click", function() {
                    container.removeChild(newReminder);
                });
        }

        function saveReminders() {
            const formId = getCurrentFormId("reminderModal");
            if (!formId) return;

            const reminders = Array.from(
                document.querySelectorAll("#remindersContainer .reminder")
            );
            formStates[formId].reminders = {
                times: reminders.map((r) => r.querySelector(".reminder_time").value),
                methods: reminders.map((r) => r.querySelector(".reminder_method").value),
            };

            updateFormInputs(formId);
            $("#reminderModal").modal("hide");
        }

        // Update all form inputs
        function updateFormInputs(formId) {
            const state = formStates[formId];

            // Voice notes
            document.querySelector(
                `.voice-notes-input[data-form-id="${formId}"]`
            ).value = JSON.stringify(state.recordings);

            // Documents (simplified - in real app you'd upload files)
            document.querySelector(
                `.uploaded-files-input[data-form-id="${formId}"]`
            ).value = JSON.stringify(state.documents);

            // Links
            document.querySelector(
                `.links-array-input[data-form-id="${formId}"]`
            ).value = JSON.stringify(state.links);

            // Reminders
            document.querySelector(
                `.reminder-times-input[data-form-id="${formId}"]`
            ).value = state.reminders.times.join(",");
            document.querySelector(
                `.reminder-methods-input[data-form-id="${formId}"]`
            ).value = state.reminders.methods.join(",");
        }

        // Initialize modals with form IDs
        $(document).on("show.bs.modal", ".modal", function(e) {
            const button = e.relatedTarget;
            if (button && button.dataset.formId) {
                this.dataset.currentForm = button.dataset.formId;
                initFormState(button.dataset.formId);
            }
        });

        // Clear modals when hidden
        $(document).on("hidden.bs.modal", ".modal", function() {
            if (this.id === "recordingVoiceModal") {
                document.getElementById("recordedNotesList").innerHTML = "";
            } else if (this.id === "uploadDocumentModal") {
                document.getElementById("documentsContainer").innerHTML = `
            <div class="form-group document">
                <div class="row">
                    <div class="col-lg-9">
                        <input type="file" class="form-control-file document_file" required>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control document_description" placeholder="Description">
                    </div>
                </div>
            </div>
        `;
            } else if (this.id === "addLinkModal") {
                document.getElementById("linksContainer").innerHTML = `
            <div class="form-group">
                <input type="url" class="form-control link-input" placeholder="https://example.com">
            </div>
        `;
            } else if (this.id === "reminderModal") {
                document.getElementById("remindersContainer").innerHTML = `
            <div class="form-group reminder">
                <div class="row">
                    <div class="col-lg-6">
                        <input type="time" class="form-control reminder_time">
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control reminder_method">
                            <option value="">Select Method</option>
                            <option value="email">Email</option>
                            <option value="whatsapp">WhatsApp</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize all frequency controls on the page
            initAllFrequencyControls();
        });

        function initAllFrequencyControls() {
            // Find all repeat checkboxes on the page
            document.querySelectorAll('[id^="repeat_task_"]').forEach((checkbox) => {
                const formId = checkbox.id.replace("repeat_task_", "");
                initFrequencyControl(formId);
            });
        }

        function initFrequencyControl(formId) {
            // Get elements with form-specific IDs
            const repeatCheckbox = document.getElementById(`repeat_task_${formId}`);
            const dueDateSection = document.getElementById(
                `due_date_section_${formId}`
            );
            const frequencySection = document.getElementById(
                `frequency_section_${formId}`
            );
            const frequencySelect = document.getElementById(`frequency_${formId}`);
            const additionalFields = document.getElementById(
                `additional_fields_${formId}`
            );

            // All frequency-specific containers
            const dailyTime = document.getElementById(`daily_time_${formId}`);
            const weeklyDays = document.getElementById(`weekly_days_${formId}`);
            const monthlyDate = document.getElementById(`monthly_date_${formId}`);
            const yearlyDate = document.getElementById(`yearly_date_${formId}`);
            const periodicFrequency = document.getElementById(
                `periodic_frequency_${formId}`
            );
            const customFrequency = document.getElementById(
                `custom_frequency_${formId}`
            );

            // Custom frequency dropdowns
            const middleDropdown = document.getElementById(
                `custom_frequency_dropdown_${formId}`
            );
            const firstDropdown = document.getElementById(
                `occurs_every_dropdown_${formId}`
            );
            const thirdDropdown = document.getElementById(
                `occurs_on_dropdown_${formId}`
            );

            // Initialize the form
            toggleFrequencyDropdown();

            // Set up event listeners
            if (repeatCheckbox) {
                repeatCheckbox.addEventListener("change", toggleFrequencyDropdown);
            }

            if (frequencySelect) {
                frequencySelect.addEventListener("change", toggleFrequencyFields);
            }

            if (middleDropdown) {
                middleDropdown.addEventListener("change", updateCustomDropdowns);
            }

            function toggleFrequencyDropdown() {
                if (repeatCheckbox.checked) {
                    dueDateSection.style.display = "none";
                    frequencySection.style.display = "block";
                    resetDueDateFields();
                } else {
                    dueDateSection.style.display = "block";
                    frequencySection.style.display = "none";
                    resetFrequencyFields();
                }
            }

            function toggleFrequencyFields() {
                // Hide all additional fields first
                hideAllFrequencyFields();

                const selectedFrequency = frequencySelect.value;

                if (!selectedFrequency) {
                    additionalFields.style.display = "none";
                    return;
                }

                additionalFields.style.display = "block";

                switch (selectedFrequency) {
                    case "Daily":
                        dailyTime.style.display = "block";
                        break;
                    case "Weekly":
                        weeklyDays.style.display = "block";
                        break;
                    case "Monthly":
                        monthlyDate.style.display = "block";
                        break;
                    case "Yearly":
                        yearlyDate.style.display = "block";
                        break;
                    case "Periodic":
                        periodicFrequency.style.display = "block";
                        break;
                    case "Custom":
                        customFrequency.style.display = "block";
                        updateCustomDropdowns();
                        break;
                }
            }

            function hideAllFrequencyFields() {
                const fields = [
                    dailyTime,
                    weeklyDays,
                    monthlyDate,
                    yearlyDate,
                    periodicFrequency,
                    customFrequency,
                ];

                fields.forEach((field) => {
                    if (field) field.style.display = "none";
                });
            }

            function resetFrequencyFields() {
                hideAllFrequencyFields();

                // Reset all inputs in additional fields
                if (additionalFields) {
                    additionalFields.querySelectorAll("input").forEach((input) => {
                        if (input.type !== "checkbox") {
                            input.value = "";
                        } else {
                            input.checked = false;
                        }
                    });
                }

                // Reset dropdowns
                if (frequencySelect) frequencySelect.value = "";
                if (middleDropdown) middleDropdown.value = "";
                if (firstDropdown) firstDropdown.innerHTML = "";
                if (thirdDropdown) thirdDropdown.innerHTML = "";
            }

            function resetDueDateFields() {
                if (dueDateSection) {
                    dueDateSection
                        .querySelectorAll("input")
                        .forEach((input) => (input.value = ""));
                }
            }

            function updateCustomDropdowns() {
                if (!middleDropdown || !firstDropdown || !thirdDropdown) return;

                const selectedFrequency = middleDropdown.value;
                firstDropdown.innerHTML = "";
                thirdDropdown.innerHTML = "";

                if (selectedFrequency === "Month") {
                    // Populate months
                    const months = [
                        "January",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                        "August",
                        "September",
                        "October",
                        "November",
                        "December",
                    ];

                    months.forEach((month) => {
                        const option = document.createElement("option");
                        option.value = month;
                        option.text = month;
                        firstDropdown.appendChild(option);
                    });

                    // Populate days
                    for (let day = 1; day <= 28; day++) {
                        const option = document.createElement("option");
                        option.value = day;
                        option.text = "Day " + day;
                        thirdDropdown.appendChild(option);
                    }
                } else if (selectedFrequency === "Week") {
                    // Populate weeks
                    for (let week = 1; week <= 52; week++) {
                        const option = document.createElement("option");
                        option.value = week;
                        option.text = "Week " + week;
                        firstDropdown.appendChild(option);
                    }

                    // Populate weekdays
                    const weekdays = [
                        "Sunday",
                        "Monday",
                        "Tuesday",
                        "Wednesday",
                        "Thursday",
                        "Friday",
                        "Saturday",
                    ];

                    weekdays.forEach((day) => {
                        const option = document.createElement("option");
                        option.value = day;
                        option.text = day;
                        thirdDropdown.appendChild(option);
                    });
                }
            }
        }
    </script>
</body>

</html>