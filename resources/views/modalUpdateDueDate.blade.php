<div class="modal fade" id="ChangeDueDateModal-{{ $taskId }}" tabindex="-1" aria-labelledby="ChangeDueDateLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ChangeDueDateLabel">Change Task Frequency</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="px-4 pt-3" id="current_schedule_info"></div>
            <form method="POST" id="changeFrequencyForm">
                @csrf
                @method('POST')

                <div class="modal-body row">
                    <div class="col-md-12 mb-3">
                        <div class="select-card active-on-hover">
                            <div class="select-card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <i class="ti ti-clock me-2"></i>Due Date
                                    </div>
                                    <!-- Hidden previous values -->
                                    <input type="hidden" name="prev_due_date" id="prev_due_date">
                                    <input type="hidden" name="prev_frequency" id="prev_frequency">
                                    <input type="hidden" name="prev_frequency_duration" id="prev_frequency_duration">
                                    <input type="hidden" name="task_id" id="modal_task_id">
                                    <!-- for identifying the task -->
                                    <div class="col-md-6">
                                        <div class="form-check form-switch float-end">
                                            <input class="form-check-input" type="checkbox" name="is_recurring"
                                                role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label"
                                                for="flexSwitchCheckDefault">Recurrence</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="frequency_section_form" class="p-3">
                                <div class="datetime-picker-container" id="due_date_section">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="ti ti-calendar-time"></i>
                                        </span>
                                        <input type="datetime-local" class="form-control border-start-0" id="due_date"
                                            name="due_date" placeholder="Select Date & Time">
                                    </div>
                                </div>

                                <div id="frequency_section" class="d-none">
                                    <select class="form-control border-bottom" id="frequency" name="frequency">
                                        <option value="">Select Frequency</option>
                                        <option value="Daily">Daily</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                        <option value="Periodic">Periodic</option>
                                        <option value="Custom">Custom</option>
                                    </select>
                                </div>

                                <!-- Recurrence Options (Hidden) -->
                                <div id="additional_fields" class="p-4 bg-light border-top d-none">
                                    <div id="weekly_days" class="frequency-option mb-3 d-none">
                                        <label class="small text-muted">Weekly on:</label>
                                        <div class="day-picker">
                                            <input type="checkbox" id="sunday" name="frequency_duration[]"
                                                value="Sunday" class="day-checkbox">
                                            <label for="sunday" class="day-label">S</label>

                                            <input type="checkbox" id="monday" name="frequency_duration[]"
                                                value="Monday" class="day-checkbox">
                                            <label for="monday" class="day-label">M</label>

                                            <input type="checkbox" id="tuesday" name="frequency_duration[]"
                                                value="Tuesday" class="day-checkbox">
                                            <label for="tuesday" class="day-label">T</label>

                                            <input type="checkbox" id="wednesday" name="frequency_duration[]"
                                                value="Wednesday" class="day-checkbox">
                                            <label for="wednesday" class="day-label">W</label>

                                            <input type="checkbox" id="thursday" name="frequency_duration[]"
                                                value="Thursday" class="day-checkbox">
                                            <label for="thursday" class="day-label">T</label>

                                            <input type="checkbox" id="friday" name="frequency_duration[]"
                                                value="Friday" class="day-checkbox">
                                            <label for="friday" class="day-label">F</label>

                                            <input type="checkbox" id="saturday" name="frequency_duration[]"
                                                value="Saturday" class="day-checkbox">
                                            <label for="saturday" class="day-label">S</label>
                                        </div>
                                    </div>

                                    <div id="monthly_date" class="frequency-option d-none">
                                        <label for="monthly_day">Enter Day of Month:</label>
                                        <input type="number" class="form-control" id="monthly_day"
                                            name="frequency_duration[]" min="1" max="31" placeholder="31">
                                    </div>

                                    <div id="yearly_date" class="frequency-option d-none">
                                        <label for="yearly_date_input">Select Date:</label>
                                        <input type="date" class="form-control" id="yearly_date_input"
                                            name="frequency_duration[]" placeholder="Select Date">
                                    </div>

                                    <div id="periodic_frequency" class="frequency-option d-none">
                                        <label for="periodic_interval">Interval (in
                                            frequency_duration):</label>
                                        <input type="number" class="form-control" id="periodic_interval"
                                            name="frequency_duration[]" min="1" placeholder="Interval Count of Days">
                                    </div>

                                    <div id="custom_frequency" class="frequency-option d-none">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="custom_frequency_dropdown">Frequency:</label>
                                                <select class="form-control" id="custom_frequency_dropdown"
                                                    name="custom_frequency_dropdown">
                                                    <option value="">Select Frequency</option>
                                                    <option value="Month">Month</option>
                                                    <option value="Week">Week</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="occurs_every_dropdown">Occurs
                                                    Every:</label>
                                                <input type="number" class="form-control" id="occurs_every_dropdown"
                                                    name="frequency_duration[]" min="1"
                                                    placeholder="Interval Count of Week/Month">
                                            </div>
                                        </div>
                                    </div>

                                    <p class="m-0 small mt-3"><b>Note: </b><span class="note"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>