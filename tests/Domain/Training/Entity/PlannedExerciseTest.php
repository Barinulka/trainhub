<?php

declare(strict_types=1);

namespace App\Tests\Domain\Training\Entity;

use App\Domain\Training\Entity\Exercise;
use App\Domain\Training\Entity\PlannedExercise;
use App\Domain\Training\ValueObject\RepetitionScheme;
use App\Domain\Training\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class PlannedExerciseTest extends TestCase
{
    public function testStoresExerciseSchemeAndWeight(): void
    {
        $exercise = $this->createExercise();
        $scheme = $this->createRepetitionScheme();
        $weight = $this->createWeight();

        $plannedExercise = new PlannedExercise($exercise, $scheme, $weight);

        self::assertSame($exercise, $plannedExercise->exercise());
        self::assertSame($scheme, $plannedExercise->scheme());
        self::assertSame($weight, $plannedExercise->weight());
    }

    public function testKeepsSeparateSchemesForSameExercise(): void
    {
        $exercise = $this->createExercise();

        $first = new PlannedExercise(
            $exercise,
            RepetitionScheme::fromSetsAndRepetitionPerSet(3, 10),
            Weight::fromGrams(72_500),
        );

        $second = new PlannedExercise(
            $exercise,
            RepetitionScheme::fromSetsAndRepetitionPerSet(4, 6),
            Weight::fromGrams(72_000),
        );

        self::assertSame($exercise, $first->exercise());
        self::assertSame($exercise, $second->exercise());

        self::assertSame(3, $first->scheme()->sets());
        self::assertSame(10, $first->scheme()->repetitionsPerSet());

        self::assertSame(4, $second->scheme()->sets());
        self::assertSame(6, $second->scheme()->repetitionsPerSet());

        self::assertSame(72500, $first->weight()->grams());
        self::assertSame(72000, $second->weight()->grams());
    }

    public function testReflectsSharedExerciseRename(): void
    {
        $exercise = $this->createExercise();

        $first = new PlannedExercise(
            $exercise,
            RepetitionScheme::fromSetsAndRepetitionPerSet(3, 10),
            Weight::fromGrams(72_500),
        );

        $second = new PlannedExercise(
            $exercise,
            RepetitionScheme::fromSetsAndRepetitionPerSet(4, 6),
            Weight::fromGrams(72_500),
        );

        $exercise->rename('Приседание со штангой');

        self::assertSame('Приседание со штангой', $first->exercise()->name());

        self::assertSame('Приседание со штангой', $second->exercise()->name());
    }

    public function testCalculatesVolumeInGrams(): void
    {
        $exercise = $this->createExercise();
        $scheme = $this->createRepetitionScheme();
        $weight = $this->createWeight();

        $plannedExercise = new PlannedExercise($exercise, $scheme, $weight);

        $volumeInGrams = $plannedExercise->volumeInGrams();

        self::assertSame(2_175_000, $volumeInGrams);
    }

    public function testCalculatesZeroVolumeForZeroWeight(): void
    {
        $exercise = $this->createExercise();
        $scheme = $this->createRepetitionScheme();
        $weight = Weight::fromGrams(0);

        $plannedExercise = new PlannedExercise($exercise, $scheme, $weight);

        $volumeInGrams = $plannedExercise->volumeInGrams();

        self::assertSame(0, $volumeInGrams);
    }

    private function createExercise(): Exercise
    {
        return new Exercise('Приседания', 'Техника');
    }

    private function createRepetitionScheme(): RepetitionScheme
    {
        return RepetitionScheme::fromSetsAndRepetitionPerSet(3, 10);
    }

    private function createWeight(): Weight
    {
        return Weight::fromGrams(72_500);
    }
}
