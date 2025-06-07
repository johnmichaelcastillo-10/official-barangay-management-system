<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of document requests.
     */
    public function index()
    {
        // Check if user has permission
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        $requests = DocumentRequest::with(['resident', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('document-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new document request.
     */
    public function create()
    {

        $residents = Resident::orderBy('last_name')->get();
        $documentTypes = [
            'barangay_clearance' => 'Barangay Clearance',
            'certificate_of_residency' => 'Certificate of Residency',
            'certificate_of_indigency' => 'Certificate of Indigency',
            'business_permit_clearance' => 'Business Permit Clearance',
            'first_time_job_seeker' => 'First Time Job Seeker Certificate',
            'good_moral_certificate' => 'Good Moral Certificate',
            'travel_permit' => 'Travel Permit'
        ];

        return view('document-requests.create', compact('residents', 'documentTypes'));
    }

    /**
     * Store a newly created document request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'document_type' => 'required|string',
            'purpose' => 'required|string|max:255',
            'fee_amount' => 'required|numeric|min:0'
        ]);

        $validated['requested_date'] = now()->toDateString();
        $validated['target_release_date'] = now()->addDays(3)->toDateString();
        $validated['processed_by'] = Auth::id();

        DocumentRequest::create($validated);

        return redirect()->route('document-requests.index')
            ->with('success', 'Document request created successfully!');
    }

    /**
     * Display the specified document request.
     */
    public function show(DocumentRequest $documentRequest)
    {
        // Check if user has permission
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        $documentRequest->load(['resident', 'processedBy']);
        return view('document-requests.show', compact('documentRequest'));
    }

    /**
     * Show the form for editing the document request.
     */
    public function edit(DocumentRequest $documentRequest)
    {
        // Check if user has permission
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        $residents = Resident::orderBy('last_name')->get();
        $documentTypes = [
            'barangay_clearance' => 'Barangay Clearance',
            'certificate_of_residency' => 'Certificate of Residency',
            'certificate_of_indigency' => 'Certificate of Indigency',
            'business_permit_clearance' => 'Business Permit Clearance',
            'first_time_job_seeker' => 'First Time Job Seeker Certificate',
            'good_moral_certificate' => 'Good Moral Certificate',
            'travel_permit' => 'Travel Permit'
        ];

        return view('document-requests.edit', compact('documentRequest', 'residents', 'documentTypes'));
    }

    /**
     * Update the specified document request.
     */
    public function update(Request $request, DocumentRequest $documentRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,ready,released,rejected',
            'remarks' => 'nullable|string',
            'payment_status' => 'required|in:unpaid,paid',
            'fee_amount' => 'required|numeric|min:0',
            'target_release_date' => 'nullable|date',
            'actual_release_date' => 'nullable|date'
        ]);

        $validated['processed_by'] = Auth::id();

        // Auto-set actual release date when status is changed to released
        if ($validated['status'] === 'released' && !$documentRequest->actual_release_date) {
            $validated['actual_release_date'] = now()->toDateString();
        }

        $documentRequest->update($validated);

        return redirect()->route('document-requests.index')
            ->with('success', 'Document request updated successfully!');
    }

    /**
     * Remove the specified document request.
     */
    public function destroy(DocumentRequest $documentRequest)
    {
        // Check if user has permission
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $documentRequest->delete();

        return redirect()->route('document-requests.index')
            ->with('success', 'Document request deleted successfully!');
    }

    /**
     * Show tracking form (public access)
     */
    public function track()
    {
        return view('document-requests.track');
    }

    /**
     * Show tracking result (public access)
     */
    public function trackResult(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string'
        ]);

        $documentRequest = DocumentRequest::with('resident')
            ->where('tracking_number', $request->tracking_number)
            ->first();

        if (!$documentRequest) {
            return back()->withErrors(['tracking_number' => 'Tracking number not found.']);
        }

        return view('document-requests.track-result', compact('documentRequest'));
    }
}
