<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game\Exception;

use DDDExample\Shared\Exception\AppException;

final class GameAlreadyFinishedException extends AppException
{
}
