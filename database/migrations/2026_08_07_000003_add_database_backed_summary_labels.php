<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $labels = $this->labels();
        DB::table('user_event_plans')->orderBy('id')->chunkById(100, function ($plans) use ($labels): void {
            foreach ($plans as $plan) {
                $summary = json_decode((string) $plan->summary, true) ?: [];
                $summary['display_content'] = array_merge((array) ($summary['display_content'] ?? []), $labels);
                DB::table('user_event_plans')->where('id', $plan->id)->update(['summary' => json_encode($summary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
            }
        });
    }

    public function down(): void
    {
        $keys = array_keys($this->labels());
        DB::table('user_event_plans')->orderBy('id')->chunkById(100, function ($plans) use ($keys): void {
            foreach ($plans as $plan) {
                $summary = json_decode((string) $plan->summary, true) ?: [];
                foreach ($keys as $key) {
                    unset($summary['display_content'][$key]);
                }
                DB::table('user_event_plans')->where('id', $plan->id)->update(['summary' => json_encode($summary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
            }
        });
    }

    private function labels(): array
    {
        return [
            'brand_label' => 'Shaadi Sense AI',
            'estimated_total_label' => 'Estimated plan total',
            'guests_label' => 'Guests',
            'services_label' => 'Costed services',
            'download_label' => 'Download plan PDF',
            'new_plan_label' => 'Generate new plan',
            'dashboard_label' => 'User dashboard',
            'category_total_label' => 'Saved category total',
            'comparison_count_label' => 'saved alternatives',
            'comparison_costing_label' => 'Open saved costing',
            'comparison_view_label' => 'View plan →',
        ];
    }
};
