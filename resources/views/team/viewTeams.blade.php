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
                                    $teamMembers = json_decode($team->team_members, true);
                                    $memberCount = is_array($teamMembers) ? count($teamMembers) : 0;
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
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ti ti-crown text-warning me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Leader</small>
                                                        <span class="fw-medium">{{ $team->team_leader }}</span>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-users text-primary me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Members</small>
                                                        <span class="fw-medium">{{ $memberCount }}
                                                            {{ $memberCount === 1 ? 'member' : 'members' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            @if (is_array($teamMembers))
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($teamMembers as $member)
                                                        @php
                                                            [$code, $name] = explode('*', $member);
                                                            $profile = $users->firstWhere('employee_code', $code)?->profile_picture;
                                                        @endphp
                                                        <div class="aspect-square-container p-0 w-auto">
                                                            <div class="aspect-square-box">
                                                                @if (!empty($profile))
                                                                    <img class="aspect-square rounded-circle" alt="{{ $name }}"
                                                                        src="{{ asset('assets/images/profile_picture/' . $profile) }}">
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
                                            <small class="text-muted">Created {{ $team->created_at->diffForHumans() }}</small>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary rounded-circle p-2 icon-btn"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical fs-5"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end p-2 shadow border-0">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a class="btn btn-sm btn-outline-primary rounded-circle p-2 icon-btn"
                                                            href="#" title="Edit">
                                                            <i class="ti ti-edit fs-5"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-outline-danger rounded-circle p-2 icon-btn"
                                                            href="#" title="Delete">
                                                            <i class="ti ti-trash fs-5"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-outline-success rounded-circle p-2 icon-btn"
                                                            href="#" title="Add Members">
                                                            <i class="ti ti-users-plus fs-5"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-outline-info rounded-circle p-2 icon-btn" href="#"
                                                            title="View Members">
                                                            <i class="ti ti-eye fs-5"></i>
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