<?php

namespace App\Models;

class CustomJob extends Model
{
    public const PRIORITY_DEFAULT  = 'default';
    public const PRIORITY_HIGH     = 'high';
    public const PRIORITY_LOW      = 'low';
    public const PRIORITY_ALL      = [
        self::PRIORITY_DEFAULT,
        self::PRIORITY_HIGH,
        self::PRIORITY_LOW,
    ];
    public const STATUS_COMPLETED  = 'completed';
    public const STATUS_FAILED     = 'failed';
    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
        ];
    }
}
