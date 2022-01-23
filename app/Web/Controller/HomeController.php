<?php

declare(strict_types=1);

namespace DDDExample\App\Web\Controller;

use DDDExample\App\Web\WebController;
use DDDExample\Application\Query\BowlingAlley\AllBowlingAlleys;
use DDDExample\Application\Query\QueryBus;
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
    public function getAction(QueryBus $queryBus): Response
    {
        $bowlingAlleys = $queryBus->ask(new AllBowlingAlleys());

        return $this->renderResponse('home.html.twig', [
            'bowlingAlleys' => $bowlingAlleys,
        ]);
    }
}
