<?php

namespace Database\Seeders;

use App\Models\AdminModuleOption;
use App\Models\AiSetting;
use App\Services\OpenRouterService;
use Illuminate\Database\Seeder;

class OpenRouterModelSeeder extends Seeder
{
    public function run(): void
    {
        if (! AiSetting::getValue('openrouter_api_key')) {
            $this->command?->warn('OpenRouter key is not configured; retained fallback models.');

            return;
        }

        $models = app(OpenRouterService::class)->models();
        foreach ($models as $order => $model) {
            AdminModuleOption::updateOrCreate(
                ['group' => 'openrouter_model', 'value' => $model['id']],
                ['label' => $model['name'].' ('.$model['id'].')', 'display_order' => $order + 1, 'status' => true],
            );
        }

        $this->command?->info(count($models).' OpenRouter text models synchronized.');
    }
}
