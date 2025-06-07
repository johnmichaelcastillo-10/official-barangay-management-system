<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Dashboard</h1>
            <div>
                <span class="badge bg-secondary">{{ ucfirst(Auth::user()->role) }}</span>
                <span class="text-muted ms-2">{{ date('F d, Y') }}</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center p-5">
                        <h3>Welcome to Barangay Maunong Management System</h3>
                        <p class="lead">You will be redirected to your role-specific dashboard momentarily.</p>
                        <div class="spinner-border text-primary mt-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <script>
                            // Redirect to role-specific dashboard
                            setTimeout(function() {
                                window.location.href = "{{ route('dashboard.' . Auth::user()->role) }}";
                            }, 2000);
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
