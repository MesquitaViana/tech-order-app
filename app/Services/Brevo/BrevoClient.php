<?php

namespace App\Services\Brevo;

use Illuminate\Support\Facades\Http;

class BrevoClient
{
    public function post(string $path, array $payload): array
    {
        $baseUrl = rtrim((string) config('services.brevo.base_url'), '/');
        $apiKey  = (string) config('services.brevo.api_key');

        $resp = Http::withHeaders([
            'api-key'      => $apiKey,
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ])->post($baseUrl . $path, $payload);

        if (! $resp->successful()) {
            throw new \RuntimeException('Brevo error: '.$resp->status().' - '.$resp->body());
        }

        return $resp->json() ?? [];
    }
}
