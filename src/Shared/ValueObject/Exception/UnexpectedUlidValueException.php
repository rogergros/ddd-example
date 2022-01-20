<?php

declare(strict_types=1);

namespace DDDExample\Shared\ValueObject\Exception;

use DDDExample\Shared\Exception\UnexpectedValueException;

class UnexpectedUlidValueException extends UnexpectedValueException
{
    public function __construct(string $invalidUlid)
    {
        parent::__construct("Invalid ulid value: $invalidUlid");
    }
}
