<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Persistence\Disk\BowlingAlley;

use DDDExample\Domain\BowlingAlley\BowlingAlley;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\BowlingAlley\BowlingAlleyRepository;
use DDDExample\Domain\BowlingAlley\Exception\BowlingAlleyNotFound;
use DDDExample\Infrastructure\Persistence\Disk\DiskRepository;

use function Lambdish\Phunctional\search;

/**
 * @extends DiskRepository<BowlingAlley>
 */
class BowlingAlleyDiskRepository extends DiskRepository implements BowlingAlleyRepository
{
    public function save(BowlingAlley $bowlingAlley): void
    {
        $this->data[] = $bowlingAlley;
        $this->saveDataOnFile();
    }

    /**
     * @return list<BowlingAlley>
     */
    public function all(): array
    {
        return $this->data;
    }

    public function byId(BowlingAlleyId $bowlingAlleyId): BowlingAlley
    {
        /** @var BowlingAlley|null $bowlingAlley */
        $bowlingAlley = search(
        /** @psalm-suppress ArgumentTypeCoercion */
            static fn(BowlingAlley $bowlingAlley): bool => $bowlingAlleyId->eq($bowlingAlley->id()),
            $this->all()
        );

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
