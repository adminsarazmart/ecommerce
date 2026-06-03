<?php

namespace App\Helpers;

final class Constants
{
    const ORDER_STATUSES = [
        'pending', 'confirmed', 'processing', 'shipped', 'delivered',
        'completed', 'cancelled', 'refunded', 'returned', 'on_hold',
    ];

    const PAYMENT_STATUSES = [
        'pending', 'processing', 'completed', 'failed', 'refunded', 'partially_refunded',
    ];

    const SHIPPING_STATUSES = [
        'pending', 'processing', 'shipped', 'in_transit', 'out_for_delivery', 'delivered', 'failed',
    ];

    const USER_ROLES = [
        'super_admin', 'admin', 'vendor', 'customer', 'reseller', 'employee', 'manager',
    ];

    const VENDOR_STATUSES = [
        'pending', 'active', 'inactive', 'suspended', 'banned',
    ];

    const PRODUCT_TYPES = [
        'simple', 'variable', 'digital', 'service', 'downloadable',
    ];

    const TRANSACTION_TYPES = [
        'sale', 'refund', 'commission', 'withdrawal', 'payout', 'transfer', 'adjustment',
    ];

    const PAYMENT_METHODS = [
        'stripe', 'paypal', 'razorpay', 'paystack', 'flutterwave',
        'sslcommerz', 'bkash', 'nagad', 'cod', 'bank_transfer',
    ];

    const MEMBERSHIP_LEVELS = [
        'bronze', 'silver', 'gold', 'platinum', 'diamond',
    ];

    const EMPLOYEE_STATUSES = [
        'active', 'inactive', 'suspended', 'terminated', 'resigned',
    ];

    const LEAVE_TYPES = [
        'sick', 'casual', 'annual', 'maternity', 'paternity', 'unpaid',
    ];

    const PAYROLL_STATUSES = [
        'pending', 'processing', 'paid', 'cancelled',
    ];

    const COMMISSION_TYPES = [
        'percentage', 'fixed',
    ];

    const SHAREHOLDER_COUNT = 6;

    const SHAREHOLDER_PERCENTAGE = 16.6667;

    const POS_TYPES = [
        'retail', 'wholesale', 'restaurant',
    ];

    const GATEWAYS = [
        'stripe', 'paypal', 'razorpay', 'paystack', 'flutterwave',
        'sslcommerz', 'bkash', 'nagad',
    ];

    const CURRENCIES = [
        'USD' => ['name' => 'US Dollar', 'symbol' => '$', 'code' => 'USD'],
        'EUR' => ['name' => 'Euro', 'symbol' => '€', 'code' => 'EUR'],
        'GBP' => ['name' => 'British Pound', 'symbol' => '£', 'code' => 'GBP'],
        'BDT' => ['name' => 'Bangladeshi Taka', 'symbol' => '৳', 'code' => 'BDT'],
        'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹', 'code' => 'INR'],
        'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦', 'code' => 'NGN'],
        'GHS' => ['name' => 'Ghanaian Cedi', 'symbol' => '₵', 'code' => 'GHS'],
    ];

    const LANGUAGES = [
        'en' => ['name' => 'English', 'native' => 'English'],
        'bn' => ['name' => 'Bengali', 'native' => 'বাংলা'],
        'hi' => ['name' => 'Hindi', 'native' => 'हिन्दी'],
        'es' => ['name' => 'Spanish', 'native' => 'Español'],
        'fr' => ['name' => 'French', 'native' => 'Français'],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية'],
    ];

    const REPORT_TYPES = [
        'sales', 'profit', 'inventory', 'vendor', 'customer', 'tax', 'shipping',
    ];

    const ANALYTICS_PERIODS = [
        'today', 'yesterday', 'this_week', 'last_week', 'this_month',
        'last_month', 'this_quarter', 'last_quarter', 'this_year', 'last_year',
        'custom',
    ];
}
