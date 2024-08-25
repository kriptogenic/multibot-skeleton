<?php
declare(strict_types=1);

namespace App\Support;
use Illuminate\Support\Collection;

trait Collectable
{
    public static function collect(): Collection
    {
        return collect(static::cases());
    }
}
