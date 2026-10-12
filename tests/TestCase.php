<?php

namespace Tests;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function subscribe(User $user): User
    {
        $plan = Subscription::create(['name' => 'Test Paid Plan '.$user->id, 'price' => 100, 'interval' => 'three_months', 'features' => []]);
        $user->update(['subscription_id' => $plan->id, 'subscription_ends_at' => now()->addMonth()]);

        return $user;
    }
}
