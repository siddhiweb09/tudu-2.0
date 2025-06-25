// Form submission handler
$(`#comment-form`).on("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    console.log(formData);

    try {
        $.ajax({
            url: '/add-comment',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: function () {
                // Show loading indicator
                $(`#${formId} #submitBtn`)
                    .prop("disabled", true)
                    .html('<i class="ti ti-loader me-2"></i>Processing...');
            },
            success: function (response) {
                if (response.status) {
                    window.location.reload;
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
                $(`#${formId} #submitBtn`)
                    .prop("disabled", false)
                    .html("Create Task");
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
        $(`#comment-form #submitBtn`).prop("disabled", false).html("Create Task");
    }
});
