<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiredMail;
use App\Mail\SubscriptionExpiryReminderMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionExpiryEmails extends Command
{
    protected $signature = 'subscriptions:send-expiry-emails';

    protected $description = 'Send one-time subscription expiry reminders and ended notices';

    public function handle(): int
    {
        $reminded = 0;
        $sent = 0;

        User::query()
            ->whereNotNull('subscription_id')
            ->whereBetween('subscription_ends_at', [now(), now()->addWeek()])
            ->chunkById(100, function ($users) use (&$reminded): void {
                foreach ($users as $user) {
                    $subscription = $user->subscriptionHistory()
                        ->with(['user', 'plan'])
                        ->where('subscription_id', $user->subscription_id)
                        ->where('status', 'active')
                        ->whereNull('reminder_sent_at')
                        ->where('ends_at', '>', now())
                        ->where('ends_at', '<=', now()->addWeek())
                        ->latest('ends_at')
                        ->first();

                    if (! $subscription) {
                        continue;
                    }

                    Mail::to($user->email)->send(new SubscriptionExpiryReminderMail($subscription));
                    $subscription->update(['reminder_sent_at' => now()]);
                    $reminded++;
                }
            });

        User::query()
            ->whereNotNull('subscription_id')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<=', now())
            ->chunkById(100, function ($users) use (&$sent): void {
                foreach ($users as $user) {
                    $subscription = $user->subscriptionHistory()
                        ->with(['user', 'plan'])
                        ->where('subscription_id', $user->subscription_id)
                        ->where('status', 'active')
                        ->latest('ends_at')
                        ->first();

                    if (! $subscription || $subscription->ends_at?->isFuture()) {
                        continue;
                    }

                    Mail::to($user->email)->send(new SubscriptionExpiredMail($subscription));
                    $subscription->update(['status' => 'expired']);
                    $sent++;
                }
            });

        $this->info("Sent {$reminded} expiry reminder(s) and {$sent} subscription ended email(s).");

        return self::SUCCESS;
    }
}
