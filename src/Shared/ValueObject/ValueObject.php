<?php

declare(strict_types=1);

namespace DDDExample\Shared\ValueObject;

abstract class ValueObject
{
    /**
     * @param static|null $one
     * @param static|null $another
     */
    public static function nullableEq($one, $another): bool
    {
        if (null === $one && null === $another) {
            return true;
        }

        return isset($one, $another)
            && get_class($one) === get_class($another)
            && $one->eq($another);
    }

    /**
     * @param static $other
     */
    abstract public function eq($other): bool;
}
