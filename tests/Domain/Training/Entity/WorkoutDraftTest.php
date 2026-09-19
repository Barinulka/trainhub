<?php

declare(strict_types=1);

namespace App\Tests\Domain\Training\Entity;

use App\Domain\Training\Entity\WorkoutDraft;
use App\Domain\Training\ValueObject\TrainingDuration;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class WorkoutDraftTest extends TestCase
{
    public function testCreatesDraftWithTrimmedTitleAndDuration(): void
    {
        $title = ' Тренировка ног ';
        $duration = TrainingDuration::fromMinutes(10);

        $workoutDraft = new WorkoutDraft($title, $duration);

        $workoutTitle = $workoutDraft->title();
        $workoutDuration = $workoutDraft->duration();

        $this->assertSame('Тренировка ног', $workoutTitle);
        $this->assertSame(10, $workoutDuration->minutes());
    }

    public function testRejectsEmptyTitleOnCreation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new WorkoutDraft('', TrainingDuration::fromMinutes(10));
    }

    public function testRejectsWhitespaceOnlyTitleOnCreation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new WorkoutDraft('   ', TrainingDuration::fromMinutes(10));
    }

    public function testRenamesWorkoutTitle(): void
    {
        $workoutDraft = $this->createWorkoutDraft();

        $workoutDraft->rename(' Силовая ');

        $this->assertSame('Силовая', $workoutDraft->title());
    }

    public function testRejectsWhitespaceOnlyTitleOnRenameAndPreservesPreviousTitle(): void
    {
        $workoutDraft = $this->createWorkoutDraft();
        $exceptionCaught = false;

        try {
            $workoutDraft->rename('   ');
        } catch (InvalidArgumentException $e) {
            $exceptionCaught = true;
        }

        $this->assertTrue($exceptionCaught);
        $this->assertSame('Тренировка ног', $workoutDraft->title());
    }

    public function testRejectsEmptyTitleOnRenameAndPreservesPreviousTitle(): void
    {
        $workoutDraft = $this->createWorkoutDraft();
        $exceptionCaught = false;

        try {
            $workoutDraft->rename('');
        } catch (InvalidArgumentException $e) {
            $exceptionCaught = true;
        }

        $this->assertTrue($exceptionCaught);
        $this->assertSame('Тренировка ног', $workoutDraft->title());
    }

    public function testChangesDurationWithoutModifyingPreviousDuration(): void
    {
        $workoutDraft = $this->createWorkoutDraft();
        $previousDuration = $workoutDraft->duration();

        $workoutDraft->changeDuration(TrainingDuration::fromMinutes(20));

        $this->assertSame(20, $workoutDraft->duration()->minutes());
        $this->assertSame(10, $previousDuration->minutes());
    }

    private function createWorkoutDraft(): WorkoutDraft
    {
        $title = 'Тренировка ног';
        $duration = TrainingDuration::fromMinutes(10);

        return new WorkoutDraft($title, $duration);
    }
}