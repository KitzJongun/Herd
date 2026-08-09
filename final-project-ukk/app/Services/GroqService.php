<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    public function generateReply($message)
    {
        try {
            $apiKey = env('GROQ_API_KEY');

            if (!$apiKey) {
                return "Terima kasih, tiket Anda sedang diproses oleh tim kami.";
            }

            $prompt = "Sebagai customer support profesional, jawablah pesan pelanggan berikut dalam Bahasa Indonesia secara singkat, jelas, sopan, dan solutif:\n\n" . $message;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah agen customer support yang ramah dan membantu.'],
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                $replyContent = $data['choices'][0]['message']['content'] ?? null;

                if (!$replyContent) {
                    Log::warning('Groq API Warning: Reply from AI is empty or null.', ['response' => $data]);
                    return "Terima kasih, tiket Anda sedang diproses oleh tim kami.";
                }

                return trim($replyContent);
            }

            Log::error("Groq API Error: " . $response->body());
            return "Terima kasih, tiket Anda sedang diproses oleh tim kami.";

        } catch (\Exception $e) {
            Log::error("Groq Exception: " . $e->getMessage());
            return "Terima kasih, tiket Anda sedang diproses oleh tim kami.";
        }
    }
}
