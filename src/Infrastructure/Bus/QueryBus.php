<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Bus;

use DDDExample\Application\Query\Query;
use DDDExample\Application\Query\QueryBus as QueryBusInterface;
use DDDExample\Infrastructure\Bus\Exception\QueryBysQueryNotRegisteredException;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(#[Target('query.bus')] MessageBusInterface $symfonyBus)
    {
        $this->messageBus = $symfonyBus;
    }

    public function ask(Query $query): mixed
    {
        try {
            return $this->handle($query);
        } catch (NoHandlerForMessageException) {
            throw new QueryBysQueryNotRegisteredException($query);
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }
}
