<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'location',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'latitude' => 'float',
        'longitude' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that created this report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category this report belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all media associated with this report.
     */
    public function media(): HasMany
    {
        return $this->hasMany(ReportMedia::class);
    }

    /**
     * Scope: Filter reports for a specific user based on their role.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            // Super admin sees all reports
            return $query;
        }

        if ($user->isAgencyAdmin()) {
            // Agency admin sees reports from their agency's categories
            return $query->whereHas('category', function ($q) use ($user) {
                $q->where('agency_id', $user->agency_id ?? null);
            });
        }

        // Regular user sees only their own reports
        return $query->where('user_id', $user->id);
    }
}
