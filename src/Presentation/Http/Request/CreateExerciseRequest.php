<?php

declare(strict_types=1);

namespace App\Presentation\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateExerciseRequest
{
    public function __construct(
        #[Assert\NotBlank(
            message: 'Название упражнения обязательно',
            normalizer: 'trim',
        )]
        public string $name,
        public ?string $description = null,
    ) {
    }
}
