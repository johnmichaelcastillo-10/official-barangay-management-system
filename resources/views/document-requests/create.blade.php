<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request - Barangay Maunong</title>
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

        .btn-primary:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            {{-- Changed 'back to home' to 'back to previous page' using history.back() --}}
                            <a href="javascript:history.back()" class="back-link">
                                <i class="fas fa-arrow-left me-2"></i>Back to Previous Page
                            </a>
                            <div class="text-center">
                                <i class="fas fa-file fa-2x mb-2"></i>
                            </div>
                            <div style="width: 100px;"></div>
                        </div>
                        <h1 class="header-title">Document Request Form</h1>
                        <p class="header-subtitle">Request a copy of your document</p>
                    </div>

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

                    <div class="form-section">
                        <form action="{{ route('document-request.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-4">
                                {{-- Resident Name Fields --}}
                                <div class="col-md-3 mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name" value="{{ old('first_name') }}" required placeholder="Juan">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                        id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="C.">
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" value="{{ old('last_name') }}" required placeholder="Dela Cruz">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="suffix" class="form-label">Suffix</label>
                                    <input type="text" class="form-control @error('suffix') is-invalid @enderror"
                                        id="suffix" name="suffix" value="{{ old('suffix') }}" placeholder="Jr. / Sr. / III">
                                    @error('suffix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Birth Date --}}
                                <div class="col-md-6 mb-3">
                                    <label for="birth_date" class="form-label">Birth Date <span class="required">*</span></label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                               id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                    <small id="residentStatus" class="form-text text-muted">Please enter your full name and birth date to check your verification status.</small>
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Hidden resident_id --}}
                                <input type="hidden" id="resident_id" name="resident_id">

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

                                <div class="col-md-12 mb-3">
                                    <label for="purpose" class="form-label">Purpose <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('purpose') is-invalid @enderror"
                                               id="purpose" name="purpose" value="{{ old('purpose') }}" required placeholder="Enter purpose of the request">
                                    @error('purpose')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" id="submitRequestBtn" class="btn btn-primary btn-lg me-3" disabled>
                                        <i class="fas fa-paper-plane me-2"></i>Submit Request
                                    </button>
                                    {{-- Updated Cancel button to use history.back() --}}
                                    <a href="javascript:history.back()" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const firstNameInput = document.getElementById('first_name');
        const middleNameInput = document.getElementById('middle_name');
        const lastNameInput = document.getElementById('last_name');
        const suffixInput = document.getElementById('suffix');
        const birthDateInput = document.getElementById('birth_date');
        const residentIdInput = document.getElementById('resident_id');
        const residentStatus = document.getElementById('residentStatus');
        const submitRequestBtn = document.getElementById('submitRequestBtn');

        function updateResidentStatusUI(message, type, enableSubmit) {
            residentStatus.textContent = message;
            residentStatus.classList.remove('text-success', 'text-danger', 'text-muted');
            residentStatus.classList.add(`text-${type}`);
            submitRequestBtn.disabled = !enableSubmit;
        }

        function fetchResidentId() {
            const firstName = firstNameInput.value.trim();
            const middleName = middleNameInput.value.trim();
            const lastName = lastNameInput.value.trim();
            const suffix = suffixInput.value.trim();
            const birthDate = birthDateInput.value.trim();

            if (!firstName || !lastName || !birthDate) {
                updateResidentStatusUI('Please enter your full name and birth date to check your verification status.', 'muted', false);
                residentIdInput.value = '';
                return;
            }

            updateResidentStatusUI('Checking resident status...', 'muted', false);
            residentIdInput.value = '';

            const queryParams = new URLSearchParams({
                first_name: firstName,
                last_name: lastName,
                birth_date: birthDate,
            });

            if (middleName) {
                queryParams.append('middle_name', middleName);
            }
            if (suffix) {
                queryParams.append('suffix', suffix);
            }

            fetch(`{{ route('residents.get-id-by-name-and-birthdate') }}?${queryParams.toString()}`)
                .then(response => {
                    if (!response.ok) {
                        // If the response is not OK (e.g., 404, 500), parse the error
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.id) {
                        console.log(data); // Log the new flag for debugging
                        if (data.is_approved_and_verified) { // Use the new flag from the backend
                            residentIdInput.value = data.id;
                            updateResidentStatusUI('Resident found and verified. You can now submit your request.', 'success', true);
                        } else {
                            residentIdInput.value = ''; // Ensure hidden ID is cleared if not approved/verified
                            updateResidentStatusUI('Resident found, but your account is not yet approved and verified. Please wait for official approval by the barangay.', 'danger', false);
                        }
                    } else {
                        residentIdInput.value = '';
                        updateResidentStatusUI('Resident not found. Please ensure your name, suffix, and birth date match your registration, or register if you haven\'t yet.', 'danger', false);
                    }
                })
                .catch(error => {
                    console.error('Error fetching resident status:', error);
                    let errorMessage = 'An error occurred while checking resident status.';
                    if (error.message) {
                        errorMessage += ` Details: ${error.message}`; // Display backend error message if available
                    }
                    updateResidentStatusUI(errorMessage, 'danger', false);
                    residentIdInput.value = ''; // Ensure hidden ID is cleared on error
                });
        }

        // Add event listeners
        firstNameInput.addEventListener('change', fetchResidentId);
        middleNameInput.addEventListener('change', fetchResidentId);
        lastNameInput.addEventListener('change', fetchResidentId);
        suffixInput.addEventListener('change', fetchResidentId);
        birthDateInput.addEventListener('change', fetchResidentId);

        // Initial check if fields are pre-filled (e.g., from old())
        fetchResidentId();

        // Client-side form validation (only for required fields other than resident status)
        document.querySelector('form')?.addEventListener('submit', function (e) {
            let hasErrors = false;
            const requiredFields = ['first_name', 'last_name', 'birth_date', 'document_type', 'purpose'];

            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input) return;

                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    hasErrors = true;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Prevent submission if a verified resident isn't found or other required fields are missing
            if (!residentIdInput.value || hasErrors) {
                e.preventDefault();
                // Re-run resident check to ensure the latest status message is displayed
                if (!residentIdInput.value) {
                    fetchResidentId();
                }
            }
        });
    </script>
</body>
</html>
