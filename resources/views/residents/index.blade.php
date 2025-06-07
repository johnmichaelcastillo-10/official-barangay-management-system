<x-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Residents Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Residents</li>
                </ol>
            </nav>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Resident Records</h6>
                <a href="{{ route('residents.register') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus"></i> Register New Resident
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="residentsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Birth Date</th>
                                <th>Gender</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($residents as $resident)
                                <tr>
                                    <td>{{ $resident->id }}</td>
                                    <td>
                                        {{ $resident->first_name }}
                                        {{ $resident->middle_name ? $resident->middle_name . ' ' : '' }}
                                        {{ $resident->last_name }}
                                        {{ $resident->suffix ? ', ' . $resident->suffix : '' }}
                                    </td>
                                    <td>{{ $resident->birth_date->format('M d, Y') }}</td>
                                    <td>{{ ucfirst($resident->gender) }}</td>
                                    <td>{{ $resident->contact_number ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('residents.show', $resident) }}" class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('residents.edit', $resident) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('residents.destroy', $resident) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this resident? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            $(document).ready(function() {
                $('#residentsTable').DataTable({
                    order: [[0, 'asc']],
                    language: {
                        search: "Search residents:",
                        lengthMenu: "Show _MENU_ residents per page",
                    }
                });
            });
        </script>
    </x-slot:scripts>
</x-layout>
