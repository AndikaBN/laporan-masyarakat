<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display all reports for agency admin.
     */
    public function agencyIndex(Request $request)
    {
        $user = $request->user();

        if (!$user->isAgencyAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Get reports for this agency
        $reports = Report::with('user', 'category', 'media')
            ->whereHas('category', function ($q) use ($user) {
                $q->where('agency_id', $user->agency_id);
            })
            ->latest()
            ->paginate(10);

        return view('agency.reports.index', compact('reports'));
    }

    /**
     * Display a specific report with map.
     */
    public function show(Request $request, Report $report)
    {
        $user = $request->user();

        // Authorize: Only super admin or agency admin from same agency can view
        if ($user->isSuperAdmin()) {
            // Super admin can view any report
        } elseif ($user->isAgencyAdmin()) {
            // Agency admin can only view reports from their agency
            if ($report->category->agency_id !== $user->agency_id) {
                abort(403, 'Unauthorized access');
            }
        } else {
            abort(403, 'Unauthorized access');
        }

        $report->load('user', 'category', 'media');

        return view('agency.reports.show', compact('report'));
    }

    /**
     * Update report status.
     */
    public function updateStatus(Request $request, Report $report)
    {
        $user = $request->user();

        // Authorize
        if ($user->isSuperAdmin()) {
            // Super admin can update any report
        } elseif ($user->isAgencyAdmin()) {
            // Agency admin can only update reports from their agency
            if ($report->category->agency_id !== $user->agency_id) {
                abort(403, 'Unauthorized access');
            }
        } else {
            abort(403, 'Unauthorized access');
        }

        // Validate status
        $request->validate([
            'status' => 'required|in:submitted,under_review,approved,rejected,completed',
            'note' => 'nullable|string',
        ]);

        // Update report
        $report->update([
            'status' => $request->status,
        ]);

        return redirect()->route('agency.reports.show', $report->id)
            ->with('success', 'Status laporan berhasil diperbarui');
    }
}
