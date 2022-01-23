<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Persistence\Disk;

/**
 * @template T
 */
abstract class FileRepository
{
    /**
     * @var list<T>
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

        $fileContent = file_get_contents($this->filePath());

        if (false === $fileContent) {
            $this->data = [];

            return;
        }

        /** @var array<T>|false|null $fileData */
        $fileData = file_exists($filePath) ? unserialize($fileContent) : null;

        $this->data = is_array($fileData) ? array_values($fileData) : [];
    }

    private function filePath(): string
    {
        return sprintf('%s/bowling-app-%s.db', sys_get_temp_dir(), $this->fileNamespace());
    }

    public function __destruct()
    {
        $this->saveDataOnFile();
    }
}