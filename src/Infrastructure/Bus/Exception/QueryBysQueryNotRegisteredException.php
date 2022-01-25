<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Bus\Exception;

use DDDExample\Application\Query\Query;
use DDDExample\Shared\Exception\AppException;

final class QueryBysQueryNotRegisteredException extends AppException
{
    public function __construct(Query $query)
    {
        parent::__construct(sprintf('No handler found for query %s', get_class($query)));
    }
}
