<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Infrastructure\TranslatorService;
use Illuminate\Http\Request;

class TranslatorController extends Controller
{
    function __construct(protected TranslatorService $translatorService)
    {
    }

    public function translate(Request $request)
    {
        try {
            return response()->json(
                $this->translatorService->translate($request->all())
            );
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
