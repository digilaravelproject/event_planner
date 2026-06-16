<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiSetting;
use Illuminate\Http\Request;

class AiSettingController extends Controller
{
    /**
     * Display the AI configuration page.
     */
    public function index()
    {
        $apiKey = AiSetting::getValue('openai_api_key', '');
        $model = AiSetting::getValue('openai_model', 'gpt-4o');

        return view('admin.ai.manage', compact('apiKey', 'model'));
    }

    /**
     * Store or update AI credentials.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'openai_api_key' => ['nullable', 'string', 'max:255'],
            'openai_model' => ['required', 'string', 'max:100'],
        ]);

        AiSetting::setValue('openai_api_key', $validated['openai_api_key'] ?? '');
        AiSetting::setValue('openai_model', $validated['openai_model']);

        return redirect()->route('admin.ai.manage')
            ->with('success', 'OpenAI credentials updated successfully!');
    }
}
