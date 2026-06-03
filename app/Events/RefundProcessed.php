<?php

namespace App\Events;

use App\Models\Refund;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefundProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Refund $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }
}
