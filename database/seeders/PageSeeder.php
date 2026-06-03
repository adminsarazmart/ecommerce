<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h2>Welcome to BanglaMart</h2><p>BanglaMart is Bangladesh\'s premier online marketplace, connecting millions of buyers with trusted vendors across the country.</p><p>Founded in 2024, we have grown to become one of the most trusted ecommerce platforms in Bangladesh, offering everything from electronics and fashion to home goods and groceries.</p><h3>Our Mission</h3><p>To provide a seamless, secure, and enjoyable shopping experience for every Bangladeshi citizen.</p><h3>Our Values</h3><ul><li><strong>Trust:</strong> We ensure all our vendors are verified and products are authentic.</li><li><strong>Quality:</strong> We maintain high standards for all products listed on our platform.</li><li><strong>Innovation:</strong> We continuously improve our platform to serve you better.</li><li><strong>Community:</strong> We support local businesses and entrepreneurs.</li></ul>',
                'meta_title' => 'About BanglaMart - Bangladesh\'s Trusted Online Marketplace',
                'meta_description' => 'Learn about BanglaMart, Bangladesh\'s premier online marketplace connecting buyers with trusted vendors.',
                'is_active' => true,
                'is_default' => true,
                'layout' => 'default',
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h2>Get in Touch</h2><p>We\'d love to hear from you! Contact us through any of the following methods:</p><p><strong>Address:</strong> Dhaka, Bangladesh</p><p><strong>Phone:</strong> +8801700000000</p><p><strong>Email:</strong> info@banglamart.com</p><p><strong>Business Hours:</strong> Sunday - Thursday, 9:00 AM - 6:00 PM (BST)</p><p>For vendor inquiries: vendor@banglamart.com</p><p>For support: support@banglamart.com</p>',
                'meta_title' => 'Contact BanglaMart',
                'meta_description' => 'Get in touch with BanglaMart. Find our address, phone number, email and business hours.',
                'is_active' => true,
                'is_default' => true,
                'layout' => 'default',
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms',
                'content' => '<h2>Terms and Conditions</h2><p>Last updated: January 2024</p><h3>1. Acceptance of Terms</h3><p>By accessing and using BanglaMart, you accept and agree to be bound by these Terms and Conditions.</p><h3>2. Use of Service</h3><p>You must be at least 18 years old to use this service. You agree to provide accurate and complete information when creating an account.</p><h3>3. Vendor Terms</h3><p>Vendors agree to list authentic products, maintain accurate inventory, and fulfill orders in a timely manner.</p><h3>4. Payment Terms</h3><p>All payments are processed securely. We support multiple payment methods including bKash, Nagad, and Cash on Delivery.</p><h3>5. Shipping & Delivery</h3><p>Delivery times may vary depending on location. Standard delivery within Dhaka is 1-3 business days.</p><h3>6. Returns & Refunds</h3><p>Customers may return products within 7 days of delivery for a full refund if the product is defective or not as described.</p>',
                'meta_title' => 'Terms & Conditions - BanglaMart',
                'meta_description' => 'Read the terms and conditions for using BanglaMart marketplace.',
                'is_active' => true,
                'is_default' => true,
                'layout' => 'default',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'content' => '<h2>Privacy Policy</h2><p>Last updated: January 2024</p><h3>Information We Collect</h3><p>We collect information you provide when creating an account, placing an order, or contacting us.</p><h3>How We Use Your Information</h3><p>We use your information to process orders, improve our services, and communicate with you about your account.</p><h3>Data Protection</h3><p>We implement security measures to protect your personal information. Your data is encrypted and stored securely.</p><h3>Third-Party Services</h3><p>We may share your information with payment processors and shipping partners solely for order fulfillment.</p><h3>Contact</h3><p>For privacy-related inquiries, contact us at privacy@banglamart.com</p>',
                'meta_title' => 'Privacy Policy - BanglaMart',
                'meta_description' => 'Read the privacy policy of BanglaMart marketplace.',
                'is_active' => true,
                'is_default' => true,
                'layout' => 'default',
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
