<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\AffiliateLink;
use App\Models\ResellerTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessAffiliateCommission implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        if (!$order->customer_id) {
            return;
        }

        $affiliateLink = AffiliateLink::where('customer_id', $order->customer_id)
            ->where('is_active', true)
            ->first();

        if (!$affiliateLink) {
            return;
        }

        $commissionRate = $affiliateLink->commission_rate ?? 5;
        $commissionAmount = $order->total * ($commissionRate / 100);

        ResellerTransaction::create([
            'reseller_id' => $affiliateLink->reseller_id,
            'order_id' => $order->id,
            'type' => 'commission',
            'amount' => $commissionAmount,
            'status' => 'pending',
            'description' => "Affiliate commission for Order #{$order->order_number}",
        ]);
    }
}
