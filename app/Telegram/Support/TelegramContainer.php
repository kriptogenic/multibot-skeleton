<?php
declare(strict_types=1);

namespace App\Telegram\Support;

use Psr\Container\ContainerInterface;
use RuntimeException;
use SergiX44\Nutgram\Nutgram;
use WeakReference;

readonly final class TelegramContainer implements ContainerInterface
{
    /**
     * @var \WeakReference<Nutgram>
     */
    private WeakReference $weakRef;

    public function __construct(private ContainerInterface $container)
    {
    }

    public function setInstance(Nutgram $bot): void
    {
        $this->weakRef = WeakReference::create($bot);
    }

    public function get(string $id)
    {
        if ($id === Nutgram::class) {
            return $this->weakRef->get() ?? throw new RuntimeException('Nutgram object destroyed');
        }

        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        if ($id === Nutgram::class) {
            return true;
        }

        return $this->container->has($id);
    }
}
