<?php

declare(strict_types = 1);

namespace App\Domain\Training\Entity;

use App\Domain\Training\ValueObject\RepetitionScheme;
use App\Domain\Training\ValueObject\Weight;

final readonly class PlannedExercise
{
    public function __construct(
        private Exercise $exercise,
        private RepetitionScheme $scheme,
        private Weight $weight,
    ) {
    }

    public function exercise(): Exercise
    {
        return $this->exercise;
    }

    public function scheme(): RepetitionScheme
    {
        return $this->scheme;
    }

    public function weight(): Weight
    {
        return $this->weight;
    }

    public function volumeInGrams(): int
    {
        return $this->scheme->totalRepetitions() * $this->weight->grams();
    }
}