<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Bus\Exception;

use DDDExample\Application\Command;
use DDDExample\Shared\Exception\AppException;

class CommandBusCommandNotRegisteredException extends AppException
{
    public function __construct(Command $command)
    {
        parent::__construct(sprintf('Command %s not found', get_class($command)));
    }
}
