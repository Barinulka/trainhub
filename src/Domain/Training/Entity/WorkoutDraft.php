<?php

declare(strict_types=1);

namespace App\Domain\Training\Entity;

use App\Domain\Training\ValueObject\TrainingDuration;
use InvalidArgumentException;

final class WorkoutDraft
{
    private string $title;
    private TrainingDuration $duration;

    public function __construct(
        string $title,
        TrainingDuration $duration
    ) {
       $this->title = $this->normalizeTitle($title);
       $this->duration = $duration;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function duration(): TrainingDuration
    {
        return $this->duration;
    }

    public function rename(string $newTitle): void
    {
        $this->title = $this->normalizeTitle($newTitle);
    }

    public function changeDuration(TrainingDuration $newDuration): void
    {
        $this->duration = $newDuration;
    }

    private function normalizeTitle(string $title): string
    {
        $title = trim($title);

        if ($title === '') {
            throw new InvalidArgumentException('Название тренировки не может быть пустым');
        }

        return $title;
    }
}