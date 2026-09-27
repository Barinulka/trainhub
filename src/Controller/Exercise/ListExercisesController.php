<?php

declare(strict_types=1);

namespace App\Controller\Exercise;

use App\Domain\Training\Entity\Exercise;
use App\Infrastructure\Persistence\Doctrine\ExerciseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListExercisesController extends AbstractController
{
    public function __construct(
        private ExerciseRepository $exerciseRepository,
    ) {
    }

    #[Route('/api/exercises', name: 'api_exercise_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $exercises = $this->exerciseRepository->findAllOrderedByName();

        $response = array_map(
            static function (Exercise $exercise): array {
                return [
                    'id' => $exercise->id(),
                    'name' => $exercise->name(),
                    'description' => $exercise->description(),
                ];
            },
            $exercises
        );

        return new JsonResponse(['items' => $response], Response::HTTP_OK);
    }
}
