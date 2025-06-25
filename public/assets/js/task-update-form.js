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