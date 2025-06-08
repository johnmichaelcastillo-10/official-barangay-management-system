<!-- resources/views/public/barangay-document-request.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    @if(isset($documentRequest))

    <div class="min-vh-100 d-flex justify-content-center align-items-center" style="background: linear-gradient(to right, #667eea, #764ba2);">
    <div class="card shadow-lg" style="border-radius: 1.5rem; max-width: 600px; width: 100%;">
        <!-- Gradient Header -->
        <div class="card-header text-center text-white" style="border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem; background: linear-gradient(to right, #6a11cb, #2575fc);">
            <h4 class="mb-1">Document Request Summary</h4>
            <p class="mb-0">Here are the details of your request</p>
        </div>

        <!-- Card Body -->
        <div class="card-body px-4 py-3">
    <div class="row mb-2">
        <div class="col-md-6">
            <strong>Tracking Number:</strong><br>
            {{ $documentRequest->tracking_number }}
        </div>
        <div class="col-md-6">
            <strong>Resident Name:</strong><br>
            {{ $documentRequest->resident->full_name }}
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <strong>Document Type:</strong><br>
            {{ ucwords(str_replace('_', ' ', $documentRequest->document_type)) }}
        </div>
        <div class="col-md-6">
            <strong>Purpose:</strong><br>
            {{ $documentRequest->purpose ?? 'N/A' }}
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <strong>Status:</strong><br>
            {{ ucfirst($documentRequest->status) }}
        </div>
        <div class="col-md-6">
            <strong>Remarks:</strong><br>
            {{ $documentRequest->remarks ?? 'None' }}
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <strong>Fee Amount:</strong><br>
            ₱{{ number_format($documentRequest->fee_amount, 2) }}
        </div>
        <div class="col-md-6">
            <strong>Payment Status:</strong><br>
            {{ ucfirst($documentRequest->payment_status) }}
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <strong>Requested Date:</strong><br>
            {{ \Carbon\Carbon::parse($documentRequest->requested_date)->format('M d, Y') }}
        </div>
        <div class="col-md-6">
            <strong>Target Release Date:</strong><br>
            {{ $documentRequest->target_release_date ? \Carbon\Carbon::parse($documentRequest->target_release_date)->format('M d, Y') : 'TBA' }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <strong>Actual Release Date:</strong><br>
            {{ $documentRequest->actual_release_date ? \Carbon\Carbon::parse($documentRequest->actual_release_date)->format('M d, Y') : 'Not Released Yet' }}
        </div>
    </div>
</div>


        <!-- Footer -->
        <div class="card-footer border-0 px-4 pb-4">
            <a href="{{ url()->previous() }}" class="btn btn-primary w-100" style="background: linear-gradient(to right, #6a11cb, #2575fc); border: none;">
                Back
            </a>
        </div>
    </div>
</div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
