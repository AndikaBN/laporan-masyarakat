<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'name',
        'slug',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Auto-generate slug from name when name is set
     */
    protected static function booted()
    {
        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = str()->slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = str()->slug($category->name);
            }
        });
    }

    /**
     * Get the agency that owns this category.
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get all reports in this category.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
