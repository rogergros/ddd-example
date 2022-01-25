<?php

declare(strict_types=1);

namespace DDDExample\Tests\Unit\Domain\Game;

use DDDExample\Domain\Game\PlayerGame;
use DDDExample\Domain\Game\PlayerGameFrameState;
use DDDExample\Domain\Game\PlayerGameState;
use DDDExample\Tests\AppTestCase;
use DDDExample\Tests\Mother\Domain\Game\GameIdMother;
use DDDExample\Tests\Mother\PrimitiveMother;

class PlayerGameTest extends AppTestCase
{
    private PlayerGame $playerGame;

    public function setUp(): void
    {
        $this->playerGame = PlayerGame::create(
            GameIdMother::create(),
            PrimitiveMother::int(1, 10),
        );
    }

    public function testStaticGetters(): void
    {
        $gameId = GameIdMother::create();
        $player = PrimitiveMother::int(1, 10);

        $playerGame = PlayerGame::create($gameId, $player);

        $this->assertEquals($gameId, $playerGame->gameId());
        $this->assertEquals($player, $playerGame->player());
    }

    public function testScoreboardWithNoRolls(): void
    {
        $this->thenTotalScoreShouldBe(null);
        $this->thenFrameIs(1);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe([], 1, false, 0);
    }

    public function testScoreboardWithOneRollAndNoMark(): void
    {
        $this->givenRolls([5]);
        $this->thenTotalScoreShouldBe(null);
        $this->thenFrameIs(1);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 5],
            ],
            1,
            false,
            0
        );
    }

    public function testScoreboardWithTwoRollsAndNoMark(): void
    {
        $this->givenRolls([3, 4]);
        $this->thenTotalScoreShouldBe(7);

        $this->thenFrameIs(2);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 3, 'roll2' => 4, 'score' => 7],
            ],
            2,
            false,
            7
        );
    }

    public function testScoreboardWithFourRollsAndNoMarks(): void
    {
        $this->givenRolls([5, 4, 7, 1]);
        $this->thenTotalScoreShouldBe(17);
        $this->thenFrameIs(3);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 5, 'roll2' => 4, 'score' => 9],
                ['roll1' => 7, 'roll2' => 1, 'score' => 17],
            ],
            3,
            false,
            17
        );
    }

    public function testSpareOnFirstFrame(): void
    {
        $this->givenRolls([5, 5, 3, 2]);
        $this->thenTotalScoreShouldBe(18);
        $this->thenFrameIs(3);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 5, 'roll2' => 5, 'score' => 13],
                ['roll1' => 3, 'roll2' => 2, 'score' => 18],
            ],
            3,
            false,
            18
        );
    }

    public function testSpareOnMultipleFrames(): void
    {
        $this->givenRolls([5, 2, 4, 6, 3, 7, 1, 4]);
        $this->thenTotalScoreShouldBe(36);
        $this->thenFrameIs(5);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 5, 'roll2' => 2, 'score' => 7],
                ['roll1' => 4, 'roll2' => 6, 'score' => 20],
                ['roll1' => 3, 'roll2' => 7, 'score' => 31],
                ['roll1' => 1, 'roll2' => 4, 'score' => 36],
            ],
            5,
            false,
            36
        );
    }

    public function testStrikeOnFirstFrame(): void
    {
        $this->givenRolls([10, 5, 2]);
        $this->thenTotalScoreShouldBe(24);
        $this->thenFrameIs(3);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll2' => 10, 'score' => 17],
                ['roll1' => 5, 'roll2' => 2, 'score' => 24],
            ],
            3,
            false,
            24
        );
    }

    public function testStrikeOnMultipleFrames(): void
    {
        $this->givenRolls([7, 1, 10, 5, 3, 10, 3, 5]);
        $this->thenTotalScoreShouldBe(60);
        $this->thenFrameIs(6);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 7, 'roll2' => 1, 'score' => 8],
                ['roll2' => 10, 'score' => 26],
                ['roll1' => 5, 'roll2' => 3, 'score' => 34],
                ['roll2' => 10, 'score' => 52],
                ['roll1' => 3, 'roll2' => 5, 'score' => 60],
            ],
            6,
            false,
            60
        );
    }

    public function testTwoConsecutiveStrikes(): void
    {
        $this->givenRolls([8, 1, 10, 10, 7, 1]);
        $this->thenTotalScoreShouldBe(62);
        $this->thenFrameIs(5);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 8, 'roll2' => 1, 'score' => 9],
                ['roll2' => 10, 'score' => 36],
                ['roll2' => 10, 'score' => 54],
                ['roll1' => 7, 'roll2' => 1, 'score' => 62],
            ],
            5,
            false,
            62
        );
    }

    public function testPerfectGame(): void
    {
        $this->givenRolls([10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10]);
        $this->thenTotalScoreShouldBe(300);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll2' => 10, 'score' => 30],
                ['roll2' => 10, 'score' => 60],
                ['roll2' => 10, 'score' => 90],
                ['roll2' => 10, 'score' => 120],
                ['roll2' => 10, 'score' => 150],
                ['roll2' => 10, 'score' => 180],
                ['roll2' => 10, 'score' => 210],
                ['roll2' => 10, 'score' => 240],
                ['roll2' => 10, 'score' => 270],
                ['roll1' => 10, 'roll2' => 10, 'roll3' => 10, 'score' => 300],
            ],
            10,
            true,
            300
        );
    }

    public function testNearlyPerfectGame(): void
    {
        $this->givenRolls([10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 9]);
        $this->thenTotalScoreShouldBe(299);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll2' => 10, 'score' => 30],
                ['roll2' => 10, 'score' => 60],
                ['roll2' => 10, 'score' => 90],
                ['roll2' => 10, 'score' => 120],
                ['roll2' => 10, 'score' => 150],
                ['roll2' => 10, 'score' => 180],
                ['roll2' => 10, 'score' => 210],
                ['roll2' => 10, 'score' => 240],
                ['roll2' => 10, 'score' => 270],
                ['roll1' => 10, 'roll2' => 10, 'roll3' => 9, 'score' => 299],
            ],
            10,
            true,
            299
        );
    }

    public function testAllPinsKnockedOnSecondFrameRollIsSpare(): void
    {
        $this->givenRolls([5, 4, 0, 10, 4, 2]);
        $this->thenTotalScoreShouldBe(29);
        $this->thenFrameIs(4);
        $this->thenGameIsNotFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 5, 'roll2' => 4, 'score' => 9],
                ['roll1' => 0, 'roll2' => 10, 'score' => 23],
                ['roll1' => 4, 'roll2' => 2, 'score' => 29],
            ],
            4,
            false,
            29
        );
    }

    public function testSampleGame(): void
    {
        $this->givenRolls([1, 4, 4, 5, 6, 4, 5, 5, 10, 0, 1, 7, 3, 6, 4, 10, 4, 2]);
        $this->thenTotalScoreShouldBe(119);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 1, 'roll2' => 4, 'score' => 5],
                ['roll1' => 4, 'roll2' => 5, 'score' => 14],
                ['roll1' => 6, 'roll2' => 4, 'score' => 29],
                ['roll1' => 5, 'roll2' => 5, 'score' => 49],
                ['roll2' => 10, 'score' => 60],
                ['roll1' => 0, 'roll2' => 1, 'score' => 61],
                ['roll1' => 7, 'roll2' => 3, 'score' => 77],
                ['roll1' => 6, 'roll2' => 4, 'score' => 97],
                ['roll2' => 10, 'score' => 113],
                ['roll1' => 4, 'roll2' => 2, 'score' => 119],
            ],
            10,
            true,
            119
        );
    }

    public function testTenthFrameSpare(): void
    {
        $this->givenRolls([1, 4, 4, 5, 6, 4, 5, 5, 10, 0, 1, 7, 3, 6, 4, 10, 2, 8, 6]);
        $this->thenTotalScoreShouldBe(133);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
        $this->thenGameStateShouldBe(
            [
                ['roll1' => 1, 'roll2' => 4, 'score' => 5],
                ['roll1' => 4, 'roll2' => 5, 'score' => 14],
                ['roll1' => 6, 'roll2' => 4, 'score' => 29],
                ['roll1' => 5, 'roll2' => 5, 'score' => 49],
                ['roll2' => 10, 'score' => 60],
                ['roll1' => 0, 'roll2' => 1, 'score' => 61],
                ['roll1' => 7, 'roll2' => 3, 'score' => 77],
                ['roll1' => 6, 'roll2' => 4, 'score' => 97],
                ['roll2' => 10, 'score' => 117],
                ['roll1' => 2, 'roll2' => 8, 'roll3' => 6, 'score' => 133],
            ],
            10,
            true,
            133
        );
    }

    /** Helper methods */

    /**
     * @param list<int> $rolls
     */
    private function givenRolls(array $rolls): void
    {
        foreach ($rolls as $roll) {
            $this->playerGame->roll($roll);
        }
    }

    private function thenTotalScoreShouldBe(?int $expectedTotalScore): void
    {
        $totalScore = $this->playerGame->totalScore();

        $this->assertEquals($expectedTotalScore, $totalScore, "Total score should be $expectedTotalScore");
    }

    private function thenGameIsFinished(): void
    {
        $this->assertTrue($this->playerGame->isFinished(), 'Game should be finished');
    }

    private function thenGameIsNotFinished(): void
    {
        $this->assertFalse($this->playerGame->isFinished(), 'Game should not be finished');
    }

    private function thenFrameIs(int $expectedFrame): void
    {
        $this->assertEquals($expectedFrame, $this->playerGame->frame(), "Game frame should be $expectedFrame");
    }

    /**
     * @param array<int,array{roll1?: int|null, roll2?: int|null, roll3?: int|null, score?: int|null}> $frameStates
     */
    private function thenGameStateShouldBe(
        array $frameStates,
        int $currentFrame,
        bool $isFinished,
        int $totalScore
    ): void {
        $frames = [];
        for ($frameNumber = 1; $frameNumber <= 10; ++$frameNumber) {
            $expectedFrameState = $frameStates[$frameNumber - 1] ?? [];

            $frames[$frameNumber] = new PlayerGameFrameState(
                $expectedFrameState['roll1'] ?? null,
                $expectedFrameState['roll2'] ?? null,
                $expectedFrameState['roll3'] ?? null,
                $expectedFrameState['score'] ?? null
            );
        }

        $expectedGameState = new PlayerGameState(
            $frames,
            $currentFrame,
            $isFinished,
            $totalScore
        );

        $this->assertEquals($expectedGameState, $this->playerGame->state(), 'Unexpected game state');
    }
}
