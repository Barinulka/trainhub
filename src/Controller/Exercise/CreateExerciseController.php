<?php

declare(strict_types=1);

namespace App\Controller\Exercise;

use App\Domain\Training\Entity\Exercise;
use App\Presentation\Http\Request\CreateExerciseRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CreateExerciseController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/api/exercises', name: 'create_exercise', methods: ['POST'], format: 'json')]
    public function __invoke(
        #[MapRequestPayload] CreateExerciseRequest $request
    ): JsonResponse
    {
        $exercise = new Exercise($request->name, $request->description);

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
