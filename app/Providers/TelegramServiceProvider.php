<?php
declare(strict_types=1);

namespace App\Providers;

use App\Telegram\Project;
use Illuminate\Support\ServiceProvider;
use SergiX44\Nutgram\Nutgram;

class TelegramServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Nutgram::macro('getProject', fn(): Project => $this->get('project'));
    }
}
