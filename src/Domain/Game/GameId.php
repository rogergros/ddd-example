<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game;

use DDDExample\Shared\ValueObject\Ulid;

class GameId extends Ulid
{
    public static function create(string $value): self
    {
        return new self($value);
    }
}