<?php

declare(strict_types=1);

namespace DDDExample\Application\Command;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('messenger.message_handler', ['bus' => 'command.bus'])]
interface CommandHandler
{
}
