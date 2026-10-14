<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $table = 'order_logs';

    protected $fillable = [
        'order_id',
        'process_id',
        'level',
        'step',
        'confirmation_type',
        'message',
        'context',
        'ip',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    // Allowed values for confirmation_type
    const CONFIRMATION_MANUAL = 'manual';
    const CONFIRMATION_AUTOMATIC = 'automatic';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Human readable label for the confirmation_type column (manual/automatic).
     */
    public function getConfirmationTypeLabelAttribute(): ?string
    {
        return match ($this->confirmation_type) {
            self::CONFIRMATION_MANUAL => 'تأكيد يدوي',
            self::CONFIRMATION_AUTOMATIC => 'تأكيد تلقائي',
            default => null,
        };
    }

    /**
     * Helper: log an info entry
     */
    public static function info(string $message, $orderId = null, string $processId = null, string $step = null, array $context = [], string $ip = null, ?string $confirmationType = null): self
    {
        return self::create([
            'order_id'          => $orderId,
            'process_id'        => $processId,
            'level'             => 'info',
            'step'              => $step,
            'confirmation_type' => $confirmationType,
            'message'           => $message,
            'context'           => $context ?: null,
            'ip'                => $ip,
        ]);
    }

    public static function success(string $message, $orderId = null, string $processId = null, string $step = null, array $context = [], string $ip = null, ?string $confirmationType = null): self
    {
        return self::create([
            'order_id'          => $orderId,
            'process_id'        => $processId,
            'level'             => 'success',
            'step'              => $step,
            'confirmation_type' => $confirmationType,
            'message'           => $message,
            'context'           => $context ?: null,
            'ip'                => $ip,
        ]);
    }

    public static function warning(string $message, $orderId = null, string $processId = null, string $step = null, array $context = [], string $ip = null, ?string $confirmationType = null): self
    {
        return self::create([
            'order_id'          => $orderId,
            'process_id'        => $processId,
            'level'             => 'warning',
            'step'              => $step,
            'confirmation_type' => $confirmationType,
            'message'           => $message,
            'context'           => $context ?: null,
            'ip'                => $ip,
        ]);
    }

    public static function error(string $message, $orderId = null, string $processId = null, string $step = null, array $context = [], string $ip = null, ?string $confirmationType = null): self
    {
        return self::create([
            'order_id'          => $orderId,
            'process_id'        => $processId,
            'level'             => 'error',
            'step'              => $step,
            'confirmation_type' => $confirmationType,
            'message'           => $message,
            'context'           => $context ?: null,
            'ip'                => $ip,
        ]);
    }
}
