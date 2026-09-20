<?php

declare(strict_types = 1);

namespace App\Domain\Training\Entity;

use App\Domain\Training\ValueObject\RepetitionScheme;

final readonly class PlannedExercise
{
    public function __construct(
        private Exercise $exercise,
        private RepetitionScheme $scheme,
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
}