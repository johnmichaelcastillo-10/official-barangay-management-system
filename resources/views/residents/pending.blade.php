<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Resident Verification</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Resident Verification</li>
                </ol>
            </nav>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-check me-2"></i>Pending Public Registrations
                </h6>
                <div class="d-flex align-items-center gap-3">
                    <!-- Search Form -->
                    <form method="GET" class="d-flex gap-2">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">

                        <input type="text"
                               class="form-control form-control-sm"
                               name="search"
                               placeholder="Search registrations..."
                               value="{{ request('search') }}"
                               style="width: 200px;">

                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>

                        @if(request()->hasAny(['search', 'sort', 'direction']))
                            <a href="{{ route('residents.pending') }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>

                    <span class="badge bg-warning text-dark">
                        {{ $pendingRegistrations->count() }} Pending
                    </span>
                </div>
            </div>
            <div class="card-body">
                @if($pendingRegistrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <!-- Sortable Reference # -->
                                    <th class="sortable-header" data-column="reference_number">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Reference #</span>
                                            <div class="sort-arrows">
                                                @if(request('sort') === 'reference_number')
                                                    @if(request('direction') === 'asc')
                                                        <i class="fas fa-sort-up text-primary"></i>
                                                    @elseif(request('direction') === 'desc')
                                                        <i class="fas fa-sort-down text-primary"></i>
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort text-muted opacity-50"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </th>

                                    <!-- Sortable Full Name -->
                                    <th class="sortable-header" data-column="last_name">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Full Name</span>
                                            <div class="sort-arrows">
                                                @if(request('sort') === 'last_name')
                                                    @if(request('direction') === 'asc')
                                                        <i class="fas fa-sort-up text-primary"></i>
                                                    @elseif(request('direction') === 'desc')
                                                        <i class="fas fa-sort-down text-primary"></i>
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort text-muted opacity-50"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </th>

                                    <!-- Sortable Birth Date -->
                                    <th class="sortable-header" data-column="birth_date">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Birth Date</span>
                                            <div class="sort-arrows">
                                                @if(request('sort') === 'birth_date')
                                                    @if(request('direction') === 'asc')
                                                        <i class="fas fa-sort-up text-primary"></i>
                                                    @elseif(request('direction') === 'desc')
                                                        <i class="fas fa-sort-down text-primary"></i>
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort text-muted opacity-50"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </th>

                                    <th>Contact</th>

                                    <!-- Sortable Submitted Date -->
                                    <th class="sortable-header" data-column="submitted_at">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Submitted Date</span>
                                            <div class="sort-arrows">
                                                @if(request('sort') === 'submitted_at')
                                                    @if(request('direction') === 'asc')
                                                        <i class="fas fa-sort-up text-primary"></i>
                                                    @elseif(request('direction') === 'desc')
                                                        <i class="fas fa-sort-down text-primary"></i>
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort text-muted opacity-50"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </th>

                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRegistrations as $registration)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $registration->reference_number }}</strong>
                                        </td>
                                        <td>
                                            <div class="fw-bold">
                                                {{ $registration->first_name }}
                                                {{ $registration->middle_name ? $registration->middle_name . ' ' : '' }}
                                                {{ $registration->last_name }}
                                                {{ $registration->suffix ? ', ' . $registration->suffix : '' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $registration->gender }}, {{ ucfirst($registration->civil_status) }}
                                            </small>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($registration->birth_date)->format('M d, Y') }}
                                            <br>
                                            <small class="text-muted">
                                                Age: {{ \Carbon\Carbon::parse($registration->birth_date)->age }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($registration->contact_number)
                                                <i class="fas fa-phone fa-sm text-muted me-1"></i>{{ $registration->contact_number }}<br>
                                            @endif
                                            @if($registration->email)
                                                <i class="fas fa-envelope fa-sm text-muted me-1"></i>{{ $registration->email }}
                                            @else
                                                <small class="text-muted">No email</small>
                                            @endif

                                            <!-- Document status indicators - UPDATED -->
                                            <div class="mt-2">
                                                @if($registration->photo)
                                                    <span class="badge bg-success me-1"
                                                          title="Click to view photo"
                                                          style="cursor: pointer;"
                                                          onclick="openImageModal('{{ asset('storage/' . $registration->photo) }}', 'Resident Photo')">
                                                        <i class="fas fa-camera"></i>
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning me-1" title="No photo">
                                                        <i class="fas fa-camera-slash"></i>
                                                    </span>
                                                @endif

                                                @if($registration->valid_id)
                                                    <span class="badge bg-success"
                                                          title="Click to view valid ID"
                                                          style="cursor: pointer;"
                                                          onclick="openImageModal('{{ asset('storage/' . $registration->valid_id) }}', 'Valid ID Document')">
                                                        <i class="fas fa-id-card"></i>
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger" title="No valid ID">
                                                        <i class="fas fa-id-card-slash"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            {{ $registration->submitted_at ? \Carbon\Carbon::parse($registration->submitted_at)->format('M d, Y g:i A') : 'N/A' }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $registration->submitted_at ? \Carbon\Carbon::parse($registration->submitted_at)->diffForHumans() : '' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- View Details -->
                                                <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewModal{{ $registration->id }}"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Accept -->
                                                <form action="{{ route('residents.approve', $registration) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                            title="Accept Registration"
                                                            onclick="return confirm('Are you sure you want to approve this registration? The resident will be added to the official list.')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <!-- Reject -->
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $registration->id }}"
                                                        title="Reject Registration">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- View Details Modal -->
                                    <div class="modal fade" id="viewModal{{ $registration->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $registration->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="viewModalLabel{{ $registration->id }}">
                                                        <i class="fas fa-user me-2"></i>Registration Details
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <h6 class="text-primary border-bottom pb-2 mb-3">Personal Information</h6>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Reference Number:</strong></div>
                                                                <div class="col-sm-8">{{ $registration->reference_number }}</div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Full Name:</strong></div>
                                                                <div class="col-sm-8">
                                                                    {{ $registration->first_name }}
                                                                    {{ $registration->middle_name ? $registration->middle_name . ' ' : '' }}
                                                                    {{ $registration->last_name }}
                                                                    {{ $registration->suffix ? ', ' . $registration->suffix : '' }}
                                                                </div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Birth Date:</strong></div>
                                                                <div class="col-sm-8">
                                                                    {{ \Carbon\Carbon::parse($registration->birth_date)->format('F d, Y') }}
                                                                    (Age: {{ \Carbon\Carbon::parse($registration->birth_date)->age }})
                                                                </div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Gender:</strong></div>
                                                                <div class="col-sm-8">{{ ucfirst($registration->gender) }}</div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Civil Status:</strong></div>
                                                                <div class="col-sm-8">{{ ucfirst($registration->civil_status) }}</div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Occupation:</strong></div>
                                                                <div class="col-sm-8">{{ $registration->occupation ?: 'Not specified' }}</div>
                                                            </div>

                                                            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Contact Information</h6>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Contact Number:</strong></div>
                                                                <div class="col-sm-8">{{ $registration->contact_number ?: 'Not provided' }}</div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Email:</strong></div>
                                                                <div class="col-sm-8">{{ $registration->email ?: 'Not provided' }}</div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Address:</strong></div>
                                                                <div class="col-sm-8">{{ $registration->address }}</div>
                                                            </div>

                                                            @if($registration->emergency_contact_name || $registration->emergency_contact_number)
                                                                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Emergency Contact</h6>

                                                                @if($registration->emergency_contact_name)
                                                                    <div class="row mb-2">
                                                                        <div class="col-sm-4"><strong>Contact Person:</strong></div>
                                                                        <div class="col-sm-8">{{ $registration->emergency_contact_name }}</div>
                                                                    </div>
                                                                @endif

                                                                @if($registration->emergency_contact_number)
                                                                    <div class="row mb-2">
                                                                        <div class="col-sm-4"><strong>Contact Number:</strong></div>
                                                                        <div class="col-sm-8">{{ $registration->emergency_contact_number }}</div>
                                                                    </div>
                                                                @endif
                                                            @endif

                                                            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Registration Info</h6>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Submitted Date:</strong></div>
                                                                <div class="col-sm-8">
                                                                    {{ $registration->submitted_at ? \Carbon\Carbon::parse($registration->submitted_at)->format('F d, Y g:i A') : 'N/A' }}
                                                                </div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Registration Type:</strong></div>
                                                                <div class="col-sm-8">
                                                                    <span class="badge bg-info">{{ ucfirst($registration->registration_type) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- UPDATED: Photo and Valid ID section -->
                                                        <div class="col-md-4">
                                                            <!-- Photo Section -->
                                                            @if($registration->photo)
                                                                <h6 class="text-primary border-bottom pb-2 mb-3">Photo</h6>
                                                                <div class="text-center mb-4">
                                                                    <img src="{{ asset('storage/' . $registration->photo) }}"
                                                                         alt="Resident Photo"
                                                                         class="img-fluid rounded border"
                                                                         style="max-width: 200px; max-height: 250px; cursor: pointer;"
                                                                         onclick="openImageModal('{{ asset('storage/' . $registration->photo) }}', 'Resident Photo')">
                                                                </div>
                                                            @else
                                                                <div class="text-center text-muted mb-4">
                                                                    <i class="fas fa-user fa-3x mb-2"></i>
                                                                    <p>No photo uploaded</p>
                                                                </div>
                                                            @endif

                                                            <!-- Valid ID Section -->
                                                            @if($registration->valid_id)
                                                                <h6 class="text-primary border-bottom pb-2 mb-3">Valid ID</h6>
                                                                <div class="text-center">
                                                                    <div class="border rounded p-3 bg-light">
                                                                        <i class="fas fa-id-card fa-2x text-primary mb-2"></i>
                                                                        <p class="mb-2 small text-muted">ID Document Uploaded</p>
                                                                        <button type="button" class="btn btn-primary btn-sm"
                                                                                onclick="openImageModal('{{ asset('storage/' . $registration->valid_id) }}', 'Valid ID Document')">
                                                                            <i class="fas fa-eye me-1"></i>View ID
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="text-center text-muted">
                                                                    <div class="border rounded p-3 bg-light">
                                                                        <i class="fas fa-id-card-slash fa-2x text-danger mb-2"></i>
                                                                        <p class="mb-0 small">No valid ID uploaded</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <form action="{{ route('residents.approve', $registration) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success"
                                                                onclick="return confirm('Are you sure you want to approve this registration?')">
                                                            <i class="fas fa-check me-2"></i>Accept Registration
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#rejectModal{{ $registration->id }}"
                                                            data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-2"></i>Reject
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $registration->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $registration->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger" id="rejectModalLabel{{ $registration->id }}">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>Reject Registration
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('residents.reject', $registration) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body">
                                                        <div class="alert alert-warning">
                                                            <strong>Warning:</strong> You are about to reject the registration for
                                                            <strong>{{ $registration->first_name }} {{ $registration->last_name }}</strong>.
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="rejection_reason{{ $registration->id }}" class="form-label">
                                                                <strong>Reason for Rejection <span class="text-danger">*</span></strong>
                                                            </label>
                                                            <textarea class="form-control" id="rejection_reason{{ $registration->id }}"
                                                                      name="rejection_reason" rows="4" required
                                                                      placeholder="Please provide a clear reason for rejecting this registration. This will be visible to the applicant when they check their status."></textarea>
                                                        </div>

                                                        <div class="form-text">
                                                            The applicant will be able to see this reason when they track their registration status.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('Are you sure you want to reject this registration? This action cannot be undone.')">
                                                            <i class="fas fa-times me-2"></i>Reject Registration
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Pending Registrations</h5>
                        <p class="text-muted">All public registrations have been processed. New registrations will appear here for verification.</p>
                        <a href="{{ route('residents.index') }}" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>View All Residents
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ID Viewer Modal -->
    <div class="modal fade" id="idViewerModal" tabindex="-1" aria-labelledby="idViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="idViewerModalLabel">
                        <i class="fas fa-id-card me-2"></i>Valid ID Document
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="idViewerImage" src="" alt="Valid ID" class="img-fluid rounded border" style="max-width: 100%; max-height: 80vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS for Sorting -->
    <style>
        .sortable-header {
            cursor: pointer;
            user-select: none;
            padding: 12px 8px !important;
            transition: all 0.2s ease;
            position: relative;
        }

        .sortable-header:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
        }

        .sortable-header:active {
            transform: translateY(0);
        }

        .sort-arrows {
            min-width: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }

        .sortable-header:hover .sort-arrows {
            transform: scale(1.1);
        }

        .sort-arrows i {
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .sort-arrows .fa-sort {
            color: #6c757d;
        }

        .sort-arrows .fa-sort-up {
            color: #28a745 !important;
        }

        .sort-arrows .fa-sort-down {
            color: #dc3545 !important;
        }

        .opacity-50 {
            opacity: 0.5;
        }

        .sortable-header:hover .opacity-50 {
            opacity: 1;
        }

        /* Animation for sorted rows */
        .table tbody tr {
            transition: all 0.3s ease;
        }

        .sorting-animation {
            animation: sortHighlight 0.6s ease;
        }

        @keyframes sortHighlight {
            0% { background-color: #fff3cd; }
            100% { background-color: transparent; }
        }
    </style>

    <!-- JavaScript for Sorting -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headers = document.querySelectorAll('.sortable-header');

            headers.forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.dataset.column;
                    const url = new URL(window.location);
                    const currentSort = url.searchParams.get('sort');
                    const currentDirection = url.searchParams.get('direction');

                    // Add visual feedback
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);

                    let newDirection = 'asc';

                    if (currentSort === column) {
                        if (currentDirection === 'asc') {
                            newDirection = 'desc';
                        } else if (currentDirection === 'desc') {
                            // Third click removes sorting
                            url.searchParams.delete('sort');
                            url.searchParams.delete('direction');
                            window.location.href = url.toString();
                            return;
                        }
                    }

                    url.searchParams.set('sort', column);
                    url.searchParams.set('direction', newDirection);

                    // Show loading state
                    const icon = this.querySelector('i');
                    const originalClass = icon.className;
                    icon.className = 'fas fa-spinner fa-spin';

                    // Navigate to new URL
                    window.location.href = url.toString();
                });
            });

            // Add animation to newly sorted rows
            if (window.location.search.includes('sort=')) {
                document.querySelectorAll('tbody tr').forEach((row, index) => {
                    setTimeout(() => {
                        row.classList.add('sorting-animation');
                    }, index * 50);
                });
            }
        });

        // Function to open image modal for both photo and ID
        function openImageModal(imageSrc, title = 'Document') {
            document.getElementById('idViewerImage').src = imageSrc;
            document.getElementById('idViewerModalLabel').innerHTML = '<i class="fas fa-image me-2"></i>' + title;
            const imageModal = new bootstrap.Modal(document.getElementById('idViewerModal'));
            imageModal.show();
        }

        // Backward compatibility - keep the old function name
        function openIdModal(imageSrc) {
            openImageModal(imageSrc, 'Valid ID Document');
        }
    </script>
</x-layout>
