    <div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="height: 90vh; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
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
                        <!-- <div class="d-flex justify-content-center align-items-center h-100">
                                                <div class="text-center">
                                                    <div class="spinner-border text-primary mb-3" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="text-muted">Loading task details...</p>
                                                </div>
                                            </div> -->
                        <div class="row g-4">
                            <!-- Task Title -->
                            <div class="col-12">
                                <label for="taskTitle" class="form-label fw-medium text-muted">TASK TITLE</label>
                                <input type="text" class="form-control border-2 py-2 px-3" id="taskTitle"
                                    value="Redesign landing page" style="border-color: #e2e8f0;">
                            </div>

                            <!-- Subtasks - Modern card style -->
                            <div class="col-12">
                                <label class="form-label fw-medium text-muted">SUBTASKS</label>
                                <div class="border rounded p-3"
                                    style="background-color: white; border-color: #e2e8f0 !important;">
                                    <div class="form-check mb-3 d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="subtask1" checked
                                            style="width: 18px; height: 18px;">
                                        <label class="form-check-label text-dark" for="subtask1">Create wireframes</label>
                                    </div>
                                    <div class="form-check mb-3 d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="subtask2"
                                            style="width: 18px; height: 18px;">
                                        <label class="form-check-label text-dark" for="subtask2">Design mockups</label>
                                    </div>
                                    <div class="form-check mb-3 d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="subtask3"
                                            style="width: 18px; height: 18px;">
                                        <label class="form-check-label text-dark" for="subtask3">Get feedback</label>
                                    </div>
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

                            <!-- Comments - Modern chat bubbles -->
                            <div class="col-12">
                                <label class="form-label fw-medium text-muted">COMMENTS</label>
                                <div class="border rounded p-3"
                                    style="background-color: white; border-color: #e2e8f0 !important;">
                                    <div class="mb-3">
                                        <div class="d-flex mb-3">
                                            <img src="../assets/images/profile_picture/10491834.jpg"
                                                class="rounded-circle me-3" width="40" height="40">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-medium">Sarah Johnson</span>
                                                    <small class="text-muted">2 days ago</small>
                                                </div>
                                                <p class="mb-0 p-2 ps-3 rounded"
                                                    style="background-color: #f1f5f9; border-left: 3px solid #3b82f6;">
                                                    I've started working on this. Will update the progress soon.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <img src="../assets/images/profile_picture/10491834.jpg"
                                                class="rounded-circle me-3" width="40" height="40">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-medium">David Kim</span>
                                                    <small class="text-muted">Yesterday</small>
                                                </div>
                                                <p class="mb-0 p-2 ps-3 rounded"
                                                    style="background-color: #f1f5f9; border-left: 3px solid #10b981;">
                                                    Let me know if you need any help with this task.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-end-0 py-2"
                                            placeholder="Add a comment..." style="border-color: #e2e8f0;">
                                        <button class="btn btn-primary px-3">
                                            <i class="ti ti-send"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Attachments - Modern file cards -->
                            <div class="col-12">
                                <label class="form-label fw-medium text-muted">ATTACHMENTS</label>
                                <div class="border rounded p-3"
                                    style="background-color: white; border-color: #e2e8f0 !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded shadow-sm"
                                        style="background-color: #f8fafc;">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 rounded me-3 d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; background-color: rgba(59, 130, 246, 0.1);">
                                                <i class="ti ti-file text-primary fs-5"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-medium">requirements.pdf</p>
                                                <small class="text-muted">1.2 MB • Added 3 days ago</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-light">
                                            <i class="ti ti-download"></i>
                                        </button>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center p-3 rounded shadow-sm"
                                        style="background-color: #f8fafc;">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 rounded me-3 d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; background-color: rgba(16, 185, 129, 0.1);">
                                                <i class="ti ti-file-analytics text-success fs-5"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-medium">data.xlsx</p>
                                                <small class="text-muted">845 KB • Added yesterday</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-light">
                                            <i class="ti ti-download"></i>
                                        </button>
                                    </div>
                                    <button class="btn btn-outline-primary w-100 mt-2 py-2" style="border-color: #e2e8f0;">
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