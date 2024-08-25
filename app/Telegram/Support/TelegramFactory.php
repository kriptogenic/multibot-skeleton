<?php
declare(strict_types=1);

namespace App\Telegram\Support;

use Illuminate\Contracts\Cache\Repository as CacheRepositor;
use Psr\Container\ContainerInterface;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;

final readonly class TelegramFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function make(string $token): Nutgram
    {
        return new Nutgram($token);
    }

    public function makeWithContainer(string $token): Nutgram
    {
        $container = new TelegramContainer($this->container);
        $config = new Configuration(
            container: $container,
            cache: $container->get(CacheRepositor::class),
        );

        $bot = new Nutgram($token, $config);
        $container->setInstance($bot);

        return $bot;
    }
}
