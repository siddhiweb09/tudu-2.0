@extends('layouts.innerFrame')

@section('main')
<style>
    .translate-profile-dot {
        transform: translate(0%, -70%) !important;
    }
</style>
<div class="container-xxl py-4">
    <div class="row g-4">
        <!-- Left Column (40%) -->
        <div class="col-lg-4">
            <!-- Profile Card with Visual Hierarchy -->
            <div class="card card-borderless shadow-sm ">
                <div class="card-body text-center p-4">
                    <div class="position-relative mb-3">
                        <div class="avatar-xxl position-relative d-inline-block">
                            <img src="{{ asset('assets/images/profile_picture/' . ($proUser->profile_picture ?: 'user.png')) }}"
                                class="rounded-circle shadow border-3 border-primary"
                                width="120" height="120"
                                alt="{{ $proUser->employee_name }}">
                            @if($proUser->status === 'TRUE')
                            <span class="position-absolute bottom-0 end-0 translate-profile-dot bg-success rounded-circle p-2 border-2 border-white"
                                style="width: 20px; height: 20px;"
                                title="Active" data-bs-toggle="tooltip">
                                <span class="visually-hidden">Active</span>
                            </span>
                            @endif
                        </div>
                    </div>
                    <h3 class="mb-1 text-primary">{{ $proUser->employee_name }}</h3>
                    <div class="text-muted mb-3">{{ $proUser->job_title_designation }}</div>

                    <!-- Micro Interactions -->
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <a href="tel:{{ $proUser->mobile_no_personal ?? '#' }}"
                            class="btn btn-sm btn-primary rounded-pill px-3 {{ !$proUser->mobile_no_personal ? 'disabled' : '' }}"
                            data-bs-toggle="tooltip" title="Call">
                            <i class="ti ti-phone"></i>
                        </a>
                        <a href="mailto:{{ $proUser->email_id_personal ?? '#' }}"
                            class="btn btn-sm btn-outline-primary rounded-pill px-3 {{ !$proUser->email_id_personal ? 'disabled' : '' }}"
                            data-bs-toggle="tooltip" title="Email">
                            <i class="ti ti-mail"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3"
                            data-bs-toggle="modal" data-bs-target="#editModal"
                            data-bs-tooltip="tooltip" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </button>
                    </div>

                    <!-- Employee Badge with Hover Effect -->
                    <div class="mb-4">
                        <span class="badge bg-primary rounded-pill px-3 py-2 hover-grow">
                            <i class="ti ti-id me-1"></i> {{ $proUser->employee_code }}
                        </span>
                    </div>

                    <!-- Department/Branch with Visual Icons -->
                    <div class="d-flex flex-column gap-2 mb-4">
                        <div class="d-flex align-items-center justify-content-center text-muted">
                            <i class="ti ti-building-community me-2"></i>
                            <span>{{ $proUser->department }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center text-muted">
                            <i class="ti ti-map-pin me-2"></i>
                            <span>{{ $proUser->branch }} ({{ $proUser->zone }})</span>
                        </div>
                    </div>


                </div>
            </div>

            <!-- Details Card with Tabler Icons -->
            <div class="card card-borderless shadow-sm mt-4">
                <div class="card-header bg-light" style="background-color: var(--light-color)">
                    <h6 class="mb-0 fw-bold" style="color: var(--primary-color)">
                        <i class="ti ti-info-circle me-2"></i>Employee Details
                    </h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                            <span class="fw-bold"><i class="ti ti-id me-2 text-muted"></i>Employee Code</span>
                            <span class="text-muted">{{ $proUser->employee_code }}</span>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                            <span class="fw-bold"><i class="ti ti-building me-2 text-muted"></i>Department</span>
                            <span class="text-muted">{{ $proUser->department }}</span>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                            <span class="fw-bold"><i class="ti ti-badge me-2 text-muted"></i>Designation</span>
                            <span class="text-muted">{{ $proUser->job_title_designation }}</span>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                            <span class="fw-bold"><i class="ti ti-building-skyscraper me-2 text-muted"></i>Branch</span>
                            <span class="text-muted">{{ $proUser->branch }}</span>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                            <span class="fw-bold"><i class="ti ti-map me-2 text-muted"></i>Zone</span>
                            <span class="text-muted">{{ $proUser->zone }}</span>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                            <span class="fw-bold"><i class="ti ti-clock me-2 text-muted"></i>Shift</span>
                            <span class="text-muted">{{ $proUser->shift_details }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column (60%) -->
        <div class="col-md-8">
            <!-- Main Profile Card -->
            <div class="card card-borderless shadow-sm ">
                <div class="card-header bg-light" style="background-color: var(--light-color)">
                    <h5 class="mb-0 fw-bold" style="color: var(--primary-color)">
                        <i class="ti ti-user me-2"></i>Profile Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-sm-4 d-flex align-items-center">
                            <i class="ti ti-user me-2 text-muted"></i>
                            <h6 class="mb-0 fw-bold">Full Name</h6>
                        </div>
                        <div class="col-sm-8 text-muted">
                            {{ $proUser->employee_name }}
                        </div>
                    </div>
                    <hr class="my-2">

                    <div class="row mb-3">
                        <div class="col-sm-4 d-flex align-items-center">
                            <i class="ti ti-gender-female me-2 text-muted"></i>
                            <h6 class="mb-0 fw-bold">Gender</h6>
                        </div>
                        <div class="col-sm-8 text-muted">
                            {{ $proUser->gender }}
                        </div>
                    </div>
                    <hr class="my-2">

                    <div class="row mb-3">
                        <div class="col-sm-4 d-flex align-items-center">
                            <i class="ti ti-cake me-2 text-muted"></i>
                            <h6 class="mb-0 fw-bold">Date of Birth</h6>
                        </div>
                        <div class="col-sm-8 text-muted">
                            {{ $proUser->dob !== '0000-00-00' ? \Carbon\Carbon::parse($proUser->dob)->format('d M Y') : 'Not specified' }}
                        </div>
                    </div>
                    <hr class="my-2">

                    <div class="row mb-3">
                        <div class="col-sm-4 d-flex align-items-center">
                            <i class="ti ti-calendar-event me-2 text-muted"></i>
                            <h6 class="mb-0 fw-bold">Date of Joining</h6>
                        </div>
                        <div class="col-sm-8 text-muted">
                            {{ $proUser->doj !== '0000-00-00' ? \Carbon\Carbon::parse($proUser->doj)->format('d M Y') : 'Not specified' }}
                        </div>
                    </div>
                    <hr class="my-2">

                    <div class="row mb-3">
                        <div class="col-sm-4 d-flex align-items-center">
                            <i class="ti ti-mail me-2 text-muted"></i>
                            <h6 class="mb-0 fw-bold">Official Email</h6>
                        </div>
                        <div class="col-sm-8 text-muted">
                            {{ $proUser->email_id_official ?? 'Not specified' }}
                        </div>
                    </div>
                    <hr class="my-2">

                    <div class="row mb-3">
                        <div class="col-sm-4 d-flex align-items-center">
                            <i class="ti ti-mail-opened me-2 text-muted"></i>
                            <h6 class="mb-0 fw-bold">Personal Email</h6>
                        </div>
                        <div class="col-sm-8 text-muted">
                            {{ $proUser->email_id_personal ?? 'Not specified' }}
                        </div>
                    </div>
                    <hr class="my-2">

                    <div class="row mb-3">
                        <div class="col-sm-4 d-flex align-items-center">
                            <i class="ti ti-phone-call me-2 text-muted"></i>
                            <h6 class="mb-0 fw-bold">Official Mobile</h6>
                        </div>
                        <div class="col-sm-8 text-muted">
                            {{ $proUser->mobile_no_official ?? 'Not specified' }}
                        </div>
                    </div>
                    <hr class="my-2">

                    <div class="row mb-3">
                        <div class="col-sm-4 d-flex align-items-center">
                            <i class="ti ti-device-mobile me-2 text-muted"></i>
                            <h6 class="mb-0 fw-bold">Personal Mobile</h6>
                        </div>
                        <div class="col-sm-8 text-muted">
                            {{ $proUser->mobile_no_personal ?? 'Not specified' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Card with Enhanced Visuals -->
            <div class="card card-borderless shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0" style="color: var(--primary-color)"><i class="ti ti-chart-bar me-2"></i>Statistics</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="ti ti-check text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted">Status</small>
                                        <h5 class="mb-0">{{ $proUser->status === 'TRUE' ? 'Active' : 'Inactive' }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                        <i class="ti ti-clock text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted">Total Project</small>
                                        <h5 class="mb-0">{{ $totalProjects }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                        <i class="ti ti-list-check text-warning"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted">Task Completion</small>
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0">{{ $taskCompletion }}%</h5>
                                            <small class="text-muted">({{ $completedTaskCount }} / {{ $taskCount }})</small>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal with Tabler Icons -->
<form id="change_pro_pic" enctype="multipart/form-data">
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="ti ti-edit me-2"></i>Edit Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><i class="ti ti-photo me-1"></i>Profile Picture</label>
                        <input type="file" name="profile_picture" id="formFile" class="form-control">
                        <input type="hidden" id="user_id" name="user_id" value="{{ $proUser->id }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="ti ti-check me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('customCss')
<style>

</style>
@endsection

@section('customJs')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enable tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Add hover class to interactive elements
        const hoverElements = document.querySelectorAll('.hover-effect, .hover-grow');
        hoverElements.forEach(el => {
            el.style.transition = 'all 0.2s ease';
        });
    });
    $(document).on("submit", "#change_pro_pic", function(event) {
        event.preventDefault(); // Prevent default form submission

        let formData = new FormData(this);
        let userId = $("#user_id").val();

        $.ajax({
            type: "POST",
            url: "{{ route('user.changepicture', ':id') }}".replace(':id', userId),
            data: formData,
            processData: false, // Required for FormData
            contentType: false, // Required for FormData
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}" // Adding CSRF token
            },
            beforeSend: function() {
                $("#submitBtn").prop("disabled", true).text("Submiting..."); // Disable button
            },
            success: function(response) {
                $("#submitBtn").prop("disabled", false).text("Submit"); // Re-enable button

                if (response.status === "success") {
                    $("#editModal").modal("hide");
                    $("#formFile").empty();
                    Swal.fire(
                        "Good job!",
                        "You have succesfully changed the profile picture.",
                        "success"
                    ).then(() => {
                        window.location.reload(); // Reloads the page after user clicks "OK"
                    });

                } else {
                    $("#al-danger-alert").modal("show");
                    $("#message").text(response.response.message || "Failed to Submit.");
                }
            },
            error: function(xhr) {
                $("#submitBtn").prop("disabled", false).text("Submit"); // Re-enable button

                let errorMsg = "Something went wrong. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                alert(errorMsg);
                console.error("Error in Submiting:", xhr);
            },
        });
    });
</script>
@endsection