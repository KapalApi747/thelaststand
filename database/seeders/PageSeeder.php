<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::firstOrCreate(['slug' => 'privacy-policy'], [
            'title' => 'Privacy Policy',
            'content_html' => <<<HTML
                <h2 class="text-xl font-bold mb-4">Our Privacy Policy</h2>
                <p class="mb-4">
                    At {{store_name}}, your privacy is important to us. This policy explains what personal data we collect and how we use it to provide and improve our services.
                </p>
                <h3 class="text-lg font-semibold mb-2">1. Information We Collect</h3>
                <p class="mb-4">
                    We may collect your name, email, contact details, and transaction data when you use our services or communicate with us.
                </p>
                <h3 class="text-lg font-semibold mb-2">2. How We Use Your Information</h3>
                <p class="mb-4">
                    Your information helps us process orders, provide support, personalize your experience, and send relevant updates.
                </p>
                <h3 class="text-lg font-semibold mb-2">3. Your Rights</h3>
                <p class="mb-4">
                    You may request access to, correction of, or deletion of your personal information at any time by contacting our support team.
                </p>
            HTML,
        ]);

        Page::firstOrCreate(['slug' => 'terms-of-service'], [
            'title' => 'Terms of Service',
            'content_html' => <<<HTML
                <h2 class="text-xl font-bold mb-4">Our Terms of Service</h2>
                <p class="mb-4">
                    These terms govern your use of {{store_name}}. By using our platform, you agree to the terms outlined here.
                </p>
                <h3 class="text-lg font-semibold mb-2">1. User Responsibilities</h3>
                <p class="mb-4">
                    Users are responsible for the security of their account and ensuring the accuracy of the information they provide.
                </p>
                <h3 class="text-lg font-semibold mb-2">2. Prohibited Use</h3>
                <p class="mb-4">
                    Our services may not be used for illegal activities, abuse of the platform, or unauthorized access to other accounts.
                </p>
                <h3 class="text-lg font-semibold mb-2">3. Modifications</h3>
                <p class="mb-4">
                    We reserve the right to update these terms. Continued use of the platform after changes means you accept the revised terms.
                </p>
            HTML,
        ]);

        Page::firstOrCreate(['slug' => 'cookies-policy'], [
            'title' => 'Cookies Policy',
            'content_html' => <<<HTML
                <h2 class="text-xl font-bold mb-4">Our Cookies Policy</h2>
                <p class="mb-4">
                    This policy describes how {{store_name}} uses cookies and similar technologies on our website.
                </p>
                <h3 class="text-lg font-semibold mb-2">1. What Are Cookies?</h3>
                <p class="mb-4">
                    Cookies are small data files stored on your device that help improve your browsing experience and allow certain functions to work.
                </p>
                <h3 class="text-lg font-semibold mb-2">2. How We Use Cookies</h3>
                <p class="mb-4">
                    We use cookies to remember preferences, enable secure login, collect analytics, and optimize performance.
                </p>
                <h3 class="text-lg font-semibold mb-2">3. Managing Cookies</h3>
                <p class="mb-4">
                    Most browsers allow you to manage cookies through settings. Disabling cookies may impact some functionality on our site.
                </p>
            HTML,
        ]);

        Page::firstOrCreate(['slug' => 'about-us'], [
            'title' => 'About Us',
            'content_html' => <<<HTML
                <h2 class="text-xl font-bold mb-4">About Us</h2>
                <p class="mb-4">
                    {{store_name}} is a platform built to support passionate sellers, small businesses, and creative entrepreneurs.
                </p>
                <p class="mb-4">
                    Our mission is to empower merchants to grow independently, offering a customizable and trustworthy storefront.
                </p>
                <p class="mb-4">
                    Whether you're just starting or scaling your brand, {{ store_name }} is here to help you thrive in a fair and transparent e-commerce ecosystem.
                </p>
            HTML,
        ]);
    }
}
