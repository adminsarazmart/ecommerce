<?php

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

if (!function_exists('formatPrice')) {
    function formatPrice($amount, $currency = null): string
    {
        $currency = $currency ?? config('app.currency', 'USD');
        $symbol = config("currencies.{$currency}.symbol", '$');
        return $symbol . number_format((float) $amount, 2);
    }
}

if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(Str::random(10)) . date('ymd');
    }
}

if (!function_exists('generateSku')) {
    function generateSku($product = null, $variant = null): string
    {
        $prefix = $product ? strtoupper(substr($product, 0, 3)) : 'PRD';
        $unique = strtoupper(Str::random(6));
        $variantPart = $variant ? '-' . strtoupper(substr($variant, 0, 3)) : '';
        return $prefix . '-' . $unique . $variantPart;
    }
}

if (!function_exists('calculateTax')) {
    function calculateTax($amount, $rate, $type = 'exclusive'): float
    {
        if ($type === 'exclusive') {
            return round($amount * ($rate / 100), 2);
        }
        return round($amount - ($amount / (1 + $rate / 100)), 2);
    }
}

if (!function_exists('calculateCommission')) {
    function calculateCommission($amount, $rate, $type = 'percentage'): float
    {
        if ($type === 'percentage') {
            return round($amount * ($rate / 100), 2);
        }
        return round(min($rate, $amount), 2);
    }
}

if (!function_exists('getPercentage')) {
    function getPercentage($total, $part, $decimals = 2): float
    {
        if ($total == 0) {
            return 0;
        }
        return round(($part / $total) * 100, $decimals);
    }
}

if (!function_exists('getGrowthRate')) {
    function getGrowthRate($current, $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($number, $decimals = 2): string
    {
        return number_format((float) $number, $decimals);
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($date): string
    {
        return Carbon::parse($date)->diffForHumans();
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($string, $separator = '-'): string
    {
        return Str::slug($string, $separator);
    }
}

if (!function_exists('isValidBase64')) {
    function isValidBase64($string): bool
    {
        if (!is_string($string)) {
            return false;
        }
        return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string);
    }
}

if (!function_exists('maskEmail')) {
    function maskEmail($email): string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 2));
        return $maskedName . '@' . $domain;
    }
}

if (!function_exists('maskPhone')) {
    function maskPhone($phone): string
    {
        $length = strlen($phone);
        if ($length <= 4) {
            return $phone;
        }
        return substr($phone, 0, 2) . str_repeat('*', $length - 4) . substr($phone, -2);
    }
}

if (!function_exists('generateReferralCode')) {
    function generateReferralCode(): string
    {
        return strtoupper(Str::random(8));
    }
}

if (!function_exists('randomColor')) {
    function randomColor(): string
    {
        $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9'];
        return $colors[array_rand($colors)];
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('sanitizeHtml')) {
    function sanitizeHtml($html): string
    {
        return strip_tags($html, '<p><br><b><strong><i><em><u><ul><ol><li><a><img><table><tr><td><th><h1><h2><h3><h4><h5><h6><blockquote><pre><code><hr><span><div>');
    }
}

if (!function_exists('truncateText')) {
    function truncateText($text, $limit = 100): string
    {
        if (strlen($text) <= $limit) {
            return $text;
        }
        return substr($text, 0, $limit) . '...';
    }
}

if (!function_exists('getInitials')) {
    function getInitials($name): string
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }
        return substr($initials, 0, 2);
    }
}
