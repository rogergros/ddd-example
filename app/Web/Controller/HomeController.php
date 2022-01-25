<?php

declare(strict_types=1);

namespace DDDExample\App\Web\Controller;

use DDDExample\App\Web\WebController;
use DDDExample\Application\Query\BowlingAlley\AllBowlingAlleys;
use DDDExample\Application\Query\Game\FinishedGames;
use DDDExample\Application\Query\Game\NotFinishedGames;
use DDDExample\Application\Query\QueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends WebController
{
    #[Route(
        path: '/',
        name: 'home',
        requirements: [],
        methods: ['GET']
    )]
    public function getAction(QueryBus $queryBus): Response
    {
        $bowlingAlleys = $queryBus->ask(new AllBowlingAlleys());
        $currentGames = $queryBus->ask(new NotFinishedGames());
        $finishedGames = $queryBus->ask(new FinishedGames());

        return $this->renderResponse(
            'home.html.twig',
            [
                'bowlingAlleys' => $bowlingAlleys,
                'currentGames' => $currentGames,
                'finishedGames' => $finishedGames,
            ]
        );
    }
}
