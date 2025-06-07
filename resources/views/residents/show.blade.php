<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Resident Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('residents.index') }}">Residents</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Details</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <!-- Resident Personal Information -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                        <div>
                            <a href="{{ route('residents.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2">Basic Information</h5>
                                <div class="mb-3">
                                    <strong>Full Name:</strong>
                                    <p>{{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Birth Date:</strong>
                                    <p>{{ $resident->birth_date->format('F d, Y') }} {{ (int)$resident->age }} years old</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Gender:</strong>
                                    <p>{{ ucfirst($resident->gender) }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Civil Status:</strong>
                                    <p>{{ ucfirst($resident->civil_status) }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Occupation:</strong>
                                    <p>{{ $resident->occupation ?? 'Not specified' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2">Contact Information</h5>
                                <div class="mb-3">
                                    <strong>Contact Number:</strong>
                                    <p>{{ $resident->contact_number ?? 'Not specified' }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Email:</strong>
                                    <p>{{ $resident->email ?? 'Not specified' }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Address:</strong>
                                    <p>{{ $resident->address }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Emergency Contact:</strong>
                                    <p>{{ $resident->emergency_contact_name ?? 'Not specified' }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Emergency Contact Number:</strong>
                                    <p>{{ $resident->emergency_contact_number ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">Registration Information</h5>
                                <div class="mb-3">
                                    <strong>Registration Date:</strong>
                                    <p>{{ $resident->created_at->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Last Updated:</strong>
                                    <p>{{ $resident->updated_at->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resident Photo and Actions -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Resident Photo</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($resident->photo)
                            <img src="{{ asset('storage/'.$resident->photo) }}" alt="{{ $resident->full_name }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                        @else
                            <div class="border rounded p-5 mb-3 bg-light">
                                <i class="fas fa-user fa-5x text-secondary"></i>
                                <p class="mt-3 text-muted">No photo available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('residents.edit', $resident) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i> Edit Information
                            </a>
                            <button type="button" class="btn btn-success">
                                <i class="fas fa-print me-2"></i> Print Details
                            </button>
                            <form action="{{ route('residents.destroy', $resident) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this resident? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i> Delete Resident
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
