<?php

namespace App\Events;

use App\Models\DividendDistribution;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DividendDistributed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public DividendDistribution $distribution;

    public function __construct(DividendDistribution $distribution)
    {
        $this->distribution = $distribution;
    }
}
