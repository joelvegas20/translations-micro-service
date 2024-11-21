<?php

namespace App\Services\Infrastructure;

class TokenCounterService
{
    public static function countTokens(string $text): int
    {
        $tokens = preg_split(
            "/\s+|(?=\W)|(?<=\W)/",
            $text,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        return count($tokens);
    }

    public static function countTokensInMessages(array $messages): int
    {
        $totalTokens = 0;

        foreach ($messages as $message) {
            if (isset($message["content"])) {
                $totalTokens += self::countTokens($message["content"]);
            }
        }

        return $totalTokens;
    }
}
