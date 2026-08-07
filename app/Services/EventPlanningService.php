<?php

namespace App\Services;

use App\Models\AiSetting;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class EventPlanningService
{
    public function __construct(private readonly OpenRouterService $openRouter) {}

    public function create(User $user, array $requirements): UserEventPlan
    {
        $answers = Arr::wrap($requirements['answers'] ?? []);
        $guestCount = max(1, min(5000, (int) ($requirements['guest_count'] ?? 150)));
        $category = 'wedding';
        $vendors = $this->vendorSnapshot();
        $prompt = $this->prompt($category, $guestCount, $answers, $vendors);
        $model = (string) AiSetting::getValue('openrouter_model', 'openrouter/auto');

        $plan = UserEventPlan::create([
            'user_id' => $user->id,
            'title' => 'Wedding Plan · '.now()->format('d M Y'),
            'category' => $category,
            'guest_count' => $guestCount,
            'answers' => $answers,
            'requirement_prompt' => $prompt,
            'vendor_snapshot' => $vendors,
            'model' => $model,
            'status' => 'generating',
        ]);

        try {
            $response = $this->openRouter->chat([
                ['role' => 'user', 'content' => $prompt],
            ], $model);
            $summary = $this->parseSummary((string) data_get($response, 'choices.0.message.content', ''), $answers, $guestCount, $vendors);
        } catch (\Throwable $exception) {
            report($exception);
            $summary = $this->fallbackSummary($answers, $guestCount, $vendors);
            $plan->error_message = Str::limit($exception->getMessage(), 1000);
        }

        $plan->fill([
            'title' => (string) ($summary['title'] ?? $plan->title),
            'summary' => $summary,
            'total_cost' => (float) $summary['total_cost'],
            'status' => 'completed',
        ])->save();

        $this->createSuggestions($plan);

        return $plan->fresh(['suggestions']);
    }

    private function vendorSnapshot(): array
    {
        return DynamicVendor::query()
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->latest('id')
            ->limit(60)
            ->get()
            ->map(fn (DynamicVendor $vendor): array => [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'category' => $vendor->category,
                'attributes' => collect((array) data_get($vendor->vendor_json, 'attributes', []))
                    ->map(fn ($value) => is_scalar($value) || is_array($value) ? $value : null)
                    ->filter(fn ($value) => $value !== null && $value !== '' && $value !== [])
                    ->take(15)->all(),
            ])->values()->all();
    }

    private function prompt(string $category, int $guestCount, array $answers, array $vendors): string
    {
        return 'Create a practical Indian wedding plan grounded only in the supplied active vendor data. '
            .'Do not invent vendor IDs or claim availability that is not present. Return JSON only, without markdown, using this exact shape: '
            .'{"title":string,"overview":string,"total_cost":number,"costing":[{"category":string,"amount":number,"percentage":number,"summary":string,"vendor_ids":number[],"attributes":[{"name":string,"value":string,"cost":number}]}],'
            .'"recommendations":[{"vendor_id":number,"name":string,"category":string,"reason":string,"estimated_cost":number}],"notes":string[]}. '
            .'All monetary numbers must be INR rupees. Keep each costing item small and understandable, include 3 to 6 attribute-level costs in every costing item, and make total_cost equal the sum of costing amounts. '
            .'Requirements: '.json_encode(['category' => $category, 'guest_count' => $guestCount, 'answers' => $answers], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'. '
            .'Active vendors: '.json_encode($vendors, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'.';
    }

    private function parseSummary(string $content, array $answers, int $guestCount, array $vendors): array
    {
        $content = trim(preg_replace('/^```(?:json)?|```$/mi', '', trim($content)) ?? '');
        $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($decoded) || ! is_array($decoded['costing'] ?? null) || $decoded['costing'] === []) {
            throw new \RuntimeException('OpenRouter returned an invalid planning summary.');
        }

        $allowedVendors = collect($vendors)->keyBy('id');
        $costing = collect($decoded['costing'])->map(function ($item): array {
            return [
                'category' => Str::limit((string) ($item['category'] ?? 'Wedding service'), 100),
                'amount' => max(0, round((float) ($item['amount'] ?? 0), 2)),
                'percentage' => max(0, min(100, round((float) ($item['percentage'] ?? 0), 1))),
                'summary' => Str::limit((string) ($item['summary'] ?? ''), 300),
                'vendor_ids' => collect($item['vendor_ids'] ?? [])->map(fn ($id) => (int) $id)->filter()->values()->all(),
                'attributes' => collect($item['attributes'] ?? [])->map(fn ($attribute): array => [
                    'name' => Str::limit((string) ($attribute['name'] ?? 'Service item'), 100),
                    'value' => Str::limit((string) ($attribute['value'] ?? ''), 200),
                    'cost' => max(0, round((float) ($attribute['cost'] ?? 0), 2)),
                ])->filter(fn (array $attribute): bool => $attribute['cost'] > 0)->take(8)->values()->all(),
            ];
        })->filter(fn (array $item): bool => $item['amount'] > 0)->values();

        if ($costing->isEmpty()) {
            return $this->fallbackSummary($answers, $guestCount, $vendors);
        }

        $total = round((float) $costing->sum('amount'), 2);
        $recommendations = collect($decoded['recommendations'] ?? [])->map(function ($item) use ($allowedVendors): ?array {
            $vendor = $allowedVendors->get((int) ($item['vendor_id'] ?? 0));
            if (! $vendor) {
                return null;
            }
            return [
                'vendor_id' => $vendor['id'],
                'name' => $vendor['name'],
                'category' => $vendor['category'],
                'reason' => Str::limit((string) ($item['reason'] ?? 'Matches your selected requirements.'), 300),
                'estimated_cost' => max(0, round((float) ($item['estimated_cost'] ?? 0), 2)),
            ];
        })->filter()->take(12)->values()->all();

        return [
            'title' => Str::limit((string) ($decoded['title'] ?? 'Your Custom Wedding Plan'), 150),
            'overview' => Str::limit((string) ($decoded['overview'] ?? 'A wedding plan tailored to your selected requirements.'), 1000),
            'total_cost' => $total,
            'costing' => $costing->all(),
            'recommendations' => $recommendations,
            'notes' => collect($decoded['notes'] ?? [])->map(fn ($note) => Str::limit((string) $note, 300))->take(8)->values()->all(),
        ];
    }

    private function fallbackSummary(array $answers, int $guestCount, array $vendors): array
    {
        $budgetLakh = max(3, min(500, (float) ($answers['wedding_budget'] ?? $answers['budget'] ?? 25)));
        $total = round($budgetLakh * 100000, 2);
        $allocations = [
            ['Venue & Stay', .35, 'Venue, seating, hospitality and essential facilities.'],
            ['Catering & Service', .30, 'Menu and service allocation for '.$guestCount.' guests.'],
            ['Decor & Styling', .15, 'Mandap, floral, lighting and styling requirements.'],
            ['Photography & Entertainment', .12, 'Photography, film and entertainment coverage.'],
            ['Planning Contingency', .08, 'Coordination, transport and contingency reserve.'],
        ];
        $costing = collect($allocations)->map(fn (array $row): array => [
            'category' => $row[0],
            'amount' => round($total * $row[1], 2),
            'percentage' => $row[1] * 100,
            'summary' => $row[2],
            'vendor_ids' => [],
            'attributes' => [],
        ])->all();
        $recommendations = collect($vendors)->unique('category')->take(8)->map(fn (array $vendor): array => [
            'vendor_id' => $vendor['id'],
            'name' => $vendor['name'],
            'category' => $vendor['category'],
            'reason' => 'Active vendor with attributes relevant to this wedding plan.',
            'estimated_cost' => 0,
        ])->values()->all();

        return [
            'title' => 'Your Custom Wedding Breakdown',
            'overview' => 'A balanced plan for '.$guestCount.' guests based on your selected budget and preferences.',
            'total_cost' => $total,
            'costing' => $costing,
            'recommendations' => $recommendations,
            'notes' => ['Confirm final quotations and availability directly with shortlisted vendors.'],
        ];
    }

    private function createSuggestions(UserEventPlan $plan): void
    {
        foreach ([['Nearby Value Plan', .90], ['Nearby Premium Plan', 1.10]] as [$title, $factor]) {
            $summary = $plan->summary;
            $summary['title'] = $title;
            $summary['overview'] = $factor < 1
                ? 'A nearby-cost alternative that preserves the core celebration priorities.'
                : 'A nearby premium alternative with extra flexibility in each service category.';
            $summary['costing'] = collect($summary['costing'])->map(function (array $item) use ($factor): array {
                $item['amount'] = round(((float) $item['amount']) * $factor, 2);
                return $item;
            })->all();
            $summary['total_cost'] = round(collect($summary['costing'])->sum('amount'), 2);

            UserEventPlan::create([
                'user_id' => $plan->user_id,
                'parent_plan_id' => $plan->id,
                'title' => $title,
                'category' => $plan->category,
                'guest_count' => $plan->guest_count,
                'answers' => $plan->answers,
                'requirement_prompt' => $plan->requirement_prompt,
                'vendor_snapshot' => $plan->vendor_snapshot,
                'summary' => $summary,
                'total_cost' => $summary['total_cost'],
                'model' => $plan->model,
                'status' => 'completed',
            ]);
        }
    }
}
