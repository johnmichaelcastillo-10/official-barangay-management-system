<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f5f5f5;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .nav-link {
            color: #333;
        }
        .nav-link:hover {
            color: #0d6efd;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #207ddb;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
            position: relative;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #0d6efd;
        }
        .card-header {
            font-weight: bold;
        }
        /* Border left colors for cards */
        .border-left-primary {
            border-left: 4px solid #4e73df;
        }
        .border-left-success {
            border-left: 4px solid #1cc88a;
        }
        .border-left-info {
            border-left: 4px solid #36b9cc;
        }
        .border-left-warning {
            border-left: 4px solid #f6c23e;
        }
        .border-left-danger {
            border-left: 4px solid #e74a3b;
        }
        /* Badge styling for sidebar */
        .sidebar .badge {
            font-size: 0.75rem;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        .sidebar .nav-link .badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% {
                transform: translateY(-50%) scale(1);
            }
            50% {
                transform: translateY(-50%) scale(1.1);
            }
            100% {
                transform: translateY(-50%) scale(1);
            }
        }
    </style>
    {{ $styles ?? '' }}
</head>
<body>

    @auth
    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/brgyLogo.png') }}" alt="Barangay Maunong Logo" height="30" class="me-2">
                Barangay Maunong
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->full_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content with Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <!-- Common Navigation Items for All Roles -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>

                        <!-- Secretary-Only Navigation Items -->
                        @if(Auth::user()->role === 'secretary')
                            @php
                                $pendingCount = \App\Models\Resident::where('registration_type', 'public')
                                    ->whereNull('approved_at')
                                    ->whereNull('rejected_at')
                                    ->count();
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('residents.pending') ? 'active' : '' }}" href="{{ route('residents.pending') }}">
                                    <i class="fas fa-user-check"></i> Resident Verification
                                    @if($pendingCount > 0)
                                        <span class="badge bg-warning text-dark">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('residents.rejected') }}">
                                    <i class="fas fa-user-times"></i>
                                    <span>Rejected Registrations</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('residents.index') || request()->routeIs('residents.show') || request()->routeIs('residents.edit') ? 'active' : '' }}" href="{{ route('residents.index') }}">
                                    <i class="fas fa-users"></i> Residents
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="fas fa-users-cog"></i> User Management
                                </a>
                            </li>

                            <li class="nav-item">
                                @php
                                    $pendingDocumentRequests = \App\Models\DocumentRequest::where('status', 'pending')
                                        ->count();
                                @endphp
                                <a class="nav-link {{ request()->routeIs('document-requests.*') ? 'active' : '' }}" href="{{ route('document-requests.index') }}">
                                    <i class="fas fa-file-alt"></i> Document Requests
                                    @if($pendingDocumentRequests > 0)
                                        <span class="badge bg-warning text-dark">{{ $pendingDocumentRequests }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                {{-- @php
                                    $pendingCertificates = \App\Models\DocumentRequest::where('status', 'ready')
                                        ->count();
                                @endphp --}}
                                <a class="nav-link {{ request()->routeIs('certificate-issuance.*') ? 'active' : '' }}" href="{{ route('certificate-issuance.index') }}">
                                    <i class="fas fa-certificate"></i> Certificate Issuance
                                    {{-- @if($pendingCertificates > 0)
                                        <span class="badge bg-warning text-dark">{{ $pendingCertificates }}</span>
                                    @endif --}}
                                </a>
                            </li>

                             <li class="nav-item">

                                <a class="nav-link {{ request()->routeIs('record-management.*') ? 'active' : '' }}" href="{{ route('record-management.index') }}">
                                    <i class="fas fa-book"></i> Record Management
                                </a>
                            </li>
                        @endif

                        <!-- Chairman-Only Navigation Items -->
                        @if(Auth::user()->role === 'chairman')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('residents.index') || request()->routeIs('residents.show') || request()->routeIs('residents.edit') ? 'active' : '' }}" href="{{ route('residents.index') }}">
                                    <i class="fas fa-users"></i> Residents
                                </a>
                            </li>

                            @php
                                $pendingCount = \App\Models\Resident::where('registration_type', 'public')
                                    ->whereNull('approved_at')
                                    ->whereNull('rejected_at')
                                    ->count();
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('residents.pending') ? 'active' : '' }}" href="{{ route('residents.pending') }}">
                                    <i class="fas fa-user-check"></i> Resident Verification
                                    @if($pendingCount > 0)
                                        <span class="badge bg-warning text-dark">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                @php
                                    $pendingDocumentRequests = \App\Models\DocumentRequest::where('status', 'pending')
                                        ->count();
                                @endphp
                                <a class="nav-link {{ request()->routeIs('document-requests.*') ? 'active' : '' }}" href="{{ route('document-requests.index') }}">
                                    <i class="fas fa-file-alt"></i> Document Requests
                                    @if($pendingDocumentRequests > 0)
                                        <span class="badge bg-warning text-dark">{{ $pendingDocumentRequests }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-chart-line"></i> Analytics & Reports
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-signature"></i> Approvals
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-cog"></i> System Settings
                                </a>
                            </li>
                        @endif

                        <!-- Staff-Only Navigation Items -->
                        @if(Auth::user()->role === 'staff')
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-tasks"></i> My Tasks
                                </a>
                            </li>
                            <li class="nav-item">
                                @php
                                    $pendingDocumentRequests = \App\Models\DocumentRequest::where('status', 'pending')
                                        ->count();
                                @endphp
                                <a class="nav-link {{ request()->routeIs('document-requests.*') ? 'active' : '' }}" href="{{ route('document-requests.index') }}">
                                    <i class="fas fa-file-alt"></i> Document Requests
                                    @if($pendingDocumentRequests > 0)
                                        <span class="badge bg-warning text-dark">{{ $pendingDocumentRequests }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                @php
                                    $pendingCertificates = \App\Models\DocumentRequest::where('status', 'ready')
                                        ->count();
                                @endphp
                                <a class="nav-link {{ request()->routeIs('certificate-issuance.*') ? 'active' : '' }}" href="{{ route('certificate-issuance.index') }}">
                                    <i class="fas fa-certificate"></i> Certificate Issuance
                                    @if($pendingCertificates > 0)
                                        <span class="badge bg-warning text-dark">{{ $pendingCertificates }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-clipboard-list"></i> Resident Inquiries
                                </a>
                            </li>
                        @endif

                        <!-- Common Navigation Items (Continued) -->
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-bell"></i> Announcements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-question-circle"></i> Help & Support
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
    @else
        <!-- When not authenticated, just show the content -->
        {{ $slot }}
    @endauth

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    {{ $scripts ?? '' }}
</body>
</html>
