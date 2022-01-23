<?php

declare(strict_types=1);

namespace DDDExample\Tests\Unit\Domain\Game;

use DDDExample\Domain\Game\PlayerGame;
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
        $this->thenScoreboardShouldBe([null, null, null, null, null, null, null, null, null, null]);
        $this->thenFrameIs(1);
        $this->thenGameIsNotFinished();
    }

    public function testScoreboardWithOneRollAndNoMark(): void
    {
        $this->givenRolls([5]);
        $this->thenScoreboardShouldBe([null, null, null, null, null, null, null, null, null, null]);
        $this->thenFrameIs(1);
        $this->thenGameIsNotFinished();
    }

    public function testScoreboardWithTwoRollsAndNoMark(): void
    {
        $this->givenRolls([3, 4]);
        $this->thenScoreboardShouldBe([7, null, null, null, null, null, null, null, null, null]);

        $this->thenFrameIs(2);
        $this->thenGameIsNotFinished();
    }

    public function testScoreboardWithFourRollsAndNoMarks(): void
    {
        $this->givenRolls([5, 4, 7, 1]);
        $this->thenScoreboardShouldBe([9, 17, null, null, null, null, null, null, null, null]);
        $this->thenFrameIs(3);
        $this->thenGameIsNotFinished();
    }

    public function testSpareOnFirstFrame(): void
    {
        $this->givenRolls([5, 5, 3, 2]);
        $this->thenScoreboardShouldBe([13, 18, null, null, null, null, null, null, null, null]);
        $this->thenFrameIs(3);
        $this->thenGameIsNotFinished();
    }

    public function testSpareOnMultipleFrames(): void
    {
        $this->givenRolls([5, 2, 4, 6, 3, 7, 1, 4]);
        $this->thenScoreboardShouldBe([7, 20, 31, 36, null, null, null, null, null, null]);
        $this->thenFrameIs(5);
        $this->thenGameIsNotFinished();
    }

    public function testStrikeOnFirstFrame(): void
    {
        $this->givenRolls([10, 5, 2]);
        $this->thenScoreboardShouldBe([17, 24, null, null, null, null, null, null, null, null]);
        $this->thenFrameIs(3);
        $this->thenGameIsNotFinished();
    }

    public function testStrikeOnMultipleFrames(): void
    {
        $this->givenRolls([7, 1, 10, 5, 3, 10, 3, 5]);
        $this->thenScoreboardShouldBe([8, 26, 34, 52, 60, null, null, null, null, null]);
        $this->thenFrameIs(6);
        $this->thenGameIsNotFinished();
    }

    public function testTwoConsecutiveStrikes(): void
    {
        $this->givenRolls([8, 1, 10, 10, 7, 1]);
        $this->thenScoreboardShouldBe([9, 36, 54, 62, null, null, null, null, null, null]);
        $this->thenFrameIs(5);
        $this->thenGameIsNotFinished();
    }

    public function testPerfectGame(): void
    {
        $this->givenRolls([10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10]);
        $this->thenScoreboardShouldBe([30, 60, 90, 120, 150, 180, 210, 240, 270, 300]);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
    }

    public function testNearlyPerfectGame(): void
    {
        $this->givenRolls([10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 9]);
        $this->thenScoreboardShouldBe([30, 60, 90, 120, 150, 180, 210, 240, 270, 299]);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
    }

    public function testAllPinsKnockedOnSecondFrameRollIsSpare(): void
    {
        $this->givenRolls([5, 4, 0, 10, 4, 2]);
        $this->thenScoreboardShouldBe([9, 23, 29, null, null, null, null, null, null, null]);
        $this->thenFrameIs(4);
        $this->thenGameIsNotFinished();
    }

    public function testSampleGame(): void
    {
        $this->givenRolls([1, 4, 4, 5, 6, 4, 5, 5, 10, 0, 1, 7, 3, 6, 4, 10, 4, 2]);
        $this->thenScoreboardShouldBe([5, 14, 29, 49, 60, 61, 77, 97, 113, 119]);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
    }

    public function testTenthFrameSpare(): void
    {
        $this->givenRolls([1, 4, 4, 5, 6, 4, 5, 5, 10, 0, 1, 7, 3, 6, 4, 10, 2, 8, 6]);
        $this->thenScoreboardShouldBe([5, 14, 29, 49, 60, 61, 77, 97, 117, 133]);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
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

    /**
     * @param list<?int> $expectedScoreboard
     */
    private function thenScoreboardShouldBe(array $expectedScoreboard): void
    {
        $scoreboard = $this->playerGame->scoreboard();

        $this->assertEquals($expectedScoreboard, $scoreboard);
    }

    private function thenGameIsFinished(): void
    {
        $this->assertTrue($this->playerGame->isFinished());
    }

    private function thenGameIsNotFinished(): void
    {
        $this->assertFalse($this->playerGame->isFinished());
    }

    private function thenFrameIs(int $expectedFrame): void
    {
        $this->assertEquals($expectedFrame, $this->playerGame->frame());
    }
}