<?php

declare(strict_types=1);

namespace DDDExample\Domain\BowlingAlley;

use DateTimeImmutable;

class BowlingAlley
{
    public static function create(
        BowlingAlleyId $id,
        BowlingAlleyName $name,
        int $lanes,
    ): self {
        return new self(
            $id,
            $name,
            $lanes,
            new DateTimeImmutable()
        );
    }

    public function __construct(
        private BowlingAlleyId $id,
        private BowlingAlleyName $name,
        private int $lanes,
        private DateTimeImmutable $createdAt
    ) {
    }

    public function id(): BowlingAlleyId
    {
        return $this->id;
    }

    public function name(): BowlingAlleyName
    {
        return $this->name;
    }

    public function lanes(): int
    {
        return $this->lanes;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
