<?php

declare(strict_types=1);

namespace DDDExample\App;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('controller.service_arguments')]
interface AppController
{
}
