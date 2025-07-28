<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CatApiService
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.cat_api.api_key');
        $this->apiUrl = config('services.cat_api.api_url');
    }

    /**
     * Fetch a random cat image
     */
    public function fetchRandomCatImage(): ?string
    {
        try {
            $headers = [];
            
            // Add API key if available
            if (!empty($this->apiKey)) {
                $headers['x-api-key'] = $this->apiKey;
            }

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get($this->apiUrl, [
                    'limit' => 1,
                    'size' => 'med',
                    'mime_types' => 'jpg,png'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data[0]['url'] ?? null;
            }

            Log::warning('Cat API request failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Cat API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch multiple random cat images
     */
    public function fetchMultipleCatImages(int $count = 5): array
    {
        try {
            $headers = [];
            
            if (!empty($this->apiKey)) {
                $headers['x-api-key'] = $this->apiKey;
            }

            $response = Http::withHeaders($headers)
                ->timeout(15)
                ->get($this->apiUrl, [
                    'limit' => min($count, 10), // Limit to 10 max
                    'size' => 'med',
                    'mime_types' => 'jpg,png'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return array_column($data, 'url');
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Cat API Error (multiple): ' . $e->getMessage());
            return [];
        }
    }
}