<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Registration - Barangay Maunong</title>
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

        .registration-card {
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

        .form-section {
            padding: 2rem;
        }

        .section-title {
            color: #667eea;
            font-weight: 600;
            border-bottom: 2px solid #f1f3f4;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
        }

        .required {
            color: #e74c3c;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
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

        .alert {
            border-radius: 15px;
            border: none;
        }

    </style>
</head>
<body>
    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="registration-card">
                    <!-- Card Header -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('welcome') }}" class="back-link">
                                <i class="fas fa-arrow-left me-2"></i>Back to Home
                            </a>
                            <div class="text-center">
                                <i class="fas fa-file fa-2x mb-2"></i>
                            </div>
                            <div style="width: 100px;"></div> <!-- Spacer -->
                        </div>
                        <h1 class="header-title">Document Request Form</h1>
                        <p class="header-subtitle">Request a copy of your document</p>
                    </div>

                    <!-- Error Display -->
                    @if ($errors->any())
                        <div class="mx-4 mt-3">
                            <div class="alert alert-danger" role="alert">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Please correct the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Form Section -->
                    <div class="form-section">
                        <form action="{{ route('document-request.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <!-- Email -->
                        {{-- Email Field --}}
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" required placeholder="your.email@example.com">
                            <small id="emailStatus" class="form-text text-muted"></small>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                                {{-- Hidden resident_id --}}
                        <input type="hidden" id="resident_id" name="resident_id">
                        </div>



        <!-- Birth Date -->
        <div class="col-md-6 mb-3">
            <label for="birth_date" class="form-label">Birth Date <span class="required">*</span></label>
            <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                   id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
            @error('birth_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Document Type -->
        <div class="col-md-6 mb-3">
            <label for="document_type" class="form-label">Document Type <span class="required">*</span></label>
            <select class="form-select @error('document_type') is-invalid @enderror"
                    id="document_type" name="document_type" required>
                <option value="" disabled selected>Select document type</option>
                @foreach ($documentTypes as $key => $label)
                    <option value="{{ $key }}" {{ old('document_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('document_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Purpose -->
        <div class="col-md-12 mb-3">
            <label for="purpose" class="form-label">Purpose <span class="required">*</span></label>
            <input type="text" class="form-control @error('purpose') is-invalid @enderror"
                   id="purpose" name="purpose" value="{{ old('purpose') }}" required placeholder="Enter purpose of the request">
            @error('purpose')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="row">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-paper-plane me-2"></i>Submit Request
            </button>
            <a href="{{ route('welcome') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </div>
</form>

                    </div>
                    <!-- End Form Section -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Form JS -->
    <script>
        document.getElementById('email').addEventListener('blur', function () {
            const email = this.value.trim();
            const status = document.getElementById('emailStatus');
            const residentIdInput = document.getElementById('resident_id');

            status.textContent = 'Checking...';
            residentIdInput.value = '';

            console.log(email);


            fetch(`{{ route('residents.get-id') }}?email=${encodeURIComponent(email)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.id) {
                        residentIdInput.value = data.id;
                        status.textContent = 'Resident found.';
                        status.classList.remove('text-danger');
                        status.classList.add('text-success');
                    } else {
                        status.textContent = 'Resident not found.';
                        status.classList.remove('text-success');
                        status.classList.add('text-danger');
                    }
                })
                .catch(() => {
                    status.textContent = 'Error checking email.';
                    status.classList.remove('text-success');
                    status.classList.add('text-danger');
                });
        });
    </script>
    <script>
        // Form validation
        document.querySelector('form')?.addEventListener('submit', function (e) {
            const requiredFields = ['email', 'birth_date', 'document_type'];
            let hasErrors = false;

            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input) return;

                if (field === 'valid_id') {
                    if (!input.files || input.files.length === 0) {
                        input.classList.add('is-invalid');
                        hasErrors = true;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                } else if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    hasErrors = true;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (hasErrors) {
                e.preventDefault();
                alert('Please fill in all required fields and upload a valid ID.');
            }
        });

        // Phone input formatting
        const formatPhone = (id) => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                e.target.value = value;
            });
        };

        formatPhone('contact_number');
        formatPhone('emergency_contact_number');
    </script>
</body>

</html>
