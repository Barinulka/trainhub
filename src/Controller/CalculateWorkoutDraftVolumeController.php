<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Training\Entity\Exercise;
use App\Domain\Training\Entity\PlannedExercise;
use App\Domain\Training\Entity\WorkoutDraft;
use App\Domain\Training\ValueObject\RepetitionScheme;
use App\Domain\Training\ValueObject\TrainingDuration;
use App\Domain\Training\ValueObject\Weight;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CalculateWorkoutDraftVolumeController extends AbstractController
{
    #[Route('/api/workout-drafts/calculate-volume', name: 'api_workout_draft_calculate_volume', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $requestData = $request->toArray();

        $workoutDraft = new WorkoutDraft(
            $requestData['title'],
            TrainingDuration::fromMinutes($requestData['durationMinutes']),
        );

        foreach ($requestData['exercises'] as $ex) {
            $exercise = new Exercise($ex['name'], $ex['description'] ?? null);
            $scheme = RepetitionScheme::fromSetsAndRepetitionPerSet($ex['sets'], $ex['repetitionsPerSet']);
            $weight = Weight::fromGrams($ex['weightInGrams']);

            $plannedExercise = new PlannedExercise(
                exercise: $exercise,
                scheme: $scheme,
                weight: $weight,
            );

            $workoutDraft->addExercise($plannedExercise);
        }

        return $this->json(['totalVolumeInGrams' => $workoutDraft->totalVolumeInGrams()], Response::HTTP_OK);
    }
}
