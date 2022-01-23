<?php

declare(strict_types=1);

namespace DDDExample\App\Web\Controller;

use DDDExample\App\Web\WebController;
use DDDExample\Application\Command\BowlingAlley\CreateBowlingAlley;
use DDDExample\Application\Command\CommandBus;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\BowlingAlley\BowlingAlleyName;
use DDDExample\Shared\Extractor\PrimitiveExtractor;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateBowlingAlleyController extends WebController
{
    #[Route(
        path: '/create-bowling-alley',
        name: 'create-bowling-alley',
        requirements: [],
        methods: ['GET']
    )]
    public function getAction(): Response
    {
        return $this->renderResponse('create-bowling-alley.html.twig');
    }

    #[Route(
        path: '/create-bowling-alley',
        name: 'create-bowling-alley-post',
        requirements: [],
        methods: ['POST']
    )]
    public function postAction(PrimitiveExtractor $post, CommandBus $commandBus): Response
    {
        $commandBus->handle(
            new CreateBowlingAlley(
                BowlingAlleyId::createRandom(),
                BowlingAlleyName::create($post->string('name')),
                (int)$post->string('lanes')
            )
        );

        return $this->redirect('web.home');
    }
}
