<?php

namespace App\Console\Commands;

use App\Telegram\Project;
use App\Telegram\Support\TelegramFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Console\Command;
use stdClass;

class RunPolling extends Command
{
    /**
     * In seconds, can be float value
     */
    const SLEEP_BETWEEN_TICKS = 0.25;
    /**
     * bot_id => Telegram bot id
     */
    protected $signature = 'run:polling {bot_id?}';

    protected $description = 'Telegram long polling command for testing';

    public function handle(Guzzle $http, TelegramFactory $factory): void
    {
        $bot_id = $this->argument('bot_id');
        $project = $bot_id === null ? Project::TestBot : Project::tryFrom($bot_id);

        $polling_url = sprintf('%s/bot%s/getUpdates?offset=', 'https://api.telegram.org', $project->token());
        $webhook_url = 'http://localhost:8000' . route('telegram.webhook', $project->value, false);
        $offset = 0;
        while (true) {
            try {
                $response = $http->get($polling_url . $offset);
            } catch (ClientException $e) {
                if ($e->getCode() !== 409) {
                    throw $e;
                }
                if (!$this->confirm('There is webhook set for this bot, do you want to delete it?')) {
                    return;
                }
                $bot = $factory->make($project->token());
                $bot->deleteWebhook();
                $this->info('Webhook deleted');
                continue;
            }
            $updates = json_decode($response->getBody());
            foreach ($updates->result as $update) {
                $offset = $update->update_id + 1;
                $this->processUpdate($update, $webhook_url, $http);
            }
            usleep(self::SLEEP_BETWEEN_TICKS * 1_000_000);
        }
    }

    private function processUpdate(stdClass $update, string $webhook_url, Client $http): void
    {
        $this->output->writeln($update->update_id);
        try {
            $response = $http->post($webhook_url , [
                'headers' => [
                    'Accept' => 'Application/json',
                ],
                'json' => $update,
            ]);
            dump($response->getBody()->getContents());
        } catch (ServerException $e) {
            $this->output->error($e->getResponse()->getStatusCode());
            $this->dumpLog($e->getResponse()->getBody()->getContents());
        }
    }

    private function dumpLog(string $log): void
    {
        $log = json_decode($log);
        $this->output->error(sprintf("%s: %s", $log->exception, $log->message));
        $this->output->writeln($log?->file . ' -> ' . $log?->line);
        foreach ($log->trace as $tr) {
            if (isset($tr->file)) {
                $this->output->writeln($tr->file . ' -> ' . $tr->line);
            }
            else {
                dump($tr);
            }
        }
    }
}
