<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Chairman Dashboard</h1>
            <div>
                <span class="badge bg-danger">Chairman</span>
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

            <!-- Total Staff -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-success text-uppercase mb-1 fw-bold">TOTAL STAFF</div>
                                <div class="h2 mb-0 fw-bold">{{ $totalStaff }}</div>
                            </div>
                            <div class="text-success opacity-75">
                                <i class="fas fa-user-tie fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Issued -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-info text-uppercase mb-1 fw-bold">DOCUMENTS ISSUED (THIS MONTH)</div>
                                <div class="h2 mb-0 fw-bold">{{ $documentsIssued }}</div>
                            </div>
                            <div class="text-info opacity-75">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items for Approval -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs text-warning text-uppercase mb-1 fw-bold">ITEMS FOR APPROVAL</div>
                                <div class="h2 mb-0 fw-bold">{{ $itemsForApproval }}</div>
                            </div>
                            <div class="text-warning opacity-75">
                                <i class="fas fa-tasks fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Reports & Analytics -->
            <div class="col-lg-8 mb-4">
                <!-- Staff Performance -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Staff Performance</h6>
                        <a href="#" class="btn btn-sm btn-primary">View Details</a>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            No performance data available yet.
                        </div>
                        <!-- Staff performance data will be listed here -->
                    </div>
                </div>

                <!-- Barangay Analytics -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Barangay Analytics</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            Analytics features will be available soon.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Administration & Approvals -->
            <div class="col-lg-4 mb-4">
                <!-- Chairman Tools -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Administration</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-warning">
                                <i class="fas fa-signature me-2"></i> Signature Documents
                            </a>
                            <a href="#" class="btn btn-success">
                                <i class="fas fa-chart-line me-2"></i> Financial Reports
                            </a>
                            <a href="#" class="btn btn-info text-white">
                                <i class="fas fa-cog me-2"></i> System Settings
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Pending Approvals</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            No pending approvals.
                        </div>
                        <!-- Pending approvals will be listed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
