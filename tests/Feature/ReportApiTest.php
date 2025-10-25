<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Report;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $regularUser;
    protected User $agencyAdmin;
    protected User $superAdmin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->regularUser = User::factory()->create(['role' => 'user']);
        $this->agencyAdmin = User::factory()->create(['role' => 'agency_admin', 'agency_id' => 1]);
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);

        // Create test category
        $this->category = Category::factory()->create(['agency_id' => 1]);

        Storage::fake('public');
    }

    /**
     * Test: User can create report with multiple images and video.
     */
    public function test_user_can_create_report_with_multiple_images_and_video()
    {
        $images = [
            UploadedFile::fake()->image('photo1.jpg', 800, 600)->size(2048),
            UploadedFile::fake()->image('photo2.jpg', 800, 600)->size(2048),
        ];

        $video = UploadedFile::fake()->create('video.mp4', 10000, 'video/mp4');

        $response = $this->actingAs($this->regularUser, 'sanctum')
            ->postJson('/api/reports', [
                'title' => 'Jalan Rusak di Dekat Sekolah',
                'description' => 'Ada lubang besar di jalan utama',
                'category_id' => $this->category->id,
                'location' => 'Jl. Merdeka No. 10',
                'images' => $images,
                'video' => $video,
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'report' => [
                    'id',
                    'user_id',
                    'category_id',
                    'title',
                    'description',
                    'location',
                    'status',
                    'media' => [
                        '*' => ['id', 'type', 'file_path', 'mime_type', 'size']
                    ]
                ]
            ]
        ]);

        $this->assertDatabaseHas('reports', [
            'title' => 'Jalan Rusak di Dekat Sekolah',
            'status' => 'submitted',
        ]);

        $this->assertCount(3, Report::first()->media); // 2 images + 1 video
    }

    /**
     * Test: User can create report with only images (no video).
     */
    public function test_user_can_create_report_with_images_only()
    {
        $images = [
            UploadedFile::fake()->image('photo1.jpg')->size(2048),
        ];

        $response = $this->actingAs($this->regularUser, 'sanctum')
            ->postJson('/api/reports', [
                'title' => 'Laporan Test',
                'description' => 'Deskripsi laporan',
                'category_id' => $this->category->id,
                'images' => $images,
            ]);

        $response->assertStatus(201);
        $this->assertCount(1, Report::first()->media);
    }

    /**
     * Test: Validation fails if required fields are missing.
     */
    public function test_report_creation_fails_with_missing_fields()
    {
        $response = $this->actingAs($this->regularUser, 'sanctum')
            ->postJson('/api/reports', [
                'title' => 'Test',
                // Missing description and category_id
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description', 'category_id']);
    }

    /**
     * Test: Agency admin can update status for their agency reports.
     */
    public function test_agency_admin_can_update_status_for_their_agency_report()
    {
        $report = Report::factory()
            ->for($this->regularUser)
            ->for($this->category)
            ->create(['status' => 'submitted']);

        $response = $this->actingAs($this->agencyAdmin, 'sanctum')
            ->postJson("/api/reports/{$report->id}/status", [
                'status' => 'under_review',
                'note' => 'Sedang diproses',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'under_review',
        ]);
    }

    /**
     * Test: Agency admin cannot update status for other agency reports.
     */
    public function test_agency_admin_cannot_update_report_of_other_agency()
    {
        $otherCategory = Category::factory()->create(['agency_id' => 2]);
        $report = Report::factory()
            ->for($this->regularUser)
            ->for($otherCategory)
            ->create(['status' => 'submitted']);

        $response = $this->actingAs($this->agencyAdmin, 'sanctum')
            ->postJson("/api/reports/{$report->id}/status", [
                'status' => 'approved',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test: Super admin can update any report status.
     */
    public function test_super_admin_can_update_any_report_status()
    {
        $report = Report::factory()
            ->for($this->regularUser)
            ->for($this->category)
            ->create(['status' => 'submitted']);

        $response = $this->actingAs($this->superAdmin, 'sanctum')
            ->postJson("/api/reports/{$report->id}/status", [
                'status' => 'approved',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'approved',
        ]);
    }

    /**
     * Test: User can only view their own reports.
     */
    public function test_user_can_only_view_their_own_reports()
    {
        $ownReport = Report::factory()
            ->for($this->regularUser)
            ->for($this->category)
            ->create();

        $otherUserReport = Report::factory()
            ->for(User::factory()->create(['role' => 'user']))
            ->for($this->category)
            ->create();

        $response = $this->actingAs($this->regularUser, 'sanctum')
            ->getJson('/api/reports');

        $response->assertStatus(200);
        $reportIds = collect($response->json('data.reports'))->pluck('id');

        $this->assertContains($ownReport->id, $reportIds);
        $this->assertNotContains($otherUserReport->id, $reportIds);
    }

    /**
     * Test: Search reports by title.
     */
    public function test_search_reports_by_title()
    {
        Report::factory(3)
            ->for($this->regularUser)
            ->for($this->category)
            ->create();

        Report::factory()
            ->for($this->regularUser)
            ->for($this->category)
            ->create(['title' => 'Jalan Rusak Parah']);

        $response = $this->actingAs($this->regularUser, 'sanctum')
            ->getJson('/api/reports?q=Rusak');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.reports'));
        $this->assertStringContainsString('Rusak', $response->json('data.reports.0.title'));
    }
}
