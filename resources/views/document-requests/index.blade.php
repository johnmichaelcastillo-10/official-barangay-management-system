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
            </div>
            <div class="card-body">
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

                                                <form action="{{ route('document-requests.reject', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Reject" onclick="return confirm('Are you sure you want to reject this request?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>

                                            @elseif($request->status === 'processing')
                                                <form action="{{ route('document-requests.ready', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-primary btn-sm" title="Mark as Ready" onclick="return confirm('Mark this document as ready for pickup?')">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </button>
                                                </form>

                                            @elseif($request->status === 'ready')
                                                <form action="{{ route('document-requests.release', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-warning btn-sm" title="Mark as Released" onclick="return confirm('Mark this document as released?')">
                                                        <i class="fas fa-hand-holding"></i>
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
