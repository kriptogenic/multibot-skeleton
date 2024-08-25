<?php
declare(strict_types=1);

namespace App\Telegram;

use App\Telegram\Support\LaravelWebhook;
use App\Telegram\Support\TelegramFactory;
use Illuminate\Http\Request;

final readonly class Kernel
{
    public function __construct(private TelegramFactory $factory, private Router $router)
    {
    }

    public function run(Project $project, Request $request): void
    {
        $bot = $this->factory->makeWithContainer($project->token());

        $bot->setRunningMode(new LaravelWebhook($request));
        $this->router->register($bot);
        $bot->set('project', $project);

        $bot->run();
    }
}
