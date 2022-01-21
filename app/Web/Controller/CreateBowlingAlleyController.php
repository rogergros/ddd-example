<?php

declare(strict_types=1);

namespace DDDExample\App\Web\Controller;

use DDDExample\App\Web\WebController;
use DDDExample\Application\CommandBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateBowlingAlleyController extends WebController
{
    #[Route(
        path: '/create-bowling-alley',
        name: 'create-bowling-alley',
        requirements: [],
        methods: ['GET']
    )]
    public function getAction(CommandBus $commandBus): Response
    {
        return $this->renderResponse('create-bowling-alley.html.twig');
    }
}
