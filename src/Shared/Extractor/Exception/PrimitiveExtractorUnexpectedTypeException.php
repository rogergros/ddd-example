<?php

declare(strict_types=1);

namespace DDDExample\Shared\Extractor\Exception;

use DDDExample\Shared\Exception\AppException;
use DDDExample\Shared\Extractor\PrimitiveExtractor;

final class PrimitiveExtractorUnexpectedTypeException extends AppException
{
    /**
     * @param int|string|array<int|string> $key
     */
    public function __construct(array|int|string $key, string $expectedType, mixed $got)
    {
        parent::__construct(
            sprintf(
                'Invalid type for field %s, expected %s and found %s',
                PrimitiveExtractor::pathFormatter($key),
                $expectedType,
                get_debug_type($got),
            )
        );
    }
}
