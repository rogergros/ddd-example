<?php

declare(strict_types=1);

namespace DDDExample\App\Web\Controller;

use DDDExample\App\Web\WebController;
use DDDExample\Application\Query\BowlingAlley\BowlingAlleyById;
use DDDExample\Application\Query\Game\GamesByBowlingAlley;
use DDDExample\Application\Query\Game\GameView;
use DDDExample\Application\Query\QueryBus;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BowlingAlleyController extends WebController
{
    #[Route(
        path: '/bowling-alley/{id}',
        name: 'bowling-alley',
        requirements: [],
        methods: ['GET']
    )]
    public function getAction(string $id, QueryBus $queryBus): Response
    {
        $bowlingAlley = $queryBus->ask(new BowlingAlleyById(BowlingAlleyId::create($id)));
        $games = $queryBus->ask(new GamesByBowlingAlley(BowlingAlleyId::create($id)));

        return $this->renderResponse(
            'bowling-alley.html.twig',
            [
                'bowlingAlley' => $bowlingAlley,
                'laneGames' => $this->indexByLane($games),
            ]
        );
    }

    /**
     * @param list<GameView> $games
     * @param array<int,GameView>
     */
    private function indexByLane(array $games): array
    {
        $indexedGames = [];
        foreach ($games as $game) {
            $indexedGames[$game->lane] = $game;
        }

        return $indexedGames;
    }
}
