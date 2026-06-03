<?php

namespace App\Traits;

trait HasPriceFormat
{
    public function formatPrice(): string
    {
        return number_format((float) $this->price, 2);
    }

    public function formatSalePrice(): ?string
    {
        if (!$this->sale_price) {
            return null;
        }
        return number_format((float) $this->sale_price, 2);
    }

    public function getPriceWithCurrencyAttribute(): string
    {
        $currency = config('app.currency', 'USD');
        return $currency . ' ' . number_format((float) $this->price, 2);
    }
}
