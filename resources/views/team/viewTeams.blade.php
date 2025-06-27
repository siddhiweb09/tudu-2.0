@extends('layouts.frame')

@section('main')
    <style>
        .team-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(21, 32, 166, 0.05);
            border: 1px solid rgba(21, 32, 166, 0.1);
        }

        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(21, 32, 166, 0.15);
            border-color: rgba(21, 32, 166, 0.2);
        }

        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-xl {
            width: 60px;
            height: 60px;
        }

        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
        }

        .dropdown-menu {
            border-radius: 12px;
            border: 1px solid rgba(21, 32, 166, 0.1);
        }

        .bg-light-primary {
            background-color: rgba(21, 32, 166, 0.08);
        }

        .btn-light-primary {
            background-color: rgba(21, 32, 166, 0.08);
            color: #1520a6;
            border: none;
        }

        .btn-light-primary:hover {
            background-color: rgba(21, 32, 166, 0.15);
        }
    </style>

    <div class="bg-white border-bottom py-4">
        <div class="container-xxl">
            <!-- Header Section -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="mb-1 fw-semibold text-dark">Team Management</h4>
                    <p class="text-muted mb-0">Organize and manage your teams efficiently</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal"
                        data-bs-target="#createTeamModal">
                        <i class="ti ti-plus me-2"></i>Create New Team
                    </button>
                </div>
            </div>

            <!-- Team Cards Grid -->
            <div class="row g-4">
                @foreach ($teams as $team)
                    @php
                        $teamMembers = json_decode($team->team_members, true);
                        $memberCount = is_array($teamMembers) ? count($teamMembers) : 0;
                    @endphp

                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 h-100 team-card"
                            style="background: linear-gradient(135deg, rgba(21, 32, 166, 0.08) 0%, rgba(21, 32, 166, 0.03) 100%);">
                            <!-- Card Header with Avatar -->
                            <div class="card-header bg-transparent border-0 position-relative pt-4">
                                <div class="d-flex align-items-center gap-3">
                                    @if ($team->team_avatar)
                                        <div class="avatar avatar-xl flex-shrink-0">
                                            <img src="{{ asset('assets/images/team_avatars/' . $team->team_avatar) }}"
                                                class="rounded-circle border border-3 border-white shadow-sm"
                                                alt="{{ $team->team_name }} avatar">
                                        </div>
                                    @else
                                        <div
                                            class="avatar avatar-xl bg-light-primary flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm border border-3 border-white">
                                            <span class="fs-4 text-primary fw-bold">{{ substr($team->team_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 text-dark">{{ $team->team_name }}</h5>
                                        <small class="text-muted">Team ID: {{ $team->team_code }}</small>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 mt-3 me-3">
                                    <span
                                        class="badge rounded-pill bg-white text-primary border border-primary border-opacity-25 shadow-sm">
                                        {{ ucfirst($team->team_visibilty) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body pt-1 pb-3">
                                <!-- Team Leader -->
                                <div class="d-flex align-items-center gap-2 mb-3 p-3 bg-white rounded-3 shadow-sm">
                                    <div class="bg-light-primary p-2 rounded-circle">
                                        <i class="ti ti-crown text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Team Leader</small>
                                        <span class="fw-medium text-dark">{{ $team->team_leader }}</span>
                                    </div>
                                </div>

                                <!-- Members Count -->
                                <div class="d-flex align-items-center gap-2 mb-3 p-3 bg-white rounded-3 shadow-sm">
                                    <div class="bg-light-primary p-2 rounded-circle">
                                        <i class="ti ti-users text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Members</small>
                                        <span class="fw-medium text-dark">{{ $memberCount }}
                                            {{ $memberCount === 1 ? 'member' : 'members' }}</span>
                                    </div>
                                </div>

                                <!-- Members List (Collapsible) -->
                                @if (is_array($teamMembers) && $memberCount > 0)
                                    <div class="mb-3">
                                        <a class="d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="collapse"
                                            href="#membersCollapse-{{ $team->id }}" role="button">
                                            <i class="ti ti-chevron-down text-primary"></i>
                                            <small class="text-primary fw-medium">View all members</small>
                                        </a>
                                        <div class="collapse mt-2" id="membersCollapse-{{ $team->id }}">
                                            <div class="bg-white p-3 rounded-3 shadow-sm">
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($teamMembers as $member)
                                                        <span class="badge bg-light text-dark rounded-pill">{{ $member }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer with Actions -->
                            <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="ti ti-clock me-1"></i>Created {{ $team->created_at->diffForHumans() }}
                                    </small>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light-primary rounded-circle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="ti ti-edit me-2"></i>Edit Team
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="ti ti-trash me-2"></i>Delete Team
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="ti ti-users-plus me-2"></i>Add Members
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if(count($teams) === 0)
                <div class="text-center py-5">
                    <div class="mb-4">
                        <div class="bg-light-primary p-4 rounded-circle d-inline-block">
                            <i class="ti ti-users-off fs-1 text-primary"></i>
                        </div>
                    </div>
                    <h5 class="mb-2">No teams found</h5>
                    <p class="text-muted mb-4">Create your first team to get started</p>
                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#createTeamModal">
                        <i class="ti ti-plus me-2"></i>Create Team
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createTeamModal" tabindex="-1" aria-labelledby="createTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form id="createTeamForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header primary-gradient-effect">
                        <h5 class="modal-title text-white" id="createTeamModalLabel">Create New Team</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Modal form content -->
                        <div class="row">

                            <div class="mb-3 col-12">
                                <label for="teamName" class="form-label">Team Name</label>
                                <input type="text" class="form-control" id="teamName" name="team_name"
                                    placeholder="Enter team name">
                            </div>
                            <div class="mb-3 col-lg-6 col-12">
                                <label for="teamLeader" class="form-label">Team Leader</label>
                                <select class="form-control" id="teamLeader" name="team_leader">
                                    <option value="">Select Team Leader</option>
                                    <option vakue="IN-001*Bindu M Agarwal">IN-001*Bindu M Agarwal</option>
                                </select>
                            </div>
                            <div class="mb-3 col-12">
                                <label for="teamAvatar" class="form-label">Team Avatar</label>
                                <input type="file" class="form-control" id="teamAvatar" name="team_avatar"
                                    accept="image/png, image/jpeg, image/jpg">
                            </div>


                            <div class="mb-3 col-12">
                                <label for="teamMembers" class="form-label">Selcect Team members</label>
                                <select class="form-control select2" id="teamMembers" name="team_members[]" multiple>
                                    @if ($users->isEmpty())
                                        <option value="">No Users Found</option>
                                    @else
                                        @foreach ($users as $user)
                                            <option value="{{ $user->employee_code }}*{{ $user->employee_name }}">
                                                {{ $user->employee_code }}*{{ $user->employee_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3 col-12">
                                <div class="select-card active-on-hover p-3">
                                    <label class="form-label d-block">Team Visibility</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="teamVisibility"
                                            name="team_visibility_switch">
                                        <input type="hidden" name="team_visibility" id="teamVisibilityValue" value="public">
                                        <label class="form-check-label" for="teamVisibility">Public (Visible to all)</label>
                                    </div>
                                    <!-- Dynamic Note -->
                                    <div id="teamVisibilityNote" class="mt-2 small text-muted">
                                        <!-- Note will appear here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add more fields as needed -->

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Team</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#teamMembers').select2({
                placeholder: "Select Team Members",
                allowClear: true,
                width: '100%' // Ensures it takes full container width
            });
            // Update team visibility state + show/hide Members Visibility section
            function updateVisibilityNote() {
                const isPrivate = $('#teamVisibility').is(':checked');

                // Set the hidden input value for backend submission
                $('#teamVisibilityValue').val(isPrivate ? 'private' : 'public');

                // Update the label text next to the toggle
                const labelText = isPrivate
                    ? 'Private (Only visible to team leader)'
                    : 'Public (Visible to all assigned members)';
                $('label[for="teamVisibility"]').text(labelText);

                // Update the visibility note below the switch
                const note = isPrivate
                    ? `<i class="ti ti-lock me-1 text-danger"></i><strong>Private:</strong> Only the team leader will be able to see this team.`
                    : `<i class="ti ti-eye me-1 text-success"></i><strong>Public:</strong> All assigned team members will be able to see this team.`;

                $('#teamVisibilityNote').html(note);
            }

            // Bind event listeners
            $('#teamVisibility').on('change', updateVisibilityNote);
            $('#teamMembers').on('change', updateVisibilityNote);

            // Initial trigger on page load
            updateVisibilityNote();


            $('#createTeamForm').on('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('store.createTeam') }}", // adjust if needed
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        // Optional: disable button, show spinner
                    },
                    success: function (response) {
                        // ✅ Success feedback
                        Swal.fire({
                            icon: 'success',
                            title: 'Team created successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#createTeamForm')[0].reset();
                        $('#teamMembers').val(null).trigger('change');
                        $('#membersVisibilityWrapper').hide();
                        // ✅ Hide the modal after short delay
                        setTimeout(function () {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('createTeamModal'));
                            if (modal) {
                                modal.hide();
                            }
                        }, 1600);
                    },
                    error: function (xhr) {
                        // ❌ Error handling
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON?.message || 'Something went wrong!',
                        });
                    }
                });
            });

        });

    </script>
@endsection