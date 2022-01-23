<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game\Exception;

use DDDExample\Shared\Exception\UnexpectedValueException;

class UnexpectedNumberOfLanes extends UnexpectedValueException
{
    public function __construct(int $received, int $maxLanes)
    {
        parent::__construct(sprintf("Alleys must have between 1 and %d lanes, received %d", $maxLanes, $received));
    }
}
