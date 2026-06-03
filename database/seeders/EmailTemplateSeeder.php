<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Order Confirmation',
                'slug' => 'order_confirmation',
                'subject' => 'Order Confirmed - #{order_number}',
                'body' => '<h2>Thank you for your order!</h2><p>Dear {customer_name},</p><p>Your order <strong>#{order_number}</strong> has been confirmed.</p><p><strong>Order Total:</strong> {order_total}</p><p><strong>Shipping Address:</strong><br>{shipping_address}</p><p>You can track your order status from your account dashboard.</p><p>Thank you for shopping with us!</p>',
                'variables' => 'customer_name,order_number,order_total,shipping_address',
                'is_active' => true,
            ],
            [
                'name' => 'Order Shipped',
                'slug' => 'order_shipped',
                'subject' => 'Your Order #{order_number} Has Been Shipped!',
                'body' => '<h2>Your Order is on the Way!</h2><p>Dear {customer_name},</p><p>Your order <strong>#{order_number}</strong> has been shipped.</p><p><strong>Tracking Number:</strong> {tracking_number}</p><p>Estimated delivery: {delivery_date}</p><p>Track your package and stay updated!</p>',
                'variables' => 'customer_name,order_number,tracking_number,delivery_date',
                'is_active' => true,
            ],
            [
                'name' => 'Order Delivered',
                'slug' => 'order_delivered',
                'subject' => 'Order #{order_number} Delivered Successfully',
                'body' => '<h2>Your Order Has Been Delivered!</h2><p>Dear {customer_name},</p><p>Your order <strong>#{order_number}</strong> has been delivered successfully.</p><p>We hope you love your purchase! Please take a moment to rate your experience.</p><p>Thank you for choosing us!</p>',
                'variables' => 'customer_name,order_number',
                'is_active' => true,
            ],
            [
                'name' => 'Vendor Registration',
                'slug' => 'vendor_registration',
                'subject' => 'Welcome to BanglaMart - Vendor Account Created',
                'body' => '<h2>Welcome to BanglaMart!</h2><p>Dear {vendor_name},</p><p>Your vendor account has been created successfully.</p><p>Your store <strong>{store_name}</strong> is now being reviewed. We will notify you once it is approved.</p><p>Start adding your products and reach millions of customers!</p>',
                'variables' => 'vendor_name,store_name',
                'is_active' => true,
            ],
            [
                'name' => 'Vendor Verification',
                'slug' => 'vendor_verified',
                'subject' => 'Your Store {store_name} Has Been Verified!',
                'body' => '<h2>Congratulations!</h2><p>Dear {vendor_name},</p><p>Your store <strong>{store_name}</strong> has been verified successfully.</p><p>You can now start selling products on BanglaMart. Add your products and reach customers across Bangladesh!</p>',
                'variables' => 'vendor_name,store_name',
                'is_active' => true,
            ],
            [
                'name' => 'Password Reset',
                'slug' => 'password_reset',
                'subject' => 'Reset Your Password',
                'body' => '<h2>Password Reset Request</h2><p>Dear {user_name},</p><p>You have requested to reset your password. Click the button below to proceed:</p><p><a href="{reset_link}" style="background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px;">Reset Password</a></p><p>This link will expire in 60 minutes.</p><p>If you did not request this, please ignore this email.</p>',
                'variables' => 'user_name,reset_link',
                'is_active' => true,
            ],
            [
                'name' => 'Welcome Email',
                'slug' => 'welcome',
                'subject' => 'Welcome to BanglaMart!',
                'body' => '<h2>Welcome to BanglaMart!</h2><p>Dear {user_name},</p><p>Thank you for creating an account with BanglaMart, Bangladesh\'s premier online marketplace.</p><p>Start exploring thousands of products from trusted vendors across the country!</p><p>Happy Shopping!</p>',
                'variables' => 'user_name',
                'is_active' => true,
            ],
            [
                'name' => 'Refund Processed',
                'slug' => 'refund_processed',
                'subject' => 'Refund Processed for Order #{order_number}',
                'body' => '<h2>Refund Processed</h2><p>Dear {customer_name},</p><p>Your refund for order <strong>#{order_number}</strong> has been processed.</p><p><strong>Refund Amount:</strong> {refund_amount}</p><p>The amount will be credited to your account within 5-7 business days.</p>',
                'variables' => 'customer_name,order_number,refund_amount',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
    }
}
