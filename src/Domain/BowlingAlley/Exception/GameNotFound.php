<?php

declare(strict_types=1);

namespace DDDExample\Domain\BowlingAlley\Exception;

use DDDExample\Shared\Exception\AppException;

final class GameNotFound extends AppException
{
    public static function withId(string $id): self
    {
        return new self("When searching by id $id");
    }
}
