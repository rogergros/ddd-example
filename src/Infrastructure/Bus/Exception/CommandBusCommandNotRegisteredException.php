<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Bus\Exception;

use DDDExample\Application\Command\Command;
use DDDExample\Shared\Exception\AppException;

class CommandBusCommandNotRegisteredException extends AppException
{
    public function __construct(Command $command)
    {
        parent::__construct(sprintf('No handler found for command %s', get_class($command)));
    }
}
