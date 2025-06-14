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
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['chairman', 'secretary', 'staff'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this page.');
        }

        $query = DocumentRequest::with(['resident', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->whereIn('status', ['received', 'rejected']);

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
            $query->where('status', $request->input('status'));
        }

        $records = $query->paginate(10);

        $availableDocumentTypes = DocumentRequest::select('document_type')
                                                ->distinct()
                                                ->orderBy('document_type')
                                                ->pluck('document_type');

        return view('record-management.index', compact('records', 'availableDocumentTypes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentRequest $documentRequest)
    {
        // Ensure only 'released' or 'rejected' documents can be viewed as records
        if (!in_array($documentRequest->status, ['received', 'rejected'])) {
            return redirect()->route('record-management.index')
                ->with('error', 'This document is not an archived record and cannot be viewed here.');
        }

        // Eager load relationships if you need resident and processedBy details in the show view
        $documentRequest->load(['resident', 'processedBy']);

        return view('record-management.show', compact('documentRequest'));
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
    // public function show(DocumentRequest $record)
    // {
    //     // Check if user has permission
    //     if (!in_array(Auth::user()->role, ['chairman', 'secretary'])) {
    //         return redirect()->route('dashboard')
    //             ->with('error', 'You do not have permission to view this page.');
    //     }

    //     $record->load(['resident', 'processedBy']);
    //     return view('record-management.show', compact('record'));
    // }

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
