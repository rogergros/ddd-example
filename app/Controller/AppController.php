<?php

declare(strict_types=1);

namespace DDDExample\App\Controller;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('controller.service_arguments')]
interface AppController
{
}
