<?php

declare(strict_types=1);

namespace DDDExample\App\Web\Controller;

use DDDExample\App\Web\WebController;
use DDDExample\Application\Command\CommandBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends WebController
{
    #[Route(
        path: '/game',
        name: 'game',
        requirements: [],
        methods: ['GET']
    )]
    public function getAction(CommandBus $commandBus): Response
    {
        return $this->renderResponse('game.html.twig');
    }
}
