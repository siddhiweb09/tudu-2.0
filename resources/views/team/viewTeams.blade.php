@extends('layouts.frame')

@section('main')

    <style>
        /* Enhanced Team Cards */
        .team-card {
            transition: all 0.3s ease-in-out;
            border-radius: 1rem;
            box-shadow: 0 0 0 rgba(0, 0, 0, 0);
        }

        .team-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-color: transparent;
        }

        /* Avatar */
        .team-card .avatar-xl {
            width: 60px;
            height: 60px;
            font-size: 20px;
            font-weight: bold;
            background-color: var(--bs-light);
        }

        /* Badge for visibility */
        .team-card .badge {
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Card header elements */
        .team-card h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        /* Collapsible members section */
        .team-card .collapse .badge {
            background-color: var(--bs-gray-100);
            border: 1px solid var(--bs-gray-300);
            color: var(--bs-dark);
            font-size: 0.75rem;
        }

        /* Dropdown button hover */
        .team-card .dropdown-menu .dropdown-item:hover {
            background-color: var(--bs-primary-bg-subtle);
            color: var(--bs-primary);
        }

        /* Divider between sections if needed */
        .team-card .card-body>.mb-3:not(:last-child) {
            border-bottom: 1px dashed #dee2e6;
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .icon-btn {
            height: 32px;
            width: 32px;
        }

        /* Modern tab styling */
        .nav-pills .nav-link {
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            color: #4b5563;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            background-color: transparent;
        }

        .nav-pills .nav-link:hover {
            color: #1520a6;
            background-color: rgba(59, 130, 246, 0.1);
        }

        .nav-pills .nav-link.active {
            background-color: #1520a6;
            color: white;
            box-shadow: 0 4px 6px -1px #1520a6;
            border-color: #1520a6;
        }

        .nav-pills .nav-link i {
            font-size: 1.1rem;
        }
    </style>

    <div class="bg-white border-bottom py-4">
        <div class="container-xxl">
            <!-- Header -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="mb-1 fw-semibold text-dark">Team Management</h4>
                    <p class="text-muted mb-0">Organize and manage your teams efficiently</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTeamModal">
                        <i class="ti ti-plus me-1"></i> Create Team
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="d-flex justify-content-end border-0">
                        <div class="nav nav-pills gap-2" id="teamTabs" role="tablist">
                            <button class="nav-link active d-flex align-items-center" id="all-tab" data-bs-toggle="pill"
                                data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                                <i class="ti ti-users-group me-2"></i>
                                <span>All Teams</span>
                            </button>

                            <button class="nav-link d-flex align-items-center" id="private-tab" data-bs-toggle="pill"
                                data-bs-target="#private" type="button" role="tab" aria-controls="private"
                                aria-selected="false">
                                <i class="ti ti-lock me-2"></i>
                                <span>Private</span>
                            </button>

                            <button class="nav-link d-flex align-items-center" id="public-tab" data-bs-toggle="pill"
                                data-bs-target="#public" type="button" role="tab" aria-controls="public"
                                aria-selected="false">
                                <i class="ti ti-eye me-2"></i>
                                <span>Public</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="teamTabsContent">
                @php
                    $visibilityGroups = [
                        'all' => $teams,
                        'private' => $teams->where('team_visibilty', 'private'),
                        'public' => $teams->where('team_visibilty', 'public'),
                    ];
                @endphp

                @foreach ($visibilityGroups as $tab => $group)
                    <div class="tab-pane fade {{ $tab == 'all' ? 'show active' : '' }}" id="{{ $tab }}" role="tabpanel">
                        <div class="row">
                            @forelse ($group as $team)
                                @php
                                        $teamMembers = json_decode($team->team_members, true) ?? [];
                                        $memberCount = count($teamMembers) + 1; // +1 for leader
                                @endphp

                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 shadow border-1 rounded-4 overflow-hidden position-relative pt-2">
                                        <!-- Visibility Badge -->
                                        <span
                                            class="badge position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill bg-{{ $team->team_visibilty === 'public' ? 'success' : 'danger' }}">
                                            <i class="ti {{ $team->team_visibilty === 'public' ? 'ti-eye' : 'ti-lock' }} me-1"></i>
                                            {{ ucfirst($team->team_visibilty) }}
                                        </span>

                                        <!-- Card Body -->
                                        <div class="card-body pb-0">
                                            <div class="d-flex align-items-center gap-3 mb-4">
                                                @if ($team->team_avatar)
                                                    <img src="{{ asset('assets/images/team_avatars/' . $team->team_avatar) }}"
                                                        class="rounded-circle border border-2" width="60" height="60" alt="Avatar">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold"
                                                        style="width:60px; height:60px;">
                                                        {{ strtoupper(substr($team->team_name, 0, 1)) }}
                                                    </div>
                                                @endif

                                                <div>
                                                    <h5 class="mb-1 fw-semibold">{{ $team->team_name }}</h5>
                                                    <small class="text-muted">Team ID: {{ $team->team_code }}</small>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                @php
                                                    [$leaderCode, $leaderName] = explode('*', $team->team_leader);
                                                    $leader = $users[$leaderCode] ?? null; 
                                                @endphp

                                                @if($leader)
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        @if (!empty($leader->profile_picture))
                                                            <img src="{{ asset('assets/images/profile_picture/' . $leader->profile_picture) }}"
                                                                alt="{{ $leaderName }}" class="aspect-square rounded-circle" width="32" height="32">
                                                        @else
                                                            <img src="{{ asset('assets/images/profile_picture/user.png') }}"
                                                                alt="{{ $leaderName }}" class="aspect-square rounded-circle" width="32" height="32">
                                                        @endif

                                                        <span class="fw-medium">{{ $team->team_leader }}</span>
                                                        <i class="ti ti-crown text-warning" title="Team Leader"></i>
                                                    </div>
                                                @endif

                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-users text-primary me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Members</small>
                                                        <span class="fw-medium">{{ $memberCount }} {{ $memberCount === 1 ? 'member' : 'members' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            @if (is_array($teamMembers))
                                                <div class="d-flex flex-wrap gap-3">
                                                    @foreach ($teamMembers as $member)
                                                        @php
                                                            [$code, $name] = explode('*', $member);
                                                        @endphp
                                                        <div class="aspect-square-container p-0 w-auto aspect-gap">
                                                            <div class="aspect-square-box">
                                                                @if (!empty($profile))
                                                                    <img class="aspect-square rounded-circle" alt="{{ $name }}"
                                                                        src="{{ asset('assets/images/profile_picture/' . $member->profile_picture) }}">
                                                                @else
                                                                    <img class="aspect-square rounded-circle" alt="{{ $name }}"
                                                                        src="{{ asset('assets/images/profile_picture/user.png') }}">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Card Footer -->
                                        <div
                                            class="card-footer bg-white border-top-0 mt-auto d-flex justify-content-between align-items-center px-4 py-3">
                                            <small class="text-muted"> {{ $team->created_at->diffForHumans() }}</small>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary rounded-circle p-2 icon-btn"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical fs-6"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end p-2 border-0">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        @php
                                                            $authUser = auth()->user();
                                                            $isTeamCreator = $team->created_by == ($authUser->employee_code . '*' .
                                                                $authUser->employee_name);
                                                            $isTeamLeader = $team->team_leader == ($authUser->employee_code . '*' .
                                                                $authUser->employee_name);
                                                            $isAuthorized = $isTeamCreator || $isTeamLeader;
                                                        @endphp

                                                        <button
                                                            class="btn btn-sm btn-outline-primary rounded-circle p-2 icon-btn edit-team-btn"
                                                            data-bs-toggle="modal" data-teamcode="{{ $team->team_code }}"
                                                            title="Edit">
                                                            <i class="ti ti-edit fs-6"></i>
                                                        </button>
                                                        <a class="btn btn-sm btn-outline-danger rounded-circle p-2 icon-btn delete-team-btn {{ !$isAuthorized ? 'disabled pointer-none opacity-50' : '' }}"
                                                            href="#" title="Delete"
                                                            aria-disabled="{{ !$isAuthorized ? 'true' : 'false' }}"
                                                            data-teamcode="{{ $team->team_code }}">
                                                            <i class="ti ti-trash fs-6"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-outline-info rounded-circle p-2 icon-btn view-members-ajax"
                                                            href="#" title="View Members" data-teamcode="{{ $team->team_code }}"
                                                            data-teamname="{{ $team->team_name }}">
                                                            <i class="ti ti-eye fs-6"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <div class="mb-4">
                                        <div class="bg-light-primary p-4 rounded-circle d-inline-block">
                                            <i class="ti ti-users-off fs-1 text-primary"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-2">No teams found</h5>
                                    <p class="text-muted mb-4">Create your first team to get started</p>
                                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal"
                                        data-bs-target="#createTeamModal">
                                        <i class="ti ti-plus me-2"></i>Create Team
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <!-- Create Team Modal -->
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
                                    @if ($authusers->isEmpty())
                                        <option value="">No Users Found</option>
                                    @else
                                        @foreach ($authusers as $user)
                                            <option value="{{ $user->employee_code }}*{{ $user->employee_name }}">
                                                {{ $user->employee_code }}*{{ $user->employee_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3 col-lg-6 col-12">
                                <label for="teamAvatar" class="form-label">Team Avatar</label>
                                <input type="file" class="form-control" id="teamAvatar" name="team_avatar"
                                    accept="image/png, image/jpeg, image/jpg">
                            </div>

                            <div class="mb-3 col-12">
                                <label for="teamMembers" class="form-label">Selcect Team members</label>
                                <select class="form-control select2" id="teamMembers" name="team_members[]" multiple>
                                    @if ($authusers->isEmpty())
                                        <option value="">No Users Found</option>
                                    @else
                                        @foreach ($authusers as $user)
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
                                        <label class="form-check-label" for="teamVisibility">Public (Visible to
                                            all)</label>
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

    <!-- View Members Modal -->
    <div class="modal fade" id="viewMembersModal" tabindex="-1" aria-labelledby="viewMembersLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4">
                <div class="modal-header primary-gradient-effect">
                    <h5 class="modal-title text-white" id="viewMembersLabel">Team Members</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="teamMembersContainer">
                    <div class="text-center text-muted">Loading members...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Teams Modal -->
    <div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4">
                <div class="modal-header primary-gradient-effect">
                    <h5 class="modal-title text-white" id="editTeamLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row" id="editTeamsContainer">
                        <div class="d-flex justify-content-center align-items-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                const labelText = isPrivate ?
                    'Private (Only visible to team leader)' :
                    'Public (Visible to all assigned members)';
                $('label[for="teamVisibility"]').text(labelText);

                // Update the visibility note below the switch
                const note = isPrivate ?
                    `<i class="ti ti-lock me-1 text-danger"></i><strong>Private:</strong> Only the team leader will be able to see this team.` :
                    `<i class="ti ti-eye me-1 text-success"></i><strong>Public:</strong> All assigned team members will be able to see this team.`;

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
                            const modal = bootstrap.Modal.getInstance(document
                                .getElementById('createTeamModal'));
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

            $('.view-members-ajax').on('click', function (e) {
                e.preventDefault();

                let teamCode = $(this).data('teamcode');
                let teamName = $(this).data('teamname');
                let modal = new bootstrap.Modal($('#viewMembersModal')[0]);
                let $container = $('#teamMembersContainer');
                let label = $('#viewMembersLabel');
                label.text(`Members Of Team: ${teamName}`);

                $container.html('<div class="text-center text-muted">Loading members...</div>');

                $.ajax({
                    url: `/get-team-members/${teamCode}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        const { leader, members } = data;

                        if (!leader && members.length === 0) {
                            $container.html('<p class="text-muted text-center">No members found.</p>');
                            return;
                        }

                        let cards = `<div class="row row-cols-1 row-cols-md-2 g-3">`;

                        // Leader card (with crown)
                        if (leader) {
                            cards += `
                            <div class="col">
                                <div class="card border-0 shadow-sm rounded-4 position-relative">
                                    <div class="card-body d-flex align-items-center gap-3">
                                        <div class="position-relative">
                                            <img src="/assets/images/profile_picture/${leader.profile_picture || 'user.png'}"
                                                 class="rounded-circle border" width="48" height="48" alt="${leader.employee_name}">
                                            <span class="position-absolute top-0 start-100 translate-middle badge bg-warning p-1" title="Team Leader" style="font-size: 0.65rem;">
                                                <i class="ti ti-crown"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">${leader.employee_code}*${leader.employee_name}</h6>
                                            <div class="text-muted small-text">Department: ${leader.department || 'N/A'}</div>
                                            <div class="text-muted small-text">Branch: ${leader.branch || 'Team Leader'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        }

                        // Members cards
                        members.forEach(member => {
                            cards += `
                            <div class="col">
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body d-flex align-items-center gap-3">
                                        <img src="/assets/images/profile_picture/${member.profile_picture || 'user.png'}"
                                             class="rounded-circle border" width="48" height="48" alt="${member.employee_name}">
                                        <div>
                                            <h6 class="mb-0 fw-medium">${member.employee_code}*${member.employee_name}</h6>
                                            <div class="text-muted small-text">Department: ${member.department || 'N/A'}</div>
                                            <div class="text-muted small-text">Branch: ${member.branch || 'Team Member'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });

                        cards += `</div>`;

                        $container.html(cards);


                    },
                    error: function () {
                        $container.html(
                            '<p class="text-danger text-center">Failed to load members.</p>');
                    }
                });

                modal.show();
            });

            $(document).on('click', '.delete-team-btn', function (e) {
                e.preventDefault();

                const teamCode = $(this).data('teamcode');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete team: ${teamCode}. This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/delete-team',
                            method: 'POST',
                            data: {
                                team_code: teamCode
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                if (res.status === 'success') {
                                    Swal.fire('Deleted!', res.message, 'success');
                                    $(`[data-teamcode="${teamCode}"]`).closest('.col-lg-4')
                                        .remove();
                                } else {
                                    Swal.fire('Failed!', 'Team could not be deleted.',
                                        'error');
                                }
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            },
                            error: function (xhr) {
                                if (xhr.status === 403) {
                                    Swal.fire('Unauthorized',
                                        'You are not allowed to delete this team.',
                                        'warning');
                                } else {
                                    Swal.fire('Error',
                                        'Something went wrong while deleting the team.',
                                        'error');
                                }
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.edit-team-btn', function (e) {
                e.preventDefault();

                const teamCode = $(this).data('teamcode');
                const modal = new bootstrap.Modal(document.getElementById('editTeamModal'));
                modal.show();

                // Show loader
                $('#editTeamsContainer').html(`
                                                                                <div class="d-flex justify-content-center align-items-center p-5">
                                                                                    <div class="spinner-border text-primary" role="status">
                                                                                        <span class="visually-hidden">Loading...</span>
                                                                                    </div>
                                                                                </div>
                                                                            `);

                $.ajax({
                    url: `/fetch-team-data/${teamCode}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.status === 'success') {
                            const team = response.data;
                            const users = response.users;
                            const selectedMembers = team.team_members;

                            $('#editTeamLabel').text(`Edit Team - ${team.team_name}`);

                            let leaderOptions = '';
                            let memberOptions = '';

                            users.forEach(user => {
                                const value = `${user.employee_code}*${user.employee_name}`;
                                const selectedLeader = team.team_leader === value ? 'selected' : '';
                                const selectedMember = selectedMembers.includes(value) ? 'selected' : '';

                                leaderOptions += `<option value="${value}" ${selectedLeader}>${user.employee_code}*${user.employee_name}</option>`;
                                memberOptions += `<option value="${value}" ${selectedMember}>${user.employee_code}*${user.employee_name}</option>`;
                            });

                            const avatarPreview = team.team_avatar
                                ? `<img src="/assets/images/team_avatars/${team.team_avatar}" class="rounded-circle border mb-2" width="60" height="60" alt="Team Avatar">`
                                : '';

                            const isPrivate = team.team_visibilty === 'private';
                            const visibilityChecked = isPrivate ? 'checked' : '';
                            const visibilityValue = isPrivate ? 'private' : 'public';
                            const visibilityNote = isPrivate
                                ? 'Only team leader and members can view this team.'
                                : 'Team is publicly visible to all.';
                            const visibilityLabel = isPrivate
                                ? 'Private (Restricted to members)'
                                : 'Public (Visible to all)';

                            const formHtml = `
                                                                                                <form id="editTeamForm" enctype="multipart/form-data" method="post">

                                                                                                    <input type="hidden" name="team_code" value="${team.team_code}">

                                                                                                        <div class="mb-3 col-12">
                                                                                                            <label for="editTeamName" class="form-label">Team Name</label>
                                                                                                            <input type="text" class="form-control" id="editTeamName" name="team_name" value="${team.team_name}">
                                                                                                        </div>

                                                                                                        <div class="mb-3 col-12">
                                                                                                            <label for="editTeamLeader" class="form-label">Team Leader</label>
                                                                                                            <select class="form-control" id="editTeamLeader" name="team_leader">
                                                                                                                ${leaderOptions}
                                                                                                            </select>
                                                                                                        </div>

                                                                                                        <div class="mb-3 col-12">
                                                                                                            <label for="editTeamAvatar" class="form-label">Team Avatar</label>
                                                                                                            ${avatarPreview}
                                                                                                            <input type="file" class="form-control" id="editTeamAvatar" name="team_avatar" accept="image/png, image/jpeg, image/jpg">
                                                                                                        </div>

                                                                                                        <div class="mb-3 col-12">
                                                                                                            <label for="editTeamMembers" class="form-label">Select Team Members</label>
                                                                                                            <select class="form-select select2" id="editTeamMembers" name="team_members[]" multiple>
                                                                                                                ${memberOptions}
                                                                                                            </select>
                                                                                                        </div>

                                                                                                        <div class="mb-3 col-12">
                                                                                                            <div class="select-card active-on-hover p-3">
                                                                                                                <label class="form-label d-block">Team Visibility</label>
                                                                                                                <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="editTeamVisibility" ${visibilityChecked}>
                            <label class="form-check-label mb-1" for="editTeamVisibility">${visibilityLabel}</label>
                            <input type="hidden" name="team_visibility" id="editTeamVisibilityValue" value="${visibilityValue}">
                        </div>
                        <div id="editTeamVisibilityNote" class="mt-1 small text-muted">
                            ${visibilityNote}
                        </div>

                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div class="modal-footer border-top-0 mt-3 px-0">
                                                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                                                            <button type="submit" class="btn btn-primary">Update Team</button>
                                                                                                        </div>

                                                                                                </form>
                                                                                            `;

                            $('#editTeamsContainer').html(formHtml);
                            $('.select2').select2(); // re-initialize if you're using Select2
                        }
                    },
                    error: function () {
                        $('#editTeamsContainer').html(`<p class="text-danger p-4">Error loading team details.</p>`);
                    }
                });
            });

            $(document).on('change', '#editTeamVisibility', function () {
                const isPrivate = $(this).is(':checked'); // checked = private
                $('#editTeamVisibilityValue').val(isPrivate ? 'private' : 'public');

                $('#editTeamVisibilityNote').text(
                    isPrivate
                        ? 'Only team leader and members can view this team.'
                        : 'Team is publicly visible to all.'
                );

                $('label[for="editTeamVisibility"]').text(
                    isPrivate
                        ? 'Private (Restricted to members)'
                        : 'Public (Visible to all)'
                );
            });

            $(document).on('submit', '#editTeamForm', function (e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);

                $.ajax({
                    url: '/update-team', // make sure this route is registered in routes/web.php
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () {
                        $(form).find('button[type="submit"]').prop('disabled', true).html(`
                                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...
                                                `);
                    },
                    success: function (res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Team Updated',
                                text: res.message || 'Team details updated successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $('#editTeamModal').modal('hide');

                            setTimeout(() => {
                                location.reload(); // You can also re-fetch specific data here if needed
                            }, 1200);

                        } else {
                            Swal.fire('Error', res.message || 'Failed to update the team.', 'error');
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // Laravel validation error handling
                            const errors = xhr.responseJSON.errors;
                            let messages = '';
                            for (let field in errors) {
                                messages += `${errors[field][0]}<br>`;
                            }
                            Swal.fire('Validation Error', messages, 'warning');
                        } else if (xhr.status === 403) {
                            Swal.fire('Unauthorized', xhr.responseJSON.message || 'You are not allowed to update this team.', 'warning');
                        } else {
                            Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                        }
                    },
                    complete: function () {
                        $(form).find('button[type="submit"]').prop('disabled', false).text('Update Team');
                    }
                });
            });


        });
    </script>
@endsection