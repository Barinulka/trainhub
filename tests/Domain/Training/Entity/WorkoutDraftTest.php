<?php

declare(strict_types=1);

namespace App\Tests\Domain\Training\Entity;

use App\Domain\Training\Entity\Exercise;
use App\Domain\Training\Entity\PlannedExercise;
use App\Domain\Training\Entity\WorkoutDraft;
use App\Domain\Training\ValueObject\RepetitionScheme;
use App\Domain\Training\ValueObject\TrainingDuration;
use App\Domain\Training\ValueObject\Weight;
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

    public function testNewDraftHasNoExercises(): void
    {
        $workoutDraft = $this->createWorkoutDraft();

        $this->assertSame([], $workoutDraft->exercises());
    }

    public function testAddsPlannedExercise(): void
    {
        $workoutDraft = $this->createWorkoutDraft();
        $plannedExercise = $this->createPlannedExercise();

        $workoutDraft->addExercise($plannedExercise);
        $actualExercises = $workoutDraft->exercises();

        $this->assertCount(1, $actualExercises);
        $this->assertSame($plannedExercise, $actualExercises[0]);
    }

    public function testKeepsExercisesInInsertionOrder(): void
    {
        $workoutDraft = $this->createWorkoutDraft();

        $first = $this->createPlannedExercise();
        $second = new PlannedExercise(
            new Exercise('Становая тяга'),
            RepetitionScheme::fromSetsAndRepetitionPerSet(4, 6),
            Weight::fromGrams(72_500),
        );

        $workoutDraft->addExercise($first);
        $workoutDraft->addExercise($second);

        $exercises = $workoutDraft->exercises();

        self::assertCount(2, $exercises);
        self::assertSame($first, $exercises[0]);
        self::assertSame($second, $exercises[1]);
    }

    public function testAllowsAddingSamePlannedExerciseTwice(): void
    {
        $workoutDraft = $this->createWorkoutDraft();
        $plannedExercise = $this->createPlannedExercise();

        $workoutDraft->addExercise($plannedExercise);
        $workoutDraft->addExercise($plannedExercise);

        $exercises = $workoutDraft->exercises();

        self::assertCount(2, $exercises);
        self::assertSame($plannedExercise, $exercises[0]);
        self::assertSame($plannedExercise, $exercises[1]);
    }

    public function testChangingReturnedArrayDoesNotChangeDraftExercises(): void
    {
        $workoutDraft = $this->createWorkoutDraft();
        $first = $this->createPlannedExercise();

        $workoutDraft->addExercise($first);

        $returnedExercises = $workoutDraft->exercises();

        $returnedExercises[] = new PlannedExercise(
            new Exercise('Становая тяга'),
            RepetitionScheme::fromSetsAndRepetitionPerSet(4, 6),
            Weight::fromGrams(72_500),
        );

        self::assertCount(2, $returnedExercises);
        self::assertCount(1, $workoutDraft->exercises());
        self::assertSame($first, $workoutDraft->exercises()[0]);
    }

    public function testEmptyDraftHasZeroTotalVolume(): void
    {
        $workoutDraft = $this->createWorkoutDraft();

        $totalVolume = $workoutDraft->totalVolumeInGrams();

        self::assertSame(0, $totalVolume);
    }

    public function testCalculatesTotalVolumeOfAllExercises(): void
    {
        $workoutDraft = $this->createWorkoutDraft();

        $first = new PlannedExercise(
            new Exercise('Приседания'),
            RepetitionScheme::fromSetsAndRepetitionPerSet(3, 10),
            Weight::fromGrams(72_500),
        );

        $second = new PlannedExercise(
            new Exercise('Становая тяга'),
            RepetitionScheme::fromSetsAndRepetitionPerSet(4, 6),
            Weight::fromGrams(50_000),
        );

        $workoutDraft->addExercise($first);
        $workoutDraft->addExercise($second);

        $totalVolume = $workoutDraft->totalVolumeInGrams();

        self::assertSame(3_375_000, $totalVolume);
    }

    private function createWorkoutDraft(): WorkoutDraft
    {
        $title = 'Тренировка ног';
        $duration = TrainingDuration::fromMinutes(10);

        return new WorkoutDraft($title, $duration);
    }

    private function createPlannedExercise(): PlannedExercise
    {
        return new PlannedExercise(
            new Exercise('Приседания', 'Описание'),
            RepetitionScheme::fromSetsAndRepetitionPerSet(10, 3),
            Weight::fromGrams(72_500),
        );
    }
}