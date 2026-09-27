<?php

declare(strict_types=1);

namespace App\Tests\Controller\Exercise;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CreateExerciseControllerTest extends WebTestCase
{
    public function testCreatesAndPersistsExercise(): void
    {
        $client = static::createClient();

        // Очищаем таблицу перед запуском
        $connection = static::getContainer()->get(Connection::class);
        $connection->executeStatement('DELETE FROM exercise');

        $client->jsonRequest(
            'POST',
            '/api/exercises',
            [
                'name' => '  Становая тяга  ',
                'description' => '  Упражнение со штангой  ',
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // Проверка самого JSON ответа
        $responseContent = $client->getResponse()->getContent();

        self::assertIsString($responseContent);

        $responseData = json_decode(
            $responseContent,
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        self::assertIsArray($responseData);
        self::assertIsInt($responseData['id']);
        self::assertSame('Становая тяга', $responseData['name']);
        self::assertSame('Упражнение со штангой', $responseData['description']);

        // Независимая проверка базы
        $storedExercise = $connection->fetchAssociative(
            'SELECT id, name, description FROM exercise WHERE id = :id',
            ['id' => $responseData['id']],
        );

        self::assertIsArray($storedExercise);
        self::assertSame('Становая тяга', $storedExercise['name']);
        self::assertSame('Упражнение со штангой', $storedExercise['description']);
    }
}
