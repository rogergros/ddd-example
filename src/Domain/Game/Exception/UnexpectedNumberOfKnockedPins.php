<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game\Exception;

use DDDExample\Shared\Exception\UnexpectedValueException;

class UnexpectedNumberOfKnockedPins extends UnexpectedValueException
{
    public function __construct(int $received)
    {
        parent::__construct(sprintf("On a frame only 10 pins can be knocked, received %s", $received));
    }
}
