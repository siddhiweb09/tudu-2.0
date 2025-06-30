$(document).on("submit", ".comment-form", function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const $form = $(form);
    const taskId = $form.data("task-id");

    $.ajax({
        url: "/add-comment",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
            $form
                .find('button[type="submit"]')
                .prop("disabled", true)
                .html('<i class="ti ti-loader me-2"></i>Processing...');
        },
        success: function (response) {
            if (response.status) {
                window.location.reload(); // Or update DOM dynamically
            } else {
                toastr.error(response.message || "An error occurred");
            }
        },
        error: function (xhr) {
            let errorMessage = "An error occurred";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = Object.values(errors)[0][0];
            }
            toastr.error(errorMessage);
        },
        complete: function () {
            $form
                .find('button[type="submit"]')
                .prop("disabled", false)
                .html("Post Reply");
        },
    });
});
// Add new task field
var modalId = "";
$(document).on("shown.bs.modal", ".modal", function () {
    modalId = $(this).attr("id");
    // console.log("Modal ID:", modalId);
});

let taskCounter = 1;

// $(document).on("click", `.add-edit-task-btn`, function () {
//     taskCounter++;
//     const newTask = `
//             <div class="edit-task-item mb-3" data-task-id="${taskCounter}">
//                 <div class="input-group">
//                     <input type="text" class="form-control task-input" name="tasks[]" placeholder="Enter task" required>
//                     <button type="button" class="btn btn-inverse-danger remove-task-btn">
//                         <i class="ti ti-minus"></i>
//                     </button>
//                 </div>
//             </div>
//         `;
//     $(`.edit-task-container`).append(newTask);
// });

// Remove task field
// $(document).on("click", `.remove-edit-task-btn`, function () {
//     if ($(`.edit-task-item`).length > 1) {
//         $(this).closest(".edit-task-item").remove();
//     } else {
//         alert("You need at least one task field!");
//     }
// });

$(document).on("click", ".add-assignees", function () {
    taskCounter++;
    $(`.assignees`).empty();

    const newAssignees = `
        <div class="row w-100 border rounded p-3 bg-white m-0">
            <div class="col-md-6">
                <div class="select-card active-on-hover">
                    <div class="select-card-header">
                        <i class="ti ti-tag me-2"></i>Category
                    </div>
                    <select name="category" class="form-control department-select" required>
                        <option value="">Select Department</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="select-card active-on-hover">
                    <div class="select-card-header">
                        <i class="ti ti-users me-2"></i>Assign To
                    </div>
                    <select name="assign_to[]" class="form-control assign-to-dropdown" required multiple>
                        <option value="">Select User</option>
                    </select>
                </div>
            </div>
        </div>`;

    $(`.assignees`).append(newAssignees);

    $(".department-select").select2({
        placeholder: "Select Department",
        dropdownParent: $("#" + modalId), // Important for modals
        width: "100%",
    });

    $(".assign-to-dropdown").select2({
        placeholder: "Select User",
        dropdownParent: $("#" + modalId), // Important for modals
        width: "100%",
    });

    // Fetch Users Departments
    $.ajax({
        url: "/get-departments",
        method: "GET",
        success: function (response) {
            $(`.department-select`).empty();
            $(`.department-select`).append(
                '<option value="">Select Department Value</option>'
            );
            response.forEach(function (dept) {
                $(`.department-select`).append(
                    `<option value="${dept}">${dept}</option>`
                );
            });
        },
        error: function (xhr) {
            console.error("Error fetching departments:", xhr.responseText);
        },
    });
});

// Fetch User Department Wise
$(document).on("change", `.department-select`, function () {
    const department = $(this).val();

    if (department) {
        $.ajax({
            url: `/get-users-by-department/${department}`,
            method: "GET",
            success: function (response) {
                console.log(response.length);

                $(`.assign-to-dropdown`).empty();
                $(`.assign-to-dropdown`).append(
                    '<option value="">Select User</option>'
                );

                if (response.length === 0) {
                    $(`.assign-to-dropdown`).append(
                        '<option value="">No users found</option>'
                    );
                }

                response.forEach(function (user) {
                    $(`.assign-to-dropdown`).append(
                        `<option value="${user.employee_code}*${user.employee_name}">${user.employee_code}*${user.employee_name}</option>`
                    );
                });
            },
            error: function () {
                $(`.assign-to-dropdown`).append(
                    '<option value="">Error loading users</option>'
                );
            },
        });
    } else {
        $(`.assign-to-dropdown`).append(
            '<option value="">Select User</option>'
        );
    }
});
