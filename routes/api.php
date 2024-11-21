<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslatorController;

Route::prefix("translate")->group(function () {
    Route::post("/", [TranslatorController::class, "translate"]);
});
