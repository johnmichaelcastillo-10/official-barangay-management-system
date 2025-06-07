<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\RejectedRegistration; // New model for rejected records
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    /**
     * Display a listing of all residents.
     * Restricted to chairman and secretary only.
     */
    public function index()
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        // Only show approved residents (exclude pending and rejected registrations)
        $residents = Resident::whereNotNull('approved_at')
            ->whereNull('rejected_at') // Exclude any that might have been rejected
            ->orWhere('registration_type', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('residents.index', compact('residents'));
    }

    /**
     * Display pending registrations that need verification.
     * Restricted to chairman and secretary only.
     * UPDATED: Now includes sorting and search functionality
     */
    public function pendingRegistrations(Request $request)
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        // Start with base query for pending registrations
        $query = Resident::where('registration_type', 'public')
            ->whereNull('approved_at')
            ->whereNull('rejected_at');

        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('contact_number', 'like', "%{$search}%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                ->orWhereRaw("CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }

        // Handle sorting
        $sortColumn = $request->get('sort');
        $sortDirection = $request->get('direction', 'asc');

        // Define allowed sort columns for security
        $allowedSortColumns = [
            'reference_number',
            'first_name',
            'last_name',
            'birth_date',
            'submitted_at',
            'contact_number',
            'email'
        ];

        // Apply sorting if valid
        if ($sortColumn && in_array($sortColumn, $allowedSortColumns)) {
            // Ensure direction is either 'asc' or 'desc'
            $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'asc';
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            // Default sorting - newest submissions first
            $query->orderBy('submitted_at', 'desc');
        }

        // Get the results
        $pendingRegistrations = $query->get();

        return view('residents.pending', compact('pendingRegistrations'));
    }

    /**
     * Display rejected registrations archive.
     * New method for viewing rejected registrations.
     */
    public function rejectedRegistrations()
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        $rejectedRegistrations = RejectedRegistration::orderBy('rejected_at', 'desc')->get();

        return view('residents.rejected', compact('rejectedRegistrations'));
    }

    /**
     * Show the form for creating a new resident (admin version).
     */
    public function showRegistrationForm()
    {
        return view('residents.register');
    }

    /**
     * Show the form for public resident registration.
     * No auth check needed - this is public
     */
    public function showPublicRegistrationForm()
    {
        return view('public.registration');
    }

    public function showDocumentRequestForm()
    {
        return view('public.barangay-document-request');
    }

    /**
     * Store a newly created resident in storage (admin version).
     */
    public function store(Request $request)
    {
        $validated = $this->validateResidentData($request);

        // Check for duplicate resident (same name + birth date)
        $existingResident = Resident::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->where('birth_date', $request->birth_date)
            ->whereNull('rejected_at') // Don't count rejected registrations as duplicates
            ->first();

        if ($existingResident) {
            return back()->withInput()
                ->withErrors(['duplicate' => 'A resident with the same name and birth date already exists. Please check if this person is already registered.']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('residents/photos', 'public');
            $validated['photo'] = $photoPath;
        }

        // Admin registrations - automatically approved
        $validated['registration_type'] = 'admin';
        $validated['approved_at'] = now();
        $validated['approved_by'] = Auth::id();

        $resident = Resident::create($validated);

        // Generate reference number for admin registrations too
        $referenceNumber = 'REG-' . date('Y') . '-' . str_pad($resident->id, 6, '0', STR_PAD_LEFT);
        $resident->update(['reference_number' => $referenceNumber]);

        return redirect()->route('dashboard')
            ->with('success', 'Resident registered successfully!');
    }

    /**
     * Store a newly created resident from public registration.
     * This will be PENDING until approved by admin.
     */
    public function storePublic(Request $request)
    {
        $validated = $this->validatePublicResidentData($request);

        // Check for duplicate resident (same name + birth date) - excluding rejected ones
        $existingResident = Resident::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->where('birth_date', $request->birth_date)
            ->whereNull('rejected_at') // Don't count rejected registrations as duplicates
            ->first();

        if ($existingResident) {
            return back()->withInput()
                ->withErrors(['duplicate' => 'A resident with the same name and birth date already exists. You may already be registered. Please contact the barangay office for assistance.']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('residents/photos', 'public');
            $validated['photo'] = $photoPath;
        }

        // Handle valid ID upload
        if ($request->hasFile('valid_id')) {
            $validIdPath = $request->file('valid_id')->store('residents/valid_ids', 'public');
            $validated['valid_id'] = $validIdPath;
        }

        // Public registrations - PENDING status (no approved_at timestamp)
        $validated['registration_type'] = 'public';
        $validated['submitted_at'] = now();
        // Do NOT set approved_at - this makes it pending verification

        // Create the resident (but it's pending approval)
        $resident = Resident::create($validated);

        // Generate reference number
        $referenceNumber = 'REG-' . date('Y') . '-' . str_pad($resident->id, 6, '0', STR_PAD_LEFT);
        $resident->update(['reference_number' => $referenceNumber]);

        // Return success page with reference number
        return view('public.registration-success', compact('referenceNumber', 'resident'));
    }

    /**
     * Track public registration status
     */
    public function trackRegistration()
    {
        return view('public.track-registration');
    }

    /**
     * Show registration tracking results
     */
    public function trackRegistrationResult(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string'
        ]);

        // First check in active residents
        $resident = Resident::where('reference_number', $request->reference_number)
            ->where('registration_type', 'public')
            ->first();

        // If not found in residents, check rejected registrations
        if (!$resident) {
            $rejectedRegistration = RejectedRegistration::where('reference_number', $request->reference_number)->first();

            if ($rejectedRegistration) {
                return view('public.registration-status', [
                    'resident' => $rejectedRegistration,
                    'isRejected' => true
                ]);
            }
        }

        if (!$resident) {
            return back()
                ->withInput()
                ->withErrors(['reference_number' => 'No registration found with this reference number. Please check and try again.']);
        }

        return view('public.registration-status', [
            'resident' => $resident,
            'isRejected' => false
        ]);
    }

    /**
     * Display the specified resident.
     * Restricted to chairman and secretary only.
     */
    public function show(Resident $resident)
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        return view('residents.show', compact('resident'));
    }

    /**
     * Show the form for editing the specified resident.
     * Restricted to chairman and secretary only.
     */
    public function edit(Resident $resident)
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        return view('residents.edit', compact('resident'));
    }

    /**
     * Update the specified resident in storage.
     * Restricted to chairman and secretary only.
     */
    public function update(Request $request, Resident $resident)
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $validated = $this->validateResidentData($request);

        // Check for duplicate resident (excluding current resident and rejected ones)
        $existingResident = Resident::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->where('birth_date', $request->birth_date)
            ->where('id', '!=', $resident->id) // Exclude current resident
            ->whereNull('rejected_at') // Don't count rejected registrations
            ->first();

        if ($existingResident) {
            return back()->withInput()
                ->withErrors(['duplicate' => 'Another resident with the same name and birth date already exists.']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($resident->photo && Storage::disk('public')->exists($resident->photo)) {
                Storage::disk('public')->delete($resident->photo);
            }

            $photoPath = $request->file('photo')->store('residents/photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $resident->update($validated);

        return redirect()->route('residents.index')
            ->with('success', 'Resident information updated successfully!');
    }

    /**
     * Approve a pending public registration.
     * Restricted to chairman and secretary only.
     */
    public function approve(Resident $resident)
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $resident->update([
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        return redirect()->route('residents.pending')
            ->with('success', 'Resident registration approved successfully! The resident has been added to the official list.');
    }

    /**
     * UPDATED: Reject a pending public registration and move to rejected archive.
     * Restricted to chairman and secretary only.
     */
    public function reject(Request $request, Resident $resident)
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Create rejected registration record
            RejectedRegistration::create([
                'reference_number' => $resident->reference_number,
                'first_name' => $resident->first_name,
                'middle_name' => $resident->middle_name,
                'last_name' => $resident->last_name,
                'suffix' => $resident->suffix,
                'birth_date' => $resident->birth_date,
                'gender' => $resident->gender,
                'civil_status' => $resident->civil_status,
                'occupation' => $resident->occupation,
                'contact_number' => $resident->contact_number,
                'email' => $resident->email,
                'address' => $resident->address,
                'emergency_contact_name' => $resident->emergency_contact_name,
                'emergency_contact_number' => $resident->emergency_contact_number,
                'photo' => $resident->photo,
                'valid_id' => $resident->valid_id,
                'registration_type' => $resident->registration_type,
                'submitted_at' => $resident->submitted_at,
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Remove from residents table
            $resident->delete();

            DB::commit();

            return redirect()->route('residents.pending')
                ->with('success', 'Registration rejected successfully. The record has been moved to the rejected archive.');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('residents.pending')
                ->with('error', 'Failed to reject registration. Please try again.');
        }
    }

    /**
     * Remove the specified resident from storage.
     * Restricted to chairman and secretary only.
     */
    public function destroy(Resident $resident)
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        // Delete photo if exists
        if ($resident->photo && Storage::disk('public')->exists($resident->photo)) {
            Storage::disk('public')->delete($resident->photo);
        }

        // Delete valid ID if exists
        if ($resident->valid_id && Storage::disk('public')->exists($resident->valid_id)) {
            Storage::disk('public')->delete($resident->valid_id);
        }

        $resident->delete();

        return redirect()->route('residents.index')
            ->with('success', 'Resident deleted successfully!');
    }

    /**
     * NEW: Permanently delete a rejected registration
     */
    public function destroyRejected(RejectedRegistration $rejectedRegistration)
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        // Delete files if they exist
        if ($rejectedRegistration->photo && Storage::disk('public')->exists($rejectedRegistration->photo)) {
            Storage::disk('public')->delete($rejectedRegistration->photo);
        }

        if ($rejectedRegistration->valid_id && Storage::disk('public')->exists($rejectedRegistration->valid_id)) {
            Storage::disk('public')->delete($rejectedRegistration->valid_id);
        }

        $rejectedRegistration->delete();

        return redirect()->route('residents.rejected')
            ->with('success', 'Rejected registration permanently deleted.');
    }

    /**
     * NEW: Cleanup old rejected registrations (older than specified days)
     */
    public function cleanupRejected(Request $request)
    {
        // Check if user is chairman or secretary
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $days = $request->input('days', 30); // Default 30 days

        $oldRejected = RejectedRegistration::where('rejected_at', '<', now()->subDays($days))->get();

        $count = 0;
        foreach ($oldRejected as $rejected) {
            // Delete files
            if ($rejected->photo && Storage::disk('public')->exists($rejected->photo)) {
                Storage::disk('public')->delete($rejected->photo);
            }
            if ($rejected->valid_id && Storage::disk('public')->exists($rejected->valid_id)) {
                Storage::disk('public')->delete($rejected->valid_id);
            }

            $rejected->delete();
            $count++;
        }

        return redirect()->route('residents.rejected')
            ->with('success', "Cleaned up {$count} old rejected registrations (older than {$days} days).");
    }

    /**
     * Check and fix status column compatibility
     */
    public function checkStatusColumn()
    {
        try {
            // Check current status column structure
            $result = DB::select("SHOW COLUMNS FROM residents LIKE 'status'");

            if (!empty($result)) {
                echo "Current status column details:\n";
                echo "Type: " . $result[0]->Type . "\n";
                echo "Default: " . $result[0]->Default . "\n";
                echo "Null: " . $result[0]->Null . "\n";

                // Check existing status values
                $existing = DB::table('residents')->select('status')->distinct()->get();
                echo "\nExisting status values in database:\n";
                foreach($existing as $row) {
                    echo "- " . $row->status . "\n";
                }
            } else {
                echo "Status column doesn't exist\n";
            }
        } catch (\Exception $e) {
            echo "Error checking status column: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Validate resident data (admin version).
     */
    private function validateResidentData(Request $request)
    {
        return $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,widowed,divorced',
            'contact_number' => 'nullable|string|max:11',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'occupation' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:11',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    }

    /**
     * Validate public resident data (includes terms acceptance).
     */
    private function validatePublicResidentData(Request $request)
    {
        return $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,widowed,divorced',
            'contact_number' => 'required|string|max:11',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'occupation' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:11',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'valid_id' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'terms' => 'required|accepted',
        ]);
    }
}
