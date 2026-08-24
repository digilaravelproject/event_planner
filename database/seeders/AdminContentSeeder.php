<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Page;
use App\Models\LandingContent;
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
        $adminId = Admin::query()->value('id');
        foreach ($this->notifications() as $item) {
            $notification = AdminNotification::updateOrCreate(
                ['title' => $item['title']],
                array_merge($item, ['created_by' => $adminId]),
            );
            $notification->users()->syncWithoutDetaching($users->pluck('id')->all());
        }

        foreach ($this->landingContents() as $item) {
            LandingContent::updateOrCreate(
                ['type' => $item['type'], 'title' => $item['title']],
                $item,
            );
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

    private function notifications(): array
    {
        return [
            ['title' => 'Welcome to EventPlanner', 'message' => 'Explore verified vendors and start planning your celebration today.', 'notification_type' => 'information', 'status' => 'sent', 'sent_at' => now(), 'schedule_at' => null],
            ['title' => 'New catering menus available', 'message' => 'Catering profiles now include menu-card items with images.', 'notification_type' => 'success', 'status' => 'sent', 'sent_at' => now(), 'schedule_at' => null],
            ['title' => 'Complete your event requirements', 'message' => 'Answer the short preference form to receive more relevant vendor matches.', 'notification_type' => 'reminder', 'status' => 'draft', 'sent_at' => null, 'schedule_at' => null],
        ];
    }

    private function landingContents(): array
    {
        return [
            ['type'=>'how-it-works','title'=>'Tell us about your shaadi','subtitle'=>null,'body'=>'Budget, guest count, area, dates, food preference, indoor or outdoor. We ask only what we need.','meta'=>['eyebrow'=>'Two minutes','footer'=>'Guided Input'],'display_order'=>1,'status'=>true],
            ['type'=>'how-it-works','title'=>'AI drafts your plan','subtitle'=>null,'body'=>'A full budget split with venue, catering, decor, photography, makeup and entertainment, sized to your spend.','meta'=>['eyebrow'=>'Instant','footer'=>'Smart Allocation'],'display_order'=>2,'status'=>true],
            ['type'=>'how-it-works','title'=>'Vendors handpicked for you','subtitle'=>null,'body'=>'Real Mumbai vendors matched to your budget, area and style. We connect you seamlessly over WhatsApp.','meta'=>['eyebrow'=>'Within 24 hrs','footer'=>'WhatsApp Connect'],'display_order'=>3,'status'=>true],

            ['type'=>'comparisons','title'=>'100+ Phone Calls & Blind Follow-ups','body'=>'Calling dozens of venue managers and caterers without knowing upfront pricing or availability.','meta'=>['side'=>'manual'],'display_order'=>1,'status'=>true],
            ['type'=>'comparisons','title'=>'Hidden Costs & Unclear Quotes','body'=>'Surprise venue fees, electricity surcharges, and last-minute price inflations right before the event.','meta'=>['side'=>'manual'],'display_order'=>2,'status'=>true],
            ['type'=>'comparisons','title'=>'Messy Excel Sheets & Budget Stress','body'=>'Trying to manually divide funds between decor, food, photography, and makeup without clear benchmarks.','meta'=>['side'=>'manual'],'display_order'=>3,'status'=>true],
            ['type'=>'comparisons','title'=>'Weeks of Waiting & Unverified Vendors','body'=>'Risking unverified vendors with zero service guarantee or verified client reviews.','meta'=>['side'=>'manual'],'display_order'=>4,'status'=>true],
            ['type'=>'comparisons','title'=>'2-Minute AI Matchmaker','body'=>'Tell us your budget, area and guest count. Our AI immediately matches available top-tier vendors.','meta'=>['side'=>'ai'],'display_order'=>5,'status'=>true],
            ['type'=>'comparisons','title'=>'100% Transparent & Verified Pricing','body'=>'Direct pricing parameters from real Mumbai vendors with zero hidden commissions or extra fees.','meta'=>['side'=>'ai'],'display_order'=>6,'status'=>true],
            ['type'=>'comparisons','title'=>'Automated Smart Budget Breakdown','body'=>'Get an itemized spend split for Venue, Catering, Decor, Photography and Entertainment tuned to your budget.','meta'=>['side'=>'ai'],'display_order'=>7,'status'=>true],
            ['type'=>'comparisons','title'=>'Direct WhatsApp One-Click Connect','body'=>'Skip the cold calls! Directly connect with shortlisted verified vendors over WhatsApp instantly.','meta'=>['side'=>'ai'],'display_order'=>8,'status'=>true],

            ['type'=>'testimonials','title'=>'Priyanka & Rahul','subtitle'=>'Udaipur • Palace Wedding','body'=>'Shaadi Sense turned our palace wedding into a royal fairytale. The AI planning dashboard made vendor budget tracking effortless!','image'=>'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&q=80&w=150','meta'=>['rating'=>5,'footer'=>'₹ 2.4 Lakh Saved','date'=>'Nov 2025'],'display_order'=>1,'status'=>true],
            ['type'=>'testimonials','title'=>'Aanya & Siddharth','subtitle'=>'Goa • Beach Wedding','body'=>'From caterers to sunset mandap decorators, everything matched in 2 mins. We loved the zero hidden cost transparency!','image'=>'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=150','meta'=>['rating'=>5,'footer'=>'₹ 1.8 Lakh Saved','date'=>'Dec 2025'],'display_order'=>2,'status'=>true],
            ['type'=>'testimonials','title'=>'Vikas Sharma','subtitle'=>'Mumbai • Corporate Gala','body'=>'Extremely professional execution for corporate awards. The budget distribution tool saved us weeks of back-and-forth email exchanges.','image'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150','meta'=>['rating'=>5,'footer'=>'100% On-Time','date'=>'Jan 2026'],'display_order'=>3,'status'=>true],
            ['type'=>'testimonials','title'=>'The Kapoor Family','subtitle'=>'Mumbai • 50th Anniversary','body'=>'Planned our grandparents 50th anniversary seamlessly. Transparent vendor estimates left our family completely stress-free!','image'=>'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=150','meta'=>['rating'=>5,'footer'=>'Flawless Event','date'=>'Feb 2026'],'display_order'=>4,'status'=>true],
            ['type'=>'testimonials','title'=>'Rohan & Meera','subtitle'=>'Jaipur • Heritage Sangeet','body'=>'Finding a heritage venue and top choreographer within budget felt impossible until we used Shaadi Sense’s AI matchmaker!','image'=>'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&q=80&w=150','meta'=>['rating'=>5,'footer'=>'₹ 1.5 Lakh Saved','date'=>'Mar 2026'],'display_order'=>5,'status'=>true],
            ['type'=>'testimonials','title'=>'Ananya Deshmukh','subtitle'=>'Pune • Destination Wedding','body'=>'The AI vendor recommendations matched our aesthetic completely. Direct WhatsApp booking saved so much time!','image'=>'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=150','meta'=>['rating'=>5,'footer'=>'Verified Booking','date'=>'Apr 2026'],'display_order'=>6,'status'=>true],
            ['type'=>'testimonials','title'=>'Karan & Simran','subtitle'=>'Delhi • Farmhouse Wedding','body'=>'Instant budget allocation helped us balance funds between decor and catering without overshooting our limit!','image'=>'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&q=80&w=150','meta'=>['rating'=>5,'footer'=>'₹ 2.1 Lakh Saved','date'=>'May 2026'],'display_order'=>7,'status'=>true],
        ];
    }
}
