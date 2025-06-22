$(document).ready(function () {
    // Handle view task button
    $(".view-task").click(function () {
        const taskId = $(this).data("id");
        $("#modalEditTaskBtn").data("id", taskId);
        $("#modalDeleteTaskBtn").data("id", taskId);
        $("#task-id").val(taskId);
        $("#document-task-id").val(taskId);
        const $modal = $("#taskDetailModal");

        $modal.modal("show");

        // Load task details
        $.get({
            url: `/personal-tasks/${taskId}`,
            type: "GET",
            dataType: "json",
            success: function (data) {
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
            error: function (xhr, status, error) {
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
                    (status === "completed"
                        ? "badge-success"
                        : status === "in_progress"
                        ? "badge-info"
                        : "badge-secondary")
            );
    }

    function updatePriorityBadge(priority) {
        const $priority = $("#detail-priority").text(priority || "unknown");
        $priority
            .removeClass()
            .addClass(
                "badge badge-pill " +
                    (priority === "high"
                        ? "badge-danger"
                        : priority === "medium"
                        ? "badge-warning"
                        : "badge-success")
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
        $(".delete-note").click(function () {
            const noteId = $(this).data("note-id");
            if (confirm("Are you sure you want to delete this note?")) {
                deleteNote(noteId);
            }
        });

        // Edit note handler
        $(".edit-note").click(function () {
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
            noteItem.find(".cancel-edit").click(function () {
                displayNotes(notesJson);
            });

            // Submit edit handler
            noteItem.find(".edit-note-form").submit(function (e) {
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
            .submit(function (e) {
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
            success: function (response) {
                if (response.success) {
                    fetchTaskDetails(taskId);
                    $("#addNoteForm")[0].reset();
                } else {
                    alert(response.error || "Failed to add note");
                }
            },
            error: function () {
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
            success: function (response) {
                if (response.success) {
                    fetchTaskDetails(taskId);
                } else {
                    alert(response.error || "Failed to update note");
                }
            },
            error: function () {
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
            success: function (response) {
                if (response.success) {
                    fetchTaskDetails(taskId);
                } else {
                    alert(response.error || "Failed to delete note");
                }
            },
            error: function () {
                alert("Error deleting note");
            },
        });
    }

    function fetchTaskDetails(taskId) {
        $.ajax({
            url: `/personal-tasks/${taskId}`,
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data && typeof data === "object") {
                    displayNotes(data.notes || null);
                }
            },
            error: function () {
                console.error("Failed to refresh task details");
            },
        });
    }

    function displayDocuments(taskId) {
        $.ajax({
            url: `/personal-tasks/${taskId}/documents`,
            type: "GET",
            dataType: "json",
            success: function (documents) {
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
            error: function () {
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
                "vnd.openxmlformats-officedocument.wordprocessingml.document":
                    "ti-file",
                "vnd.ms-excel": "ti-file",
                "vnd.openxmlformats-officedocument.spreadsheetml.sheet": "ti-file",
                "vnd.ms-powerpoint": "ti-file",
                "vnd.openxmlformats-officedocument.presentationml.presentation":
                    "ti-file",
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
        $(".delete-doc").click(function () {
            const docId = $(this).data("doc-id");
            if (confirm("Are you sure you want to delete this document?")) {
                deleteDocument(docId);
            }
        });

        // Edit document handler
        $(".edit-doc").click(function () {
            const docId = $(this).data("doc-id");
            const docItem = $(this).closest(".document-item");

            $.ajax({
                url: `/documents/${docId}`,
                type: "GET",
                dataType: "json",
                success: function (doc) {
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
                    docItem.find(".cancel-edit-doc").click(function () {
                        displayDocuments($("#task-id").val());
                    });

                    // Submit edit handler
                    docItem.find(".edit-document-form").submit(function (e) {
                        e.preventDefault();
                        const formData = $(this).serialize();
                        updateDocument(formData);
                    });
                },
                error: function () {
                    alert("Failed to load document details");
                },
            });
        });
    }

    $("#uploadDocumentForm").on("submit", function (e) {
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
            success: function (response) {
                console.log("Upload response:", response);
                if (response.success) {
                    displayDocuments(taskId);
                    form.reset();
                } else {
                    alert(response.error || "Failed to upload document");
                }
            },
            error: function (xhr, status, error) {
                console.error("Upload error:", status, error);
                alert("Upload failed: " + error);
            },
            complete: function () {
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
            success: function (response) {
                if (response.success) {
                    displayDocuments(taskId);
                } else {
                    alert(response.error || "Failed to delete document");
                }
            },
            error: function () {
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
            success: function (response) {
                if (response.success) {
                    displayDocuments(taskId);
                } else {
                    alert(response.error || "Failed to update document");
                }
            },
            error: function () {
                alert("Error updating document");
            },
        });
    }

    function loadTotalTimeSpent(taskId) {
        $.ajax({
            url: `/personal-tasks/${taskId}/time`,
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data && data.total_minutes) {
                    $("#total-time-spent").text(data.total_minutes);
                }
            },
            error: function () {
                console.error("Failed to load time spent");
            },
        });
    }

    // Edit task button
    $(".edit-task").click(function () {
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
    $(".delete-task").click(function () {
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

    // Start timer from modal
    $(".start-timer-from-modal").click(function() {
        const taskId = $("#task-id").val();
        const duration = 25; // Default to pomodoro
        
        $.ajax({
            url: "/personal-tasks/start-timer",
            method: "POST",
            data: {
                task_id: taskId,
                focus_duration: duration,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    alert(response.error || "Failed to start timer");
                }
            },
            error: function() {
                alert("Error starting timer");
            }
        });
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