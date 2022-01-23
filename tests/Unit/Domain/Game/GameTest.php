<?php

declare(strict_types=1);

namespace DDDExample\Tests\Unit\Domain\Game;

use DateTimeImmutable;
use DDDExample\Domain\Game\Exception\UnexpectedNumberOfLanes;
use DDDExample\Domain\Game\Exception\UnexpectedNumberOfPlayers;
use DDDExample\Domain\Game\Game;
use DDDExample\Domain\Game\PlayerGame;
use DDDExample\Tests\AppTestCase;
use DDDExample\Tests\Mother\Domain\BowlingAlley\BowlingAlleyIdMother;
use DDDExample\Tests\Mother\Domain\Game\GameIdMother;
use DDDExample\Tests\Mother\PrimitiveMother;

class GameTest extends AppTestCase
{
    public function testGameMustHaveAtLeastOnePlayer(): void
    {
        $game = Game::create(
            GameIdMother::create(),
            BowlingAlleyIdMother::create(),
            PrimitiveMother::int(1, 10),
            1
        );

        $this->assertInstanceOf(Game::class, $game);
    }

    public function testGameCantHaveMoreThanTenLanes(): void
    {
        $this->expectException(UnexpectedNumberOfLanes::class);

        Game::create(
            GameIdMother::create(),
            BowlingAlleyIdMother::create(),
            PrimitiveMother::int(11),
            PrimitiveMother::int(1, 10)
        );
    }

    public function testGameCantHaveMoreThanTenPlayers(): void
    {
        $this->expectException(UnexpectedNumberOfPlayers::class);

        Game::create(
            GameIdMother::create(),
            BowlingAlleyIdMother::create(),
            PrimitiveMother::int(1, 10),
            PrimitiveMother::int(11)
        );
    }

    public function testOnNewGameFirstPlayerShouldStart(): void
    {
        $game = $this->givenGameWithPlayerGames(
            [
                $this->playerGameOnFrame(1, 1),
                $this->playerGameOnFrame(2, 1),
                $this->playerGameOnFrame(3, 1),
            ]
        );

        $nextPlayer = $game->nextPlayer();
        $this->assertEquals(1, $nextPlayer);
    }

    public function testStartDateIsCreationTime(): void
    {
        $now = new DateTimeImmutable();

        $game = Game::create(
            GameIdMother::create(),
            BowlingAlleyIdMother::create(),
            PrimitiveMother::int(1, 10),
            PrimitiveMother::int(1, 10)
        );

        self::assertDateTimeSimilar($now, $game->startedAt());
    }

    public function testNextPlayerShouldBeTheOneWithTheLowestFrame(): void
    {
        $game = $this->givenGameWithPlayerGames(
            [
                $this->playerGameOnFrame(1, 3),
                $this->playerGameOnFrame(2, 3),
                $this->playerGameOnFrame(3, 2),
            ]
        );

        $nextPlayer = $game->nextPlayer();
        $this->assertEquals(3, $nextPlayer);
    }

    public function testNextPlayerShouldBeTheLowestOneWhenMultipleOnLowestFrame(): void
    {
        $game = $this->givenGameWithPlayerGames(
            [
                $this->playerGameOnFrame(1, 3),
                $this->playerGameOnFrame(2, 2),
                $this->playerGameOnFrame(3, 2),
            ]
        );

        $nextPlayer = $game->nextPlayer();
        $this->assertEquals(2, $nextPlayer);
    }

    public function testNextRollShouldBeTheOneWithLowestFrame(): void
    {
        $expectedNextRollGame = $this->playerGameOnFrame(3, 2, true);

        $game = $this->givenGameWithPlayerGames(
            [
                $this->playerGameOnFrame(1, 3),
                $this->playerGameOnFrame(2, 3),
                $expectedNextRollGame,
            ]
        );

        $game->roll(5);
        $this->assertEquals(3, $expectedNextRollGame->frame());
    }

    public function testNextRollShouldBeTheLowestPlayerWhenMultipleOnLowestFrame(): void
    {
        $expectedNextRollGame = $this->playerGameOnFrame(2, 2, true);

        $game = $this->givenGameWithPlayerGames(
            [
                $this->playerGameOnFrame(1, 3),
                $expectedNextRollGame,
                $this->playerGameOnFrame(3, 2),
            ]
        );

        $game->roll(5);
        $this->assertEquals(3, $expectedNextRollGame->frame());
    }

    public function testBasicGettersReturnCorrectData(): void
    {
        $gameId = GameIdMother::create();
        $bowlingAlleyId = BowlingAlleyIdMother::create();
        $lane = PrimitiveMother::int(1, 10);
        $numberOfPlayers = PrimitiveMother::int(1, 10);

        $game = Game::create($gameId, $bowlingAlleyId, $lane, $numberOfPlayers);

        $this->assertEquals($gameId, $game->gameId());
        $this->assertEquals($bowlingAlleyId, $game->bowlingAlleyId());
        $this->assertEquals($lane, $game->lane());
        $this->assertEquals($numberOfPlayers, $game->numberOfPlayers());
    }

    /** Helper methods */

    /**
     * @param list<PlayerGame> $playerGames
     */
    private function givenGameWithPlayerGames(array $playerGames): Game
    {
        return new Game(
            GameIdMother::create(),
            BowlingAlleyIdMother::create(),
            PrimitiveMother::int(1, 10),
            $playerGames,
            new DateTimeImmutable()
        );
    }

    private function playerGameOnFrame(int $player, int $frame, bool $rollAlreadyOnFrame = false): PlayerGame
    {
        $subOnFrame = $rollAlreadyOnFrame ? 1 : 2;

        return new PlayerGame(
            GameIdMother::create(),
            $player,
            array_fill(0, ($frame * 2) - $subOnFrame, 1)
        );
    }
}
