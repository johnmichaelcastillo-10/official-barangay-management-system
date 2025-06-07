<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Staff Dashboard</h1>
            <div>
                <span class="badge bg-info">Staff</span>
                <span class="text-muted ms-2">{{ date('F d, Y') }}</span>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <!-- Residents Assisted -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-primary text-uppercase mb-1 fw-bold">RESIDENTS ASSISTED (TODAY)</div>
                                <div class="h2 mb-0 fw-bold">{{ $residentsAssisted }}</div>
                            </div>
                            <div class="text-primary opacity-75">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Processed -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-success text-uppercase mb-1 fw-bold">DOCUMENTS PROCESSED</div>
                                <div class="h2 mb-0 fw-bold">{{ $documentsProcessed }}</div>
                            </div>
                            <div class="text-success opacity-75">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Tasks -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-info text-uppercase mb-1 fw-bold">ASSIGNED TASKS</div>
                                <div class="h2 mb-0 fw-bold">{{ $assignedTasks }}</div>
                            </div>
                            <div class="text-info opacity-75">
                                <i class="fas fa-tasks fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-warning text-uppercase mb-1 fw-bold">PENDING REQUESTS</div>
                                <div class="h2 mb-0 fw-bold">{{ $pendingRequests }}</div>
                            </div>
                            <div class="text-warning opacity-75">
                                <i class="fas fa-clipboard-list fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Tasks & Interactions -->
            <div class="col-lg-8 mb-4">
                <!-- Assigned Tasks -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Assigned Tasks</h6>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            No tasks assigned at the moment.
                        </div>
                        <!-- Tasks will be listed here -->
                    </div>
                </div>

                <!-- Recent Interactions -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Recent Resident Interactions</h6>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            No recent interactions recorded.
                        </div>
                        <!-- Recent interactions will be listed here -->
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Tools & Announcements -->
            <div class="col-lg-4 mb-4">
                <!-- Staff Tools -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Quick Tools</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('residents.register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i> Register New Resident
                            </a>
                            <a href="#" class="btn btn-success">
                                <i class="fas fa-search me-2"></i> Search Resident
                            </a>
                            <a href="{{ route('document-requests.index') }}" class="btn btn-info text-white">
                                <i class="fas fa-file-alt me-2"></i> Process Document Request
                            </a>
                            <a href="#" class="btn btn-secondary">
                                <i class="fas fa-question-circle me-2"></i> Resident Inquiry
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Announcements -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Announcements</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            No new announcements.
                        </div>
                        <!-- Announcements will be placed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
