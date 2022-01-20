<?php

declare(strict_types=1);

namespace DDDExample\Application;

interface CommandBus
{
    public function handle(Command $command): void;
}
