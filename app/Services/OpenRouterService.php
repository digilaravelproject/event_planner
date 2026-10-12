<?php

namespace App\Services;

use App\Models\AiSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenRouterService
{
    public function models(?string $apiKey = null): array
    {
        $key = $apiKey ?: $this->apiKey();

        return Cache::remember('openrouter.models', now()->addHour(), function () use ($key): array {
            $response = $this->client($key)->get('/models', ['output_modalities' => 'text']);
            $response->throw();

            return collect($response->json('data', []))
                ->filter(fn (array $model): bool => isset($model['id'], $model['name']))
                ->map(fn (array $model): array => [
                    'id' => (string) $model['id'],
                    'name' => (string) $model['name'],
                    'context_length' => (int) ($model['context_length'] ?? 0),
                ])->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values()->all();
        });
    }

    public function chat(array $messages, ?string $model = null): array
    {
        $prompt = trim((string) AiSetting::getValue('openrouter_prompt_template', ''));
        if ($prompt !== '') {
            array_unshift($messages, ['role' => 'system', 'content' => $prompt]);
        }

        $response = $this->client($this->apiKey())->post('/chat/completions', [
            'model' => $model ?: AiSetting::getValue('openrouter_model', 'openrouter/auto'),
            'messages' => $messages,
        ]);
        $response->throw();

        return $response->json();
    }

    public function apiKey(): string
    {
        $stored = (string) AiSetting::getValue('openrouter_api_key', '');
        if ($stored === '') {
            throw new RuntimeException('OpenRouter API key is not configured.');
        }

        try {
            return Crypt::decryptString($stored);
        } catch (\Throwable) {
            return $stored;
        }
    }

    private function client(string $apiKey)
    {
        return Http::baseUrl(config('services.openrouter.url'))
            ->acceptJson()
            ->asJson()
            ->withToken($apiKey)
            ->withHeaders([
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])
            ->connectTimeout(3)
            ->timeout(8);
    }
}
