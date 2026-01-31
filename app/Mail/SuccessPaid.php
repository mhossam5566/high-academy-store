<?php


namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessPaid extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Order */
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this
            ->subject('تم الدفع بنجاح')
            ->view('emails.success_paid')
            ->with([
                'order' => $this->order,
            ]);
    }
}
