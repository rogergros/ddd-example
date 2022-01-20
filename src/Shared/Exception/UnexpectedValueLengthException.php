<?php

declare(strict_types=1);

namespace DDDExample\Shared\Exception;

abstract class UnexpectedValueLengthException extends UnexpectedValueException
{
    public function __construct(string $fieldName, int $maxExpectedLength, string $receivedString)
    {
        parent::__construct(
            sprintf(
                "Unexpected %s '%s', expected a max length of %d characters and received %d",
                $fieldName,
                $receivedString,
                $maxExpectedLength,
                strlen($receivedString),
            )
        );
    }
}
