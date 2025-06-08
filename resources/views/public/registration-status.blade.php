<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request Status - Barangay Maunong</title>
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
        }

        .status-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .status-header {
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .status-pending {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
        }

        .status-approved {
            background: linear-gradient(45deg, #28a745, #20c997);
        }

        .status-rejected {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
        }

        .status-default {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .status-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        .status-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .status-subtitle {
            opacity: 0.9;
            margin: 0;
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

        .details-section {
            padding: 2rem;
        }

        .section-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f1f3f4;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
        }

        .detail-label i {
            width: 20px;
            margin-right: 0.5rem;
            color: #667eea;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 500;
            text-align: right;
        }

        .reference-number {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin: 1.5rem 0;
            text-align: center;
            border: 2px solid #e9ecef;
        }

        .reference-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .reference-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }

        .next-steps {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .next-steps-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .steps-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .steps-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: start;
        }

        .steps-list li i {
            color: #667eea;
            margin-right: 1rem;
            margin-top: 0.2rem;
            width: 16px;
        }

        .timeline {
            background: #fff;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
            border: 2px solid #f1f3f4;
        }

        .timeline-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .timeline-completed {
            background: #28a745;
            color: white;
        }

        .timeline-current {
            background: #ffc107;
            color: #856404;
        }

        .timeline-pending {
            background: #f8f9fa;
            color: #6c757d;
            border: 2px solid #dee2e6;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-step {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.2rem;
        }

        .timeline-date {
            font-size: 0.9rem;
            color: #6c757d;
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

        .rejection-reason {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            color: #721c24;
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
            <div class="col-lg-8 col-md-10">
                <div class="status-card">
                    <!-- Status Header -->
                    <div class="status-header
                        @if(isset($resident->approved_at)) status-approved
                        @elseif(isset($resident->rejected_at)) status-rejected
                        @elseif($resident->submitted_at) status-pending
                        @else status-default
                        @endif">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('residents.track') }}" class="back-link">
                                <i class="fas fa-arrow-left me-2"></i>Track Another
                            </a>
                            <a href="{{ route('welcome') }}" class="back-link">
                                Home <i class="fas fa-home ms-2"></i>
                            </a>
                        </div>

                        <div class="status-icon">
                            @if(isset($resident->approved_at))
                                <i class="fas fa-check-circle"></i>
                            @elseif(isset($resident->rejected_at))
                                <i class="fas fa-times-circle"></i>
                            @elseif($resident->submitted_at)
                                <i class="fas fa-clock"></i>
                            @else
                                <i class="fas fa-user-check"></i>
                            @endif
                        </div>

                        <h1 class="status-title">
                            @if(isset($resident->approved_at))
                                Registration Approved!
                            @elseif(isset($resident->rejected_at))
                                Registration Rejected
                            @elseif($resident->submitted_at)
                                Registration Pending
                            @else
                                Registration Found
                            @endif
                        </h1>

                        <p class="status-subtitle">
                            @if(isset($resident->approved_at))
                                Your registration has been approved. You can now claim your resident ID.
                            @elseif(isset($resident->rejected_at))
                                Your registration was rejected. Please see details below.
                            @elseif($resident->submitted_at)
                                Your registration is being reviewed by our office.
                            @else
                                Registration information found in our system.
                            @endif
                        </p>
                    </div>

                    <!-- Registration Details -->
                    <div class="details-section">
                        <!-- Reference Number -->
                        <div class="reference-number">
                            <div class="reference-label">Reference Number</div>
                            <div class="reference-value">{{ $resident->reference_number }}</div>
                        </div>

                        <!-- Personal Information -->
                        <h5 class="section-title">
                            <i class="fas fa-user me-2"></i>Registration Details
                        </h5>

                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-user"></i>Full Name
                            </div>
                            <div class="detail-value">
                                {{ $resident->first_name }}
                                {{ $resident->middle_name ? $resident->middle_name . ' ' : '' }}
                                {{ $resident->last_name }}
                                {{ $resident->suffix ? ', ' . $resident->suffix : '' }}
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-calendar"></i>Birth Date
                            </div>
                            <div class="detail-value">
                                {{ \Carbon\Carbon::parse($resident->birth_date)->format('F d, Y') }}
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-phone"></i>Contact Number
                            </div>
                            <div class="detail-value">{{ $resident->contact_number ?: 'Not provided' }}</div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-calendar-plus"></i>Submitted Date
                            </div>
                            <div class="detail-value">
                                {{ $resident->submitted_at ? \Carbon\Carbon::parse($resident->submitted_at)->format('F d, Y g:i A') : 'N/A' }}
                            </div>
                        </div>

                        @if(isset($resident->approved_at))
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-check"></i>Approved Date
                                </div>
                                <div class="detail-value">
                                    {{ \Carbon\Carbon::parse($resident->approved_at)->format('F d, Y g:i A') }}
                                </div>
                            </div>
                        @endif

                        @if(isset($resident->rejected_at))
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-times"></i>Rejected Date
                                </div>
                                <div class="detail-value">
                                    {{ \Carbon\Carbon::parse($resident->rejected_at)->format('F d, Y g:i A') }}
                                </div>
                            </div>
                        @endif

                        <!-- Rejection Reason -->
                        @if(isset($resident->rejected_at) && $resident->rejection_reason)
                            <div class="rejection-reason">
                                <strong><i class="fas fa-info-circle me-2"></i>Rejection Reason:</strong><br>
                                {{ $resident->rejection_reason }}
                            </div>
                        @endif

                        <!-- Timeline -->
                        <div class="timeline">
                            <h6 class="timeline-title">Registration Timeline</h6>

                            <div class="timeline-item">
                                <div class="timeline-icon timeline-completed">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-step">Application Submitted</div>
                                    <div class="timeline-date">
                                        {{ $resident->submitted_at ? \Carbon\Carbon::parse($resident->submitted_at)->format('M d, Y g:i A') : 'Completed' }}
                                    </div>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-icon {{ isset($resident->approved_at) || isset($resident->rejected_at) ? 'timeline-completed' : 'timeline-current' }}">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-step">Under Review</div>
                                    <div class="timeline-date">
                                        @if(isset($resident->approved_at) || isset($resident->rejected_at))
                                            Completed
                                        @else
                                            In Progress
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-icon {{ isset($resident->approved_at) ? 'timeline-completed' : (isset($resident->rejected_at) ? 'timeline-rejected' : 'timeline-pending') }}">
                                    @if(isset($resident->approved_at))
                                        <i class="fas fa-check"></i>
                                    @elseif(isset($resident->rejected_at))
                                        <i class="fas fa-times"></i>
                                    @else
                                        <i class="fas fa-clock"></i>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-step">
                                        @if(isset($resident->approved_at))
                                            Approved
                                        @elseif(isset($resident->rejected_at))
                                            Rejected
                                        @else
                                            Decision Pending
                                        @endif
                                    </div>
                                    <div class="timeline-date">
                                        @if(isset($resident->approved_at))
                                            {{ \Carbon\Carbon::parse($resident->approved_at)->setTimezone('Asia/Manila')->format('M d, Y g:i A') }}
                                        @elseif(isset($resident->rejected_at))
                                            {{ \Carbon\Carbon::parse($resident->rejected_at)->setTimezone('Asia/Manila')->format('M d, Y g:i A') }}
                                        @else
                                            Pending
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(isset($resident->approved_at))
                                <div class="timeline-item">
                                    <div class="timeline-icon timeline-pending">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-step">Ready for ID Claim</div>
                                        <div class="timeline-date">Visit Barangay Office</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Next Steps -->
                        <div class="next-steps">
                            <h6 class="next-steps-title">
                                <i class="fas fa-list-check me-2"></i>Next Steps
                            </h6>
                            <ul class="steps-list">
                                @if(isset($resident->approved_at))
                                    <li>
                                        <i class="fas fa-building"></i>
                                        Visit the Barangay Maunong office during business hours
                                    </li>
                                    <li>
                                        <i class="fas fa-id-card"></i>
                                        Bring valid government-issued ID and supporting documents
                                    </li>
                                    <li>
                                        <i class="fas fa-receipt"></i>
                                        Present this reference number: <strong>{{ $resident->reference_number }}</strong>
                                    </li>
                                    <li>
                                        <i class="fas fa-user-check"></i>
                                        Claim your official Barangay Resident ID
                                    </li>
                                @elseif(isset($resident->rejected_at))
                                    <li>
                                        <i class="fas fa-phone"></i>
                                        Contact the barangay office for clarification
                                    </li>
                                    <li>
                                        <i class="fas fa-file-alt"></i>
                                        Review the rejection reason above
                                    </li>
                                    <li>
                                        <i class="fas fa-redo"></i>
                                        You may reapply after addressing the issues
                                    </li>
                                @else
                                    <li>
                                        <i class="fas fa-clock"></i>
                                        Wait for the review process to complete (3-5 business days)
                                    </li>
                                    <li>
                                        <i class="fas fa-bell"></i>
                                        You will be notified via SMS/email when decision is made
                                    </li>
                                    <li>
                                        <i class="fas fa-search"></i>
                                        Check back periodically using this tracking page
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <a href="{{ route('residents.track') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Track Another Registration
                            </a>
                            <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                        </div>

                        <!-- Print Option -->
                        <div class="text-center mt-3">
                            <button class="btn btn-link" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Print Status Report
                            </button>
                        </div>

                        <!-- Contact Section -->
                        <div class="contact-section">
                            <div class="contact-title">
                                <i class="fas fa-phone me-2"></i>Need Assistance?
                            </div>
                            <div class="contact-info">
                                <strong>Barangay Maunong Office</strong><br>
                                Phone: (02) 123-4567<br>
                                Email: info@barangaymaunong.gov.ph<br>
                                Office Hours: Monday-Friday, 8:00 AM - 5:00 PM
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
