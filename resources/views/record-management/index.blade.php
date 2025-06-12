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
                                {{-- REMOVED: <th>Actions</th> --}}
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
                                    {{-- REMOVED: Actions Column --}}
                                    {{-- If you still want View/Print for records, you'd add them here,
                                       but the request was to remove "the actions" column. --}}
                                </tr>
                            @empty
                                <tr>
                                    {{-- Adjusted colspan from 7 to 6 (because 1 column is removed) --}}
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No document records found.</p>
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

    {{-- The Print Modal and its JavaScript are no longer needed on this page
         since there are no "Print" buttons to trigger it. You can remove this section
         if you are certain you won't need to trigger the modal from this page. --}}
    {{-- <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printModalLabel">Print Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-3">You are about to print the <strong id="modalDocumentType"></strong> with tracking number <strong id="modalTrackingNumber"></strong>.</p>
                    <p>Click "View & Print" to open the certificate in a new tab for printing.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a id="printCertificateLink" href="#" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i> View & Print
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const printModal = document.getElementById('printModal');
            printModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const documentId = button.getAttribute('data-document-id');
                const documentType = button.getAttribute('data-document-type');
                const trackingNumber = button.getAttribute('data-tracking-number');

                const modalDocumentType = printModal.querySelector('#modalDocumentType');
                const modalTrackingNumber = printModal.querySelector('#modalTrackingNumber');
                const printCertificateLink = printModal.querySelector('#printCertificateLink');

                modalDocumentType.textContent = documentType;
                modalTrackingNumber.textContent = trackingNumber;
                printCertificateLink.href = `/document-requests/${documentId}/print`;
            });
        });
    </script> --}}
</x-layout>
