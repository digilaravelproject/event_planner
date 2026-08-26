<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiredMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionExpiryEmails extends Command
{
    protected $signature = 'subscriptions:send-expiry-emails';

    protected $description = 'Send one-time emails for subscriptions that have ended';

    public function handle(): int
    {
        $sent = 0;

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

        $this->info("Sent {$sent} subscription expiry email(s).");

        return self::SUCCESS;
    }
}
