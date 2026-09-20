<?php

declare(strict_types=1);

namespace App\Tests\Domain\Training\Entity;

use App\Domain\Training\Entity\Exercise;
use App\Domain\Training\Entity\PlannedExercise;
use App\Domain\Training\ValueObject\RepetitionScheme;
use PHPUnit\Framework\TestCase;

class PlannedExerciseTest extends TestCase
{
    public function testStoresExerciseAndScheme(): void
    {
        $exercise = $this->createExercise();
        $scheme = $this->createRepetitionScheme();

        $plannedExercise = new PlannedExercise($exercise, $scheme,);

        self::assertSame($exercise, $plannedExercise->exercise());
        self::assertSame($scheme, $plannedExercise->scheme());
    }

    public function testKeepsSeparateSchemesForSameExercise(): void
    {
        $exercise = $this->createExercise();

        $first = new PlannedExercise(
            $exercise,
            RepetitionScheme::fromSetsAndRepetitionPerSet(3, 10),
        );

        $second = new PlannedExercise(
            $exercise,
            RepetitionScheme::fromSetsAndRepetitionPerSet(4, 6),
        );

        self::assertSame($exercise, $first->exercise());
        self::assertSame($exercise, $second->exercise());

        self::assertSame(3, $first->scheme()->sets());
        self::assertSame(10, $first->scheme()->repetitionsPerSet());

        self::assertSame(4, $second->scheme()->sets());
        self::assertSame(6, $second->scheme()->repetitionsPerSet());
    }

    public function testReflectsSharedExerciseRename(): void
    {
        $exercise = $this->createExercise();

        $first = new PlannedExercise(
            $exercise,
            RepetitionScheme::fromSetsAndRepetitionPerSet(3, 10),
        );

        $second = new PlannedExercise(
            $exercise,
            RepetitionScheme::fromSetsAndRepetitionPerSet(4, 6),
        );

        $exercise->rename('Приседание со штангой');

        self::assertSame('Приседание со штангой', $first->exercise()->name());

        self::assertSame('Приседание со штангой', $second->exercise()->name());
    }

    private function createExercise(): Exercise
    {
        return new Exercise('Приседания', 'Техника');
    }

    private function createRepetitionScheme(): RepetitionScheme
    {
        return RepetitionScheme::fromSetsAndRepetitionPerSet(3, 10);
    }
}
