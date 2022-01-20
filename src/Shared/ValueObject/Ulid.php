<?php

declare(strict_types=1);

namespace DDDExample\Shared\ValueObject;

use DDDExample\Shared\ValueObject\Exception\UnexpectedUlidValueException;
use Symfony\Component\Uid\Ulid as SymfonyUlid;

abstract class Ulid extends StringValueObject
{
    public function __construct(string $value)
    {
        if (!static::isValid($value)) {
            throw new UnexpectedUlidValueException($value);
        }

        parent::__construct($value);
    }

    public static function isValid(string $value): bool
    {
        return SymfonyUlid::isValid($value);
    }
}
