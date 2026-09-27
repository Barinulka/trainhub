<?php

declare(strict_types=1);

namespace App\Presentation\Http\EventSubscriber;

use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiValidationExceptionSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 10],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof UnprocessableEntityHttpException) {
            return;
        }

        $validationException = $exception->getPrevious();

        if (!$validationException instanceof ValidationFailedException) {
            return;
        }

        $validationExceptions = $validationException->getViolations();

        $details = [];
        foreach ($validationExceptions as $violation) {
            $details[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        $event->setResponse(
            new JsonResponse(
                ['error' => [
                    'code' => 'validation_failed',
                    'message' => 'Некорректные данные запроса',
                    'details' => $details
                ]],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            ),
        );

    }
}
