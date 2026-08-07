<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('user_event_plans')->whereNull('parent_plan_id')->orderBy('id')->chunkById(100, function ($plans): void {
            foreach ($plans as $plan) {
                $summary = $this->decode($plan->summary);
                $answers = $this->decode($plan->answers);
                $summary['display_content'] = $this->displayContent($summary, $answers, (int) $plan->guest_count, (string) $plan->category);
                DB::table('user_event_plans')->where('id', $plan->id)->update(['summary' => json_encode($summary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);

                $existing = DB::table('user_event_plans')->where('parent_plan_id', $plan->id)->get()->keyBy('title');
                foreach ($this->definitions() as $index => [$title, $factor, $tier]) {
                    $suggestion = $existing->get($title);
                    if ($suggestion) {
                        $suggestionSummary = $this->decode($suggestion->summary);
                        $suggestionSummary['overview'] = $this->overview($factor);
                        $suggestionSummary['comparison'] = $this->comparison($factor, $tier, $answers, $suggestionSummary, $index);
                        $suggestionSummary['display_content'] = $this->displayContent($suggestionSummary, $answers, (int) $plan->guest_count, (string) $plan->category);
                        DB::table('user_event_plans')->where('id', $suggestion->id)->update(['summary' => json_encode($suggestionSummary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);

                        continue;
                    }

                    $suggestionSummary = $summary;
                    $suggestionSummary['title'] = $title;
                    $suggestionSummary['overview'] = $this->overview($factor);
                    $suggestionSummary['costing'] = collect($summary['costing'] ?? [])->map(function (array $item) use ($factor): array {
                        $item['amount'] = round(((float) ($item['amount'] ?? 0)) * $factor, 2);

                        return $item;
                    })->all();
                    $suggestionSummary['total_cost'] = round((float) collect($suggestionSummary['costing'])->sum('amount'), 2);
                    $suggestionSummary['comparison'] = $this->comparison($factor, $tier, $answers, $suggestionSummary, $index);
                    $suggestionSummary['display_content'] = $this->displayContent($suggestionSummary, $answers, (int) $plan->guest_count, (string) $plan->category);

                    DB::table('user_event_plans')->insert([
                        'user_id' => $plan->user_id,
                        'parent_plan_id' => $plan->id,
                        'title' => $title,
                        'category' => $plan->category,
                        'guest_count' => $plan->guest_count,
                        'answers' => $plan->answers,
                        'requirement_prompt' => $plan->requirement_prompt,
                        'vendor_snapshot' => $plan->vendor_snapshot,
                        'summary' => json_encode($suggestionSummary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'total_cost' => $suggestionSummary['total_cost'],
                        'model' => $plan->model,
                        'status' => 'completed',
                        'created_at' => $plan->created_at,
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        $addedTitles = collect($this->definitions())->pluck(0)->except([2, 3])->all();
        DB::table('user_event_plans')->whereNotNull('parent_plan_id')->whereIn('title', $addedTitles)->delete();

        DB::table('user_event_plans')->orderBy('id')->chunkById(100, function ($plans): void {
            foreach ($plans as $plan) {
                $summary = $this->decode($plan->summary);
                unset($summary['display_content'], $summary['comparison']);
                DB::table('user_event_plans')->where('id', $plan->id)->update(['summary' => json_encode($summary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
            }
        });
    }

    private function definitions(): array
    {
        return [
            ['Essential Wedding Plan', .75, 'Essential option'],
            ['Smart Value Wedding Plan', .85, 'Smart value option'],
            ['Nearby Value Plan', .90, 'Value option'],
            ['Nearby Premium Plan', 1.10, 'Premium option'],
            ['Signature Wedding Plan', 1.15, 'Signature option'],
            ['Luxury Wedding Plan', 1.25, 'Luxury option'],
        ];
    }

    private function displayContent(array $summary, array $answers, int $guestCount, string $category): array
    {
        $title = (string) ($summary['title'] ?? Str::headline($category).' plan');
        $serviceCount = count($summary['costing'] ?? []);
        $answerCount = count($answers);

        return [
            'brand_label' => 'Shaadi Sense AI',
            'sidebar_title' => $title,
            'sidebar_description' => $guestCount.' guests · '.$serviceCount.' costed services · '.$answerCount.' saved requirements',
            'estimated_total_label' => 'Estimated plan total',
            'guests_label' => 'Guests',
            'services_label' => 'Costed services',
            'download_label' => 'Download plan PDF',
            'new_plan_label' => 'Generate new plan',
            'dashboard_label' => 'User dashboard',
            'hero_badge' => $guestCount.'-guest '.Str::headline($category).' plan',
            'selection_eyebrow' => $answerCount.' saved requirements',
            'selection_title' => 'Selections used for '.$title,
            'costing_eyebrow' => $serviceCount.' costed service categories',
            'costing_title' => 'Detailed costing for '.$title,
            'costing_description' => 'Every amount below comes from the costing saved with this generated plan.',
            'category_total_label' => 'Saved category total',
            'comparison_eyebrow' => 'Saved plan alternatives',
            'comparison_title' => 'More budgets for the same requirements',
            'comparison_description' => 'Compare saved alternatives generated from this plan and its recorded selections.',
            'comparison_count_label' => 'saved alternatives',
            'comparison_costing_label' => 'Open saved costing',
            'comparison_view_label' => 'View plan →',
        ];
    }

    private function comparison(float $factor, string $tier, array $answers, array $summary, int $index): array
    {
        $change = (int) round(abs(1 - $factor) * 100);

        return [
            'tier' => $tier,
            'change_label' => $change.'% '.($factor < 1 ? 'lower investment' : 'higher investment'),
            'requirements_label' => count($answers).' saved selections retained',
            'costing_label' => count($summary['costing'] ?? []).' service costs recalculated',
            'image' => $index % 2 === 0 ? 'images/planner/value-wedding-plan.webp' : 'images/planner/premium-wedding-plan.webp',
        ];
    }

    private function overview(float $factor): string
    {
        $change = (int) round(abs(1 - $factor) * 100);

        return $factor < 1
            ? $change.'% below the original plan while retaining the saved wedding requirements.'
            : $change.'% above the original plan with additional budget across the saved service categories.';
    }

    private function decode(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        return json_decode((string) $value, true) ?: [];
    }
};
