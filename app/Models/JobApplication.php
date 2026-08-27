<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends BaseModel
{
    protected $table = 'job_application';

    protected $fillable = [
        'career_id', 'full_name', 'email', 'phone', 'cover_letter',
        'resume_media_id', 'portfolio_url', 'linkedin_url', 'expected_salary',
        'status', 'reviewer_id', 'reviewer_notes', 'ip_address',
    ];

    protected $casts = [
        'expected_salary' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class, 'career_id');
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'resume_media_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_SCREENING = 'screening';
    public const STATUS_INTERVIEW = 'interview';
    public const STATUS_OFFERED = 'offered';
    public const STATUS_HIRED = 'hired';
    public const STATUS_REJECTED = 'rejected';
}
