<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

    public function certificateIndex(){
        if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        $requests = DocumentRequest::with(['resident', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->whereIn('status', ['ready', 'released']) // Use whereIn for multiple status values
            ->paginate(10);

        return view('certificate-issuance.index', compact('requests'));
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
            'birth_date' => 'required|date',
            'document_type' => 'required|string',
            'purpose' => 'required|string|max:255',
        ]);

        // Find the resident based on provided name, suffix, and birth date
        $query = Resident::where('first_name', $request->first_name)
                        ->where('last_name', $request->last_name)
                        ->where('birth_date', $request->birth_date)
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
                'resident_info' => 'No approved resident found with the provided full name, suffix, and birth date. Please ensure your details are correct or register first.'
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
        $documentRequest->status = 'rejected';
        $documentRequest->processed_by = Auth::id();
        $documentRequest->save();

        return redirect()->back()->with('success', 'Document request status updated to rejected.');
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
    public function download(DocumentRequest $documentRequest)
    {
        $pdf = null; // Initialize $pdf to null

        // Use a switch statement to select the correct view based on document type
        switch ($documentRequest->document_type) {
            case 'barangay_clearance':
                $pdf = Pdf::loadView('certificate-issuance.barangay-clearance', ['request' => $documentRequest]);
                break;
            case 'certificate_of_residency':
                $pdf = Pdf::loadView('certificate-issuance.certificate-residency', ['request' => $documentRequest]);
                break;
            case 'certificate_of_indigency':
                $pdf = Pdf::loadView('certificate-issuance.certificate-indigency', ['request' => $documentRequest]);
                break;
            case 'business_permit_clearance':
                $pdf = Pdf::loadView('certificate-issuance.business-permit-clearance', ['request' => $documentRequest]);
                break;
            case 'first_time_job_seeker':
                $pdf = Pdf::loadView('certificate-issuance.first-time-job-seeker', ['request' => $documentRequest]);
                break;
            case 'good_moral_certificate':
                $pdf = Pdf::loadView('certificate-issuance.good-moral-certificate', ['request' => $documentRequest]);
                break;
            case 'travel_permit':
                $pdf = Pdf::loadView('certificate-issuance.travel-permit', ['request' => $documentRequest]);
                break;
            // You might want a default case or handle unrecognized types
            default:
                // Handle unsupported document types, e.g., redirect back with an error
                return redirect()->back()->with('error', 'Document type not recognized for download.');
        }

        // If a PDF was successfully loaded, then download it
        if ($pdf) {
            $filename = "Document_{$documentRequest->document_type}_{$documentRequest->tracking_number}.pdf";
            return $pdf->download($filename);
        }

        // Fallback in case no PDF could be generated (e.g., unrecognized type)
        return redirect()->back()->with('error', 'Failed to generate PDF for download.');
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
