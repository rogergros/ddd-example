<?php

declare(strict_types=1);

namespace DDDExample\Application\Query;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('messenger.message_handler', ['bus' => 'query.bus'])]
interface QueryHandler
{
}
