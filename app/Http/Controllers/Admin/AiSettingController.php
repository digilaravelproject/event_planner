<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAiConfigurationRequest;
use App\Models\AdminModuleOption;
use App\Models\AiSetting;
use Illuminate\Support\Facades\Crypt;

class AiSettingController extends Controller
{
    public function index()
    {
        $storedKey = AiSetting::getValue('openai_api_key', '');
        try { $apiKey = $storedKey ? Crypt::decryptString($storedKey) : ''; } catch (\Throwable) { $apiKey = $storedKey; }

        return view('admin.ai.manage', [
            'apiKey' => $apiKey,
            'model' => AiSetting::getValue('openai_model', 'gpt-4o'),
            'promptTemplate' => AiSetting::getValue('ai_prompt_template', ''),
            'status' => (bool) AiSetting::getValue('status', true),
            'updatedBy' => AiSetting::getValue('updated_by_name', 'Not updated yet'),
            'lastUpdated' => AiSetting::query()->latest('updated_at')->value('updated_at'),
            'models' => AdminModuleOption::forGroup('ai_model')->get(),
        ]);
    }

    public function store(UpdateAiConfigurationRequest $request)
    {
        $validated = $request->validated();
        AiSetting::setValue('openai_api_key', empty($validated['openai_api_key']) ? '' : Crypt::encryptString($validated['openai_api_key']));
        AiSetting::setValue('openai_model', $validated['openai_model']);
        AiSetting::setValue('ai_prompt_template', $validated['ai_prompt_template'] ?? '');
        AiSetting::setValue('status', $validated['status']);
        AiSetting::setValue('updated_by', auth('admin')->id());
        AiSetting::setValue('updated_by_name', auth('admin')->user()->name);

        return to_route('admin.ai.manage')->with('success', 'AI configuration updated successfully.');
    }
}
