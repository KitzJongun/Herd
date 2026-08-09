<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAssistantService
{
    /**
     * Analyze ticket description and return AI suggestions
     *
     * @param string $title
     * @param string $description
     * @return array|null
     */
    public function analyzeTicket($title, $description)
    {
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            Log::warning('Gemini API Key is not set. Skipping AI triage.');
            return null;
        }

        // Available categories to prompt the AI to select from
        $categories = Category::pluck('name')->implode(', ');

        $prompt = "Sebagai asisten AI IT Helpdesk, tolong analisis laporan masalah berikut:
            Judul: $title
            Deskripsi: $description

            Analisis dan berikan output dalam format JSON:
            {
                \"summary\": \"Ringkasan masalah maksimal 1 kalimat saja.\",
                \"category\": \"Pilih satu nama kategori paling relevan dari daftar ini: [$categories]\",
                \"priority\": \"Pilih antara 'Rendah', 'Sedang', atau 'Tinggi'\"
            }";

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                $result = json_decode($content, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $result;
                } else {
                    Log::error('AI JSON Decode Error: ' . json_last_error_msg() . ' Content: ' . $content);
                }
            } else {
                Log::error('Gemini API Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('AI Service Exception: ' . $e->getMessage());
        }

        return null;
    }
}
