<?php
declare(strict_types=1);

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('telegram/webhook/{bot_id}', WebhookController::class)
    ->name('telegram.webhook')
    ->where('bot_id', '\d+');
