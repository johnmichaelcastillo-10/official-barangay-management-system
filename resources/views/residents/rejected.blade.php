<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Rejected Registrations</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rejected Registrations</li>
                </ol>
            </nav>
        </div>

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

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-user-times me-2"></i>Rejected Registrations Archive
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger">
                        {{ $rejectedRegistrations->count() }} Rejected
                    </span>
                    @if($rejectedRegistrations->count() > 0)
                        <!-- Cleanup Button -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cleanupModal">
                            <i class="fas fa-broom me-1"></i>Cleanup Old Records
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($rejectedRegistrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Reference #</th>
                                    <th>Full Name</th>
                                    <th>Birth Date</th>
                                    <th>Contact</th>
                                    <th>Rejected Date</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rejectedRegistrations as $registration)
                                    <tr>
                                        <td>
                                            <strong class="text-danger">{{ $registration->reference_number }}</strong>
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
                                        </td>
                                        <td>
                                            {{ $registration->rejected_at ? \Carbon\Carbon::parse($registration->rejected_at)->format('M d, Y g:i A') : 'N/A' }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $registration->rejected_at ? \Carbon\Carbon::parse($registration->rejected_at)->diffForHumans() : '' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($registration->rejectedBy)
                                                {{ $registration->rejectedBy->name }}
                                            @else
                                                <span class="text-muted">Unknown</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#reasonModal{{ $registration->id }}">
                                                <i class="fas fa-eye"></i> View
                                            </button>
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

                                                <!-- Delete Permanently -->
                                                <form action="{{ route('residents.rejected.destroy', $registration) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Delete Permanently"
                                                            onclick="return confirm('Are you sure you want to permanently delete this rejected registration? This action cannot be undone.')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Reason Modal -->
                                    <div class="modal fade" id="reasonModal{{ $registration->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Rejection Reason</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Reference:</strong> {{ $registration->reference_number }}</p>
                                                    <p><strong>Applicant:</strong> {{ $registration->first_name }} {{ $registration->last_name }}</p>
                                                    <p><strong>Reason:</strong></p>
                                                    <div class="alert alert-warning">
                                                        {{ $registration->rejection_reason }}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- View Details Modal - Similar to pending.blade.php -->
                                    <div class="modal fade" id="viewModal{{ $registration->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-user-times me-2 text-danger"></i>Rejected Registration Details
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Similar content structure as pending registrations modal -->
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <!-- Personal Information section -->
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
                                                            <!-- Add other fields as needed -->

                                                            <h6 class="text-danger border-bottom pb-2 mb-3 mt-4">Rejection Information</h6>
                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Rejected Date:</strong></div>
                                                                <div class="col-sm-8">{{ $registration->rejected_at->format('F d, Y g:i A') }}</div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-sm-4"><strong>Reason:</strong></div>
                                                                <div class="col-sm-8">
                                                                    <div class="alert alert-warning">
                                                                        {{ $registration->rejection_reason }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <!-- Photo and ID sections -->
                                                            @if($registration->photo)
                                                                <h6 class="text-primary border-bottom pb-2 mb-3">Photo</h6>
                                                                <div class="text-center mb-4">
                                                                    <img src="{{ asset('storage/' . $registration->photo) }}"
                                                                         alt="Resident Photo"
                                                                         class="img-fluid rounded border"
                                                                         style="max-width: 200px; max-height: 250px;">
                                                                </div>
                                                            @endif

                                                            @if($registration->valid_id)
                                                                <h6 class="text-primary border-bottom pb-2 mb-3">Valid ID</h6>
                                                                <div class="text-center">
                                                                    <div class="border rounded p-3 bg-light">
                                                                        <i class="fas fa-id-card fa-2x text-primary mb-2"></i>
                                                                        <p class="mb-2 small text-muted">ID Document Available</p>
                                                                        <button type="button" class="btn btn-primary btn-sm"
                                                                                onclick="openImageModal('{{ asset('storage/' . $registration->valid_id) }}', 'Valid ID Document')">
                                                                            <i class="fas fa-eye me-1"></i>View ID
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
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
                        <h5 class="text-muted">No Rejected Registrations</h5>
                        <p class="text-muted">There are currently no rejected registrations in the archive.</p>
                        <a href="{{ route('residents.pending') }}" class="btn btn-primary">
                            <i class="fas fa-user-clock me-2"></i>View Pending Registrations
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Cleanup Modal -->
    <div class="modal fade" id="cleanupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning">
                        <i class="fas fa-broom me-2"></i>Cleanup Old Rejected Registrations
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('residents.rejected.cleanup') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>Warning:</strong> This will permanently delete old rejected registrations and their associated files.
                        </div>
                        <div class="mb-3">
                            <label for="days" class="form-label">Delete records older than:</label>
                            <select class="form-select" id="days" name="days" required>
                                <option value="30">30 days</option>
                                <option value="60">60 days</option>
                                <option value="90">90 days</option>
                                <option value="180">6 months</option>
                                <option value="365">1 year</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure? This action cannot be undone.')">
                            <i class="fas fa-broom me-2"></i>Cleanup Records
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Viewer Modal (reuse from pending.blade.php) -->
    <div class="modal fade" id="idViewerModal" tabindex="-1" aria-labelledby="idViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="idViewerModalLabel">
                        <i class="fas fa-id-card me-2"></i>Document
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="idViewerImage" src="" alt="Document" class="img-fluid rounded border" style="max-width: 100%; max-height: 80vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openImageModal(imageSrc, title = 'Document') {
            document.getElementById('idViewerImage').src = imageSrc;
            document.getElementById('idViewerModalLabel').innerHTML = '<i class="fas fa-image me-2"></i>' + title;
            const imageModal = new bootstrap.Modal(document.getElementById('idViewerModal'));
            imageModal.show();
        }
    </script>
</x-layout>
