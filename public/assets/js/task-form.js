$(document).ready(function() {
    // Initialize all forms
    initializeTaskForm('form1');
    initializeTaskForm('form2');
    initializeTaskForm('form3');
});

function initializeTaskForm(formId) {
    // Fetch Users Departments
    $.ajax({
        url: '/get-departments',
        method: 'GET',
        success: function(response) {
            $(`#${formId} #department`).empty();
            $(`#${formId} #department`).append('<option value="">Select Department Value</option>');
            response.forEach(function(dept) {
                $(`#${formId} #department`).append(`<option value="${dept}">${dept}</option>`);
            });
        },
        error: function(xhr) {
            console.error('Error fetching departments:', xhr.responseText);
        }
    });

    // Fetch User Department Wise 
    $(`#${formId} #department`).on('change', function() {
        const department = $(this).val();
        const assignDropdown = $(`#${formId} #assign_to`);

        assignDropdown.html('<option value="">Loading...</option>');

        if (department) {
            $.ajax({
                url: `/get-users-by-department/${department}`,
                method: 'GET',
                success: function(response) {
                    assignDropdown.html('<option value="">Select User</option>');

                    if (response.length === 0) {
                        assignDropdown.append('<option value="">No users found</option>');
                    }

                    response.forEach(function(user) {
                        assignDropdown.append(`<option value="${user.employee_code} * ${user.employee_name}">${user.employee_code} - ${user.employee_name}</option>`);
                    });
                },
                error: function() {
                    assignDropdown.html('<option value="">Error loading users</option>');
                }
            });
        } else {
            assignDropdown.html('<option value="">Select User</option>');
        }
    });

    // Step navigation
    var nextButtons = $(`#${formId} .next-step`);
    var prevButtons = $(`#${formId} .prev-step`);
    var formSteps = $(`#${formId} .form-step`);
    var stepIndicators = $(`#${formId} .step`);

    nextButtons.on("click", function() {
        var currentStep = $(`#${formId} .form-step.active`);
        const currentStepNumber = parseInt(currentStep.data("step"));
        const nextStepNumber = currentStepNumber + 1;

        if (validateStep(formId, currentStepNumber)) {
            currentStep.removeClass("active").addClass("hidden");
            $(`#${formId} .form-step[data-step="${nextStepNumber}"]`)
                .removeClass("hidden")
                .addClass("active");
            updateStepIndicators(formId, nextStepNumber);
        }
    });

    prevButtons.on("click", function() {
        var currentStep = $(`#${formId} .form-step.active`);
        const currentStepNumber = parseInt(currentStep.data("step"));
        const prevStepNumber = currentStepNumber - 1;

        currentStep.removeClass("active").addClass("hidden");
        $(`#${formId} .form-step[data-step="${prevStepNumber}"]`)
            .removeClass("hidden")
            .addClass("active");
        updateStepIndicators(formId, prevStepNumber);
    });

    function updateStepIndicators(formId, activeStep) {
        $(`#${formId} .step`).each(function() {
            var step = $(this);
            var stepNumber = parseInt(step.data("step"));
            var stepNumberElement = step.find(".step-number");
            var stepTitleElement = step.find(".step-title");

            if (stepNumber === activeStep) {
                stepNumberElement
                    .removeClass("bg-primary-light text-secondary")
                    .addClass("bg-primary text-white");
                stepTitleElement
                    .removeClass("text-gray-500")
                    .addClass("text-gray-900");
            } else if (stepNumber < activeStep) {
                stepNumberElement
                    .removeClass("bg-primary text-white")
                    .addClass("bg-info text-white");
                stepTitleElement
                    .removeClass("text-gray-500")
                    .addClass("text-gray-900");
            } else {
                stepNumberElement
                    .removeClass("bg-blue-600 text-white bg-green-500")
                    .addClass("bg-gray-200 text-gray-700");
                stepTitleElement
                    .removeClass("text-gray-900")
                    .addClass("text-gray-500");
            }
        });
    }

    function validateStep(formId, stepNumber) {
        let isValid = true;

        if (stepNumber === 1) {
            const title = $(`#${formId} #taskTitleInput`).val();
            const priority = $(`#${formId} .btn-check`).val();

            if (!title.trim()) {
                alert("Task title is required");
                isValid = false;
            } else if (!priority) {
                alert("Priority is required");
                isValid = false;
            }
        }

        return isValid;
    }

    let taskCounter = 1;

    // Add new task field
    $(document).on("click", `#${formId} .add-task-btn`, function() {
        taskCounter++;
        const newTask = `
            <div class="task-item mb-3" data-task-id="${taskCounter}">
                <div class="input-group">
                    <input type="text" class="form-control task-input me-3" name="tasks[]" placeholder="Enter task" required>
                    <button type="button" class="btn btn-inverse-danger remove-task-btn">
                        <i class="ti ti-minus"></i>
                    </button>
                </div>
            </div>
        `;
        $(`#${formId} .task-container`).append(newTask);
    });

    // Remove task field
    $(document).on("click", `#${formId} .remove-task-btn`, function() {
        if ($(`#${formId} .task-item`).length > 1) {
            $(this).closest(".task-item").remove();
        } else {
            alert("You need at least one task field!");
        }
    });

    $(`#${formId} #flexSwitchCheckDefault`).on("change", function() {
        if ($(this).is(":checked")) {
            $(`#${formId} #frequency_section`).removeClass("d-none");
            $(`#${formId} #due_date_section_form1`).addClass("d-none");
            $(`#${formId} #additional_fields_form1`).removeClass("d-none");
        } else {
            $(`#${formId} #frequency_section`).addClass("d-none");
            $(`#${formId} #due_date_section_form1`).removeClass("d-none");
            $(`#${formId} #additional_fields_form1`).addClass("d-none");
        }
        updateFrequencyDuration(formId);
    });

    $(`#${formId} #frequency_form1`).on("change", function() {
        var frequency = $(this).val();
        $(`#${formId} #additional_fields_form1`).removeClass("d-none");

        if (frequency === "Daily") {
            $(`#${formId} #weekly_days_form1`).addClass("d-none");
            $(`#${formId} #monthly_date_form1`).addClass("d-none");
            $(`#${formId} #yearly_date_form1`).addClass("d-none");
            $(`#${formId} #periodic_frequency_form1`).addClass("d-none");
            $(`#${formId} #custom_frequency_form1`).addClass("d-none");
            $(`#${formId} .note_form1`).text(
                "This task will be automatically reassigned on a daily basis until it is either closed or manually stopped."
            );
        } else if (frequency === "Weekly") {
            $(`#${formId} #weekly_days_form1`).removeClass("d-none");
            $(`#${formId} #monthly_date_form1`).addClass("d-none");
            $(`#${formId} #yearly_date_form1`).addClass("d-none");
            $(`#${formId} #periodic_frequency_form1`).addClass("d-none");
            $(`#${formId} #custom_frequency_form1`).addClass("d-none");
            $(`#${formId} .note_form1`).text(
                "This task will be automatically reassigned on the selected days of each week."
            );
        } else if (frequency === "Monthly") {
            $(`#${formId} #weekly_days_form1`).addClass("d-none");
            $(`#${formId} #monthly_date_form1`).removeClass("d-none");
            $(`#${formId} #yearly_date_form1`).addClass("d-none");
            $(`#${formId} #periodic_frequency_form1`).addClass("d-none");
            $(`#${formId} #custom_frequency_form1`).addClass("d-none");
            $(`#${formId} .note_form1`).text(
                "This task will be automatically reassigned on the selected date each month."
            );
        } else if (frequency === "Yearly") {
            $(`#${formId} #weekly_days_form1`).addClass("d-none");
            $(`#${formId} #monthly_date_form1`).addClass("d-none");
            $(`#${formId} #yearly_date_form1`).removeClass("d-none");
            $(`#${formId} #periodic_frequency_form1`).addClass("d-none");
            $(`#${formId} #custom_frequency_form1`).addClass("d-none");
            $(`#${formId} .note_form1`).text(
                "This task will be automatically reassigned on the selected date each year."
            );
        } else if (frequency === "Periodic") {
            $(`#${formId} #weekly_days_form1`).addClass("d-none");
            $(`#${formId} #monthly_date_form1`).addClass("d-none");
            $(`#${formId} #yearly_date_form1`).addClass("d-none");
            $(`#${formId} #periodic_frequency_form1`).removeClass("d-none");
            $(`#${formId} #custom_frequency_form1`).addClass("d-none");
            $(`#${formId} .note_form1`).text(
                "This task will be automatically reassigned at the interval of days specified in the input"
            );
        } else if (frequency === "Custom") {
            $(`#${formId} #weekly_days_form1`).addClass("d-none");
            $(`#${formId} #monthly_date_form1`).addClass("d-none");
            $(`#${formId} #yearly_date_form1`).addClass("d-none");
            $(`#${formId} #periodic_frequency_form1`).addClass("d-none");
            $(`#${formId} #custom_frequency_form1`).removeClass("d-none");
            $(`#${formId} .note_form1`).text(
                "This task will be automatically reassigned at the interval of months or weeks you've specified in the input."
            );
        } else {
            $(`#${formId} #weekly_days_form1`).addClass("d-none");
            $(`#${formId} #monthly_date_form1`).addClass("d-none");
            $(`#${formId} #yearly_date_form1`).addClass("d-none");
            $(`#${formId} #periodic_frequency_form1`).addClass("d-none");
            $(`#${formId} #custom_frequency_form1`).addClass("d-none");

            $(`#${formId} #additional_fields_form1`).addClass("d-none");
            $(`#${formId} .note_form1`).text("");
        }
        updateFrequencyDuration(formId);
    });

    // Update frequency duration when any related field changes
    $(document).on("change", `#${formId} .day-checkbox, #${formId} #monthly_day_form1, #${formId} #yearly_date_input_form1, #${formId} #periodic_interval_form1, #${formId} #custom_frequency_dropdown_form1, #${formId} #occurs_every_dropdown_form1`, function() {
        updateFrequencyDuration(formId);
    });

    // Voice Recording - Multiple Notes Version
    var startButton = $(`#${formId} #startButton`);
    var stopButton = $(`#${formId} #stopButton`);
    var cancelButton = $(`#${formId} #cancelButton`);
    var recordedNotesList = $(`#${formId} #recordedNotesList`);
    var voiceNotesInput = $(`#${formId} #voiceNotesInput`);

    let mediaRecorder;
    let audioChunks = [];
    let voiceNotes = [];

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        startButton.on("click", async function() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = function(e) {
                    audioChunks.push(e.data);
                };

                mediaRecorder.onstop = function() {
                    const audioBlob = new Blob(audioChunks, {
                        type: "audio/wav",
                    });
                    const audioUrl = URL.createObjectURL(audioBlob);

                    // Create unique ID for this note
                    const noteId = "voice-note-" + Date.now();

                    // Convert blob to data URL for storage
                    const reader = new FileReader();
                    reader.onload = function() {
                        const dataUrl = reader.result;

                        // Add to our voice notes array
                        voiceNotes.push({
                            id: noteId,
                            url: audioUrl,
                            blob: audioBlob,
                            dataUrl: dataUrl,
                        });

                        // Update the hidden input with JSON of all notes
                        updateVoiceNotesInput();

                        // Create audio player
                        const audioPlayer = $(`
                            <div class="audio-player mb-2 d-flex align-items-center" data-note-id="${noteId}">
                                <audio controls src="${audioUrl}"></audio>
                                <button type="button" class="btn btn-sm btn-danger ms-2 remove-audio">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        `);

                        recordedNotesList.append(audioPlayer);
                        cancelButton.prop("disabled", false);

                        // Stop all tracks
                        mediaRecorder.stream
                            .getTracks()
                            .forEach((track) => track.stop());
                    };
                    reader.readAsDataURL(audioBlob);
                };

                mediaRecorder.start();
                startButton.prop("disabled", true);
                stopButton.prop("disabled", false);
            } catch (error) {
                console.error("Error accessing microphone:", error);
                toastr.error(
                    "Could not access microphone. Please ensure you have granted permission."
                );
            }
        });

        stopButton.on("click", function() {
            if (mediaRecorder && mediaRecorder.state !== "inactive") {
                mediaRecorder.stop();
                startButton.prop("disabled", false);
                stopButton.prop("disabled", true);
            }
        });

        cancelButton.on("click", function() {
            recordedNotesList.empty();
            voiceNotes = [];
            updateVoiceNotesInput();
            $(this).prop("disabled", true);
        });

        // Handle audio removal
        recordedNotesList.on("click", ".remove-audio", function() {
            const noteId = $(this).closest(".audio-player").data("note-id");
            voiceNotes = voiceNotes.filter((note) => note.id !== noteId);
            $(this).closest(".audio-player").remove();
            updateVoiceNotesInput();

            if (recordedNotesList.children().length === 0) {
                cancelButton.prop("disabled", true);
            }
        });

        // Update the hidden input with JSON of all voice notes
        function updateVoiceNotesInput() {
            const notesForStorage = voiceNotes.map((note) => ({
                id: note.id,
                url: note.url,
                dataUrl: note.dataUrl,
            }));
            voiceNotesInput.val(JSON.stringify(notesForStorage));
        }
    } else {
        startButton.prop("disabled", true);
        console.warn(
            "MediaDevices API or getUserMedia not supported in this browser"
        );
    }

    // Documents
    var documentsContainer = $(`#${formId} #documentsContainer`);
    var addDocumentButton = $(`#${formId} #addDocument`);

    addDocumentButton.on("click", function() {
        const newDocument = $(`
            <div class="document-item mb-3">
                <div class="input-group">
                    <input type="file" name="documents[]" class="form-control document-file" required>
                    <button type="button" class="btn btn-outline-danger remove-document">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        `);
        documentsContainer.append(newDocument);
    });

    // Handle document removal
    documentsContainer.on("click", ".remove-document", function() {
        $(this).closest(".document-item").remove();
    });

    // Links
    var linksContainer = $(`#${formId} #linksContainer`);
    var addMoreLinksButton = $(`#${formId} #addMoreLinks`);
    var linksInput = $(`#${formId} #linksInput`);

    addMoreLinksButton.on("click", function() {
        const newLink = $(`
            <div class="link-item mb-2">
                <div class="input-group">
                    <input type="url" class="form-control link-input" placeholder="https://example.com">
                    <button type="button" class="btn btn-outline-danger remove-link">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        `);
        linksContainer.append(newLink);
    });

    // Handle link removal and update hidden input
    linksContainer.on("click", ".remove-link", function() {
        $(this).closest(".link-item").remove();
        updateLinksInput(formId);
    });

    // Update links input when links change
    linksContainer.on("change", ".link-input", function() {
        updateLinksInput(formId);
    });

    // Update priority input more reliably
    function updatePriorityInput(formId) {
        const priority = $(`#${formId} input[name="btnradio"]:checked`).attr("id");
        $(`#${formId} #priorityInput`).val(priority.replace("btnradio", "").toLowerCase());
    }

    // Update tasks input
    function updateTasksInput(formId) {
        const tasks = [];
        $(`#${formId} .task-input`).each(function() {
            const val = $(this).val().trim();
            if (val) tasks.push(val);
        });
        $(`#${formId} #tasksInput`).val(JSON.stringify(tasks));
    }

    // Add this new function to handle frequency duration
    function updateFrequencyDuration(formId) {
        const frequency = $(`#${formId} #frequency_form1`).val();
        let duration = [];

        if (frequency === "Weekly") {
            duration = $(`#${formId} .day-checkbox:checked`)
                .map(function() {
                    return $(this).val();
                })
                .get();
        } else if (frequency === "Monthly") {
            const day = $(`#${formId} #monthly_day_form1`).val();
            if (day) duration = [day];
        } else if (frequency === "Yearly") {
            const date = $(`#${formId} #yearly_date_input_form1`).val();
            if (date) duration = [date];
        } else if (frequency === "Periodic") {
            const interval = $(`#${formId} #periodic_interval_form1`).val();
            if (interval) duration = [interval];
        } else if (frequency === "Custom") {
            const freq = $(`#${formId} #custom_frequency_dropdown_form1`).val();
            const occurs = $(`#${formId} #occurs_every_dropdown_form1`).val();
            if (freq && occurs) duration = [freq, occurs];
        }

        let $input = $(`#${formId} #frequencyDurationInput`);
        if ($input.length === 0) {
            $input = $(
                '<input type="hidden" name="frequency_duration_json" id="frequencyDurationInput">'
            );
            $(`#${formId} form`).append($input);
        }
        $input.val(JSON.stringify(duration));
    }

    // Update links input more reliably
    function updateLinksInput(formId) {
        const links = [];
        $(`#${formId} .link-input`).each(function() {
            const val = $(this).val().trim();
            if (val) links.push(val);
        });

        // Add to form as hidden input if not exists
        if ($(`#${formId} #linksInput`).length === 0) {
            $(`#${formId} form`).append(
                '<input type="hidden" name="links_json" id="linksInput">'
            );
        }
        $(`#${formId} #linksInput`).val(JSON.stringify(links));
    }

    // Update reminders input
    function updateRemindersInput(formId) {
        const reminders = [];
        $(`#${formId} input[name="reminders[]"]:checked`).each(function() {
            reminders.push($(this).val());
        });
        $(`#${formId} #remindersInput`).val(JSON.stringify(reminders));
    }

    // Initialize form elements
    function initializeForm(formId) {
        // Add hidden inputs if they don't exist
        if ($(`#${formId} #frequencyDurationInput`).length === 0) {
            $(`#${formId} form`).append(
                '<input type="hidden" name="frequency_duration_json" id="frequencyDurationInput">'
            );
        }
        if ($(`#${formId} #linksInput`).length === 0) {
            $(`#${formId} form`).append(
                '<input type="hidden" name="links_json" id="linksInput">'
            );
        }
        if ($(`#${formId} #remindersInput`).length === 0) {
            $(`#${formId} form`).append(
                '<input type="hidden" name="reminders_json" id="remindersInput">'
            );
        }
        if ($(`#${formId} input[name="priority"]`).length === 0) {
            $(`#${formId} form`).append('<input type="hidden" name="priority">');
        }

        // Initialize priority from radio buttons
        updatePriorityInput(formId);
    }

    // Initialize the form
    initializeForm(formId);

    // Event listeners for form-specific elements
    $(`#${formId} input[name="btnradio"]`).change(function() {
        updatePriorityInput(formId);
    });

    // Form submission handler
    $(`#${formId} form`).on("submit", function(e) {
        // Prevent default form submission
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to create this task?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4cc9f0',
            cancelButtonColor: '#f72585',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Update all dynamic inputs
                updatePriorityInput(formId);
                updateTasksInput(formId);
                updateLinksInput(formId);
                updateRemindersInput(formId);
                updateFrequencyDuration(formId);

                // Serialize form data including our hidden inputs
                const formData = new FormData(this);

                // Remove array versions
                formData.delete("tasks[]");
                formData.delete("reminders[]");
                formData.delete("frequency_duration[]");
                formData.delete("voice_notes"); // Remove the JSON version

                for (const [index, note] of voiceNotes.entries()) {
                    formData.append(
                        `voice_notes[${index}]`,
                        note.blob,
                        `voice_note_${note.id}.wav`
                    );
                }

                // Submit the form via AJAX
                try {
                    $.ajax({
                        url: "/add-task",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        beforeSend: function() {
                            // Show loading indicator
                            $(`#${formId} #submitBtn`)
                                .prop("disabled", true)
                                .html('<i class="ti ti-loader me-2"></i>Processing...');
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Task Created!',
                                    text: response.message || 'A new task has been successfully created.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    },
                                    buttonsStyling: false
                                }).then(() => {
                                    if (response.redirect) {
                                        window.location.href = response.redirect;
                                    } else {
                                        // Use Bootstrap 5 compatible hide
                                        let modalEl = document.getElementById(formId);
                                        let modalInstance = bootstrap.Modal.getInstance(modalEl);
                                        if (modalInstance) {
                                            modalInstance.hide();
                                        }
                                    }
                                });
                            } else {
                                toastr.error(response.message || "An error occurred");
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = "An error occurred";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                // Handle validation errors
                                const errors = xhr.responseJSON.errors;
                                errorMessage = Object.values(errors)[0][0];
                            }
                            toastr.error(errorMessage);
                        },
                        complete: function() {
                            // Re-enable submit button
                            $(`#${formId} #submitBtn`).prop("disabled", false).html("Create Task");
                        },
                    });
                } catch (error) {
                    let errorMessage = "An error occurred";
                    if (error.responseJSON && error.responseJSON.message) {
                        errorMessage = error.responseJSON.message;
                    } else if (error.responseJSON && error.responseJSON.errors) {
                        const errors = error.responseJSON.errors;
                        errorMessage = Object.values(errors)[0][0];
                    }
                    toastr.error(errorMessage);
                } finally {
                    $(`#${formId} #submitBtn`).prop("disabled", false).html("Create Task");
                }
            }
        });
    });
}