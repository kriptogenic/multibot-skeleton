<?php
declare(strict_types=1);

namespace App\Telegram\Support;

use Illuminate\Http\Request;
use SergiX44\Nutgram\RunningMode\Webhook;

class LaravelWebhook extends Webhook
{
    public function __construct(private readonly Request $request)
    {
        parent::__construct();
    }

    protected function input(): string
    {
        return $this->request->getContent();
    }
}
