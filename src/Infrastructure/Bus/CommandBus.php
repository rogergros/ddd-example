<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Bus;

use DDDExample\Application\Command;
use DDDExample\Application\CommandBus as CommandBusInterface;
use DDDExample\Infrastructure\Bus\Exception\CommandBusCommandNotRegisteredException;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
    private MessageBusInterface $symfonyBus;

    public function __construct(#[Target('command.bus')] MessageBusInterface $symfonyBus)
    {
        $this->symfonyBus = $symfonyBus;
    }

    public function handle(Command $command): void
    {
        try {
            $this->symfonyBus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw new CommandBusCommandNotRegisteredException($command);
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }
}
