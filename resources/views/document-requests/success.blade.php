<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request Submitted - Barangay Maunong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Your existing CSS here */
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
            justify-content: center; /* Added for horizontal centering */
        }

        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            padding: 3rem 2rem;
        }

        .success-icon {
            color: #28a745;
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: checkmark 0.8s ease-in-out;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-title {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .success-message {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .reference-box {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            position: relative;
        }

        .reference-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .reference-number {
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            margin-bottom: 1rem;
        }

        .copy-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }

        .info-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
        }

        .info-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list li i {
            color: #667eea;
            width: 20px;
            margin-right: 1rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }

        .btn-outline-primary:hover {
            background: #667eea;
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .estimated-time {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 1rem;
            margin: 1.5rem 0;
            color: #856404;
        }

        .contact-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 10px;
            padding: 1rem;
            margin: 1.5rem 0;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="success-card">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>

                    <h1 class="success-title">Document Request Submitted!</h1>
                    <p class="success-message">
                        Your document request has been submitted successfully. Please save your tracking number to monitor its status.
                    </p>

                    <div class="reference-box">
                        <div class="reference-label">Your Tracking Number</div>
                        <div class="reference-number" id="trackingNumber">{{ $trackingNumber }}</div>
                        <button class="copy-btn" onclick="copyTrackingNumber()">
                            <i class="fas fa-copy me-2"></i>Copy Tracking Number
                        </button>
                    </div>

                    <div class="estimated-time">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Estimated Processing Time:</strong> 3-5 business days
                    </div>

                    <div class="info-section">
                        <h5 class="info-title">Important Information</h5>
                        <ul class="info-list">
                            <li>
                                <i class="fas fa-bookmark"></i>
                                Save your tracking number for monitoring your document request status.
                            </li>
                            <li>
                                <i class="fas fa-bell"></i>
                                You will be notified via SMS/email when your document is ready for pickup.
                            </li>
                            <li>
                                <i class="fas fa-id-card"></i>
                                Bring a valid ID when claiming your document at the barangay office.
                            </li>
                            <li>
                                <i class="fas fa-file-alt"></i>
                                Processing fees may apply. Please inquire at the barangay office.
                            </li>
                        </ul>
                    </div>

                    <div class="contact-info">
                        <strong><i class="fas fa-phone me-2"></i>Need Help?</strong><br>
                        Contact us at (02) 123-4567 or visit our office during business hours.
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('document-requests.track.result') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Track Request Status
                        </a>

                        {{-- Conditional button text and route based on user role --}}
                        @auth
                            @if(Auth::user()->role === 'secretary')
                                <a href="{{ route('document-requests.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-file-alt me-2"></i>Back to Document Requests
                                </a>
                            @else
                                <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-home me-2"></i>Back to Home
                                </a>
                            @endif
                        @else
                            {{-- If no user is logged in, show 'Back to Home' --}}
                            <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                        @endauth
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-link" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print this confirmation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyTrackingNumber() {
            const trackingNumber = document.getElementById('trackingNumber').textContent;

            // Create a temporary textarea element
            const tempTextarea = document.createElement('textarea');
            tempTextarea.value = trackingNumber;
            document.body.appendChild(tempTextarea);

            // Select and copy the text
            tempTextarea.select();
            document.execCommand('copy');

            // Remove the temporary element
            document.body.removeChild(tempTextarea);

            // Show feedback to user
            const copyBtn = document.querySelector('.copy-btn');
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
            copyBtn.style.background = 'rgba(40, 167, 69, 0.3)';

            setTimeout(() => {
                copyBtn.innerHTML = originalText;
                copyBtn.style.background = 'rgba(255,255,255,0.2)';
            }, 2000);
        }

        // Auto-focus on tracking number for easy copying
        document.addEventListener('DOMContentLoaded', function() {
            const trackingElement = document.getElementById('trackingNumber');

            // Add click event to select all text
            trackingElement.addEventListener('click', function() {
                const range = document.createRange();
                range.selectNodeContents(this);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            });
        });
    </script>
</body>
</html>
