<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportMedia extends Model
{
    use HasFactory;

    protected $table = 'report_media';

    protected $fillable = [
        'report_id',
        'type',
        'file_path',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'type' => 'string',
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the report that owns this media.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
