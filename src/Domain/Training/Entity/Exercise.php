<?php

declare(strict_types=1);

namespace App\Domain\Training\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity]
final class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    public function __construct(
        string $name,
        ?string $description = null,
    ) {
        $this->name = $this->normalizeName($name);
        $this->description = $this->normalizeDescription($description);
    }

    public function id(): ?int
    {
        return $this->id;
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
