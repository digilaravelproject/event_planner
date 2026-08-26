<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\SubscriptionActivatedMail;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UserSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $plans = Subscription::orderBy('price')->get();
        $history = $user->subscriptionHistory()->with('plan')->latest()->get();

        return view('user.subscription', compact('plans', 'history', 'user'));
    }

    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate(['plan_id' => ['required', 'exists:subscriptions,id']]);
        $plan = Subscription::findOrFail($validated['plan_id']);
        $amount = (float) $plan->price;
        if ($plan->isFree()) {
            $this->activateFree($request, $plan);

            return response()->json(['success' => true, 'free' => true, 'redirect' => route('user.dashboard')]);
        }

        $keyId = (string) config('services.razorpay.key_id');
        $secret = (string) config('services.razorpay.key_secret');
        if ($keyId === '' || $secret === '') {
            throw ValidationException::withMessages(['payment' => 'Razorpay is not configured. Please contact the administrator.']);
        }
        $response = Http::withBasicAuth($keyId, $secret)->acceptJson()
            ->post(rtrim((string) config('services.razorpay.url'), '/').'/orders', [
                'amount' => (int) round($amount * 100), 'currency' => 'INR',
                'receipt' => 'sub_'.$request->user()->id.'_'.now()->format('YmdHis').'_'.bin2hex(random_bytes(3)),
                'notes' => ['user_id' => (string) $request->user()->id, 'plan_id' => (string) $plan->id, 'plan_interval' => $plan->interval],
            ])->throw()->json();

        UserSubscription::create([
            'user_id' => $request->user()->id, 'subscription_id' => $plan->id,
            'billing_cycle' => $plan->interval, 'amount' => $amount, 'status' => 'created',
            'razorpay_order_id' => $response['id'], 'gateway_payload' => $response,
        ]);

        return response()->json(['success' => true, 'key' => $keyId, 'order_id' => $response['id'], 'amount' => (int) round($amount * 100), 'currency' => 'INR', 'plan_name' => $plan->name]);
    }

    public function verifyPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'razorpay_payment_id' => ['required', 'string', 'max:255'], 'razorpay_order_id' => ['required', 'string', 'max:255'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);
        $record = $request->user()->subscriptionHistory()->where('razorpay_order_id', $validated['razorpay_order_id'])->where('status', 'created')->firstOrFail();
        $expected = hash_hmac('sha256', $validated['razorpay_order_id'].'|'.$validated['razorpay_payment_id'], (string) config('services.razorpay.key_secret'));
        if (! hash_equals($expected, $validated['razorpay_signature'])) {
            throw ValidationException::withMessages(['payment' => 'Razorpay signature verification failed.']);
        }
        DB::transaction(function () use ($request, $record, $validated): void {
            $endsAt = $record->plan->expirationDate();
            $record->update(['status' => 'active', 'razorpay_payment_id' => $validated['razorpay_payment_id'], 'starts_at' => now(), 'ends_at' => $endsAt, 'paid_at' => now()]);
            $request->user()->update(['subscription_id' => $record->subscription_id, 'subscription_ends_at' => $endsAt, 'razorpay_payment_id' => $validated['razorpay_payment_id'], 'razorpay_order_id' => $validated['razorpay_order_id']]);
        });
        Mail::to($request->user()->email)->send(new SubscriptionActivatedMail($record->fresh(['user', 'plan'])));

        return response()->json(['success' => true, 'redirect' => route('user.dashboard'), 'message' => 'Subscription activated successfully.']);
    }

    private function activateFree(Request $request, Subscription $plan): void
    {
        $record = DB::transaction(function () use ($request, $plan): UserSubscription {
            $endsAt = now()->addDays(30);
            $record = UserSubscription::create(['user_id' => $request->user()->id, 'subscription_id' => $plan->id, 'billing_cycle' => 'free', 'amount' => 0, 'status' => 'active', 'starts_at' => now(), 'ends_at' => $endsAt, 'paid_at' => now()]);
            $request->user()->update(['subscription_id' => $plan->id, 'subscription_ends_at' => $endsAt, 'razorpay_payment_id' => null, 'razorpay_order_id' => null]);

            return $record;
        });
        Mail::to($request->user()->email)->send(new SubscriptionActivatedMail($record->load(['user', 'plan'])));
    }
}
