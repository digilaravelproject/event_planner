<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAiConfigurationRequest;
use App\Models\AdminModuleOption;
use App\Models\AiSetting;
use App\Services\OpenRouterService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;

class AiSettingController extends Controller
{
    public function __construct(private readonly OpenRouterService $openRouter) {}

    public function index()
    {
        $storedKey = (string) AiSetting::getValue('openrouter_api_key', '');
        $modelLoadError = null;
        if ($storedKey !== '') {
            try {
                foreach ($this->openRouter->models() as $order => $model) {
                    AdminModuleOption::updateOrCreate(
                        ['group' => 'openrouter_model', 'value' => $model['id']],
                        ['label' => $model['name'].' ('.$model['id'].')', 'display_order' => $order + 1, 'status' => true],
                    );
                }
            } catch (\Throwable $exception) {
                report($exception);
                $modelLoadError = 'The live OpenRouter model list could not be refreshed. Showing the last synchronized list.';
            }
        }

        return view('admin.ai.manage', [
            'hasApiKey' => $storedKey !== '',
            'model' => AiSetting::getValue('openrouter_model', 'openrouter/auto'),
            'promptTemplate' => AiSetting::getValue('openrouter_prompt_template', ''),
            'status' => (bool) AiSetting::getValue('status', true),
            'updatedBy' => AiSetting::getValue('updated_by_name', 'Not updated yet'),
            'lastUpdated' => AiSetting::query()->latest('updated_at')->value('updated_at'),
            'models' => AdminModuleOption::forGroup('openrouter_model')->get(),
            'modelLoadError' => $modelLoadError,
        ]);
    }

    public function store(UpdateAiConfigurationRequest $request)
    {
        $validated = $request->validated();
        if (! empty($validated['openrouter_api_key'])) {
            AiSetting::setValue('openrouter_api_key', Crypt::encryptString($validated['openrouter_api_key']));
            Cache::forget('openrouter.models');
        }
        AiSetting::setValue('openrouter_model', $validated['openrouter_model']);
        AiSetting::setValue('openrouter_prompt_template', $validated['openrouter_prompt_template'] ?? '');
        AiSetting::setValue('status', $validated['status']);
        AiSetting::setValue('updated_by', auth('admin')->id());
        AiSetting::setValue('updated_by_name', auth('admin')->user()->name);

        return to_route('admin.ai.manage')->with('success', 'AI configuration updated successfully.');
    }
}
