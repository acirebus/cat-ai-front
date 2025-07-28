<?php

namespace App\Services;

use App\Models\ApiUsageLog;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->apiUrl = config('services.groq.api_url');
        $this->model = config('services.groq.model');
    }

    /**
     * Send a chat completion request to Groq API
     */
    public function sendChatCompletion(string $message, User $user, array $context = []): array
    {
        $startTime = microtime(true);
        
        try {
            $payload = $this->buildChatPayload($message, $context);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, $payload);

            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $this->formatCatResponse($data['choices'][0]['message']['content'] ?? '');
                
                // Log successful API usage
                $this->logApiUsage($user, $payload, $data, $responseTime, 'success');

                return [
                    'success' => true,
                    'message' => $aiResponse,
                    'tokens_used' => $data['usage']['total_tokens'] ?? 0,
                ];
            } else {
                $errorMessage = $response->json()['error']['message'] ?? 'Unknown error';
                
                // Log failed API usage
                $this->logApiUsage($user, $payload, $response->json(), $responseTime, 'error', $errorMessage);

                return [
                    'success' => false,
                    'error' => $errorMessage,
                ];
            }
        } catch (\Exception $e) {
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000);
            
            Log::error('Groq API Error: ' . $e->getMessage());
            
            // Log exception
            $this->logApiUsage($user, $payload ?? [], [], $responseTime, 'exception', $e->getMessage());

            return [
                'success' => false,
                'error' => 'meow (something went wrong with the AI service. please try again later!)',
            ];
        }
    }

    /**
     * Build the chat completion payload
     */
    private function buildChatPayload(string $message, array $context = []): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are cat-AI, a feline AI assistant. Always respond in a cat-themed way with "meow" at the beginning, use "ur" instead of "your", and maintain a playful, cat-like personality. Be helpful but keep the cat theme throughout ur responses.'
            ]
        ];

        // Add context messages if provided
        foreach ($context as $contextMessage) {
            $messages[] = [
                'role' => $contextMessage['is_user'] ? 'user' : 'assistant',
                'content' => $contextMessage['content']
            ];
        }

        // Add the current message
        $messages[] = [
            'role' => 'user',
            'content' => $message
        ];

        return [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'stream' => false,
        ];
    }

    /**
     * Format the AI response to ensure it follows cat theme
     */
    private function formatCatResponse(string $response): string
    {
        // Ensure response starts with "meow"
        if (!str_starts_with(strtolower($response), 'meow')) {
            $response = 'meow (' . $response . ')';
        }

        // Replace "your" with "ur"
        $response = str_ireplace(['your ', 'Your ', 'YOUR '], ['ur ', 'ur ', 'ur '], $response);

        return $response;
    }

    /**
     * Log API usage for analytics
     */
    private function logApiUsage(
        User $user, 
        array $requestData, 
        array $responseData, 
        int $responseTime, 
        string $status, 
        ?string $errorMessage = null
    ): void {
        ApiUsageLog::create([
            'user_id' => $user->id,
            'endpoint' => $this->apiUrl,
            'method' => 'POST',
            'request_data' => $requestData,
            'response_data' => $responseData,
            'response_time_ms' => $responseTime,
            'status' => $status,
            'error_message' => $errorMessage,
            'model_used' => $this->model,
            'tokens_used' => $responseData['usage']['total_tokens'] ?? null,
        ]);
    }

    /**
     * Get available models
     */
    public function getAvailableModels(): array
    {
        return [
            'llama3-8b-8192' => 'Llama 3 8B',
            'llama3-70b-8192' => 'Llama 3 70B',
            'mixtral-8x7b-32768' => 'Mixtral 8x7B',
            'gemma-7b-it' => 'Gemma 7B',
        ];
    }
}