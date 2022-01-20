<?php

declare(strict_types=1);

namespace DDDExample\App\Controller\BowlingAlley;

use DDDExample\App\Controller\AppController;
use DDDExample\Application\BowlingAlley\CreateBowlingAlley;
use DDDExample\Application\CommandBus;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\BowlingAlley\BowlingAlleyName;
use DDDExample\Shared\Extractor\PrimitiveExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class CreateBowlingAlleyController implements AppController
{
    #[Route(
        path: '/bowling-alleys',
        name: 'bowling-alleys.create',
        requirements: [],
        methods: ['POST']
    )]
    public function __invoke(CommandBus $commandBus, PrimitiveExtractor $body): JsonResponse
    {
        $commandBus->handle(
            new CreateBowlingAlley(
                BowlingAlleyId::create($body->string('id')),
                BowlingAlleyName::create($body->string('name')),
                $body->int('lanes')
            )
        );

        return new JsonResponse();
    }
}
