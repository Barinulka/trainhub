<?php

declare(strict_types=1);

namespace App\Domain\Training\Entity;

use InvalidArgumentException;

final class Exercise
{
    private string $name;
    private ?string $description;

    public function __construct(
        string $name,
        ?string $description = null,
    ) {
        $this->name = $this->normalizeName($name);
        $this->description = $this->normalizeDescription($description);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function rename(string $newName): void
    {
        $this->name = $this->normalizeName($newName);
    }

    public function changeDescription(?string $description): void
    {
        $this->description = $this->normalizeDescription($description);
    }

    private function normalizeName(string $name): string
    {
        $name = trim($name);

        if ($name === '') {
            throw new InvalidArgumentException('Название упражнения не может быть пустым');
        }

        return $name;
    }

    private function normalizeDescription(?string $description): ?string
    {
        if ($description === null) {
            return null;
        }

        return trim($description);
    }
}