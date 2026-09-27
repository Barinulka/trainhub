<?php

declare(strict_types=1);

namespace App\Controller\Exercise;

use App\Domain\Training\Entity\Exercise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateExerciseController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/api/exercises', name: 'create_exercise', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $requestData = $request->toArray();

        $exercise = new Exercise($requestData['name'], $requestData['description'] ?? null);

        $this->entityManager->persist($exercise);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'id' => $exercise->id(),
                'name' => $exercise->name(),
                'description' => $exercise->description(),
            ],
            Response::HTTP_CREATED
        );
    }
}
