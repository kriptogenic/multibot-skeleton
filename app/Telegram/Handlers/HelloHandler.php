<?php
declare(strict_types=1);

namespace App\Telegram\Handlers;

use SergiX44\Nutgram\Nutgram;

class HelloHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $bot->sendMessage('Hello! I\'m ' . $bot->getProject()->name);
    }
}
