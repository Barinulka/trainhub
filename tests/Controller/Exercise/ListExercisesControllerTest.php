<?php

declare(strict_types=1);

namespace App\Tests\Controller\Exercise;

use App\Domain\Training\Entity\Exercise;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ListExercisesControllerTest extends WebTestCase
{
    public function testReturnsExercisesOrderedByName(): void
    {
        $client = static::createClient();

        // Arrange: готовим известное состояние тестовой базы
        $connection = static::getContainer()->get(Connection::class);
        $connection->executeStatement('DELETE FROM exercise');

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist(new Exercise('Приседания'));
        $entityManager->persist(new Exercise('Выпады', 'С утяжелением'));
        $entityManager->flush();

        // Act: выполняем тот же GET, который отправляли через Postman
        $client->request('GET', '/api/exercises');

        // Assert: проверяем HTTP-ответ
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = $client->getResponse()->getContent();

        self::assertIsString($responseContent);

        $responseData = json_decode(
            $responseContent,
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        self::assertIsArray($responseData);
        self::assertArrayHasKey('items', $responseData);
        self::assertCount(2, $responseData['items']);

        self::assertSame(
            ['Выпады', 'Приседания'],
            array_column($responseData['items'], 'name'),
        );

        self::assertSame(
            ['С утяжелением', null],
            array_column($responseData['items'], 'description'),
        );

        self::assertIsInt($responseData['items'][0]['id']);
        self::assertIsInt($responseData['items'][1]['id']);

        // GET не должен добавлять или удалять записи
        $connection = static::getContainer()->get(Connection::class);

        self::assertSame(
            2,
            (int) $connection->fetchOne('SELECT COUNT(*) FROM exercise'),
        );
    }
}
