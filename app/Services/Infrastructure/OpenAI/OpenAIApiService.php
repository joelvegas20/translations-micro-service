<?php

namespace App\Services\Infrastructure\OpenAI;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;
use App\Services\Infrastructure\TokenCounterService;

class OpenAIApiService
{
    protected string $API_KEY;
    protected string $API_URL;
    protected string $MODEL;

    public function __construct()
    {
        $this->API_KEY = config("app.OPENAI_API_KEY");
        $this->API_URL = "https://api.openai.com";
        $this->MODEL = config("app.OPENAI_MODEL", "gpt-4");
    }

    public function client(): Client
    {
        return new Client([
            "base_uri" => $this->API_URL,
            "headers" => [
                "Authorization" => "Bearer " . $this->API_KEY,
                "Content-Type" => "application/json",
            ],
            "timeout" => 60,
        ]);
    }

    public function getInstruction(): string
    {
        $instructionData = json_decode(Storage::get("instructions.json"), true);
        return $instructionData["instruction"] ?? "";
    }

    public function translateJson(array $jsonInput): array
    {
        $instruction = $this->getInstruction();

        $messages = [
            [
                "role" => "system",
                "content" => $instruction,
            ],
            [
                "role" => "user",
                "content" => json_encode($jsonInput, JSON_PRETTY_PRINT),
            ],
        ];

        // Calcular tokens usados por los mensajes
        $inputTokens = TokenCounterService::countTokensInMessages($messages);

        // Calcular max_tokens restantes
        $maxTokens = min(16384 - $inputTokens, 8192); // Ajuste dinámico basado en el modelo

        try {
            $response = $this->client()->post("/v1/chat/completions", [
                "json" => [
                    "model" => $this->MODEL,
                    "messages" => $messages,
                    "max_tokens" => $maxTokens,
                    "temperature" => 0.0,
                ],
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents(),
                true
            );

            $content = $responseData["choices"][0]["message"]["content"];
            $cleanedContent = $this->cleanResponse($content);
            $decodedContent = json_decode($cleanedContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("La respuesta no es un JSON válido.");
            }

            return [
                "tokens" => $inputTokens,
                "translated_json" => $decodedContent,
            ];
        } catch (GuzzleException $e) {
            throw new \Exception(
                "Error al comunicarse con la API de OpenAI: " . $e->getMessage()
            );
        }
    }

    private function cleanResponse(string $content): string
    {
        $content = preg_replace('/^```json|```|^"|"$/', "", $content);
        return trim($content);
    }
}
