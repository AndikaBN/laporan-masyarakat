<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportMedia;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Get paginated list of reports with filters.
     * Auto-filtered based on user role.
     */
    public function index(Request $request)
    {
        $query = Report::with('user', 'category', 'media')
            ->forUser($request->user());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Search by title or description
        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $reports = $query->latest()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil diambil',
            'data' => [
                'reports' => $reports->items(),
                'pagination' => [
                    'total' => $reports->total(),
                    'per_page' => $reports->perPage(),
                    'current_page' => $reports->currentPage(),
                    'last_page' => $reports->lastPage(),
                ],
            ],
        ], 200);
    }

    /**
     * Create a new report with multiple images and optional video.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:4096', // max 4MB per image
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-matroska|max:40960', // max 40MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Create report with 'submitted' status
            $report = Report::create([
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => 'submitted',
            ]);

            // Store images
            if ($request->has('images') && is_array($request->images)) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store("reports/{$report->id}/photos", 'public');

                    ReportMedia::create([
                        'report_id' => $report->id,
                        'type' => 'image',
                        'file_path' => $path,
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize(),
                    ]);
                }
            }

            // Store video if provided
            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $path = $video->store("reports/{$report->id}/video", 'public');

                ReportMedia::create([
                    'report_id' => $report->id,
                    'type' => 'video',
                    'file_path' => $path,
                    'mime_type' => $video->getMimeType(),
                    'size' => $video->getSize(),
                ]);
            }

            // Load relationships
            $report->load('user', 'category', 'media');

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dibuat',
                'data' => [
                    'report' => $report,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat laporan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single report with all media and status history.
     */
    public function show(Request $request, Report $report)
    {
        $this->authorize('view', $report);

        try {
            $report->load('user', 'category', 'media');

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil diambil',
                'data' => [
                    'report' => $report,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update report status (admin/agency only).
     */
    public function updateStatus(Request $request, Report $report)
    {
        $this->authorize('updateStatus', $report);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:submitted,under_review,approved,rejected,completed',
            'note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $oldStatus = $report->status;

            // Update report status
            $report->update([
                'status' => $request->status,
            ]);

            // Send notification to report creator
            // TODO: Get user device tokens and send FCM notification
            // FcmService::notify($userToken, "Status Laporan Updated", "Laporan Anda telah diupdate menjadi {$request->status}");

            $report->load('user', 'category', 'media');

            return response()->json([
                'status' => 'success',
                'message' => 'Status laporan berhasil diperbarui',
                'data' => [
                    'report' => $report,
                    'old_status' => $oldStatus,
                    'new_status' => $request->status,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
