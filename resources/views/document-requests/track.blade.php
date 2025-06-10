<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Registration - Barangay Maunong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            padding: 2rem 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .track-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .header-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
        }

        .header-subtitle {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }

        .back-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }

        .form-section {
            padding: 3rem 2rem;
        }

        .track-icon {
            color: #667eea;
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.8rem;
        }

        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-align: center;
            text-transform: uppercase;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            border: 2px solid #6c757d;
            color: #6c757d;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }


        .info-box {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
            border-left: 4px solid #667eea;
        }

        .info-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .info-text {
            color: #6c757d;
            margin: 0;
            line-height: 1.6;
        }

        .example-format {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            text-align: center;
        }

        .example-format strong {
            color: #856404;
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            letter-spacing: 1px;
        }

        .alert {
            border-radius: 15px;
            border: none;
            padding: 1rem 1.5rem;
        }

        .contact-section {
            background: #e8f4fd;
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: center;
        }

        .contact-title {
            color: #0c5460;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .contact-info {
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="track-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            {{-- Dynamic Back Link --}}
                            <a href="{{ Auth::check() && Auth::user()->role === 'secretary' ? route('document-requests.index') : route('welcome') }}" class="back-link">
                                <i class="fas fa-arrow-left me-2"></i>
                                @if(Auth::check() && Auth::user()->role === 'secretary')
                                    Back to Document Requests
                                @else
                                    Back to Home
                                @endif
                            </a>
                            <div style="width: 100px;"></div> </div>
                        <div class="text-center">
                            <i class="fas fa-search fa-2x mb-3"></i>
                        </div>
                        <h1 class="header-title">Track Your Request</h1>
                        <p class="header-subtitle">Enter your reference number to check your request status</p>
                    </div>

                    <div class="form-section">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif

                        <div class="text-center">
                            <div class="track-icon">
                                <i class="fas fa-file-search"></i>
                            </div>
                        </div>

                        <form action="{{ route('document-requests.track.result') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="tracking_number" class="form-label">
                                    <i class="fas fa-hashtag me-2"></i>Reference Number
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('tracking_number') is-invalid @enderror"
                                    id="tracking_number"
                                    name="tracking_number"
                                    value="{{ old('tracking_number') }}"
                                    placeholder="BR-2025-000000"
                                    maxlength="15"
                                    required>
                                @error('tracking_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="example-format">
                                <small class="text-muted">Format: </small>
                                <strong>BR-2025-000000</strong>
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Track Document Request
                                </button>
                            </div>

                            <div class="text-center">
                                {{-- Dynamic Back Link for bottom button --}}
                                <a href="{{ Auth::check() && Auth::user()->role === 'secretary' ? route('document-requests.index') : route('welcome') }}" class="btn btn-secondary">
                                    <i class="fas fa-home me-2"></i>
                                    @if(Auth::check() && Auth::user()->role === 'secretary')
                                        Back to Document Requests
                                    @else
                                        Back to Home
                                    @endif
                                </a>
                            </div>
                        </form>

                        <div class="info-box">
                            <div class="info-title">
                                <i class="fas fa-info-circle me-2"></i>How to Track
                            </div>
                            <div class="info-text">
                                Enter the reference number you received after completing your request.
                                This number was provided on your request page and should be saved for your records.
                            </div>
                        </div>

                        <div class="contact-section">
                            <div class="contact-title">
                                <i class="fas fa-question-circle me-2"></i>Need Help?
                            </div>
                            <div class="contact-info">
                                Can't find your reference number?<br>
                                <strong>Contact us:</strong> (02) 123-4567<br>
                                <strong>Email:</strong> info@barangaymaunong.gov.ph
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-format reference number input
        document.getElementById('tracking_number').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();

            // Remove any characters that aren't letters, numbers, or dashes
            value = value.replace(/[^A-Z0-9-]/g, '');

            // Limit length
            if (value.length > 15) {
                value = value.substring(0, 15);
            }

            e.target.value = value;
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const referenceNumber = document.getElementById('tracking_number').value.trim();

            if (!referenceNumber) {
                e.preventDefault();
                alert('Please enter your reference number.');
                return;
            }

            if (referenceNumber.length < 10) { // Assuming BR-2025-000000 is 13 characters, adjust as needed.
                e.preventDefault();
                alert('Please enter a valid reference number.');
                return;
            }
        });
    </script>
</body>
</html>
