<?php

declare(strict_types=1);

namespace App\Tests\Domain\Training\Entity;

use App\Domain\Training\Entity\Exercise;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ExerciseTest extends TestCase
{
    public function testCreatesExerciseWithTrimmedNameAndDescription(): void
    {
        $exercise = new Exercise(' Приседания ', ' Техника ');

        self::assertSame('Приседания', $exercise->name());
        self::assertSame('Техника', $exercise->description());
    }

    public function testCreatesExerciseWithTrimmedNameAndWithoutDescription(): void
    {
        $exercise = new Exercise(' Приседания ');

        self::assertSame('Приседания', $exercise->name());
        self::assertNull($exercise->description());
    }

    public function testCreatesExerciseWithTrimmedNameAnNullDescription(): void
    {
        $exercise = new Exercise(' Приседания ', null);

        self::assertSame('Приседания', $exercise->name());
        self::assertNull($exercise->description());
    }

    public function testCreatesExerciseWithTrimmedNameAndEmptyStringDescription(): void
    {
        $exercise = new Exercise(' Приседания ', ' ');

        self::assertSame('Приседания', $exercise->name());
        self::assertSame('', $exercise->description());
    }

    public function testRejectsEmptyNameOnCreation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Exercise('');
    }

    public function testRejectsWhitespaceOnlyNameOnCreation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Exercise('   ');
    }

    public function testRenamesExerciseName(): void
    {
        $exercise = $this->createExerciseDraft();

        $exercise->rename(' Тяга ');

        self::assertSame('Тяга', $exercise->name());
    }

    public function testRejectsWhitespaceOnlyNameOnRenameAndPreservesPreviousName(): void
    {
        $exercise = $this->createExerciseDraft();
        $exceptionCaught = false;

        try {
            $exercise->rename('   ');
        } catch (InvalidArgumentException $e) {
            $exceptionCaught = true;
        }

        self::assertTrue($exceptionCaught);
        self::assertSame('Приседания', $exercise->name());
    }

    public function testRejectsEmptyNameOnRenameAndPreservesPreviousName(): void
    {
        $exercise = $this->createExerciseDraft();
        $exceptionCaught = false;

        try {
            $exercise->rename('');
        } catch (InvalidArgumentException $e) {
            $exceptionCaught = true;
        }

        self::assertTrue($exceptionCaught);
        self::assertSame('Приседания', $exercise->name());
    }

    public function testChangesDescriptionAndTrimsWhitespace(): void
    {
        $exercise = $this->createExerciseDraft();

        $exercise->changeDescription(' Новая техника ');

        self::assertSame('Новая техника', $exercise->description());
    }

    public function testChangesDescriptionWithWhitespaceString(): void
    {
        $exercise = $this->createExerciseDraft();

        $exercise->changeDescription('  ');

        self::assertSame('', $exercise->description());
    }

    public function testChangesDescriptionWithEmptyString(): void
    {
        $exercise = $this->createExerciseDraft();

        $exercise->changeDescription('');

        self::assertSame('', $exercise->description());
    }

    public function testClearsDescriptionWithNull(): void
    {
        $exercise = $this->createExerciseDraft();

        $exercise->changeDescription(null);

        self::assertNull($exercise->description());
    }

    private function createExerciseDraft(): Exercise
    {
        return new Exercise('Приседания', 'Техника');
    }
}
