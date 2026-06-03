<?php

namespace App\Listeners;

use App\Events\DividendDistributed;
use App\Notifications\CustomerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDividendNotifications implements ShouldQueue
{
    public function handle(DividendDistributed $event): void
    {
        $distribution = $event->distribution;

        foreach ($distribution->payouts as $payout) {
            if ($payout->shareholder && $payout->shareholder->user) {
                $payout->shareholder->user->notify(new CustomerNotification(
                    'Dividend Distributed',
                    "You have received a dividend of {$payout->amount} for period {$distribution->period}."
                ));
            }
        }
    }
}
