<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

use DDDExample\Domain\BowlingAlley\BowlingAlleyRepository;
use DDDExample\Domain\Game\Game;
use DDDExample\Domain\Game\PlayerGameState;

use function Lambdish\Phunctional\map;

final class GameViewAssembler
{
    public function __construct(
        private BowlingAlleyRepository $bowlingAlleyRepository
    ) {
    }

    public function assemble(Game $game): GameView
    {
        $bowlingAlley = $this->bowlingAlleyRepository->byId($game->bowlingAlleyId());

        return new GameView(
            $game->gameId()->value(),
            $bowlingAlley->name()->value(),
            $bowlingAlley->id()->value(),
            $game->lane(),
            $game->numberOfPlayers(),
            $game->frame(),
            $game->nextPlayer(),
            $game->isFinished(),
            $game->minutesElapsed(),
            $game->startedAt()->getTimestamp(),
            $this->assemblePlayerGameStates($game),
        );
    }

    /**
     * @param list<Game> $games
     * @return list<GameView>
     */
    public function assembleMultiple(array $games): array
    {
        /** @var list<GameView> $assembledViews */
        $assembledViews = map(
            fn(Game $game): GameView => $this->assemble($game),
            $games
        );

        return $assembledViews;
    }

    /**
     * @return array<int,PlayerGameStateView>
     */
    private function assemblePlayerGameStates(Game $game): array
    {
        $assembled = [];
        $playerGameStates = $game->playerGameStates();
        foreach ($playerGameStates as $player => $playerGameState) {
            $assembled[$player] = $this->assemblePlayerGameState($playerGameState);
        }

        return $assembled;
    }

    private function assemblePlayerGameState(PlayerGameState $playerGameState): PlayerGameStateView
    {
        $assembledFrames = [];
        foreach ($playerGameState->frames() as $frame => $playerGameFrameState) {
            $assembledFrames[$frame] = new GameFrameStateView(
                $playerGameFrameState->firstRoll(),
                $playerGameFrameState->secondRoll(),
                $playerGameFrameState->thirdRoll(),
                $playerGameFrameState->score(),
            );
        }

        return new PlayerGameStateView(
            $assembledFrames,
            $playerGameState->currentFrame(),
            $playerGameState->isFinished(),
            $playerGameState->totalScore()
        );
    }
}
