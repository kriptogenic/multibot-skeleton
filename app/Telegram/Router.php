<?php
declare(strict_types=1);

namespace App\Telegram;
use App\Telegram\Handlers\HelloHandler;
use SergiX44\Nutgram\Nutgram;

final class Router
{
    public function register(Nutgram $bot): void
    {
        $bot->onCommand('start', HelloHandler::class);
    }
}
