<?php

declare(strict_types=1);

namespace App\Domain\Training\ValueObject;

use InvalidArgumentException;

readonly class TrainingDuration
{
    private function __construct(
        private int $duration,
    ) {
    }

    public static function fromMinutes(int $duration): self
    {
        if ($duration <= 0) {
            throw new InvalidArgumentException('Длительность должна быть целым, положительным числом');
        }

        return new self($duration);
    }

    public function minutes(): int
    {
        return $this->duration;
    }

    public function seconds(): int
    {
        return $this->duration * 60;
    }
}