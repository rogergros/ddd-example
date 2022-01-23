<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game\Exception;

use DDDExample\Shared\Exception\UnexpectedValueException;

class UnexpectedNumberOfPlayers extends UnexpectedValueException
{
    public function __construct(int $received, int $maxLanes)
    {
        parent::__construct(sprintf("Each game must have between 1 and %d players, received %d", $maxLanes, $received));
    }
}
