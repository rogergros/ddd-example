<?php

declare(strict_types=1);

namespace DDDExample\App\Web\Controller;

use DDDExample\App\Web\WebController;
use DDDExample\Application\Command\CommandBus;
use DDDExample\Application\Command\Game\CreateGame;
use DDDExample\Application\Query\BowlingAlley\BowlingAlleyById;
use DDDExample\Application\Query\QueryBus;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\Game\GameId;
use DDDExample\Shared\Extractor\PrimitiveExtractor;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateGameController extends WebController
{
    #[Route(
        path: 'create-game/{bowlingAlleyId}/{lane}',
        name: 'create-game',
        requirements: [],
        methods: ['GET']
    )]
    public function getAction(QueryBus $queryBus, string $bowlingAlleyId, int $lane): Response
    {
        $bowlingAlley = $queryBus->ask(new BowlingAlleyById(BowlingAlleyId::create($bowlingAlleyId)));

        return $this->renderResponse('create-game.html.twig', [
            'bowlingAlley' => $bowlingAlley,
            'lane' => $lane,
        ]);
    }

    #[Route(
        path: 'create-game/{bowlingAlleyId}/{lane}',
        name: 'create-game-post',
        requirements: [],
        methods: ['POST']
    )]
    public function postAction(
        CommandBus $commandBus,
        string $bowlingAlleyId,
        int $lane,
        PrimitiveExtractor $post
    ): Response {
        $gameId = GameId::createRandom();
        $commandBus->handle(
            new CreateGame(
                $gameId,
                BowlingAlleyId::create($bowlingAlleyId),
                $lane,
                (int)$post->string('numberOfPlayers')
            )
        );

        return $this->redirect('web.game', ['id' => $gameId->value()]);
    }
}
