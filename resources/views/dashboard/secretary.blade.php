<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Secretary Dashboard</h1>
            <div>
                <span class="badge bg-success">Secretary</span>
                <span class="text-muted ms-2">{{ date('F d, Y') }}</span>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <!-- Total Residents -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-primary text-uppercase mb-1 fw-bold">TOTAL RESIDENTS</div>
                                <div class="h2 mb-0 fw-bold">{{ $totalResidents }}</div>
                            </div>
                            <div class="text-primary opacity-75">
                                <i class="fas fa-users fa-2x"></i>
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
                                <div class="text-xs text-success text-uppercase mb-1 fw-bold">DOCUMENTS PROCESSED (TODAY)</div>
                                <div class="h2 mb-0 fw-bold">{{ $certificatesIssued }}</div>
                            </div>
                            <div class="text-success opacity-75">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Documents -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-info text-uppercase mb-1 fw-bold">PENDING DOCUMENTS</div>
                                <div class="h2 mb-0 fw-bold">{{ $pendingRequests }}</div>
                            </div>
                            <div class="text-info opacity-75">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-warning text-uppercase mb-1 fw-bold">ACTIVE USERS</div>
                                <div class="h2 mb-0 fw-bold">{{ $activeUsers }}</div>
                            </div>
                            <div class="text-warning opacity-75">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Requests -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Recent Document Requests</h6>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            No recent document requests found.
                        </div>
                        <!-- Recent requests will be listed here -->
                    </div>
                </div>

                <!-- Recent Registrations -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Recent Resident Registrations</h6>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            No recent registrations found.
                        </div>
                        <!-- Recent registrations will be listed here -->
                    </div>
                </div>
            </div>

            <!-- Secretary Tools -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Secretary Tools</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('residents.register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i> Register New Resident
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-danger">
                                <i class="fas fa-users-cog me-2"></i> Manage Users
                            </a>
                            <a href="{{route('certificate-issuance.index')}}" class="btn btn-success">
                                <i class="fas fa-file-alt me-2"></i> Issue Certificate
                            </a>
                            <a href="#" class="btn btn-info text-white">
                                <i class="fas fa-book me-2"></i> Manage Records
                            </a>
                            <a href="#" class="btn btn-warning">
                                <i class="fas fa-clipboard-list me-2"></i> Barangay Reports
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Calendar</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            Calendar module coming soon.
                        </div>
                        <!-- Mini calendar will be placed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
