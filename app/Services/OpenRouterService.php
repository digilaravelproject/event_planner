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
            'max_tokens' => 1800,
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
        $connectTimeout = max(1, min(5, (int) config('services.openrouter.connect_timeout', 3)));
        $requestTimeout = max($connectTimeout + 1, min(20, (int) config('services.openrouter.timeout', 10)));

        return Http::baseUrl(config('services.openrouter.url'))
            ->acceptJson()
            ->asJson()
            ->withToken($apiKey)
            ->withHeaders([
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])
            ->connectTimeout($connectTimeout)
            ->timeout($requestTimeout)
            ->withOptions([
                // On Windows the generic Guzzle timeout can remain blocked inside
                // CurlHandler until PHP's 60-second fatal limit. Native cURL options
                // guarantee that Laravel regains control and can build a fallback plan.
                'force_ip_resolve' => 'v4',
                'curl' => [
                    CURLOPT_CONNECTTIMEOUT => $connectTimeout,
                    CURLOPT_TIMEOUT => $requestTimeout,
                    CURLOPT_LOW_SPEED_LIMIT => 1,
                    CURLOPT_LOW_SPEED_TIME => min(5, $requestTimeout),
                    CURLOPT_NOSIGNAL => true,
                ],
            ]);
    }
}
