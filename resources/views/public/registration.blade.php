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

        .preview-box {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .preview-box:hover {
            border-color: #667eea;
            background: #f0f2ff;
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

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin: 2rem 0;
            padding: 0 1rem;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #dee2e6;
            z-index: 1;
        }

        .step:last-child::after {
            display: none;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: 600;
            position: relative;
            z-index: 2;
        }

        .step-label {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
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
                            <a href="{{ route('welcome') }}" class="back-link">
                                <i class="fas fa-arrow-left me-2"></i>Back to Home
                            </a>
                            <div class="text-center">
                                <i class="fas fa-building fa-2x mb-2"></i>
                            </div>
                            <div style="width: 100px;"></div> <!-- Spacer -->
                        </div>
                        <h1 class="header-title">Resident Registration</h1>
                        <p class="header-subtitle">Register as a new resident of Barangay Maunong</p>
                    </div>

                    <!-- Progress Steps -->
                    <div class="progress-steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <div class="step-label">Personal Info</div>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-label">Contact & Address</div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-label">Emergency Contact</div>
                        </div>
                        <div class="step">
                            <div class="step-number">4</div>
                            <div class="step-label">Photo & Submit</div>
                        </div>
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

                    <div class="form-section">
                        <form action="{{ route('public.residents.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Personal Information -->
                            <div class="row mb-4">
                                <div class="col-md-12 mb-3">
                                    <h5 class="section-title">Personal Information</h5>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="suffix" class="form-label">Suffix</label>
                                    <input type="text" class="form-control @error('suffix') is-invalid @enderror" id="suffix" name="suffix" value="{{ old('suffix') }}" placeholder="Jr., Sr., III, etc.">
                                    @error('suffix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Demographics -->
                            <div class="row mb-4">
                                <div class="col-md-3 mb-3">
                                    <label for="birth_date" class="form-label">Birth Date <span class="required">*</span></label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="gender" class="form-label">Gender <span class="required">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="" selected disabled>Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="civil_status" class="form-label">Civil Status <span class="required">*</span></label>
                                    <select class="form-select @error('civil_status') is-invalid @enderror" id="civil_status" name="civil_status" required>
                                        <option value="" selected disabled>Select Civil Status</option>
                                        <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced/Separated</option>
                                    </select>
                                    @error('civil_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control @error('occupation') is-invalid @enderror" id="occupation" name="occupation" value="{{ old('occupation') }}">
                                    @error('occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="row mb-4">
                                <div class="col-md-12 mb-3">
                                    <h5 class="section-title">Contact Information</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_number" class="form-label">Contact Number <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" placeholder="09XX-XXX-XXXX" required>
                                    @error('contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="your.email@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="row mb-4">
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Complete Address <span class="required">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required placeholder="House No., Street, Purok/Sitio, Barangay Maunong, Municipality, Province">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div class="row mb-4">
                                <div class="col-md-12 mb-3">
                                    <h5 class="section-title">Emergency Contact</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_name" class="form-label">Contact Person Name</label>
                                    <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Full name of emergency contact">
                                    @error('emergency_contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_number" class="form-label">Contact Person Number</label>
                                    <input type="text" class="form-control @error('emergency_contact_number') is-invalid @enderror" id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}" placeholder="09XX-XXX-XXXX">
                                    @error('emergency_contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Photo & Document Upload -->
                            <div class="row mb-4">
                                <div class="col-md-12 mb-3">
                                    <h5 class="section-title">Photo & Document Upload</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="photo" class="form-label">Resident Photo (2x2 ID Picture)</label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                                    <div class="form-text">Upload a clear photo. Maximum file size: 2MB. Accepted formats: JPG, JPEG, PNG.</div>
                                    @error('photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <div class="d-flex align-items-center h-100">
            <div class="preview-box border d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                <span class="text-muted" id="preview-text">
                    <i class="fas fa-camera fa-2x mb-2 d-block"></i>
                    Photo Preview
                </span>
                <img id="photo-preview" src="#" alt="Preview" style="display: none; max-width: 100%; max-height: 100%; border-radius: 8px;">
            </div>
        </div>
    </div>

    <!-- NEW VALID ID SECTION -->
    <div class="col-md-6 mb-3">
        <label for="valid_id" class="form-label">Valid ID for Verification <span class="required">*</span></label>
        <input type="file" class="form-control @error('valid_id') is-invalid @enderror" id="valid_id" name="valid_id" accept="image/*" required>
        <div class="form-text">Upload a clear copy of any government-issued ID (Driver's License, Passport, PhilSys ID, etc.) Maximum file size: 2MB.</div>
        @error('valid_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <div class="d-flex align-items-center h-100">
            <div class="preview-box border d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                <span class="text-muted" id="id-preview-text">
                    <i class="fas fa-id-card fa-2x mb-2 d-block"></i>
                    ID Preview
                </span>
                <img id="id-preview" src="#" alt="ID Preview" style="display: none; max-width: 100%; max-height: 100%; border-radius: 8px;">
            </div>
        </div>
    </div>
</div>

                            <!-- Terms and Conditions -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Privacy Policy</a>. I certify that all information provided is true and accurate. <span class="required">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg me-3">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Registration
                                    </button>
                                    <a href="{{ route('welcome') }}" class="btn btn-secondary btn-lg">
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
        // Photo preview functionality
        document.getElementById('photo').addEventListener('change', function(e) {
            const previewText = document.getElementById('preview-text');
            const previewImg = document.getElementById('photo-preview');

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    previewText.style.display = 'none';
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                previewImg.style.display = 'none';
                previewText.style.display = 'block';
            }
        });

        // Valid ID preview functionality
        document.getElementById('valid_id').addEventListener('change', function(e) {
            const previewText = document.getElementById('id-preview-text');
            const previewImg = document.getElementById('id-preview');

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    previewText.style.display = 'none';
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                previewImg.style.display = 'none';
                previewText.style.display = 'block';
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['first_name', 'last_name', 'birth_date', 'gender', 'civil_status', 'contact_number', 'address', 'valid_id'];
            let hasErrors = false;

            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (field === 'valid_id') {
                    // Check if file is selected
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

            const termsCheckbox = document.getElementById('terms');
            if (!termsCheckbox.checked) {
                alert('Please agree to the Terms and Conditions to proceed.');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
                alert('Please fill in all required fields and upload a valid ID.');
            }
        });

        // Contact number formatting
        document.getElementById('contact_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            e.target.value = value;
        });

        // Emergency contact number formatting
        document.getElementById('emergency_contact_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            e.target.value = value;
        });
    </script>
</body>
</html>
