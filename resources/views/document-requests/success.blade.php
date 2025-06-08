<!-- resources/views/public/barangay-document-request.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Document Request Submitted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    <div class="modal fade show" id="trackingModal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100">Request Submitted</h5>
                </div>
                <div class="modal-body">
                    <p>Your document request has been submitted successfully.</p>
                    <p>Please take a phone to save the Tracking Number. You will not be able to see this page again!</p>
                    <h4 class="text-primary">Tracking Number</h4>
                    <div class="fs-4 fw-bold mb-3">{{ $trackingNumber }}</div>
                </div>
                <div class="modal-footer border-0">
                    <a href="{{ url('/') }}" class="btn btn-primary w-100">Close</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Backdrop -->
    <div class="modal-backdrop fade show"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
