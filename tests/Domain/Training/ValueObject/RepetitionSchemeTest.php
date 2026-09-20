<?php

declare(strict_types=1);

namespace App\Tests\Domain\Training\ValueObject;

use App\Domain\Training\ValueObject\RepetitionScheme;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RepetitionSchemeTest extends TestCase
{
    public function testItCreatesRepetitionSchemeFromValidSetsAndRepetitionPerSet(): void
    {
        $scheme = RepetitionScheme::fromSetsAndRepetitionPerSet(5, 10);

        $sets = $scheme->sets();
        $repetitionsPerSet = $scheme->repetitionsPerSet();

        self::assertSame(5, $sets);
        self::assertSame(10, $repetitionsPerSet);
    }

    public function testAcceptsOneSetWithOneRepetition(): void
    {
        $scheme = RepetitionScheme::fromSetsAndRepetitionPerSet(1, 1);

        self::assertSame(1, $scheme->sets());
        self::assertSame(1, $scheme->repetitionsPerSet());
        self::assertSame(1, $scheme->totalRepetitions());
    }

    public function testItCalculatesTotalRepetitions(): void
    {
        $scheme = RepetitionScheme::fromSetsAndRepetitionPerSet(5, 10);

        $totalRepetitions = $scheme->totalRepetitions();

        self::assertSame(50, $totalRepetitions);
    }

    public function testItThrowsExceptionWhenSetsAreZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RepetitionScheme::fromSetsAndRepetitionPerSet(0, 10);
    }

    public function testItThrowsExceptionWhenSetsAreNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RepetitionScheme::fromSetsAndRepetitionPerSet(-1, 10);
    }

    public function testItThrowsExceptionWhenRepetitionsPerSetAreZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RepetitionScheme::fromSetsAndRepetitionPerSet(5, 0);
    }

    public function testItThrowsExceptionWhenRepetitionsPerSetAreNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RepetitionScheme::fromSetsAndRepetitionPerSet(5, -5);
    }

    public function testRejectsNegativeSetsAndRepetitions(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RepetitionScheme::fromSetsAndRepetitionPerSet(-2, -3);
    }
}
