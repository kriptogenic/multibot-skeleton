<?php

namespace App\Console\Commands;

use App\Telegram\Support\TelegramFactory;

class SetWebhookCommand extends ProjectChoiceCommand
{
    protected $signature = 'set:webhook {bot_id?}';

    protected $description = 'Sets webhook url for bot';

    public function handle(TelegramFactory $factory): int
    {
        $project = $this->getProject();

        $url = route('telegram.webhook', ['bot_id' => $project->value]);

        $parsedUrl = parse_url($url);

        $isUnsecure = $parsedUrl['scheme'] === 'http';
        $isLocalhost = $parsedUrl['host'] === 'localhost';

        if ($isUnsecure || $isLocalhost) {
            $this->error('Looks like your APP_URL in .env not correctly configured.');
            $this->info('Current app url is: ' . url('/'));
            $this->error(
                sprintf(
                    'Your APP_URL must be %s%s%s',
                    $isUnsecure ? 'secure (https)' : '',
                    $isUnsecure && $isLocalhost ? ' and ' : '',
                    $isLocalhost ? 'non-localhost (example.com)' : '',
                ),
            );
            return 1;
        }

        $bot = $factory->make($project->token());

        $bot->setWebhook($url);

        $this->info(sprintf('Webhook successfully set for %s url: %s', $project->name, $url));
        return 0;
    }
}
