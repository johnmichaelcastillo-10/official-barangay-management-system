<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request Summary - Barangay Maunong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .summary-card {
            background: white;
            border-radius: 15px; /* Softer border-radius like forms */
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); /* Slightly less intense shadow */
            overflow: hidden;
            max-width: 750px; /* Adjust width as needed */
            width: 100%;
            margin: 2rem;
        }

        .card-header-form {
            background-color: #f8f9fa; /* Light background like form headers */
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem 2rem;
            text-align: center;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .header-title-form {
            color: #343a40; /* Darker text for header */
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .header-subtitle-form {
            color: #6c757d;
            font-size: 1rem;
            margin: 0;
        }

        .card-body-form {
            padding: 2.5rem; /* Consistent padding with forms */
        }

        .detail-item {
            margin-bottom: 1.5rem; /* Spacing between detail rows */
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-label-form {
            font-weight: 600;
            color: #495057; /* Darker label color */
            margin-bottom: 0.5rem;
            display: block; /* Ensures label takes full width */
            font-size: 0.95rem;
        }

        .detail-value-form {
            background-color: #e9ecef; /* Light grey background for values */
            border: 1px solid #ced4da;
            border-radius: 8px; /* Rounded corners for value display */
            padding: 0.75rem 1rem;
            color: #495057;
            font-size: 1rem;
            word-wrap: break-word;
            min-height: 40px; /* Ensure consistent height */
            display: flex;
            align-items: center; /* Vertically center content */
        }

        /* Status and Payment Status Badges */
        .status-badge, .payment-status-badge {
            padding: 0.4em 0.8em;
            border-radius: 0.4rem;
            font-weight: bold;
            font-size: 0.95em;
            display: inline-block; /* Allow text to flow around */
        }

        .status-badge-pending, .payment-status-pending {
            background-color: #ffc107; /* Warning yellow */
            color: #333;
        }

        .status-badge-processing { /* Added for processing status */
            background-color: #17a2b8; /* Info blue */
            color: white;
        }

        .status-badge-ready, .payment-status-paid { /* Renamed for consistency */
            background-color: #28a745; /* Success green */
            color: white;
        }

        .status-badge-rejected {
            background-color: #dc3545; /* Danger red */
            color: white;
        }

        .status-badge-released {
            background-color: #6f42c1; /* Purple */
            color: white;
        }

        .card-footer-form {
            padding: 1.5rem 2.5rem 2.5rem;
            background-color: #f8f9fa; /* Light background */
            border-top: 1px solid #dee2e6;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            text-align: center;
        }

        .btn-back-form {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px; /* Rounded button like in forms */
            padding: 12px 35px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 1.05rem;
            color: white; /* Ensure text is white */
        }

        .btn-back-form:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .no-request-found {
            padding: 70px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            color: white; /* Ensure text is visible on the gradient background */
        }

        .no-request-found h2 {
            color: white;
            margin-bottom: 1rem;
        }

        .no-request-found p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .btn-primary-no-request {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white; /* Ensure text is white */
        }

        .btn-primary-no-request:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-light-no-request {
            border: 2px solid #fff;
            color: #fff;
            background-color: transparent;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-outline-light-no-request:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
    </style>
</head>
<body>
    @if(isset($documentRequest))
        <div class="summary-card">
            <div class="card-header-form">
                <h4 class="header-title-form">Document Request Summary</h4>
                <p class="header-subtitle-form">Here are the details of your submitted request.</p>
            </div>

            <div class="card-body-form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label-form">Tracking Number</label>
                            <div class="detail-value-form">{{ $documentRequest->tracking_number }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label-form">Resident Name</label>
                            <div class="detail-value-form">{{ $documentRequest->resident->full_name }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label-form">Document Type</label>
                            <div class="detail-value-form">{{ ucwords(str_replace('_', ' ', $documentRequest->document_type)) }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label-form">Purpose</label>
                            <div class="detail-value-form">{{ $documentRequest->purpose ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-item">
                            <label class="detail-label-form">Status</label>
                            <div class="detail-value-form">
                                @php
                                    $statusClass = '';
                                    switch ($documentRequest->status) {
                                        case 'pending': $statusClass = 'status-badge-pending'; break;
                                        case 'processing': $statusClass = 'status-badge-processing'; break;
                                        case 'ready': $statusClass = 'status-badge-ready'; break;
                                        case 'released': $statusClass = 'status-badge-released'; break;
                                        case 'rejected': $statusClass = 'status-badge-rejected'; break;
                                        default: $statusClass = 'bg-secondary text-white'; // Default if status is unknown
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($documentRequest->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label-form">Requested Date</label>
                            <div class="detail-value-form">{{ \Carbon\Carbon::parse($documentRequest->requested_date)->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label-form">Target Release Date</label>
                            <div class="detail-value-form">{{ $documentRequest->target_release_date ? \Carbon\Carbon::parse($documentRequest->target_release_date)->format('M d, Y') : 'TBA' }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-item">
                            <label class="detail-label-form">Actual Release Date</label>
                            <div class="detail-value-form">{{ $documentRequest->released_at ? \Carbon\Carbon::parse($documentRequest->released_at)->format('M d, Y H:i A') : 'Not Released Yet' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer-form">
                <a href="{{ url()->previous() }}" class="btn btn-back-form">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    {{-- @else
        <div class="container text-center no-request-found">
            <h2 class="mb-4">No Request Found</h2>
            <p>It looks like there's no document request to display or the tracking number is invalid.</p>
            <a href="{{ route('document-request.track') }}" class="btn btn-primary-no-request mt-3">
                <i class="fas fa-search me-2"></i>Track Another Request
            </a>
            <a href="{{ route('welcome') }}" class="btn btn-outline-light-no-request mt-3 ms-2">
                <i class="fas fa-home me-2"></i>Back to Home
            </a>
        </div> --}}
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
