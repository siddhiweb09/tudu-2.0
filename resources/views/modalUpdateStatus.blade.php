<!-- Change Priority Modal -->
<div class="modal fade" id="ChangePriorityModal-{{ $taskId }}" tabindex="-1"
    aria-labelledby="ChangePriorityModalLabel-{{ $taskId }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-3">
            <div class="modal-header primary-gradient-effect">
                <h5 class="modal-title text-white" id="ChangePriorityModalLabel-{{ $taskId }}">Change Task Priority -
                    {{ $taskId }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="change-priority-form" data-task-id="{{ $taskId }}">
                @csrf
                <div class="modal-body">
                    <div class="select-card active-on-hover">
                        <div class="mb-4">
                            <div class="select-card-header">
                                <label class="d-block text-uppercase small fw-bold text-muted mb-3">
                                    <i class="ti ti-bolt me-2"></i>Priority Level
                                </label>
                            </div>
                            <div class="priority-pills mx-3">
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="btnradio-{{ $taskId }}"
                                        id="high-{{ $taskId }}" value="high" autocomplete="off">
                                    <label class="btn btn-check-inverse btn-inverse-danger" for="high-{{ $taskId }}">
                                        <i class="ti ti-flame me-1"></i>High
                                    </label>

                                    <input type="radio" class="btn-check" name="btnradio-{{ $taskId }}"
                                        id="medium-{{ $taskId }}" value="medium" autocomplete="off">
                                    <label class="btn btn-check-inverse btn-inverse-warning" for="medium-{{ $taskId }}">
                                        <i class="ti ti-sun-high me-1"></i>Medium
                                    </label>

                                    <input type="radio" class="btn-check" name="btnradio-{{ $taskId }}"
                                        id="low-{{ $taskId }}" value="low" autocomplete="off">
                                    <label class="btn btn-check-inverse btn-inverse-success" for="low-{{ $taskId }}">
                                        <i class="ti ti-leaf me-1"></i>Low
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Priority</button>
                </div>
            </form>
        </div>
    </div>
</div>