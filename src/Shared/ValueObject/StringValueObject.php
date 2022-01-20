<?php

declare(strict_types=1);

namespace DDDExample\Shared\ValueObject;

use Stringable;

abstract class StringValueObject extends ValueObject implements Stringable
{
    protected function __construct(protected string $value)
    {
    }

    public function hasValue(string $value): bool
    {
        return $this->value === $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param static $other
     */
    public function eq($other): bool
    {
        return $other instanceof static && $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
