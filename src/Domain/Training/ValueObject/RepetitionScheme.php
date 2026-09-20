<?php

declare(strict_types=1);

namespace App\Domain\Training\ValueObject;

use InvalidArgumentException;

final readonly class RepetitionScheme
{
    private function __construct(
        private int $sets,
        private int $repetitionsPerSet,
    ) {
    }

    public static function fromSetsAndRepetitionPerSet(int $sets, int $repetitionsPerSet): self
    {
       self::validateSets($sets);
       self::validateRepetitionsPerSet($repetitionsPerSet);

        return new self($sets, $repetitionsPerSet);
    }

    public function sets(): int
    {
        return $this->sets;
    }

    public function repetitionsPerSet(): int
    {
        return $this->repetitionsPerSet;
    }

    public function totalRepetitions(): int
    {
        return $this->sets * $this->repetitionsPerSet;
    }

    private static function validateSets(int $sets): void
    {
        if ($sets <= 0) {
            throw new InvalidArgumentException('Количество подходов должно быть больше 0');
        }
    }

    private static function validateRepetitionsPerSet(int $repetitionsPerSet): void
    {
        if ($repetitionsPerSet <= 0) {
            throw new InvalidArgumentException('Количество повторений должно быть больше 0');
        }
    }
}