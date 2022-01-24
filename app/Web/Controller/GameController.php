<?php

declare(strict_types=1);

namespace DDDExample\App\Web\Controller;

use DDDExample\App\Web\WebController;
use DDDExample\Application\Command\Game\GameRoll;
use DDDExample\Application\Query\Game\GameById;
use DDDExample\Application\Query\QueryBus;
use DDDExample\Domain\Game\GameId;
use DDDExample\Infrastructure\Bus\CommandBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GameController extends WebController
{
    #[Route(
        path: '/game/{id}',
        name: 'game',
        requirements: [],
        methods: ['GET']
    )]
    public function getAction(QueryBus $queryBus, string $id): Response
    {
        $game = $queryBus->ask(new GameById(GameId::create($id)));

        return $this->renderResponse(
            'game.html.twig',
            [
                'game' => $game,
            ]
        );
    }

    #[Route(
        path: '/game/{id}/roll/{knockedPins}',
        name: 'game-roll',
        requirements: [],
        methods: ['GET']
    )]
    public function rollAction(CommandBus $commandBus, string $id, int $knockedPins): Response
    {
        $commandBus->handle(new GameRoll(GameId::create($id), $knockedPins));

        return $this->redirect('web.game', ['id' => $id]);
    }
}
