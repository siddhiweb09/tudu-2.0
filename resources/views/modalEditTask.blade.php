<div class="modal fade" id="taskDetailModal-{{ $taskId }}" tabindex="-1" aria-labelledby="taskDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content bg-light border-0">
            <!-- Modern header with gradient and better spacing -->
            <div class="modal-header border-0 primary-gradient-effect">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fs-5 fw-semibold text-white">Task Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>

            <!-- Modern body with subtle background and better spacing -->
            <div class="modal-body p-4 pt-3">
                <form id="edit-form-{{ $taskId }}" method="POST" enctype="multipart/form-data" class="pt-3 px-0">
                    @csrf
                    <div class="row g-4">
                        <!-- Task Title -->
                        <div class="col-12">
                            <label for="taskTitle" class="form-label fw-medium text-muted">TASK TITLE</label>
                            <input type="text" class="form-control border py-2 px-3" id="taskTitle"
                                value="{{ $taskIdData['each_task_info']['title'] }}">
                            <input type="hidden" name="task_id" value="{{ $taskId }}" />
                            <input type="hidden" name="tasks_json" id="tasksInput">
                            <input type="hidden" name="links_json" id="linksInput">
                        </div>

                        <!-- Subtasks - Modern card style -->
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted">SUBTASKS</label>
                            <div class="border rounded p-3 bg-white">
                                @foreach ($taskIdData['lists'] as $task)
                                    <div class="form-check mb-3 d-flex align-items-center">
                                        <input
                                            class="form-check-input me-3"
                                            type="checkbox"
                                            name="subtasks[]"
                                            value="{{ $task->tasks }}"
                                            id="subtask-{{ $task->tasks }}"
                                            {{ $task->status === 'Completed' ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark" for="subtask-{{ $task->tasks }}">
                                            {{ $task->tasks }}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="task-container"></div>
                                <!-- Add Button -->
                                <button class="btn btn-sm btn-outline-primary w-100 mt-3 py-2 add-task-btn" type="button">
                                    <i class="ti ti-plus me-2"></i>Add
                                </button>
                            </div>
                        </div>

                        <!-- Assignees - Modern badge style -->
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted mb-2">ASSIGNEES</label>
                            <div class="d-flex flex-wrap align-items-center gap-2 assignees">
                                @php
                                    $assign_by = $taskIdData['each_task_info']['assign_by'];
                                @endphp

                                @foreach ($taskIdData['team_members'] as $member)
                                    @php
                                        $employee = $member['employee_code'] . '*' . $member['employee_name'];
                                    @endphp
                                    @if($assign_by !== $employee)
                                        @if(!empty($member['profile_picture']))
                                            <!-- User Badge 1 -->
                                            <div class="d-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-xs">
                                                <img src="../assets/images/profile_picture/{{$member['profile_picture']}}"
                                                    class="rounded-circle me-2" width="20" height="20">
                                                <span class="text-dark fs-6 fw-medium">{{ $member['employee_name'] }} </span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-xs">
                                                <img src="../assets/images/profile_picture/user.png" class="rounded-circle me-2"
                                                    width="20" height="20">
                                                <span class="text-dark fs-6 fw-medium">{{ $member['employee_name'] }} </span>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach

                                <!-- Add Button -->
                                <button type="button"
                                    class="btn btn-sm btn-outline-primary d-flex align-items-center px-3 add-assignees">
                                    <i class="ti ti-plus me-1"></i>
                                    <span>Add</span>
                                </button>
                            </div>
                        </div>

                        <!-- Attachments - Modern file cards -->
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted">ATTACHMENTS</label>
                            <div class="border rounded p-3 bg-white border-gray-200">
                                <!-- Document Attachments -->
                                <h6 class="text-muted mb-3"><i class="ti ti-file-text me-2"></i> Documents</h6>
                                <div class="attachment-group mb-3 p-3 rounded bg-gray-50 shadow-sm">
                                    @foreach ($taskIdData['documents'] as $docs)
                                        @if(!empty($docs['file_name']))
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="attachment-icon bg-green-50 text-green-500">
                                                        <i class="ti ti-file-text fs-5"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <p class="mb-0 fw-medium text-truncate">
                                                            {{ $docs['file_name'] }}
                                                        </p>
                                                        <small
                                                            class="text-muted">{{ $docs['created_at']->format('M d, Y h:i A') }}</small>
                                                    </div>
                                                </div>
                                                <a href="../assets/uploads/{{ $docs['file_name'] }}"
                                                    class="btn btn-sm btn-light text-success" target="_blank"
                                                    title="Open {{ $docs['file_name'] }}">
                                                    <i class="ti ti-download"></i>
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center py-4 text-muted">
                                                <i class="ti ti-files-off fs-5"></i>
                                                <p class="mt-2 mb-0">No documents found</p>
                                                <small class="d-block mt-1">Upload documents to get started</small>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div id="documentsContainer"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-3 py-2" id="addDocument">
                                        <i class="ti ti-file-text me-2"></i>Add Documents
                                    </button>
                                </div>

                                <!-- Links -->
                                <h6 class="text-muted mb-3"><i class="ti ti-link me-2"></i> Links</h6>
                                <div class="attachment-group mb-3 p-3 rounded bg-gray-50 shadow-sm">
                                    @foreach ($taskIdData['links'] as $docs)
                                        @if(!empty($docs['file_name']))
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="attachment-icon bg-green-50 text-green-500">
                                                        <i class="ti ti-paperclip fs-5"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <p class="mb-0 fw-medium text-break">
                                                            {{ $docs['file_name'] }}
                                                        </p>
                                                        <small
                                                            class="text-muted">{{ $docs['created_at']->format('M d, Y h:i A') }}</small>
                                                    </div>
                                                </div>
                                                <a href="../assets/uploads/{{ $docs['file_name'] }}"
                                                    class="btn btn-sm btn-light text-success" target="_blank"
                                                    title="Open {{ $docs['file_name'] }}">
                                                    <i class="ti ti-external-link"></i>
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center py-4 text-muted">
                                                <i class="ti ti-files-off fs-5"></i>
                                                <p class="mt-2 mb-0">No documents found</p>
                                                <small class="d-block mt-1">Upload documents to get started</small>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div id="linksContainer"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-3 py-2" id="addMoreLinks">
                                        <i class="ti ti-link me-2"></i>Add Links
                                    </button>
                                </div>

                                <!-- Voice Notes -->
                                <h6 class="text-muted mb-3"><i class="ti ti-microphone me-2"></i> Voice Notes</h6>
                                <div class="attachment-group mb-3 p-3 rounded bg-gray-50 shadow-sm">
                                    @foreach ($taskIdData['voice_notes'] as $docs)
                                        @if(!empty($docs['file_name']))
                                            <div class="voice-note-container mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="attachment-icon bg-purple-50 text-purple-500">
                                                            <i class="ti ti-microphone fs-5"></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <p class="mb-0 fw-medium">Voice Note</p>
                                                            <small class="text-muted">
                                                                {{ $docs['created_at']->format('M d, Y h:i A') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <audio controls class="me-2">
                                                            <source src="../assets/uploads/{{ $docs['file_name'] }}"
                                                                type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                        <a href="../assets/uploads/{{ $docs['file_name'] }}"
                                                            class="btn btn-sm btn-light text-purple-500 align-self-center"
                                                            download title="Download Voice Note">
                                                            <i class="ti ti-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-4 text-muted">
                                                <i class="ti ti-files-off fs-5"></i>
                                                <p class="mt-2 mb-0">No voice notes found</p>
                                                <small class="d-block mt-1">Upload voice notes to get started</small>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div id="recordedContainer" class="d-none">
                                        <div id="recordedNotesList" class="mb-3"></div>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-sm btn-primary" id="startButton">
                                                <i class="ti ti-microphone"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary" id="stopButton"
                                                disabled>
                                                <i class="ti ti-player-stop"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" id="cancelButton"
                                                disabled>
                                                <i class="ti ti-cancel"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-3 py-2" id="add-recording">
                                        <i class="ti ti-microphone me-2"></i>Add Voice Notes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Modern footer with better buttons -->
                    <div class="modal-footer border-0 px-4 pb-4 pt-3">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>