<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Persistence\Disk\BowlingAlley;

use DDDExample\Domain\BowlingAlley\BowlingAlley;
use DDDExample\Domain\BowlingAlley\BowlingAlleyRepository;
use DDDExample\Infrastructure\Persistence\Disk\FileRepository;

/**
 * @extends FileRepository<BowlingAlley>
 */
class BowlingAlleyDiskRepository extends FileRepository implements BowlingAlleyRepository
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

    protected function fileNamespace(): string
    {
        return 'bowling-alley';
    }
}
