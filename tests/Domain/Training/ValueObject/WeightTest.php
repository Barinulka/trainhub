<?php

declare(strict_types=1);

namespace App\Tests\Domain\Training\ValueObject;

use App\Domain\Training\ValueObject\Weight;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testCreatedWeightFromGrams(): void
    {
        $weight = Weight::fromGrams(72_500);
        $grams = $weight->grams();

        self::assertSame(72500, $grams);
    }

    public function testCreatedWeightWithZeroGrams(): void
    {
        $weight = Weight::fromGrams(0);
        $grams = $weight->grams();

        self::assertSame(0, $grams);
    }

    public function testThrowInvalidArgumentExceptionOnNegativeWeight(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Weight::fromGrams(-1);
    }
}
