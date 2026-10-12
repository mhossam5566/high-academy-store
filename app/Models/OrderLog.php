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
        'message',
        'context',
        'ip',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Helper: log an info entry
     */
    public static function info(string $message, $orderId = null, string $processId = null, string $step = null, array $context = [], string $ip = null): self
    {
        return self::create([
            'order_id'   => $orderId,
            'process_id' => $processId,
            'level'      => 'info',
            'step'       => $step,
            'message'    => $message,
            'context'    => $context ?: null,
            'ip'         => $ip,
        ]);
    }

    public static function success(string $message, $orderId = null, string $processId = null, string $step = null, array $context = [], string $ip = null): self
    {
        return self::create([
            'order_id'   => $orderId,
            'process_id' => $processId,
            'level'      => 'success',
            'step'       => $step,
            'message'    => $message,
            'context'    => $context ?: null,
            'ip'         => $ip,
        ]);
    }

    public static function warning(string $message, $orderId = null, string $processId = null, string $step = null, array $context = [], string $ip = null): self
    {
        return self::create([
            'order_id'   => $orderId,
            'process_id' => $processId,
            'level'      => 'warning',
            'step'       => $step,
            'message'    => $message,
            'context'    => $context ?: null,
            'ip'         => $ip,
        ]);
    }

    public static function error(string $message, $orderId = null, string $processId = null, string $step = null, array $context = [], string $ip = null): self
    {
        return self::create([
            'order_id'   => $orderId,
            'process_id' => $processId,
            'level'      => 'error',
            'step'       => $step,
            'message'    => $message,
            'context'    => $context ?: null,
            'ip'         => $ip,
        ]);
    }
}
