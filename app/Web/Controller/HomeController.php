<?php

declare(strict_types=1);

namespace DDDExample\App\Web\Controller;

use DDDExample\App\Web\WebController;
use DDDExample\Application\CommandBus;
use DDDExample\Domain\Game\Game;
use DDDExample\Tests\Mother\Domain\BowlingAlley\BowlingAlleyIdMother;
use DDDExample\Tests\Mother\Domain\Game\GameIdMother;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends WebController
{
    #[Route(
        path: '/',
        name: 'home',
        requirements: [],
        methods: ['GET']
    )]
    public function getAction(CommandBus $commandBus): Response
    {
        return $this->renderResponse('home.html.twig');
    }
}
