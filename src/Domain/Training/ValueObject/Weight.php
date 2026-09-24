<?php

declare(strict_types=1);

namespace App\Domain\Training\ValueObject;

use InvalidArgumentException;

final readonly class Weight
{
    private function __construct(
        private int $grams,
    ) {
    }

    public static function fromGrams(int $grams): self
    {
        if ($grams < 0) {
            throw new InvalidArgumentException('Вес не может быть отрицательным');
        }

        return new self($grams);
    }

    public function grams(): int
    {
        return $this->grams;
    }

}