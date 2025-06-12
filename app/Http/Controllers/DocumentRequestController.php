<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View; // Import the View facade
use Carbon\Carbon;

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of document requests.
     */
    public function index(Request $request)
    {
        $query = DocumentRequest::query()->with('resident');

        // Filter by Search (Tracking # or Resident Name)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                  ->orWhereHas('resident', function ($qr) use ($search) {
                      $qr->where('first_name', 'like', '%' . $search . '%')
                         ->orWhere('middle_name', 'like', '%' . $search . '%')
                         ->orWhere('last_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by Document Type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->input('document_type'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Order the results (optional, but good practice)
        $query->orderBy('requested_date', 'desc');

        $requests = $query->paginate(10); // Adjust pagination as needed

        // Get unique document types for the filter dropdown
        // This assumes 'document_type' is a column in your DocumentRequest model
        $availableDocumentTypes = DocumentRequest::distinct()->pluck('document_type')->sort()->toArray();

        return view('document-requests.index', compact('requests', 'availableDocumentTypes'));
    }

    public function certificateIndex(Request $request)
    {
        // Role-based access control
        if (!in_array(Auth::user()->role, ['chairman', 'secretary', 'staff'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        // Start building the query
        $query = DocumentRequest::with(['resident', 'processedBy']);

        $query->whereIn('status', ['ready']);


        // Filter by Search (Tracking # or Resident Name)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                  ->orWhereHas('resident', function ($qr) use ($search) {
                      $qr->where('first_name', 'like', '%' . $search . '%')
                         ->orWhere('middle_name', 'like', '%' . $search . '%')
                         ->orWhere('last_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by Document Type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->input('document_type'));
        }

        $query->orderBy('created_at', 'desc');

        $requests = $query->paginate(10);

        $availableDocumentTypes = DocumentRequest::distinct()->pluck('document_type')->sort()->toArray();

        return view('certificate-issuance.index', compact('requests', 'availableDocumentTypes'));
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
        // Validate name, suffix, and birth date to find resident
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255', // New: Suffix validation
            'resident_id' => 'required|integer|exists:residents,id', // This will be populated by JavaScript
            'document_type' => 'required|string',
            'purpose' => 'required|string|max:255',
        ]);

        // Find the resident based on provided name, suffix, and birth date
        $query = Resident::where('first_name', $request->first_name)
                        ->where('last_name', $request->last_name)
                        ->where('id', $request->resident_id)
                        ->whereNotNull('approved_at') // Ensure only approved residents can request documents
                        ->whereNull('rejected_at'); // Ensure not a rejected registration

        if ($request->filled('middle_name')) {
            $query->where('middle_name', $request->middle_name);
        } else {
            // If middle name is not provided, ensure the stored middle name is also null or empty
            $query->where(function ($q) {
                $q->whereNull('middle_name')
                ->orWhere('middle_name', '');
            });
        }

        if ($request->filled('suffix')) { // New: Add suffix to query
            $query->where('suffix', $request->suffix);
        } else {
            $query->where(function ($q) {
                $q->whereNull('suffix')
                  ->orWhere('suffix', '');
            });
        }


        $resident = $query->first();

        if (!$resident) {
            return back()->withInput()->withErrors([
                'resident_info' => 'No approved resident found with the provided full name, suffix, and ID. Please ensure your details are correct or register first.'
            ]);
        }

        // Now that we have resident_id, proceed with document request creation
        // The 'resident_id' hidden input from the form is now used.
        $validatedData = $request->validate([
            'resident_id' => 'required|exists:residents,id', // This field will now be populated by JavaScript
            'document_type' => 'required|string',
            'purpose' => 'required|string|max:255',
        ]);

        // Ensure the resident_id from the form matches the one found by the server-side lookup
        if ($validatedData['resident_id'] != $resident->id) {
            return back()->withInput()->withErrors([
                'resident_info' => 'Mismatched resident information. Please try again.'
            ]);
        }


        $validatedData['requested_date'] = now()->toDateString();
        $validatedData['target_release_date'] = now()->addDays(3)->toDateString();
        $validatedData['processed_by'] = Auth::id(); // This assumes staff are creating, adjust if public users process

        $documentRequest = DocumentRequest::create($validatedData);

        if (Auth::check()) { // Check if a user is authenticated
            if (Auth::user()->role === 'secretary') {
                return redirect()->route('document-requests.index')->with('success', 'Document request created successfully! Tracking number: ' . $documentRequest->tracking_number);
            }
        }
        return view('document-requests.success', [
            'trackingNumber' => $documentRequest->tracking_number,
        ]);
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

        $resident = $documentRequest->resident;
        // dd($documentRequest);
        if ($documentRequest->status === 'rejected') {
            return view('document-requests.status', [
                'resident' => $resident,
                'isRejected' => true,
                'remarks' => $documentRequest->remarks ?? 'No remarks provided.',
                'documentRequest' => $documentRequest,
            ]);
        }

        return view('document-requests.status', [
            'resident' => $resident,
            'isRejected' => false,
            'documentRequest' => $documentRequest,
        ]);
    }


    // Using route model binding (recommended)
    public function process(Request $request, DocumentRequest $documentRequest)
    {
        $documentRequest->status = 'processing';
        $documentRequest->processed_by = Auth::id();
        $documentRequest->save();

        return redirect()->back()->with('success', 'Document request status updated to processing.');
    }

    public function reject(Request $request, DocumentRequest $documentRequest)
    {
        // 1. Validate the rejection reason
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000', // Example validation rules
        ], [
            'rejection_reason.required' => 'The reason for rejection is required.',
            'rejection_reason.min' => 'The rejection reason must be at least :min characters.',
            'rejection_reason.max' => 'The rejection reason may not be greater than :max characters.',
        ]);

        // 2. Update the document request status
        $documentRequest->status = 'rejected';

        // 3. Assign the processed_by user (if authenticated)
        // Ensure that 'processed_by' is nullable in your database migration if it can be null.
        $documentRequest->processed_by = Auth::id();

        // 4. Save the rejection reason to the 'remarks' column
        $documentRequest->remarks = $request->input('rejection_reason');

        // 5. Save the changes to the database
        $documentRequest->save();

        // 6. Redirect back with a success message
        return redirect()->back()->with('success', 'Document request has been rejected successfully and reason saved.');
    }

    public function ready(Request $request, DocumentRequest $documentRequest)
    {
        $documentRequest->status = 'ready';
        $documentRequest->processed_by = Auth::id();
        $documentRequest->save();

        return redirect()->back()->with('success', 'Document request status updated to ready.');
    }

    public function release(DocumentRequest $documentRequest)
{
    // Check if the document status is 'ready' before proceeding
    if ($documentRequest->status === 'ready') {
        // Update the document status to 'released'
        $documentRequest->status = 'released';
        // Set the actual release date to now
        $documentRequest->actual_release_date = now();
        // Record the user who processed (released) the document
        $documentRequest->processed_by = Auth::id();
        // Save the changes to the database
        $documentRequest->save();

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', 'Document successfully marked as released.');
    }

    // If the document is not in a 'ready' status, redirect back with an error message
    return redirect()->back()->with('error', 'Document is not in a "ready" status for release.');
}

    /**
     * Download the generated PDF document.
     */
    public function print(DocumentRequest $documentRequest)
    {
        // Ensure the document is in a 'released' state before allowing print
        if ($documentRequest->status !== 'ready') {
            return redirect()->back()->with('error', 'This document is not yet ready and cannot be printed.');
        }

        DocumentRequest::where('id', $documentRequest->id)
            ->update(['actual_release_date' => Carbon::now(), 'processed_by' => Auth::id(), 'status' => 'released']);

        $pdf = null;
        $viewName = null;

        // Use a switch statement to select the correct view based on document type
        // These are your existing views in `resources/views/certificate-issuance/`
        switch ($documentRequest->document_type) {
            case 'barangay_clearance':
                $viewName = 'certificate-issuance.barangay-clearance';
                break;
            case 'certificate_of_residency':
                $viewName = 'certificate-issuance.certificate-residency';
                break;
            case 'certificate_of_indigency':
                $viewName = 'certificate-issuance.certificate-indigency';
                break;
            case 'business_permit_clearance':
                $viewName = 'certificate-issuance.business-permit-clearance';
                break;
            case 'first_time_job_seeker':
                $viewName = 'certificate-issuance.first-time-job-seeker';
                break;
            case 'good_moral_certificate':
                $viewName = 'certificate-issuance.good-moral-certificate';
                break;
            case 'travel_permit':
                $viewName = 'certificate-issuance.travel-permit';
                break;
            default:
                return redirect()->back()->with('error', 'Document type not recognized for PDF generation.');
        }

        // Check if the specific view exists before loading
        if (!View::exists($viewName)) {
            return redirect()->back()->with('error', 'The specific PDF template for this document type is missing or path is incorrect.');
        }

        // Pass the data to the view using the variable name it expects ('request')
        // This is crucial to avoid "Undefined variable $request" error
        $data = ['request' => $documentRequest];

        // You might also need to pass common variables like barangayName, cityName, etc.
        // if they are used in all your certificate templates.
        // Example:
        // $data['barangayName'] = 'Your Barangay Name';
        // $data['cityName'] = 'Your City Name';
        // $data['barangayCaptain'] = 'Hon. John Doe';
        // $data['secretary'] = 'Ms. Jane Smith';

        $pdf = Pdf::loadView($viewName, $data);

        // Instead of download(), use stream() or inline() to display in the browser
        $filename = "Document_{$documentRequest->document_type}_{$documentRequest->tracking_number}.pdf";
        return $pdf->stream($filename); // or $pdf->inline($filename)

        // If something went wrong and PDF wasn't loaded
        return redirect()->back()->with('error', 'Failed to generate PDF for display.');
    }


    public function fetchByTrackingNumber(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255'
        ]);

        $document = DocumentRequest::where('tracking_number', $request->tracking_number)->first();

        if (!$document) {
            return back()->withErrors(['tracking_number' => 'No document found with this tracking number.'])->withInput();
        }

        return back()->with([
            'show_modal' => true,
            'tracking_number' => $document->tracking_number,
            'doc_type' => $document->document_type ?? 'N/A',
            'purpose' => $document->purpose ?? 'N/A',
            'status' => $document->status ?? 'Pending',
            'requested_date' => $document->created_at->toDateString(),
            'payment_status' => $document->payment_status ?? 'Unpaid'
        ]);
    }

}
