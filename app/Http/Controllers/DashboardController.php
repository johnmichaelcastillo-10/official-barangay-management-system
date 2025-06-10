<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Resident;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the user's role and redirect to the appropriate dashboard
        $user = Auth::user();
        $role = $user->role;

        // Return the view directly rather than redirecting
        if ($role === 'chairman') {
            return $this->chairman();
        } elseif ($role === 'secretary') {
            return $this->secretary();
        } elseif ($role === 'staff') {
            return $this->staff();
        } else {
            // Default dashboard for unknown roles
            return view('dashboard.index', [
                'role' => $role
            ]);
        }
    }

    /**
     * Show the chairman dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function chairman()
    {
        // Count only APPROVED residents (exclude pending registrations)
        $totalResidents = Resident::whereNotNull('approved_at')
            ->orWhere('registration_type', 'admin')
            ->count();

        $totalStaff = User::where('role', '!=', 'chairman')->count();
        $documentsIssued = 0; // Replace with actual calculation when available
        $itemsForApproval = 0; // Replace with actual calculation when available

        return view('dashboard.chairman', compact(
            'totalResidents',
            'totalStaff',
            'documentsIssued',
            'itemsForApproval'
        ));
    }

    /**
     * Show the secretary dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function secretary()
    {
        // Count only APPROVED residents (exclude pending registrations)
        $totalResidents = Resident::whereNotNull('approved_at')
            ->orWhere('registration_type', 'admin')
            ->count();

        $certificatesIssued = 0; // Replace with actual calculation when available
        $pendingRequests = 0; // Replace with actual calculation when available
        $pendingDocumentRequests = 0; // Replace with actual calculation when available
        $pendingCertificates = 0; // Replace with actual calculation when available
        $activeUsers = User::where('status', 'active')->count();
        return view('dashboard.secretary', compact(
            'totalResidents',
            'certificatesIssued',
            'pendingCertificates',
            'pendingDocumentRequests',
            'pendingRequests',
            'activeUsers'
        ));
    }

    /**
     * Show the staff dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function staff()
    {
        $residentsAssisted = 0; // Replace with actual calculation when available
        $documentsProcessed = 0; // Replace with actual calculation when available
        $assignedTasks = 0; // Replace with actual calculation when available
        $pendingRequests = 0; // Replace with actual calculation when available

        return view('dashboard.staff', compact(
            'residentsAssisted',
            'documentsProcessed',
            'assignedTasks',
            'pendingRequests'
        ));
    }
}
