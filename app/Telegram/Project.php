<?php
declare(strict_types=1);

namespace App\Telegram;

use App\Support\Collectable;

enum Project: int
{
    use Collectable;

    case TestBot = 432192320;
    case TestBot2 = 112192320;

    public function token(): string
    {
        return match ($this) {
            self::TestBot => config('telegram.tokens.test_bot'),
            self::TestBot2 => config('telegram.tokens.test_bot_2'),
        };
    }
}
