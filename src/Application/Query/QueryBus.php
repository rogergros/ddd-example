<?php

declare(strict_types=1);

namespace DDDExample\Application\Query;

interface QueryBus
{
    public function ask(Query $query): mixed;
}
