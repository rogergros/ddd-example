<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Persistence\Disk;

/**
 * @template T
 */
abstract class DiskRepository
{
    /**
     * @var array<string,T>
     */
    protected array $data = [];

    abstract protected function fileNamespace(): string;

    public function __construct()
    {
        $this->getDataFromFile();
    }

    protected function saveDataOnFile(): void
    {
        file_put_contents(
            $this->filePath(),
            serialize($this->data),
        );
    }

    protected function getDataFromFile(): void
    {
        $filePath = $this->filePath();

        if (!file_exists($filePath)) {
            $this->data = [];

            return;
        }

        $fileContent = file_get_contents($this->filePath());

        if (false === $fileContent) {
            $this->data = [];

            return;
        }

        /** @var array<string,T>|false|null $fileData */
        $fileData = unserialize($fileContent);

        $this->data = is_array($fileData) ? $fileData : [];
    }

    private function filePath(): string
    {
        return sprintf('%s/bowling-app-%s.db', sys_get_temp_dir(), $this->fileNamespace());
    }
}
