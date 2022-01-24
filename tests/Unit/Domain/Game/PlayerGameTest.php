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
        $this->thenTotalScoreShouldBe(null);
        $this->thenFrameIs(1);
        $this->thenGameIsNotFinished();
    }

    public function testScoreboardWithOneRollAndNoMark(): void
    {
        $this->givenRolls([5]);
        $this->thenTotalScoreShouldBe(null);
        $this->thenFrameIs(1);
        $this->thenGameIsNotFinished();
    }

    public function testScoreboardWithTwoRollsAndNoMark(): void
    {
        $this->givenRolls([3, 4]);
        $this->thenTotalScoreShouldBe(7);

        $this->thenFrameIs(2);
        $this->thenGameIsNotFinished();
    }

    public function testScoreboardWithFourRollsAndNoMarks(): void
    {
        $this->givenRolls([5, 4, 7, 1]);
        $this->thenTotalScoreShouldBe(17);
        $this->thenFrameIs(3);
        $this->thenGameIsNotFinished();
    }

    public function testSpareOnFirstFrame(): void
    {
        $this->givenRolls([5, 5, 3, 2]);
        $this->thenTotalScoreShouldBe(18);
        $this->thenFrameIs(3);
        $this->thenGameIsNotFinished();
    }

    public function testSpareOnMultipleFrames(): void
    {
        $this->givenRolls([5, 2, 4, 6, 3, 7, 1, 4]);
        $this->thenTotalScoreShouldBe(36);
        $this->thenFrameIs(5);
        $this->thenGameIsNotFinished();
    }

    public function testStrikeOnFirstFrame(): void
    {
        $this->givenRolls([10, 5, 2]);
        $this->thenTotalScoreShouldBe(24);
        $this->thenFrameIs(3);
        $this->thenGameIsNotFinished();
    }

    public function testStrikeOnMultipleFrames(): void
    {
        $this->givenRolls([7, 1, 10, 5, 3, 10, 3, 5]);
        $this->thenTotalScoreShouldBe(60);
        $this->thenFrameIs(6);
        $this->thenGameIsNotFinished();
    }

    public function testTwoConsecutiveStrikes(): void
    {
        $this->givenRolls([8, 1, 10, 10, 7, 1]);
        $this->thenTotalScoreShouldBe(62);
        $this->thenFrameIs(5);
        $this->thenGameIsNotFinished();
    }

    public function testPerfectGame(): void
    {
        $this->givenRolls([10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10]);
        $this->thenTotalScoreShouldBe(300);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
    }

    public function testNearlyPerfectGame(): void
    {
        $this->givenRolls([10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 9]);
        $this->thenTotalScoreShouldBe(299);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
    }

    public function testAllPinsKnockedOnSecondFrameRollIsSpare(): void
    {
        $this->givenRolls([5, 4, 0, 10, 4, 2]);
        $this->thenTotalScoreShouldBe(29);
        $this->thenFrameIs(4);
        $this->thenGameIsNotFinished();
    }

    public function testSampleGame(): void
    {
        $this->givenRolls([1, 4, 4, 5, 6, 4, 5, 5, 10, 0, 1, 7, 3, 6, 4, 10, 4, 2]);
        $this->thenTotalScoreShouldBe(119);
        $this->thenFrameIs(10);
        $this->thenGameIsFinished();
    }

    public function testTenthFrameSpare(): void
    {
        $this->givenRolls([1, 4, 4, 5, 6, 4, 5, 5, 10, 0, 1, 7, 3, 6, 4, 10, 2, 8, 6]);
        $this->thenTotalScoreShouldBe(133);
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
}
