<?php

declare(strict_types=1);

namespace App\Tests\Domain\Training\ValueObject;

use App\Domain\Training\ValueObject\TrainingDuration;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TrainingDurationTest extends TestCase
{
    public function testStoresMinutesAndConvertsToSeconds(): void
    {
        $duration = TrainingDuration::fromMinutes(45);

        $minutes = $duration->minutes();
        $seconds = $duration->seconds();

        $this->assertSame(45, $minutes);
        $this->assertSame(2700, $seconds);
    }

    public function testStoresMinutesWithAMinimalValue(): void
    {
        $duration = TrainingDuration::fromMinutes(1);

        $minutes = $duration->minutes();
        $seconds = $duration->seconds();

        $this->assertSame(1, $minutes);
        $this->assertSame(60, $seconds);
    }

    public function testThrowsInvalidArgumentExceptionOnInvalidMinutes(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TrainingDuration::fromMinutes(0);
    }

    public function testThrowsInvalidArgumentExceptionOnNegativeMinutes(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TrainingDuration::fromMinutes(-1);
    }
}