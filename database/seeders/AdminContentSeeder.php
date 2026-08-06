<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Feedback;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }

        $users = User::query()->orderBy('id')->get();
        if ($users->isNotEmpty()) {
            foreach ($this->feedbackItems() as $index => $item) {
                Feedback::updateOrCreate(
                    ['user_id' => $users[$index % $users->count()]->id, 'subject' => $item['subject']],
                    $item,
                );
            }
        }

        $adminId = Admin::query()->value('id');
        foreach ($this->notifications() as $item) {
            $notification = AdminNotification::updateOrCreate(
                ['title' => $item['title']],
                array_merge($item, ['created_by' => $adminId]),
            );
            $notification->users()->syncWithoutDetaching($users->pluck('id')->all());
        }
    }

    private function pages(): array
    {
        return [
            ['title' => 'About Us', 'slug' => 'about-us', 'description' => '<h2>Celebrations made simple</h2><p>EventPlanner connects customers with trusted professionals for every part of their event.</p>', 'status' => true],
            ['title' => 'How It Works', 'slug' => 'how-it-works', 'description' => '<h2>Plan in three steps</h2><ol><li>Share your event requirements.</li><li>Compare matching vendors.</li><li>Connect and confirm your favourites.</li></ol>', 'status' => true],
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'description' => '<h2>Your privacy</h2><p>We use account and event details only to operate the platform, provide vendor matches, and improve our services.</p>', 'status' => true],
            ['title' => 'Terms and Conditions', 'slug' => 'terms-and-conditions', 'description' => '<h2>Platform terms</h2><p>Vendor availability, pricing, and final service terms must be confirmed directly before booking.</p>', 'status' => true],
            ['title' => 'Contact Us', 'slug' => 'contact-us', 'description' => '<h2>We are here to help</h2><p>Email support@eventplanner.com for account, vendor, or event-planning assistance.</p>', 'status' => true],
        ];
    }

    private function feedbackItems(): array
    {
        return [
            ['subject' => 'Easy vendor comparison', 'message' => 'The category filters made it easy to compare venue and catering options.', 'rating' => 5, 'status' => 'reviewed', 'admin_reply' => 'Thank you for sharing your experience.'],
            ['subject' => 'More decoration photos', 'message' => 'Please add more photographs for each decoration style.', 'rating' => 4, 'status' => 'resolved', 'admin_reply' => 'Decoration images are now supported on vendor attributes.'],
            ['subject' => 'Helpful planning questions', 'message' => 'The short requirement form helped me identify the right services quickly.', 'rating' => 5, 'status' => 'pending', 'admin_reply' => null],
        ];
    }

    private function notifications(): array
    {
        return [
            ['title' => 'Welcome to EventPlanner', 'message' => 'Explore verified vendors and start planning your celebration today.', 'notification_type' => 'information', 'status' => 'sent', 'sent_at' => now(), 'schedule_at' => null],
            ['title' => 'New catering menus available', 'message' => 'Catering profiles now include menu-card items with images.', 'notification_type' => 'success', 'status' => 'sent', 'sent_at' => now(), 'schedule_at' => null],
            ['title' => 'Complete your event requirements', 'message' => 'Answer the short preference form to receive more relevant vendor matches.', 'notification_type' => 'reminder', 'status' => 'draft', 'sent_at' => null, 'schedule_at' => null],
        ];
    }
}
