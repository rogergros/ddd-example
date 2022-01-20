<?php

declare(strict_types=1);

namespace DDDExample\Shared\Extractor\Exception;

use DDDExample\Shared\Exception\AppException;

final class PrimitiveExtractorKeyNotFoundException extends AppException
{
    public function __construct(int|string $key, string $keyPath)
    {
        parent::__construct(sprintf("Key %s not found on path %s", $key, $keyPath));
    }
}
