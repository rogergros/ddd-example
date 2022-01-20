<?php

declare(strict_types=1);

namespace DDDExample\Shared\Extractor;

use ArrayAccess;
use DDDExample\Shared\Extractor\Exception\PrimitiveExtractorKeyNotFoundException;
use DDDExample\Shared\Extractor\Exception\PrimitiveExtractorUnexpectedTypeException;
use Throwable;

use function Lambdish\Phunctional\reduce;

class PrimitiveExtractor
{
    /**
     * @param ArrayAccess|array<mixed> $primitives
     */
    public static function for(ArrayAccess|array $primitives): self
    {
        return new self($primitives);
    }

    /**
     * @param ArrayAccess|array<mixed> $data
     */
    private function __construct(
        private ArrayAccess|array $data
    ) {
    }

    /**
     * @param int|string|array<int|string> $key
     */
    public function string(string|int|array $key, ?Throwable $customException = null): string
    {
        $value = $this->extract($key, $customException);
        if (!is_string($value)) {
            throw $customException ?? new PrimitiveExtractorUnexpectedTypeException($key, 'string', $value);
        }

        return $value;
    }

    /**
     * @param int|string|array<int|string> $key
     */
    public function int(string|int|array $key, ?Throwable $customException = null): int
    {
        $value = $this->extract($key, $customException);
        if (!is_int($value)) {
            throw $customException ?? new PrimitiveExtractorUnexpectedTypeException($key, 'int', $value);
        }

        return $value;
    }

    /**
     * @param int|string|array<int|string> $key
     */
    private function extract(string|int|array $key, ?Throwable $customException): mixed
    {
        $keys = is_array($key) ? $key : [$key];

        $currentPath = [];
        $data = $this->data;
        foreach ($keys as $pathKey) {
            $currentPath[] = $pathKey;
            if (!is_array($data) || !array_key_exists($pathKey, $data)) {
                throw $customException ?? new PrimitiveExtractorKeyNotFoundException(
                        self::pathFormatter($key),
                        self::pathFormatter($currentPath)
                    );
            }
            /** @var mixed $data */
            $data = $data[$pathKey];
        }

        return $data;
    }

    /**
     * @param int|string|array<int|string> $key
     */
    public static function pathFormatter(array|int|string $key): string
    {
        $keys = is_array($key) ? $key : [$key];

        /** @var string $fullPath */
        $fullPath = reduce(
            fn(string $acc, int|string $value): string => $acc . '[\'' . $value . '\']',
            $keys,
            ''
        );

        return $fullPath;
    }
}
