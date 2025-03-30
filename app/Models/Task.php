<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    // use HasFactory;

    public const NEW_STATUS         = 'New';
    public const IN_PROGRESS_STATUS = 'In Progress';
    public const FAILED_STATUS      = 'Failed';
    public const FINISHED_STATUS    = 'Finished';

    public const STATUSES = [
        self::NEW_STATUS,
        self::IN_PROGRESS_STATUS,
        self::FAILED_STATUS,
        self::FINISHED_STATUS,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'start_date_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date_time' => 'datetime',
        'password'        => 'hashed',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
