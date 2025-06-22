<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="addTaskForm" action="{{ route('personal-tasks.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Task Title*</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="okr" class="form-label">Linked Objective/OKR (Optional)</label>
                                <input type="text" class="form-control" id="okr" name="okr">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Select a category</option>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                </select>
                            </div>

                            <div class="mb-3 new-category-group" style="display: none;">
                                <label for="new_category_name" class="form-label">New Category Name</label>
                                <input type="text" class="form-control" id="new_category_name" name="new_category_name">
                                <label for="new_category_color" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="new_category_color" name="new_category_color" value="#3f51b5">
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="datetime-local" class="form-control" id="due_date" name="due_date">
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="time_estimate" class="form-label">Time Estimate (minutes)</label>
                                <input type="number" class="form-control" id="time_estimate" name="time_estimate" min="0" step="5">
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="is_habit" name="is_habit">
                                <label class="form-check-label" for="is_habit">This is a habit/recurring task</label>
                            </div>

                            <div class="mb-3 habit-frequency" style="display: none;">
                                <label for="habit_frequency" class="form-label">Habit Frequency</label>
                                <select class="form-select" id="habit_frequency" name="habit_frequency">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="weekdays">Weekdays Only</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_task" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Task Detail Modal -->
<div class="modal fade" id="taskDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Task Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="task-header mb-4">
                            <h3 id="detail-title" class="text-primary"></h3>
                            <div id="detail-description" class="lead mb-3"></div>
                        </div>

                        <div class="task-meta card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="meta-item mb-3">
                                            <strong><i class="ti-check-box me-2"></i>Status:</strong>
                                            <span id="detail-status" class="badge ms-2"></span>
                                        </div>
                                        <div class="meta-item mb-3">
                                            <strong><i class="ti-alert me-2"></i>Priority:</strong>
                                            <span id="detail-priority" class="badge ms-2"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="meta-item mb-3">
                                            <strong><i class="ti-calendar me-2"></i>Due Date:</strong>
                                            <span id="detail-due-date" class="ms-2"></span>
                                        </div>
                                        <div class="meta-item mb-3">
                                            <strong><i class="ti-tag me-2"></i>Category:</strong>
                                            <span id="detail-category" class="badge text-white ms-2"></span>
                                        </div>
                                    </div>
                                </div>
                                <div data-habit-section style="display: none;">
                                    <hr>
                                    <div class="meta-item">
                                        <strong><i class="ti-reload me-2"></i>Habit Frequency:</strong>
                                        <span id="detail-habit-frequency" class="ms-2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="notes-section card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="ti-notepad me-2"></i>Notes</h5>
                            </div>
                            <div class="card-body">
                                <div id="detail-notes" class="notes-container"></div>

                                <form id="addNoteForm" class="mt-4">
                                    <input type="hidden" name="task_id" id="task-id">
                                    <div class="mb-3">
                                        <textarea class="form-control" name="note" placeholder="Add a note or reflection..." rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti-plus me-1"></i> Add Note
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="documents-section card mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="ti-file me-2"></i>Documents</h5>
                            </div>
                            <div class="card-body">
                                <div id="detail-documents" class="documents-container"></div>

                                <form id="uploadDocumentForm" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="task_id" id="document-task-id">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="documentFile" class="form-label">Choose file:</label>
                                        <input type="file" class="form-control" id="documentFile" name="document" required>
                                        <div class="form-text">Max 10MB (images, PDF, docs)</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="documentDescription" class="form-label">Description:</label>
                                        <input type="text" class="form-control" id="documentDescription" name="description" placeholder="Optional description">
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti-upload me-1"></i> Upload Document
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="task-actions sticky-top" style="top: 20px;">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="ti-settings me-2"></i>Task Actions</h5>
                                </div>
                                <div class="card-body">
                                    <button class="btn btn-warning w-100 mb-3 edit-task" id="modalEditTaskBtn">
                                        <i class="ti-pencil me-1"></i> Edit Task
                                    </button>
                                    <button class="btn btn-danger w-100 mb-3 delete-task" id="modalDeleteTaskBtn">
                                        <i class="ti-trash me-1"></i> Delete Task
                                    </button>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="ti-timer me-2"></i>Time Tracking</h5>
                                </div>
                                <div class="card-body">
                                    <div class="time-tracked mb-3 text-center">
                                        <div class="display-4 text-primary" id="total-time-spent">0</div>
                                        <small class="text-muted">minutes spent</small>
                                    </div>
                                    <button class="btn btn-success w-100 start-timer-from-modal">
                                        <i class="ti-control-play me-1"></i> Start Timer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editTaskForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_task_id" name="task_id">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Task Title*</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit_okr" class="form-label">Linked Objective/OKR (Optional)</label>
                                <input type="text" class="form-control" id="edit_okr" name="okr">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_category" class="form-label">Category</label>
                                <select class="form-select" id="edit_category" name="category">
                                    <option value="">Select a category</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                </select>
                            </div>

                            <div class="mb-3 new-category-group" style="display: none;">
                                <label for="edit_new_category_name" class="form-label">New Category Name</label>
                                <input type="text" class="form-control" id="edit_new_category_name" name="new_category_name">
                                <label for="edit_new_category_color" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="edit_new_category_color" name="new_category_color" value="#3f51b5">
                            </div>

                            <div class="mb-3">
                                <label for="edit_due_date" class="form-label">Due Date</label>
                                <input type="datetime-local" class="form-control" id="edit_due_date" name="due_date">
                            </div>

                            <div class="mb-3">
                                <label for="edit_priority" class="form-label">Priority</label>
                                <select class="form-select" id="edit_priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_time_estimate" class="form-label">Time Estimate (minutes)</label>
                                <input type="number" class="form-control" id="edit_time_estimate" name="time_estimate" min="0" step="5">
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="edit_is_habit" name="is_habit">
                                <label class="form-check-label" for="edit_is_habit">This is a habit/recurring task</label>
                            </div>

                            <div class="mb-3 edit-habit-frequency" style="display: none;">
                                <label for="edit_habit_frequency" class="form-label">Habit Frequency</label>
                                <select class="form-select" id="edit_habit_frequency" name="habit_frequency">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="weekdays">Weekdays Only</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_task" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>