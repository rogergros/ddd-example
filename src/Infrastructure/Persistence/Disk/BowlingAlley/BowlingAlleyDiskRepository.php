<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Persistence\Disk\BowlingAlley;

use DDDExample\Domain\BowlingAlley\BowlingAlley;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\BowlingAlley\BowlingAlleyRepository;
use DDDExample\Domain\BowlingAlley\Exception\BowlingAlleyNotFound;
use DDDExample\Infrastructure\Persistence\Disk\DiskRepository;

/**
 * @extends DiskRepository<BowlingAlley>
 */
final class BowlingAlleyDiskRepository extends DiskRepository implements BowlingAlleyRepository
{
    public function save(BowlingAlley $bowlingAlley): void
    {
        $this->data[$bowlingAlley->id()->value()] = $bowlingAlley;
        $this->saveDataOnFile();
    }

    /**
     * @return list<BowlingAlley>
     */
    public function all(): array
    {
        return array_values($this->data);
    }

    public function byId(BowlingAlleyId $bowlingAlleyId): BowlingAlley
    {
        $bowlingAlley = $this->data[$bowlingAlleyId->value()] ?? null;

        if (!$bowlingAlley instanceof BowlingAlley) {
            throw BowlingAlleyNotFound::withId($bowlingAlleyId->value());
        }

        return $bowlingAlley;
    }

    protected function fileNamespace(): string
    {
        return 'bowling-alley';
    }
}
