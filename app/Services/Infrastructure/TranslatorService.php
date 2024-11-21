<?php

namespace App\Services\Infrastructure;

use App\Services\Infrastructure\OpenAI\OpenAIApiService;

class TranslatorService
{
    function __construct(protected OpenAIApiService $openAIApiService)
    {
    }

    public function translate($data)
    {
        return $this->openAIApiService->translateJson($data);
    }
}
