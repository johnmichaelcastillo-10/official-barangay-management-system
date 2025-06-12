<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Record Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Record Management</li>
                </ol>
            </nav>
        </div>

        {{-- Session Messages (copied from Document Requests index) --}}
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
                <h6 class="m-0 font-weight-bold text-primary">Archived Document Records</h6>
                {{-- No "Go to Record Management" button here as we are already on the page --}}
            </div>
            <div class="card-body">
                {{-- Filter Form --}}
                <form action="{{ route('record-management.index') }}" method="GET" class="mb-4">
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
                                {{-- Only show 'released' and 'rejected' as options for records --}}
                                @foreach(['released', 'rejected'] as $statusOption)
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
                            <a href="{{ route('record-management.index') }}" class="btn btn-secondary w-100">
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
                                {{-- Actions column is intentionally removed as per your original request --}}
                                {{-- You can re-add it if you want view/print options for records --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $record->tracking_number }}</strong>
                                    </td>
                                    <td>
                                        {{ $record->resident->first_name }}
                                        {{ $record->resident->middle_name ? $record->resident->middle_name . ' ' : '' }}
                                        {{ $record->resident->last_name }}
                                    </td>
                                    <td>{{ ucwords(str_replace('_', ' ', $record->document_type)) }}</td>
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
                                        <span class="badge bg-{{ $statusColors[$record->status] ?? 'secondary' }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $record->requested_date->format('M d, Y') }}</td>
                                    <td>
                                        {{ $record->target_release_date ? $record->target_release_date->format('M d, Y') : 'TBA' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4"> {{-- Adjusted colspan to 6 --}}
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No document records found matching your filters.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($records->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $records->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-layout>
