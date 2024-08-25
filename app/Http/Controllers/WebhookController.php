<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Telegram\Project;
use App\Telegram\Kernel;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WebhookController extends Controller
{
    public function __construct(private readonly Kernel $kernel)
    {
    }

    public function __invoke(Request $request, int $bot_id): void
    {
        $project = Project::tryFrom($bot_id) ?? throw new NotFoundHttpException();

        $this->kernel->run($project, $request);
    }
}
