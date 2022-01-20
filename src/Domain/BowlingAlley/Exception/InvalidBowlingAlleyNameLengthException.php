<?php

declare(strict_types=1);

namespace DDDExample\Domain\BowlingAlley\Exception;

use DDDExample\Shared\Exception\UnexpectedValueLengthException;

final class InvalidBowlingAlleyNameLengthException extends UnexpectedValueLengthException
{
    public function __construct(string $received, int $maxLength)
    {
        parent::__construct('BowlingAlleyName', $maxLength, $received);
    }
}
