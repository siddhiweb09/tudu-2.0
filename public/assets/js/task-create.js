$(document).ready(function () {
    // Step navigation
    const $nextButtons = $(".next-step");
    const $prevButtons = $(".prev-step");
    const $formSteps = $(".form-step");
    const $stepIndicators = $(".step");

    $nextButtons.on("click", function () {
        const $currentStep = $(".form-step.active");
        const currentStepNumber = parseInt($currentStep.data("step"));
        const nextStepNumber = currentStepNumber + 1;

        if (validateStep(currentStepNumber)) {
            $currentStep.removeClass("active").addClass("hidden");
            $(`.form-step[data-step="${nextStepNumber}"]`)
                .removeClass("hidden")
                .addClass("active");
            updateStepIndicators(nextStepNumber);
        }
    });

    $prevButtons.on("click", function () {
        const $currentStep = $(".form-step.active");
        const currentStepNumber = parseInt($currentStep.data("step"));
        const prevStepNumber = currentStepNumber - 1;

        $currentStep.removeClass("active").addClass("hidden");
        $(`.form-step[data-step="${prevStepNumber}"]`)
            .removeClass("hidden")
            .addClass("active");
        updateStepIndicators(prevStepNumber);
    });

    function updateStepIndicators(activeStep) {
        $stepIndicators.each(function () {
            const $step = $(this);
            const stepNumber = parseInt($step.data("step"));
            const $stepNumberElement = $step.find(".step-number");
            const $stepTitleElement = $step.find(".step-title");

            if (stepNumber === activeStep) {
                $stepNumberElement
                    .removeClass("bg-primary-light text-secondary")
                    .addClass("bg-primary text-white");
                $stepTitleElement
                    .removeClass("text-gray-500")
                    .addClass("text-gray-900");
            } else if (stepNumber < activeStep) {
                $stepNumberElement
                    .removeClass("bg-primary text-white")
                    .addClass("bg-info text-white");
                $stepTitleElement
                    .removeClass("text-gray-500")
                    .addClass("text-gray-900");
            } else {
                $stepNumberElement
                    .removeClass("bg-blue-600 text-white bg-green-500")
                    .addClass("bg-gray-200 text-gray-700");
                $stepTitleElement
                    .removeClass("text-gray-900")
                    .addClass("text-gray-500");
            }
        });
    }

    function validateStep(stepNumber) {
        let isValid = true;

        if (stepNumber === 1) {
            const title = $("#taskTitleInput").val();
            const priority = $(".btn-check").val();

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
    $(document).on("click", ".add-task-btn", function () {
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
        $(".task-container").append(newTask);
    });

    // Remove task field
    $(document).on("click", ".remove-task-btn", function () {
        if ($(".task-item").length > 1) {
            $(this).closest(".task-item").remove();
        } else {
            alert("You need at least one task field!");
        }
    });

    $("#flexSwitchCheckDefault").on("change", function () {
        if ($(this).is(":checked")) {
            $("#frequency_section").removeClass("d-none");
            $("#due_date_section_form1").addClass("d-none");
            $("#additional_fields_form1").removeClass("d-none");
        } else {
            console.log("Switch is OFF");
            $("#frequency_section").addClass("d-none");
            $("#due_date_section_form1").removeClass("d-none");
            $("#additional_fields_form1").addClass("d-none");
        }
    });

    $("#frequency_form1").on("change", function () {
        var frequency = $(this).val();
        $("#additional_fields_form1").removeClass("d-none");

        if (frequency === "Daily") {
            $("#additional_fields_form1").removeClass("d-none");
            $("#weekly_days_form1").addClass("d-none");
            $("#monthly_date_form1").addClass("d-none");
            $("#yearly_date_form1").addClass("d-none");
            $("#periodic_frequency_form1").addClass("d-none");
            $("#custom_frequency_form1").addClass("d-none");
            $(".note").text(
                "This task will be automatically reassigned on a daily basis until it is either closed or manually stopped."
            );
        } else if (frequency === "Weekly") {
            $("#weekly_days_form1").removeClass("d-none");
            $("#monthly_date_form1").addClass("d-none");
            $("#yearly_date_form1").addClass("d-none");
            $("#periodic_frequency_form1").addClass("d-none");
            $("#custom_frequency_form1").addClass("d-none");
            $(".note").text(
                "This task will be automatically reassigned on the selected days of each week."
            );
        } else if (frequency === "Monthly") {
            $("#weekly_days_form1").addClass("d-none");
            $("#monthly_date_form1").removeClass("d-none");
            $("#yearly_date_form1").addClass("d-none");
            $("#periodic_frequency_form1").addClass("d-none");
            $("#custom_frequency_form1").addClass("d-none");
            $(".note").text(
                "This task will be automatically reassigned on the selected date each month."
            );
        } else if (frequency === "Yearly") {
            $("#weekly_days_form1").addClass("d-none");
            $("#monthly_date_form1").addClass("d-none");
            $("#yearly_date_form1").removeClass("d-none");
            $("#periodic_frequency_form1").addClass("d-none");
            $("#custom_frequency_form1").addClass("d-none");
            $(".note").text(
                "This task will be automatically reassigned on the selected date each year."
            );
        } else if (frequency === "Periodic") {
            $("#weekly_days_form1").addClass("d-none");
            $("#monthly_date_form1").addClass("d-none");
            $("#yearly_date_form1").addClass("d-none");
            $("#periodic_frequency_form1").removeClass("d-none");
            $("#custom_frequency_form1").addClass("d-none");
            $(".note").text(
                "This task will be automatically reassigned at the interval of days specified in the input"
            );
        } else if (frequency === "Custom") {
            $("#weekly_days_form1").addClass("d-none");
            $("#monthly_date_form1").addClass("d-none");
            $("#yearly_date_form1").addClass("d-none");
            $("#periodic_frequency_form1").addClass("d-none");
            $("#custom_frequency_form1").removeClass("d-none");
            $(".note").text(
                "This task will be automatically reassigned at the interval of months or weeks you’ve specified in the input."
            );
        } else {
            $("#weekly_days_form1").addClass("d-none");
            $("#monthly_date_form1").addClass("d-none");
            $("#yearly_date_form1").addClass("d-none");
            $("#periodic_frequency_form1").addClass("d-none");
            $("#custom_frequency_form1").addClass("d-none");

            $("#additional_fields_form1").addClass("d-none");
            $(".note").text("");
        }
    });

    // Voice Recording
    const $startButton = $("#startButton");
    const $stopButton = $("#stopButton");
    const $cancelButton = $("#cancelButton");
    const $recordedNotesList = $("#recordedNotesList");
    const $voiceNotesInput = $("#voiceNotesInput");

    let mediaRecorder;
    let audioChunks = [];

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        $startButton.on("click", async function () {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = function (e) {
                    audioChunks.push(e.data);
                };

                mediaRecorder.onstop = function () {
                    const audioBlob = new Blob(audioChunks, {
                        type: "audio/wav",
                    });
                    const audioUrl = URL.createObjectURL(audioBlob);

                    // Create audio player
                    const audioPlayer = $(`
                        <div class="audio-player mb-2 d-flex align-items-center">
                            <audio controls src="${audioUrl}"></audio>
                            <button type="button" class="btn btn-sm btn-danger ms-2 remove-audio">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    `);

                    $recordedNotesList.append(audioPlayer);
                    $voiceNotesInput.val(audioUrl);

                    // Enable cancel button
                    $cancelButton.prop("disabled", false);
                };

                mediaRecorder.start();
                $startButton.prop("disabled", true);
                $stopButton.prop("disabled", false);
            } catch (error) {
                console.error("Error accessing microphone:", error);
                alert(
                    "Could not access microphone. Please ensure you have granted permission."
                );
            }
        });

        $stopButton.on("click", function () {
            if (mediaRecorder && mediaRecorder.state !== "inactive") {
                mediaRecorder.stop();
                mediaRecorder.stream
                    .getTracks()
                    .forEach((track) => track.stop());
                $startButton.prop("disabled", false);
                $stopButton.prop("disabled", true);
            }
        });

        $cancelButton.on("click", function () {
            $recordedNotesList.empty();
            $voiceNotesInput.val("");
            $(this).prop("disabled", true);
        });

        // Handle audio removal
        $recordedNotesList.on("click", ".remove-audio", function () {
            $(this).closest(".audio-player").remove();
            if ($recordedNotesList.children().length === 0) {
                $cancelButton.prop("disabled", true);
            }
        });
    } else {
        $startButton.prop("disabled", true);
        console.warn(
            "MediaDevices API or getUserMedia not supported in this browser"
        );
    }

    // Documents
    const $documentsContainer = $("#documentsContainer");
    const $addDocumentButton = $("#addDocument");

    $addDocumentButton.on("click", function () {
        const newDocument = $(`
            <div class="document-item mb-3">
                <div class="input-group">
                    <input type="file" name="document" class="form-control document-file" required>
                    <button type="button" class="btn btn-outline-danger remove-document">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        `);
        $documentsContainer.append(newDocument);
    });

    // Handle document removal
    $documentsContainer.on("click", ".remove-document", function () {
        $(this).closest(".document-item").remove();
    });

    // Links
    const $linksContainer = $("#linksContainer");
    const $addMoreLinksButton = $("#addMoreLinks");
    const $linksInput = $("#linksInput");

    $addMoreLinksButton.on("click", function () {
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
        $linksContainer.append(newLink);
    });

    // Handle link removal and update hidden input
    $linksContainer.on("click", ".remove-link", function () {
        $(this).closest(".link-item").remove();
        updateLinksInput();
    });

    // Update links input when links change
    $linksContainer.on("change", ".link-input", updateLinksInput);

    function updateLinksInput() {
        const links = $(".link-input")
            .map(function () {
                return $(this).val().trim();
            })
            .get()
            .filter((value) => value !== "");

        $linksInput.val(JSON.stringify(links));
    }
    // Update all inputs before form submission
    $("form").on("submit", function (e) {
        // Prevent default form submission
        e.preventDefault();

        // Update all dynamic inputs
        updatePriorityInput();
        updateTasksInput();
        updateLinksInput();
        updateRemindersInput();
        updateFrequencyDuration();
        // Serialize form data including our hidden inputs
        const formData = new FormData(this);
        formData.delete("tasks[]");
        formData.delete("frequency_duration[]");
        formData.delete("reminders[]");

        // Submit the form via AJAX
        $.ajax({
            url: "/add-task",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Laravel
            },
            beforeSend: function () {
                // Show loading indicator
                $("#submitBtn")
                    .prop("disabled", true)
                    .html('<i class="ti ti-loader me-2"></i>Processing...');
            },
            success: function (response) {
                if (response.success) {
                    // Show success message
                    toastr.success(response.message);

                    // Redirect or close modal as needed
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        $("#assign_task").modal("hide");
                    }
                } else {
                    toastr.error(response.message || "An error occurred");
                }
            },
            error: function (xhr) {
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
            complete: function () {
                // Re-enable submit button
                $("#submitBtn").prop("disabled", false).html("Create Task");
            },
        });
    });

    // Update priority input more reliably
    function updatePriorityInput() {
        const priority = $('input[name="btnradio"]:checked').attr("id");
        $("#priorityInput").val(priority.replace("btnradio", "").toLowerCase());
    }

    // Update tasks input
    function updateTasksInput() {
        const tasks = [];
        $(".task-input").each(function () {
            const val = $(this).val().trim();
            if (val) tasks.push(val);
        });
        $("#tasksInput").val(JSON.stringify(tasks));
    }

    // Add this new function to handle frequency duration
    function updateFrequencyDuration() {
        const frequency = $("#frequency_form1").val();
        let duration = [];

        if (frequency === "Weekly") {
            duration = $(".day-checkbox:checked")
                .map(function () {
                    return $(this).val();
                })
                .get();
        } else if (frequency === "Monthly") {
            duration = [$("#monthly_day_form1").val()];
        } else if (frequency === "Yearly") {
            duration = [$("#yearly_date_input_form1").val()];
        } else if (frequency === "Periodic") {
            duration = [$("#periodic_interval_form1").val()];
        } else if (frequency === "Custom") {
            duration = [
                $("#custom_frequency_dropdown_form1").val(),
                $("#occurs_every_dropdown_form1").val(),
            ];
        }

        // Add to form as hidden input if not exists
        if ($("#frequencyDurationInput").length === 0) {
            $("form").append(
                '<input type="hidden" name="frequency_duration_json" id="frequencyDurationInput">'
            );
        }
        $("#frequencyDurationInput").val(JSON.stringify(duration));
    }

    // Update links input more reliably
    function updateLinksInput() {
        const links = [];
        $(".link-input").each(function () {
            const val = $(this).val().trim();
            if (val) links.push(val);
        });

        // Add to form as hidden input if not exists
        if ($("#linksInput").length === 0) {
            $("form").append(
                '<input type="hidden" name="links_json" id="linksInput">'
            );
        }
        $("#linksInput").val(JSON.stringify(links));
    }

    // Update reminders input
    function updateRemindersInput() {
        const reminders = [];
        $('input[name="reminders[]"]:checked').each(function () {
            reminders.push($(this).val());
        });
        $("#remindersInput").val(JSON.stringify(reminders));
    }

    // Initialize form elements
    function initializeForm() {
        // Add hidden inputs if they don't exist
        if ($("#linksInput").length === 0) {
            $("form").append(
                '<input type="hidden" name="links_json" id="linksInput">'
            );
        }
        if ($("#remindersInput").length === 0) {
            $("form").append(
                '<input type="hidden" name="reminders_json" id="remindersInput">'
            );
        }
        if ($('input[name="priority"]').length === 0) {
            $("form").append('<input type="hidden" name="priority">');
        }

        // Initialize priority from radio buttons
        updatePriorityInput();
    }

    // Call initialize when document is ready
    $(document).ready(function () {
        initializeForm();

        // Also update when priority changes
        $('input[name="btnradio"]').change(function () {
            updatePriorityInput();
        });
    });
});
