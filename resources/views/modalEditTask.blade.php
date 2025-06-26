<div class="modal fade" id="taskDetailModal-{{ $taskStat['task_id'] }}" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0">
            <!-- Modern header with gradient and better spacing -->
            <div class="modal-header border-0 primary-gradient-effect">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fs-5 fw-semibold text-white">Task Details</h5>
                    <button type="button"
                        class="btn btn-sm btn-icon rounded-circle bg-white bg-opacity-20 hover-bg-opacity-30 transition-all text-white"
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x fs-4 text-primary fw-bold"></i>
                    </button>
                </div>
            </div>

            <!-- Modern body with subtle background and better spacing -->
            <div class="modal-body p-4 pt-3" style="
                            overflow-y: auto;
                            background-color: #f8fafc;
                        ">
                <div id="taskDetailContent">
                    <div class="row g-4">
                        <!-- Task Title -->
                        <div class="col-12">
                            <label for="taskTitle" class="form-label fw-medium text-muted">TASK TITLE</label>
                            <input type="text" class="form-control border-2 py-2 px-3" id="taskTitle"
                                value="{{ $taskStat['title'] }}" style="border-color: #e2e8f0;">
                        </div>

                        <!-- Subtasks - Modern card style -->
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted">SUBTASKS</label>
                            <div class="border rounded p-3"
                                style="background-color: white; border-color: #e2e8f0 !important;">
                                @php
                                $taskList = $taskStat['task_list_items']->where('task_id', $taskStat['task_id'])->groupBy('task_id');
                                @endphp
                                @if($taskList->isNotEmpty())
                                @foreach ($taskList as $taskId => $taskValue)
                                @foreach ($taskValue as $task)
                                @if($task->status === 'Completed')
                                <div class="form-check mb-3 d-flex align-items-center">
                                    <input class="form-check-input me-3" value="{{ $task->tasks }}" type="checkbox" id="subtask1" checked>
                                    <label class="form-check-label text-dark" for="subtask1">{{ $task->tasks }}</label>
                                </div>
                                @else
                                <div class="form-check mb-3 d-flex align-items-center">
                                    <input class="form-check-input me-3" type="checkbox" id="subtask1">
                                    <label class="form-check-label text-dark" for="subtask1">{{ $task->tasks }}</label>
                                </div>
                                @endif
                                @endforeach
                                @endforeach
                                @endif

                                <button class="btn btn-outline-primary w-100 mt-1 py-2" style="border-color: #e2e8f0;">
                                    <i class="ti ti-plus me-2"></i>Add Subtask
                                </button>
                            </div>
                        </div>

                        <!-- Assignees - Modern badge style -->
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted mb-2">ASSIGNEES</label>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <!-- User Badge 1 -->
                                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-xs"
                                    style="border: 1px solid #e2e8f0; height: 32px;">
                                    <img src="../assets/images/profile_picture/10491834.jpg" class="rounded-circle me-2"
                                        width="20" height="20" style="object-fit: cover;">
                                    <span class="text-dark fs-6 fw-medium">Sarah Johnson</span>
                                    <button class="btn btn-icon p-0 ms-2" style="width: 16px; height: 16px;">
                                        <i class="ti ti-x text-muted" style="font-size: 0.75rem;"></i>
                                    </button>
                                </div>

                                <!-- User Badge 2 -->
                                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-xs"
                                    style="border: 1px solid #e2e8f0; height: 32px;">
                                    <img src="../assets/images/profile_picture/10491834.jpg" class="rounded-circle me-2"
                                        width="20" height="20" style="object-fit: cover;">
                                    <span class="text-dark fs-6 fw-medium">David Kim</span>
                                    <button class="btn btn-icon p-0 ms-2" style="width: 16px; height: 16px;">
                                        <i class="ti ti-x text-muted" style="font-size: 0.75rem;"></i>
                                    </button>
                                </div>

                                <!-- Add Button -->
                                <button
                                    class="btn btn-sm btn-outline-primary d-flex align-items-center rounded-pill px-3"
                                    style="height: 32px;">
                                    <i class="ti ti-plus me-1" style="font-size: 0.75rem;"></i>
                                    <span style="font-size: 0.8125rem;">Add</span>
                                </button>
                            </div>
                        </div>

                        <!-- Attachments - Modern file cards -->
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted">ATTACHMENTS</label>
                            <div class="border rounded p-3 bg-white border-gray-200">
                                <!-- Document Attachments -->
                                @php
                                $documentAttachments = $taskMedias->where('category', 'document')->where('task_id', $taskStat['task_id'])->groupBy('task_id');
                                @endphp
                                @if($documentAttachments->isNotEmpty())
                                <h6 class="text-muted mb-3"><i class="ti ti-files me-2"></i> Documents</h6>
                                @foreach ($documentAttachments as $taskId => $docs)
                                <div class="attachment-group mb-3 p-3 rounded bg-gray-50 shadow-sm">
                                    @foreach ($docs as $taskMedia)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="attachment-icon bg-blue-50 text-blue-500">
                                                <i class="ti ti-file fs-5"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="mb-0 fw-medium text-truncate" style="max-width: 250px;">
                                                    {{ $taskMedia->file_name }}
                                                </p>
                                                <small
                                                    class="text-muted">{{ $taskMedia->created_at->format('M d, Y h:i A') }}</small>
                                            </div>
                                        </div>
                                        <a href="../assets/uploads/{{ $taskMedia->file_name }}"
                                            class="btn btn-sm btn-light text-primary" download
                                            title="Download {{ $taskMedia->file_name }}">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                                @endif

                                <!-- Link Attachments -->
                                @php
                                $linkAttachments = $taskMedias->where('category', 'link')->groupBy('task_id');
                                @endphp
                                @if($linkAttachments->isNotEmpty())
                                <h6 class="text-muted mb-3"><i class="ti ti-link me-2"></i> Links</h6>
                                @foreach ($linkAttachments as $taskId => $links)
                                <div class="attachment-group mb-3 p-3 rounded bg-gray-50 shadow-sm">
                                    @foreach ($links as $taskMedia)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="attachment-icon bg-green-50 text-green-500">
                                                <i class="ti ti-paperclip fs-5"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="mb-0 fw-medium text-truncate" style="max-width: 250px;">
                                                    {{ $taskMedia->file_name }}
                                                </p>
                                                <small
                                                    class="text-muted">{{ $taskMedia->created_at->format('M d, Y h:i A') }}</small>
                                            </div>
                                        </div>
                                        <a href="{{ $taskMedia->file_path }}" class="btn btn-sm btn-light text-success"
                                            target="_blank" title="Open {{ $taskMedia->file_name }}">
                                            <i class="ti ti-external-link"></i>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                                @endif

                                <!-- Link Attachments -->
                                @php
                                $voiceNotes = $taskMedias->where('category', 'voice_note')->groupBy('task_id');
                                @endphp
                                @if($voiceNotes->isNotEmpty())
                                <h6 class="text-muted mb-3"><i class="ti ti-microphone me-2"></i> Voice Notes</h6>
                                @foreach ($voiceNotes as $taskId => $notes)
                                <div class="attachment-group mb-3 p-3 rounded bg-gray-50 shadow-sm">
                                    @foreach ($notes as $taskMedia)
                                    <div class="voice-note-container mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="attachment-icon bg-purple-50 text-purple-500">
                                                    <i class="ti ti-microphone fs-5"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <p class="mb-0 fw-medium">Voice Note</p>
                                                    <small class="text-muted">
                                                        {{ $taskMedia->created_at->format('M d, Y h:i A') }}
                                                        {{ $taskMedia->duration ? $taskMedia->duration . ' sec' : '' }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <audio controls class="me-2" style="height: 36px;">
                                                    <source src="../assets/uploads/{{ $taskMedia->file_name }}"
                                                        type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                                <a href="../assets/uploads/{{ $taskMedia->file_name }}"
                                                    class="btn btn-sm btn-light text-purple-500 align-self-center"
                                                    download title="Download Voice Note">
                                                    <i class="ti ti-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                                @endif

                                @if($documentAttachments->isEmpty() && $linkAttachments->isEmpty() && $voiceNoteAttachments->isEmpty())
                                <div class="text-center py-4 text-muted">
                                    <i class="ti ti-files-off fs-5"></i>
                                    <p class="mt-2 mb-0">No attachments found</p>
                                    <small class="d-block mt-1">Upload documents, links or voice notes to get
                                        started</small>
                                </div>
                                @endif

                                <button class="btn btn-outline-primary w-100 mt-3 py-2">
                                    <i class="ti ti-paperclip me-2"></i>Add Attachment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern footer with better buttons -->
            <div class="modal-footer border-0 px-4 pb-4 pt-3" style="background-color: #f8fafc;">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary px-4 py-2 shadow-sm">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>