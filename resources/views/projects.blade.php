@extends('layouts.frame')

@section('main')

<section class="container">
    <div class="border-top px-3 py-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <!-- Search and Filter Section -->
            <div class="d-flex align-items-center w-100 w-sm-auto gap-2">
                <div class="position-relative w-100" style="max-width: 260px;">
                    <i class="ti ti-search me-2 position-absolute top-50 translate-middle-y ms-2 text-muted"></i>
                    <input type="search" class="form-control ps-5 py-2" placeholder="Search projects...">
                </div>
                <button class="btn btn-outline-secondary d-flex align-items-center">
                    <i class="ti ti-filter me-2"></i>
                    All Projects
                </button>
            </div>

            <!-- View Toggle Buttons -->
            <div class="d-flex w-100 w-sm-auto justify-content-between justify-content-sm-end">
                <div class="bg-light rounded d-flex p-1 gap-2">
                    <button id="gridViewBtn" type="button" class="btn btn-light border d-flex align-items-center active">
                        <i class="ti ti-layout-grid me-2"></i>
                        <span>Grid</span>
                    </button>
                    <button id="listViewBtn" class="btn btn-light text-muted d-flex align-items-center">
                        <i class="ti ti-list-details me-2"></i>
                        List
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- List View (hidden by default) -->
<section class="container">
    <main id="listView" class="flex-grow-1 overflow-auto p-3 p-lg-4" style="display: none;">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Deadline</th>
                            <th>Team</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 4px; height: 32px; background-color: #facc15; border-radius: 0.375rem;"></div>
                                    <div>
                                        <div class="fw-medium text-dark d-flex align-items-center">
                                            Figma Design System
                                            <svg class="ms-2 text-warning" width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="..."></path>
                                            </svg>
                                        </div>
                                        <div class="small text-muted">UI component library for design system</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">In Progress</span>
                            </td>
                            <td>
                                <div style="width: 130px;">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Progress</span><span class="fw-semibold">65%</span>
                                    </div>
                                    <div class="progress progress-sm" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 65%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ti ti-calendar me-2"></i> Nov 15, 2023
                                </div>
                            </td>
                            <td>
                                <div class="aspect-square-container mb-2">
                                    <div class="aspect-square-box">
                                        <img class="aspect-square"
                                            src="../assets/images/profile_picture/user.png">
                                    </div>
                                    <div class="aspect-square-box">
                                        <img class="aspect-square"
                                            src="../assets/images/profile_picture/user.png">
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="ti ti-dots"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Repeat <tr> for other projects -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Grid View (shown by default) -->
    <main id="gridView" class="flex-grow-1 overflow-auto min-vh-0 p-3 p-lg-6">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xxl-3 g-3 g-xl-6">
                <!-- Project Card 1 -->
                <div class="col">
                    <div class="card rounded-lg border shadow-sm overflow-hidden transition-all hover-shadow">
                        <div class="card-body p-3 p-sm-6 pb-2">
                            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-start">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="w-1 h-12 rounded-full bg-warning"></div>
                                    <div>
                                        <h5 class="card-title font-semibold text-lg d-flex align-items-center">
                                            Figma Design System
                                            <i class="ti ti-star-fill text-warning ms-2"></i>
                                        </h5>
                                        <p class="card-subtitle text-muted text-sm">UI component library for design system</p>
                                    </div>
                                </div>
                                <span class="badge bg-warning bg-opacity-10 text-nowrap">In Progress</span>
                            </div>
                        </div>
                        <div class="card-body p-3 p-sm-6 pt-0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center text-muted text-sm">
                                    <i class="ti ti-calendar me-1"></i>
                                    <span>Deadline: Nov 15, 2023</span>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary" type="button">
                                    <i class="ti ti-dots"></i>
                                    <span class="visually-hidden">Project actions</span>
                                </button>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between text-sm">
                                    <span class="text-muted">Progress</span>
                                    <span class="fw-medium">65%</span>
                                </div>
                                <div class="progress" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-primary" style="width: 65%"></div>
                                </div>
                            </div>
                            <div class=" d-flex justify-content-between align-items-center">
                                <div class="aspect-square-container mb-2">
                                    <div class="aspect-square-box">
                                        <img class="aspect-square"
                                            src="../assets/images/profile_picture/user.png">
                                    </div>
                                    <div class="aspect-square-box">
                                        <img class="aspect-square"
                                            src="../assets/images/profile_picture/user.png">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3 text-xs text-muted">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-file-text me-1"></i>
                                        <span>24 tasks</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-chart-bar me-1"></i>
                                        <span>128 activities</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 border-top">
                                <div class="row g-4 text-xs">
                                    <div class="col-6">
                                        <p class="text-muted mb-1">Start Date</p>
                                        <p class="fw-medium">Oct 1, 2023</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="text-muted mb-1">Priority</p>
                                        <span class="badge bg-danger bg-opacity-10">High</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</section>

@endsection

@section('customJs')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');

        // Show grid view by default
        gridView.style.display = 'block';
        listView.style.display = 'none';

        // Grid view button click handler
        gridViewBtn.addEventListener('click', function() {
            gridView.style.display = 'block';
            listView.style.display = 'none';

            // Update button styles
            gridViewBtn.classList.add('active', 'border');
            gridViewBtn.classList.remove('text-muted');
            listViewBtn.classList.remove('active', 'border');
            listViewBtn.classList.add('text-muted');
        });

        // List view button click handler
        listViewBtn.addEventListener('click', function() {
            gridView.style.display = 'none';
            listView.style.display = 'block';

            // Update button styles
            listViewBtn.classList.add('active', 'border');
            listViewBtn.classList.remove('text-muted');
            gridViewBtn.classList.remove('active', 'border');
            gridViewBtn.classList.add('text-muted');
        });
    });
</script>
@endsection