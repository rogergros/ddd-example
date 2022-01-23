<?php

declare(strict_types=1);

namespace DDDExample\Tests\Mother\Domain\Game;

use DDDExample\Domain\Game\GameId;
use Symfony\Component\Uid\Ulid;

class GameIdMother
{
    public static function create(?string $value = null): GameId
    {
        return new GameId(
            $value ?? Ulid::generate()
        );
    }
}
