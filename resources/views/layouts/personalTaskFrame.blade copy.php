<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />

    <!-- Core Css -->
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="stylesheet" href="assets/css/buttons.css" />

    <title>Make Your Tasks Easy</title>
</head>

<body class="loading">

    <!-- Preloader -->
    <div class="preloader">
        <div class="scene">
            <div class="shadow"></div>
            <div class="jumper">
                <div class="spinner">
                    <div class="scaler">
                        <div class="loader">
                            <div class="cuboid">
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="main-wrapper">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg navbar-light bg-blue d-flex flex-wrap p-0">
            <div class="col-12 border-bottom py-2">
                <div class="container">
                    <div class="d-flex justify-content-between">
                        <a class="navbar-brand" href="#"><img src="assets/images/logo.png" class="logo" /></a>

                        <!-- Example single danger button -->
                        <div class="dropdown-center">
                            <button class="btn p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="assets/images/logo.png" class="rounded-circle profile-pic" />
                            </button>
                            <ul class="dropdown-menu profile-menu">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12 py-2">
                <div class="container">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mb-2 mb-lg-0 d-flex justify-content-between w-100">
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-home-2 menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-list-details menu-icon"></i>
                                    <span class="menu-title">My Tasks</span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('tasks.allTasks') }}">Tasks</a></li>
                                    <li><a class="dropdown-item" href="#">Pending Tasks</a></li>
                                    <li><a class="dropdown-item" href="#">Delayed Tasks</a></li>
                                    <li><a class="dropdown-item" href="#">Completed Tasks</a></li>
                                    <li><a class="dropdown-item" href="{{ route('personal-tasks.index') }}">To Do List</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-presentation-analytics menu-icon"></i>
                                    <span class="menu-title">Projects</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-table-plus menu-icon"></i>
                                    <span class="menu-title">Quick Add Task </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('tasks.calender') }}">
                                    <i class="ti ti-calendar-week menu-icon"></i>
                                    <span class="menu-title">Calendar View</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-tags menu-icon"></i>
                                    <span class="menu-title">Tags / Labels</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('helpAndSupport') }}">
                                    <i class="ti ti-info-square-rounded menu-icon"></i>
                                    <span class="menu-title">Support</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!--  Navbar End -->
        <div class="page-wrapper " style="min-height: 100vh;">
            @yield('main')
        </div>
    </div>



    @extends('modalCreateTask')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
                preloader.style.transition = 'opacity 0.5s ease';
            }
        });
    </script>
    <script>
        $(document).ready(function() {
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
            initializeForm();

            // Also update when priority changes
            $('input[name="btnradio"]').change(function() {
                updatePriorityInput();
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
                        if (!data) {
                            throw new Error("Invalid data received");
                        }

                        // Update basic task info
                        $("#detail-title").text(data.title || "No title");
                        $("#detail-description").text(data.description || "No description");

                        // Update status and priority badges
                        updateStatusBadge(data.status);
                        updatePriorityBadge(data.priority);

                        // Format and display due date
                        $("#detail-due-date").text(
                            data.due_date ? formatDate(data.due_date) : "No deadline"
                        );

                        // Update category
                        $("#detail-category")
                            .text(data.category || "None")
                            .css("background-color", getCategoryColor(data.category));

                        // Handle habit section
                        if (data.is_habit) {
                            $("#detail-habit-frequency").text(data.habit_frequency || "Not specified");
                            $("[data-habit-section]").show();
                        } else {
                            $("[data-habit-section]").hide();
                        }

                        // Display notes and documents
                        displayNotes(taskId, data.notes || null);
                        displayDocuments(taskId);

                        // Initialize form handlers
                        initFormHandlers(taskId);
                    },
                    error: function(xhr) {
                        console.error("AJAX Error:", xhr.responseText);
                        $modal.find(".modal-body").html(
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
                    url: "/add-personal-tasks-notes",
                    method: "POST",
                    data: {
                        task_id: taskId,
                        content: content,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            displayNotes(taskId, response.notes);
                            $("#addNoteForm")[0].reset();
                        } else {
                            alert(response.error || "Failed to add note");
                        }
                    },
                    error: function(xhr) {
                        console.error("Error adding note:", xhr.responseText);
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

            // Update the displayDocuments function
            function displayNotes(taskId, notesJson) {
                const notesContainer = $("#detail-notes");
                notesContainer.empty();

                if (!notesJson) {
                    notesContainer.html('<div class="alert alert-light">No notes yet</div>');
                    return;
                }

                try {
                    const notes = typeof notesJson === 'string' ? JSON.parse(notesJson) : notesJson;

                    if (!notes || !notes.length) {
                        notesContainer.html('<div class="alert alert-light">No notes yet</div>');
                        return;
                    }

                    let html = '<div class="notes-list">';
                    notes.forEach((note) => {
                        html += `
                <div class="note-item card mb-3" data-note-id="${note.id}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <small class="text-muted">
                            <i class="ti-time mr-1"></i>${new Date(note.created_at).toLocaleString()}
                        </small>
                        <div class="note-actions">
                            <button class="btn btn-sm btn-outline-primary edit-note mr-1" data-note-id="${note.id}">
                                <i class="ti-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-note" data-note-id="${note.id}">
                                <i class="ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="note-content mt-2">${note.content.replace(/\n/g, "<br>")}</div>
                </div>
                </div>`;
                        });
                        html += "</div>";
                        notesContainer.html(html);

                        initNoteActionHandlers(taskId);
                    } catch (e) {
                        console.error("Error parsing notes:", e);
                        notesContainer.html(`
                <div class="alert alert-info">
                    <h5>Notes History</h5>
                    <div style="white-space: pre-line">${notesJson.replace(/\n/g, "<br>")}</div>
                </div>
            `);
                    }
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

            // Update document upload handler
            $("#uploadDocumentForm").off('submit').on("submit", function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);
                const taskId = $("#task-id").val();

                // Validate file was selected
                const fileInput = document.getElementById("documentFile");
                if (fileInput.files.length === 0) {
                    alert("Please select a file to upload");
                    return;
                }

                // Show loading state
                const submitBtn = $(form).find("button[type=submit]");
                submitBtn.prop("disabled", true).html('<i class="ti-reload mr-1 spinning"></i> Uploading...');

                $.ajax({
                    url: `/personal-tasks/${taskId}/documents/upload`,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            displayDocuments(taskId);
                            form.reset();
                        } else {
                            alert(response.error || "Failed to upload document");
                        }
                    },
                    error: function(xhr) {
                        console.error("Upload error:", xhr.responseText);
                        alert("Upload failed: " + (xhr.responseJSON?.error || 'Unknown error'));
                    },
                    complete: function() {
                        submitBtn.prop("disabled", false).html('<i class="ti-upload mr-1"></i> Upload');
                    }
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

    @yield('customJs')
</body>

</html>