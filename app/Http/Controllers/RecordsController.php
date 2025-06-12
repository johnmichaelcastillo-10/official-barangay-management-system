<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\Auth;

class RecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Inject Request object to access query parameters
    {
        // Permission check
        if (!in_array(Auth::user()->role, ['chairman', 'secretary', 'staff'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        $query = DocumentRequest::with(['resident', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->whereIn('status', ['released', 'rejected']); // Only show relevant statuses for records

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                  ->orWhereHas('resident', function ($qr) use ($search) {
                      $qr->where('first_name', 'like', '%' . $search . '%')
                         ->orWhere('last_name', 'like', '%' . $search . '%')
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%'])
                         ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
                  });
            });
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->input('document_type'));
        }

        if ($request->filled('status') && in_array($request->input('status'), ['released', 'rejected'])) {
            // Only apply status filter if it's one of the valid record statuses
            $query->where('status', $request->input('status'));
        } elseif ($request->filled('status') && !in_array($request->input('status'), ['released', 'rejected'])) {
            // If an invalid status for records is selected, redirect or ignore
            // For now, we'll ignore it as the initial whereIn filters it already
            // but you could add a redirect with an error message if you prefer.
        }

        $records = $query->paginate(10);

        // Get unique document types for the filter dropdown
        $availableDocumentTypes = DocumentRequest::select('document_type')
                                                ->distinct()
                                                ->orderBy('document_type')
                                                ->pluck('document_type');

        return view('record-management.index', compact('records', 'availableDocumentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentRequest $documentRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentRequest $documentRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentRequest $documentRequest)
    {
        //
    }
}
