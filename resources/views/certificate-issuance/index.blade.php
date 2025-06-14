<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Certificate Issuance</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Certificate Issuance</li>
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
                <h6 class="m-0 font-weight-bold text-primary">Certificate Issuance Records</h6>
                {{-- No "Go to Record Management" button here as per the original snippet --}}
            </div>
            <div class="card-body">
                {{-- Filter Form --}}
                <form action="{{ route('certificate-issuance.index') }}" method="GET" class="mb-4">
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
                                {{-- Only show relevant statuses for issuance, typically 'ready' and 'released' --}}
                                @foreach(['ready', 'released'] as $statusOption)
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
                            <a href="{{ route('certificate-issuance.index') }}" class="btn btn-secondary w-100">
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
                                                'pending' => 'secondary',
                                                'processing' => 'info',
                                                'ready' => 'success',
                                                'released' => 'primary',
                                                'cancelled' => 'danger',
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
                                            @if($request->status === 'released')
                                                {{-- Print button appears when status is 'released' --}}
                                                <a href="{{ route('document-requests.print', $request) }}" target="_blank" class="btn btn-success btn-sm" title="Print Document">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            @elseif($request->status === 'ready')
                                                <form action="{{ route('document-requests.release', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-primary btn-sm" title="Mark as Released" onclick="return confirm('Mark this document as released?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
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
