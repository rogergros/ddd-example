<?php

declare(strict_types=1);

namespace DDDExample\Domain\BowlingAlley;

use DDDExample\Domain\BowlingAlley\Exception\InvalidBowlingAlleyNameLengthException;
use DDDExample\Shared\ValueObject\StringValueObject;

final class BowlingAlleyName extends StringValueObject
{
    private const MAX_LENGTH = 200;

    public static function create(string $value): self
    {
        return new self($value);
    }

    public function __construct(string $value)
    {
        if (strlen($value) > self::MAX_LENGTH) {
            throw new InvalidBowlingAlleyNameLengthException($value, self::MAX_LENGTH);
        }

        parent::__construct($value);
    }
}
