@extends('layouts.personalTaskFrame')

@section('main')

<style>
    .view-container {
        min-height: 500px;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 1.5rem;
    }

    .task-card {
        transition: all 0.2s ease;
        border-left: 4px solid;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .high-priority {
        border-left-color: #dc3545;
    }

    .medium-priority {
        border-left-color: #fd7e14;
    }

    .low-priority {
        border-left-color: #28a745;
    }
</style>
<!-- AI Suggestions -->
<div class="personal-tasks-container p-4">
    <!-- Header with View Controls -->
    <div class=" row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-3 mb-md-0">
                    <h2 class="fw-bold mb-0">
                        <i class="ti ti-checklist me-2"></i>My Personal Tasks
                    </h2>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <!-- View Selector -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button"
                            id="viewDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-layout-grid me-1"></i>
                            <span class="view-label">{{ ucfirst($view) }}</span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="viewDropdown">
                            <li>
                                <a class="dropdown-item view-switcher" href="#" data-view="list">
                                    <i class="ti ti-list me-2"></i>List View
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item view-switcher" href="#" data-view="kanban">
                                    <i class="ti ti-layout-kanban me-2"></i>Kanban Board
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item view-switcher" href="#" data-view="calendar">
                                    <i class="ti ti-calendar me-2"></i>Calendar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item view-switcher" href="#" data-view="matrix">
                                    <i class="ti ti-urgent me-2"></i>Priority Matrix
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Add Task Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addTaskModal">
                        <i class="ti ti-plus me-1"></i>Add Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Content Container -->
    <div class="row">
        <div class="col-12">
            <div id="view-container" class="animate__animated animate__fadeIn">
                @include("personal-tasks.{$view}")
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
@include('personal-tasks.partials.modals')
@endsection

@section('customJs')
<script>
    $(document).ready(function() {
        // View switcher - handle both direct links and AJAX
        $('.view-switcher').click(function(e) {
            e.preventDefault();
            const view = $(this).data('view');
            window.history.pushState({}, '', `/personal-tasks/${view}`);
            loadView(view);
        });

        // Function to load view content
        function loadView(view) {
            $.ajax({
                url: "{{ route('personal-tasks.index') }}",
                data: {
                    view: view
                },
                success: function(response) {
                    if (typeof response.html !== 'undefined') {
                        $('#view-container').html(response.html);
                    } else {
                        $('#view-container').html($(response).find('#view-container').html());
                    }

                    // Update active view label
                    $('.view-label').text(view.charAt(0).toUpperCase() + view.slice(1));

                    // Add animation
                    $('#view-container').addClass('animate__animated animate__fadeIn');
                    setTimeout(() => {
                        $('#view-container').removeClass('animate__fadeIn');
                    }, 500);
                }
            });
        }

        // Handle browser back/forward
        window.onpopstate = function() {
            const path = window.location.pathname;
            const view = path.split('/').pop();
            if (['list', 'kanban', 'calendar', 'matrix'].includes(view)) {
                loadView(view);
            }
        };

        // Calendar navigation
        $(document).on('click', '.calendar-nav', function(e) {
            e.preventDefault();
            const month = $(this).data('month');
            const year = $(this).data('year');

            $.ajax({
                url: "{{ route('personal-tasks.index') }}",
                data: {
                    view: 'calendar',
                    month: month,
                    year: year
                },
                success: function(response) {
                    if (typeof response.html !== 'undefined') {
                        $('#view-container').html(response.html);
                    } else {
                        $('#view-container').html($(response).find('#view-container').html());
                    }
                }
            });
        });

        //   personal task 
        // Step navigation
        const $nextButtons = $(".next-step");
        const $prevButtons = $(".prev-step");
        const $formSteps = $(".form-step");
        const $stepIndicators = $(".step");

        $nextButtons.on("click", function() {
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

        $prevButtons.on("click", function() {
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
            $stepIndicators.each(function() {
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

        $("#flexSwitchCheckDefault").on("change", function() {
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

        $("#frequency_form1").on("change", function() {
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

        // Documents
        const $documentsContainer = $("#documentsContainer");
        const $addDocumentButton = $("#addDocument");

        $addDocumentButton.on("click", function() {
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
            $documentsContainer.append(newDocument);
        });

        // Handle document removal
        $documentsContainer.on("click", ".remove-document", function() {
            $(this).closest(".document-item").remove();
        });


        // Update all inputs before form submission
        $("form").on("submit", function(e) {
            // Prevent default form submission
            e.preventDefault();

            // Update all dynamic inputs
            updatePriorityInput();
            updateRemindersInput();
            updateFrequencyDuration();
            // Serialize form data including our hidden inputs
            const formData = new FormData(this);
            formData.delete("frequency_duration[]");
            formData.delete("reminders[]");

            // Submit the form via AJAX
            $.ajax({
                url: "/add-personal-tasks",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Laravel
                },
                beforeSend: function() {
                    // Show loading indicator
                    $("#submitBtn")
                        .prop("disabled", true)
                        .html('<i class="ti ti-loader me-2"></i>Processing...');
                },
                success: function(response) {
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

        // Add this new function to handle frequency duration
        function updateFrequencyDuration() {
            const frequency = $("#frequency_form1").val();
            let duration = [];

            if (frequency === "Weekly") {
                duration = $(".day-checkbox:checked")
                    .map(function() {
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

        // Update reminders input
        function updateRemindersInput() {
            const reminders = [];
            $('input[name="reminders[]"]:checked').each(function() {
                reminders.push($(this).val());
            });
            $("#remindersInput").val(JSON.stringify(reminders));
        }

        // Initialize form elements
        function initializeForm() {
            // Add hidden inputs if they don't exist

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
        $(document).ready(function() {
            initializeForm();

            // Also update when priority changes
            $('input[name="btnradio"]').change(function() {
                updatePriorityInput();
            });
        });


        $(".view-task").click(function() {
            const taskId = $(this).data("id");
            $("#modalEditTaskBtn").data("id", taskId);
            $("#modalDeleteTaskBtn").data("id", taskId);
            $("#task-id").val(taskId);
            $("#document-task-id").val(taskId);
            const $modal = $("#taskDetailModal");

            $modal.modal("show");

            // Load task details
            $.get({
                url: `/personal-tasks-show/${taskId}`,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("Received data:", data);
                    if (!data || typeof data !== "object") {
                        throw new Error("Invalid data received");
                    }

                    // Update basic task info
                    $("#detail-title").text(data.title || "No title");
                    $("#detail-description").text(data.description || "No description");

                    // Update status with appropriate badge
                    updateStatusBadge(data.status);

                    // Update priority with appropriate badge
                    updatePriorityBadge(data.priority);

                    // Format and display due date
                    $("#detail-due-date").text(
                        data.due_date ? formatDate(data.due_date) : "No deadline"
                    );

                    // Update category with color
                    $("#detail-category")
                        .text(data.category || "None")
                        .css("background-color", getCategoryColor(data.category));

                    // Handle habit section visibility
                    if (data.is_habit) {
                        $("#detail-habit-frequency").text(
                            data.habit_frequency || "Not specified"
                        );
                        $("[data-habit-section]").show();
                    } else {
                        $("[data-habit-section]").hide();
                    }

                    // Display notes
                    displayNotes(data.notes || null);

                    displayDocuments(taskId);

                    // Initialize form submission handlers
                    initFormHandlers(taskId);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    $modal
                        .find(".modal-body")
                        .html(
                            '<div class="alert alert-danger">Failed to load task details. Please check console for errors.</div>'
                        );
                },
            });

            loadTotalTimeSpent(taskId);
        });

        // Helper functions
        function updateStatusBadge(status) {
            const $status = $("#detail-status").text(status || "unknown");
            $status
                .removeClass()
                .addClass(
                    "badge badge-pill " +
                    (status === "completed" ?
                        "badge-success" :
                        status === "in_progress" ?
                        "badge-info" :
                        "badge-secondary")
                );
        }

        function updatePriorityBadge(priority) {
            const $priority = $("#detail-priority").text(priority || "unknown");
            $priority
                .removeClass()
                .addClass(
                    "badge badge-pill " +
                    (priority === "high" ?
                        "badge-danger" :
                        priority === "medium" ?
                        "badge-warning" :
                        "badge-success")
                );
        }

        function formatDate(dateString) {
            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }

        function displayNotes(notesJson) {
            const notesContainer = $("#detail-notes");
            notesContainer.empty();

            if (!notesJson) {
                notesContainer.html(
                    '<div class="alert alert-light">No notes yet</div>'
                );
                return;
            }

            try {
                const notes = JSON.parse(notesJson);
                if (!notes.length) {
                    notesContainer.html(
                        '<div class="alert alert-light">No notes yet</div>'
                    );
                    return;
                }

                let html = '<div class="notes-list">';
                notes.forEach((note, index) => {
                    if (!note.id) {
                        console.warn("Note missing ID:", note);
                        return;
                    }

                    html += `
                    <div class="note-item card mb-3" data-note-id="${note.id}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <small class="text-muted">
                                    <i class="ti-time mr-1"></i>${note.timestamp}
                                </small>
                                <div class="note-actions">
                                    <button class="btn btn-sm btn-outline-primary edit-note mr-1" data-note-id="${
                                      note.id
                                    }">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-note" data-note-id="${
                                      note.id
                                    }">
                                        <i class="ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="note-content mt-2">${note.content.replace(
                              /\n/g,
                              "<br>"
                            )}</div>
                        </div>
                    </div>`;
                });
                html += "</div>";
                notesContainer.html(html);

                // Initialize note action handlers
                initNoteActionHandlers(notesJson);
            } catch (e) {
                console.error("Error parsing notes:", e);
                // Fallback for plain text notes
                notesContainer.html(
                    `<div class="alert alert-info">
                        <h5>Notes History</h5>
                        <div style="white-space: pre-line">${notesJson.replace(
                          /\n/g,
                          "<br>"
                        )}</div>
                    </div>`
                );
            }
        }

        function initNoteActionHandlers(notesJson) {
            // Delete note handler
            $(".delete-note").click(function() {
                const noteId = $(this).data("note-id");
                if (confirm("Are you sure you want to delete this note?")) {
                    deleteNote(noteId);
                }
            });

            // Edit note handler
            $(".edit-note").click(function() {
                const noteId = $(this).data("note-id");
                const noteItem = $(this).closest(".note-item");
                const currentContent = noteItem
                    .find(".note-content")
                    .html()
                    .replace(/<br\s*[\/]?>/gi, "\n");

                noteItem.html(`
                <div class="card-body">
                    <form class="edit-note-form">
                        <div class="form-group">
                            <textarea class="form-control" rows="3" name="note">${currentContent}</textarea>
                        </div>
                        <input type="hidden" name="note_id" value="${noteId}">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary mr-2 cancel-edit">
                                <i class="ti-close mr-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti-save mr-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            `);

                // Cancel edit handler
                noteItem.find(".cancel-edit").click(function() {
                    displayNotes(notesJson);
                });

                // Submit edit handler
                noteItem.find(".edit-note-form").submit(function(e) {
                    e.preventDefault();
                    const newContent = $(this).find("[name=note]").val().trim();
                    if (newContent) {
                        updateNote(noteId, newContent);
                    }
                });
            });
        }

        function initFormHandlers(taskId) {
            // Add note form handler
            $("#addNoteForm")
                .off("submit")
                .submit(function(e) {
                    e.preventDefault();
                    const noteContent = $(this).find("[name=note]").val().trim();
                    if (noteContent) {
                        addNote(taskId, noteContent);
                    }
                });
        }

        function addNote(taskId, content) {
            $.ajax({
                url: "/add-note",
                method: "POST",
                data: {
                    task_id: taskId,
                    note: content,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        fetchTaskDetails(taskId);
                        $("#addNoteForm")[0].reset();
                    } else {
                        alert(response.error || "Failed to add note");
                    }
                },
                error: function() {
                    alert("Error adding note");
                },
            });
        }

        function updateNote(noteId, newContent) {
            const taskId = $("#task-id").val();

            $.ajax({
                url: "/update-note",
                method: "POST",
                data: {
                    task_id: taskId,
                    note_id: noteId,
                    note: newContent,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        fetchTaskDetails(taskId);
                    } else {
                        alert(response.error || "Failed to update note");
                    }
                },
                error: function() {
                    alert("Error updating note");
                },
            });
        }

        function deleteNote(noteId) {
            const taskId = $("#task-id").val();

            $.ajax({
                url: "/delete-note",
                method: "POST",
                data: {
                    task_id: taskId,
                    note_id: noteId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        fetchTaskDetails(taskId);
                    } else {
                        alert(response.error || "Failed to delete note");
                    }
                },
                error: function() {
                    alert("Error deleting note");
                },
            });
        }

        function fetchTaskDetails(taskId) {
            $.ajax({
                url: `/personal-tasks/${taskId}`,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data && typeof data === "object") {
                        displayNotes(data.notes || null);
                    }
                },
                error: function() {
                    console.error("Failed to refresh task details");
                },
            });
        }

        function displayDocuments(taskId) {
            $.ajax({
                url: `/personal-tasks/${taskId}/documents`,
                type: "GET",
                dataType: "json",
                success: function(documents) {
                    const container = $("#detail-documents");
                    container.empty();

                    if (!documents || documents.length === 0) {
                        container.html(
                            '<div class="alert alert-light">No documents uploaded yet</div>'
                        );
                        return;
                    }

                    let html = '<div class="document-list">';
                    documents.forEach((doc) => {
                        html += `
                        <div class="document-item card mb-3" data-doc-id="${doc.id}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            ${getDocumentIcon(doc.file_type)} ${
                          doc.file_name
                        }
                                        </h6>
                                        <small class="text-muted">
                                            ${formatFileSize(doc.file_size)} • 
                                            ${new Date(doc.created_at).toLocaleString()}
                                        </small>
                                        ${
                                          doc.description
                                            ? `<p class="mt-2 mb-1">${doc.description}</p>`
                                            : ""
                                        }
                                    </div>
                                    <div class="document-actions">
                                        <button class="btn btn-sm btn-outline-primary edit-doc mr-1" data-doc-id="${
                                          doc.id
                                        }">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-doc" data-doc-id="${
                                          doc.id
                                        }">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="document-preview mt-2">
                                    ${getDocumentPreview(doc)}
                                </div>
                            </div>
                        </div>`;
                    });
                    html += "</div>";
                    container.html(html);

                    initDocumentActionHandlers();
                },
                error: function() {
                    console.error("Failed to load documents");
                },
            });
        }

        function getDocumentIcon(fileType) {
            const type = fileType.split("/")[0];
            const icons = {
                image: "ti-image",
                application: {
                    pdf: "ti-file",
                    msword: "ti-file",
                    "vnd.openxmlformats-officedocument.wordprocessingml.document": "ti-file",
                    "vnd.ms-excel": "ti-file",
                    "vnd.openxmlformats-officedocument.spreadsheetml.sheet": "ti-file",
                    "vnd.ms-powerpoint": "ti-file",
                    "vnd.openxmlformats-officedocument.presentationml.presentation": "ti-file",
                    zip: "ti-zip",
                    "x-rar-compressed": "ti-zip",
                    "x-7z-compressed": "ti-zip",
                },
                text: "ti-file",
                audio: "ti-music-alt",
                video: "ti-video-clapper",
            };

            if (type === "application") {
                const subtype = fileType.split("/")[1];
                return `<i class="${
              icons.application[subtype] || "ti-file"
            } mr-2"></i>`;
            }
            return `<i class="${icons[type] || "ti-file"} mr-2"></i>`;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB", "GB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
        }

        function getDocumentPreview(doc) {
            const type = doc.file_type.split("/")[0];
            const fileUrl = "/storage/" + doc.file_path;

            if (type === "image") {
                return `<img src="${fileUrl}" class="img-thumbnail" style="max-height: 200px;">`;
            }

            if (doc.file_type === "application/pdf") {
                return `
            <div class="pdf-preview">
                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="ti-eye mr-1"></i> View PDF
                </a>
                <embed src="${fileUrl}#toolbar=0&navpanes=0" width="100%" height="300px" type="application/pdf">
            </div>`;
            }

            return `
        <div class="file-preview">
            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="ti-download mr-1"></i> Download File
            </a>
        </div>`;
        }

        function initDocumentActionHandlers() {
            // Delete document handler
            $(".delete-doc").click(function() {
                const docId = $(this).data("doc-id");
                if (confirm("Are you sure you want to delete this document?")) {
                    deleteDocument(docId);
                }
            });

            // Edit document handler
            $(".edit-doc").click(function() {
                const docId = $(this).data("doc-id");
                const docItem = $(this).closest(".document-item");

                $.ajax({
                    url: `/documents/${docId}`,
                    type: "GET",
                    dataType: "json",
                    success: function(doc) {
                        docItem.html(`
                        <div class="card-body">
                            <form class="edit-document-form">
                                <div class="form-group">
                                    <label>File Name</label>
                                    <input type="text" class="form-control" name="file_name" value="${
                                      doc.file_name
                                    }">
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description">${
                                      doc.description || ""
                                    }</textarea>
                                </div>
                                <input type="hidden" name="doc_id" value="${docId}">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary mr-2 cancel-edit-doc">
                                        <i class="ti-close mr-1"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti-save mr-1"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    `);

                        // Cancel edit handler
                        docItem.find(".cancel-edit-doc").click(function() {
                            displayDocuments($("#task-id").val());
                        });

                        // Submit edit handler
                        docItem.find(".edit-document-form").submit(function(e) {
                            e.preventDefault();
                            const formData = $(this).serialize();
                            updateDocument(formData);
                        });
                    },
                    error: function() {
                        alert("Failed to load document details");
                    },
                });
            });
        }

        $("#uploadDocumentForm").on("submit", function(e) {
            e.preventDefault();
            e.stopPropagation(); // Add this to prevent any parent form handlers

            const form = this;
            const formData = new FormData(form);
            const taskId = $("#task-id").val();

            // Debug: Log FormData contents
            for (let pair of formData.entries()) {
                console.log(pair[0] + ": " + pair[1]);
            }

            // Validate file was selected
            const fileInput = document.getElementById("documentFile");
            if (fileInput.files.length === 0) {
                alert("Please select a file to upload");
                return;
            }

            // Show loading state
            const submitBtn = $(form).find("button[type=submit]");
            submitBtn
                .prop("disabled", true)
                .html('<i class="ti-reload mr-1 spinning"></i> Uploading...');

            console.log(formData);

            $.ajax({
                url: `/personal-tasks/${taskId}/documents`,
                type: "POST",
                data: formData,
                processData: false, // Crucial for file uploads
                contentType: false, // Crucial for file uploads
                cache: false,
                success: function(response) {
                    console.log("Upload response:", response);
                    if (response.success) {
                        displayDocuments(taskId);
                        form.reset();
                    } else {
                        alert(response.error || "Failed to upload document");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Upload error:", status, error);
                    alert("Upload failed: " + error);
                },
                complete: function() {
                    submitBtn
                        .prop("disabled", false)
                        .html('<i class="ti-upload mr-1"></i> Upload');
                },
            });
        });

        function deleteDocument(docId) {
            const taskId = $("#task-id").val();

            $.ajax({
                url: `/documents/${docId}`,
                method: "DELETE",
                data: {
                    task_id: taskId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        displayDocuments(taskId);
                    } else {
                        alert(response.error || "Failed to delete document");
                    }
                },
                error: function() {
                    alert("Error deleting document");
                },
            });
        }

        function updateDocument(formData) {
            const taskId = $("#task-id").val();

            $.ajax({
                url: `/documents/${formData.doc_id}`,
                method: "PUT",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        displayDocuments(taskId);
                    } else {
                        alert(response.error || "Failed to update document");
                    }
                },
                error: function() {
                    alert("Error updating document");
                },
            });
        }

        function loadTotalTimeSpent(taskId) {
            $.ajax({
                url: `/personal-tasks/${taskId}/time`,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data && data.total_minutes) {
                        $("#total-time-spent").text(data.total_minutes);
                    }
                },
                error: function() {
                    console.error("Failed to load time spent");
                },
            });
        }

        // Edit task button
        $(".edit-task").click(function() {
            const taskId = $(this).data("id");

            $.get(`/personal-tasks/${taskId}`, function(data) {
                $("#edit_task_id").val(data.id);
                $("#edit_title").val(data.title);
                $("#edit_description").val(data.description);
                $("#edit_okr").val(data.okr);
                $("#edit_category").val(data.category);

                if (data.due_date) {
                    const dueDate = new Date(data.due_date);
                    const formattedDate = dueDate.toISOString().slice(0, 16);
                    $("#edit_due_date").val(formattedDate);
                }

                $("#edit_priority").val(data.priority);
                $("#edit_time_estimate").val(data.time_estimate);
                $("#edit_is_habit").prop("checked", data.is_habit);

                if (data.is_habit) {
                    $(".edit-habit-frequency").show();
                    $("#edit_habit_frequency").val(data.habit_frequency);
                } else {
                    $(".edit-habit-frequency").hide();
                }

                $("#editTaskModal").modal("show");
            });
        });

        // Delete task button
        $(".delete-task").click(function() {
            const taskId = $(this).data("id");
            if (confirm("Are you sure you want to delete this task?")) {
                $.ajax({
                    url: `/personal-tasks/${taskId}`,
                    method: "DELETE",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            alert(response.error || "Failed to delete task");
                        }
                    },
                    error: function() {
                        alert("Error deleting task");
                    }
                });
            }
        });

        // Category selection handler
        $("#category, #edit_category").change(function() {
            if ($(this).val() === "_new_category") {
                $(this).closest(".form-group").next(".new-category-group").show();
            } else {
                $(this).closest(".form-group").next(".new-category-group").hide();
            }
        });

        // Habit checkbox handler
        $("#is_habit, #edit_is_habit").change(function() {
            if ($(this).is(":checked")) {
                $(this).closest(".form-check").next(".habit-frequency, .edit-habit-frequency").show();
            } else {
                $(this).closest(".form-check").next(".habit-frequency, .edit-habit-frequency").hide();
            }
        });

        // Status change handler
        $(".status-select").change(function() {
            $(this).closest("form").submit();
        });
    });
</script>
@endsection