<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Document Requests</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Document Requests</li>
                </ol>
            </nav>
        </div>

        @php
            $successMessage = session()->pull('success');
            $errorMessage = session()->pull('error');
        @endphp

        @if($successMessage)
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $successMessage }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errorMessage)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errorMessage }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Document Request Records</h6>
                <a href="{{ route('document-requests.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> New Document Request
                </a>
            </div>
            <div class="card-body">
                {{-- Filter Form --}}
                <form action="{{ route('document-requests.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search (Tracking # or Resident Name)</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   placeholder="e.g., TRK-1234, Juan Dela Cruz" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="document_type" class="form-label">Document Type</label>
                            <select class="form-select" id="document_type" name="document_type">
                                <option value="">All Document Types</option>
                                {{-- Populate this dynamically from your database or a predefined list --}}
                                @foreach($availableDocumentTypes as $type)
                                    <option value="{{ $type }}" {{ request('document_type') == $type ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Statuses</option>
                                @foreach(['pending', 'processing', 'ready', 'released', 'rejected'] as $statusOption)
                                    <option value="{{ $statusOption }}" {{ request('status') == $statusOption ? 'selected' : '' }}>
                                        {{ ucfirst($statusOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('document-requests.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-redo me-1"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tracking #</th>
                                <th>Resident</th>
                                <th>Document Type</th>
                                <th>Status</th>
                                <th>Requested Date</th>
                                <th>Target Release</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $request->tracking_number }}</strong>
                                    </td>
                                    <td>
                                        {{ $request->resident->first_name }}
                                        {{ $request->resident->middle_name ? $request->resident->middle_name . ' ' : '' }}
                                        {{ $request->resident->last_name }}
                                    </td>
                                    <td>{{ ucwords(str_replace('_', ' ', $request->document_type)) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'ready' => 'success',
                                                'released' => 'primary',
                                                'rejected' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$request->status] ?? 'secondary' }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $request->requested_date->format('M d, Y') }}</td>
                                    <td>
                                        {{ $request->target_release_date ? $request->target_release_date->format('M d, Y') : 'TBA' }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('document-requests.show', $request) }}" class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($request->status === 'pending')
                                                <form action="{{ route('document-requests.process', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm" title="Process/Approve" onclick="return confirm('Are you sure you want to process this request?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $request->id }}"
                                                        title="Reject request">
                                                    <i class="fas fa-times"></i>
                                                </button>

                                            @elseif($request->status === 'processing')
                                                <form action="{{ route('document-requests.ready', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-primary btn-sm" title="Mark as Ready" onclick="return confirm('Mark this document as ready for pickup?')">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- REJECT MODAL (moved directly inside the loop) --}}
                                <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger" id="rejectModalLabel{{ $request->id }}">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>Reject Document Request
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('document-requests.reject', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="alert alert-warning">
                                                        <strong>Warning:</strong> You are about to reject the document request for
                                                        <strong>{{ $request->resident->first_name }} {{ $request->resident->last_name }}</strong>
                                                        (Tracking #: <strong>{{ $request->tracking_number }}</strong>).
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="rejection_reason{{ $request->id }}" class="form-label">
                                                            <strong>Reason for Rejection <span class="text-danger">*</span></strong>
                                                        </label>
                                                        <textarea class="form-control" id="rejection_reason{{ $request->id }}"
                                                                  name="rejection_reason" rows="4" required
                                                                  placeholder="Please provide a clear reason for rejecting this document request. This will be visible to the applicant when they check their status."></textarea>
                                                    </div>

                                                    <div class="form-text">
                                                        The applicant will be able to see this reason when they track their request status.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to reject this document request? This action cannot be undone.')">
                                                        <i class="fas fa-times me-2"></i>Reject Request
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- END REJECT MODAL --}}

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No document requests found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $requests->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-layout>
