<?php

namespace App\Console\Commands;

use App\Telegram\Project;
use App\Telegram\Support\TelegramFactory;
use Illuminate\Console\Command;

class SetWebhookCommand extends Command
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

    private function getProject(): Project
    {
        $bot_id = $this->argument('bot_id');
        $project = $bot_id === null ? null : Project::tryFrom($bot_id);

        if ($project !== null) {
            return $project;
        }

        $choices = Project::collect()
            ->mapWithKeys(function (Project $project) {
                return ["#" . $project->value . "" => $project->name];
            })
            ->toArray();
        $bot_id = $this->choice('Choose project to set webhook', $choices);

        return Project::from(substr($bot_id, 1));
    }
}
